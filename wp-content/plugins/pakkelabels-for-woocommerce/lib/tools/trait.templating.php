<?php namespace ShipmondoForWooCommerce\Lib\Tools;

use ShipmondoForWooCommerce\Plugin\Plugin;

trait Templating {

    // GETTERS

    public static function getTextDomain() {
        if(isset(static::$slug)) {
            return static::$slug;
        }

        return '';
    }

    public static function getRoot() {
        if(isset(static::$root)) {
            return static::$root;
        }

        return '';
    }

    public static function getVersion() {
        if(isset(static::$version)) {
            return static::$version;
        }

        return '';
    }

    public static function getFilterName($ending, $type = '') {
        return trailingslashit(static::getTextDomain()) . (!empty($type) ? trailingslashit($type) : '') . $ending;
    }

    // PRIVATE HELPER FUNCTIONS

    private static function decodeFolderStructure($path, $type = array('php', 'css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'json', 'htm', 'html')) {
        $type = implode('$|', (array) $type);

        $path = preg_replace('/\.(?!' . $type . '$)/', '/', $path);

        return $path;
    }

    private static function singleEnding($subjects, $ending) {
        if(is_array($subjects)) {
            foreach($subjects as &$subject) {
                $subject = static::strRightTrim($subject, $ending) . $ending;
            }
        } else {
            $subjects = static::strRightTrim($subjects, $ending) . $ending;
        }

        return $subjects;
    }

    private static function strRightTrim($subject, $ending) {
        $length = strlen($ending);
        if(substr($subject, -$length) === $ending) {
            return substr($subject, 0, -$length);
        }
        return $subject;
    }

    private static function locateFile($files, $folders = array(''), $debug = WP_DEBUG) {
        $root_locations = array(
            'child_theme' => trailingslashit(get_stylesheet_directory()) . static::getTextDomain(),
        );

        $theme = trailingslashit(get_template_directory()) . static::getTextDomain();

        if($root_locations['child_theme'] != $theme) {
            $root_locations['theme'] = $theme;
        }

        $root = static::getRoot();

        if(!empty($root)) {
            $root_locations['plugin'] = $root;
        }

        $root_locations = (array) apply_filters(
            static::getFilterName('root_locations', 'templating'),
            $root_locations,
            $files,
            $folders
        );

        foreach($root_locations as $key => $location) {
            $root_locations[$key] = trailingslashit($location);
        }

        if(isset(static::$dir_namespace)) {
            $namespaced_folders = array();
            foreach((array) $folders as $key => $folder) {
                $namespaced_folders[$key . '_namespaced'] = $folder . '.' . static::$dir_namespace;
            }
            $folders = array_merge($namespaced_folders, (array) $folders);
        }

        $folders = (array) apply_filters(
            static::getFilterName('folders', 'templating'),
            $folders,
            $files,
            $root_locations
        );

        foreach($folders as $key => $folder) {
            $folders[$key] = trailingslashit(static::decodeFolderStructure($folder, null));
        }

        $files = (array) apply_filters(
            static::getFilterName('files', 'templating'),
            $files,
            $folders,
            $root_locations
        );

        $located_file = null;
        foreach($files as $file) {
            $file_decoded = static::decodeFolderStructure($file);
            foreach($folders as $folder) {
                foreach($root_locations as $location) {
                    $abs_path = wp_normalize_path($location . $folder . $file);
                    if(is_file($abs_path)) {
                        $located_file = $abs_path;
                        break 3;
                    }

                    $abs_path = wp_normalize_path($location . $folder . $file_decoded);
                    if(is_file($abs_path)) {
                        $located_file = $abs_path;
                        break 3;
                    }
                }
            }
        }

        if(is_null($located_file)) {
            if($debug === true) {
                var_dump(array($root_locations, $folders, $files));
            }
            return false;
        }

        return $located_file;
    }

    private static function isFileURL($file_name) {
        if (substr($file_name, 0, 7) === 'http://' || substr($file_name, 0, 8) === 'https://' || substr($file_name, 0, 2) === '//') {
            return true;
        }
        return false;
    }

    protected static function generateFileSlug($file_name) {
        return strtolower(trim(static::getTextDomain() . str_replace(array('.js', '.css', 'http://', 'https://', '.', ' ', '/', '_', '\\', '//'), '_', $file_name), '_'));
    }

    /* PUBLIC FUNCTIONS */

    public static function getTemplate($template_names, $args = array(), $echo = true, $folders = array('templates', 'lib.templates')) {
        $template_names = static::singleEnding($template_names, '.php');

        if($file = static::locateFile($template_names, $folders)) {
            $args = apply_filters(
                static::getFilterName('args', 'templating'),
                $args,
                $template_names,
                $file
            );

            extract($args);

            ob_start();
            do_action(
                static::getFilterName('html/before', 'templating'),
                $template_names,
                $args,
                $file
            );
            include($file);
            do_action(
                static::getFilterName('html/after', 'templating'),
                $template_names,
                $args,
                $file
            );
            $html = ob_get_clean();

            if($echo) {
                echo $html;
            }

            return $html;
        }

        return false;
    }

    public static function getFileURL($files, $folders = array(''), $debug = WP_DEBUG) {
        if($file = static::locateFile($files, $folders, $debug)) {
            $url = str_replace(wp_normalize_path(Plugin::getRoot()), trailingslashit(plugin_dir_url(Plugin::getPluginInfo('file'))), $file);

            return $url;
        }

        return false;
    }

    public static function addStyle($file_name, $deps = array(), $media = 'all', $folders = 'css', $debug = WP_DEBUG) {
        $file_name = static::singleEnding($file_name, '.css');
        if (static::isFileURL($file_name)) {
            $url = $file_name;
        } else {
            $url = static::getFileURL($file_name, $folders);
        }

        \wp_enqueue_style(static::generateFileSlug($file_name), $url, $deps, static::getVersion(), $media, $debug);
    }

    public static function addScript($file_name, $deps = array('jquery'), $in_footer = true, $folders = 'js', $debug = WP_DEBUG) {
        if (static::isFileURL($file_name)) {
            $url = $file_name;
            $version = null;
        } else {
            $file_name = static::singleEnding($file_name, '.js');
            $url = static::getFileURL($file_name, $folders);
            $version = static::getVersion();
        }

        \wp_enqueue_script(static::generateFileSlug($file_name), $url, $deps, $version, $in_footer, $debug);
    }

    public static function localizeScript($file_name, $object_name, array $data) {
        \wp_localize_script(static::generateFileSlug($file_name), $object_name, $data);
    }

    public static function getAssetsURL($file = null, $folders = array('assets')) {
        return static::getFileURL($file, $folders);
    }

    public static function getImgURL($file = null, $folders = array('images'), $debug = WP_DEBUG)  {
        return static::getFileURL($file, $folders, $debug);
    }
}