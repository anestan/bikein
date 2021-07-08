<?php
	/**
	 * @var string $settings_section
	 */
?>
<form action='options.php' method='post'>
	<?php
		settings_fields($settings_section);
		do_settings_sections($settings_section);
		submit_button();
	?>
</form>