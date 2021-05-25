<?php

/* Add custom selling point to theme customization */

function storefrontchild_register_theme_customizer( $wp_customize ) {
	// Create custom panel.
	$wp_customize->add_panel( 'text_blocks', array(
		'priority'       => 500,
		'theme_supports' => '',
		'title'          => __( 'Topbar', 'storefrontchild' ),
		'description'    => __( 'Tilføj selling point til headeren.', 'storefrontchild' ),
	) );
	// Add Selling point text
	// Add section 1.
	$wp_customize->add_section( 'custom_selling_point_1' , array(
		'title'    => __('Selling point 1','storefrontchild'),
		'panel'    => 'text_blocks',
		'priority' => 10
    ) );
    // Add section 2.
    $wp_customize->add_section( 'custom_selling_point_2' , array(
		'title'    => __('Selling point 2','storefrontchild'),
		'panel'    => 'text_blocks',
		'priority' => 10
    ) );
    // Add section 3.
    $wp_customize->add_section( 'custom_selling_point_3' , array(
		'title'    => __('Selling point 3','storefrontchild'),
		'panel'    => 'text_blocks',
		'priority' => 10
    ) );
	// Add setting 1
	$wp_customize->add_setting( 'header_selling_block_1', array(
		 'default'           => __( 'Selling point 1', 'storefrontchild' ),
		 'sanitize_callback' => 'sanitize_text'
    ) );
    // Add setting icon 1
	$wp_customize->add_setting( 'header_selling_icon_1', array(
        'default'           => __( 'fas fa-bicycle', 'storefrontchild' ),
        'sanitize_callback' => 'sanitize_text'
    ) );
    // Add setting 2
    $wp_customize->add_setting( 'header_selling_block_2', array(
        'default'           => __( 'Selling point 2', 'storefrontchild' ),
        'sanitize_callback' => 'sanitize_text'
    ) );
    // Add setting icon 2
	$wp_customize->add_setting( 'header_selling_icon_2', array(
        'default'           => __( 'fas fa-bicycle', 'storefrontchild' ),
        'sanitize_callback' => 'sanitize_text'
    ) );
    // Add setting 3
    $wp_customize->add_setting( 'header_selling_block_3', array(
        'default'           => __( 'Selling point 3', 'storefrontchild' ),
        'sanitize_callback' => 'sanitize_text'
    ) );
    // Add setting icon 3
	$wp_customize->add_setting( 'header_selling_icon_3', array(
        'default'           => __( 'fas fa-bicycle', 'storefrontchild' ),
        'sanitize_callback' => 'sanitize_text'
    ) );

	// Add control 1
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'custom_selling_point_1',
		    array(
		        'label'    => __( 'Selling Point', 'storefrontchild' ),
		        'section'  => 'custom_selling_point_1',
		        'settings' => 'header_selling_block_1',
		        'type'     => 'text'
            )
	    )
    );

    // Add control icon 1
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'custom_selling_icon_1',
		    array(
                'label'    => __( 'Selling icon', 'storefrontchild' ),
                'description'   => __('Indtast klassen for Font Awesome ikonet. Se ikonerne her: https://fontawesome.com/icons?d=gallery&m=free', 'storefrontchild'),
		        'section'  => 'custom_selling_point_1',
		        'settings' => 'header_selling_icon_1',
		        'type'     => 'text'
		    )
	    )
    );

    // Add control 2
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'custom_selling_point_2',
		    array(
		        'label'    => __( 'Selling Point', 'storefrontchild' ),
		        'section'  => 'custom_selling_point_2',
		        'settings' => 'header_selling_block_2',
		        'type'     => 'text'
		    )
	    )
    );

    // Add control icon 2
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'custom_selling_icon_2',
		    array(
                'label'    => __( 'Selling icon', 'storefrontchild' ),
                'description'   => __('Indtast klassen for Font Awesome ikonet. Se ikonerne her: https://fontawesome.com/icons?d=gallery&m=free', 'storefrontchild'),
		        'section'  => 'custom_selling_point_2',
		        'settings' => 'header_selling_icon_2',
		        'type'     => 'text'
		    )
	    )
    );

        
    // Add control 3
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'custom_selling_point_3',
		    array(
		        'label'    => __( 'Selling Point', 'storefrontchild' ),
		        'section'  => 'custom_selling_point_3',
		        'settings' => 'header_selling_block_3',
		        'type'     => 'text'
            )
	    )
    );
    
    // Add control icon 3
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'custom_selling_icon_3',
		    array(
                'label'         => __( 'Selling icon', 'storefrontchild' ),
                'description'   => __('Indtast klassen for Font Awesome ikonet. Se ikonerne her: https://fontawesome.com/icons?d=gallery&m=free', 'storefrontchild'),
		        'section'       => 'custom_selling_point_3',
		        'settings'      => 'header_selling_icon_3',
		        'type'          => 'text'
		    )
	    )
    );


 	// Sanitize text
	function sanitize_text( $text ) {
	    return sanitize_text_field( $text );
	}
}
add_action( 'customize_register', 'storefrontchild_register_theme_customizer' );


/*
***
# Add topbar customization
***
*/
class MyTheme_TopBar_Customize{
    public static function register ( $wp_customize ) {
        $wp_customize->add_section('sf_top_bar', array(
                'title'      => __( 'Top Bar', 'storefront-child' ),
                'priority'   => 30,
            ));
        $wp_customize->add_setting('sf_child_phone_1', array(
            'default'        => '+45 12 34 56 78',
            'capability'     => 'edit_theme_options',
            'type'           => 'option',
        ));
        $wp_customize->add_setting('sf_child_phone_2', array(
                'default'        => '+45 12 34 56 78',
                'capability'     => 'edit_theme_options',
                'type'           => 'option',
            ));

        $wp_customize->add_setting('sf_child_phone_3', array(
                'default'        => '+45 12 34 56 78',
                'capability'     => 'edit_theme_options',
                'type'           => 'option',
            ));    
        $wp_customize->add_control(
            new WP_Customize_Color_Control($wp_customize,'sf_child_phone_1',
                array(
                    'label'      => __('Telefon 1', 'storefront-child'),
                    'section'    => 'sf_top_bar',
                    'settings'   => 'sf_child_phone_1',
                    'priority'   => 10,
                    'type'       => 'text'
                )) );
        $wp_customize->add_control(
            new WP_Customize_Color_Control($wp_customize,'sf_child_phone_2',
                array(
                    'label'      => __('Telefon 2', 'storefront-child'),
                    'section'    => 'sf_top_bar',
                    'settings'   => 'sf_child_phone_2',
                    'priority'   => 10,
                    'type'       => 'text'
                )) );
        $wp_customize->add_control(
            new WP_Customize_Color_Control($wp_customize,'sf_child_phone_3',
                array(
                    'label'      => __('Telefon 3', 'storefront-child'),
                    'section'    => 'sf_top_bar',
                    'settings'   => 'sf_child_phone_3',
                    'priority'   => 10,
                    'type'       => 'text'
                )) );
    }

    public static function top_bar_html( $wp_customize ){
        $phoneOne=get_option('sf_child_phone_1');
        $phoneTwo=get_option('sf_child_phone_2');
        $phoneThree=get_option('sf_child_phone_3');
        ?>
        <div class="sf_top_bar">
          <div class="sf_top_bar_inner">
            <div class="top_bar_left"><?php echo $phoneOne; ?></div>
            <div class="top_bar_center"><?php echo $phoneTwo; ?></div>
            <div class="top_bar_right"><?php echo $phoneThree; ?></div>
          </div>
        </div>
<?php }
}
add_action( 'customize_register' , array( 'MyTheme_TopBar_Customize' , 'register' ) );
add_action('MyTheme_before_header', array( 'MyTheme_TopBar_Customize' , 'top_bar_html' ));
