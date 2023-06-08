<?php
global $flare_sections;
global $flare_settings_controls;
global $flare_repeated_settings_controls;
global $flare_customizer_defaults;

/*defaults values*/
$flare_customizer_defaults['flare-enable-static-page'] = 1;
$flare_customizer_defaults['flare-default-layout'] = 'right-sidebar';
$flare_customizer_defaults['flare-number-of-words'] = 30;
$flare_customizer_defaults['flare-archive-layout'] = 'thumbnail-and-excerpt';
$flare_customizer_defaults['flare-archive-image-align'] = 'full';
$flare_customizer_defaults['flare-single-post-image-align'] = 'full';
$flare_customizer_defaults['flare-single-post-image'] = '';



$flare_sections['flare-layout-options'] =
    array(
        'priority'       => 20,
        'title'          => __( 'Layout Options', 'flare' ),
        'panel'          => 'flare-theme-options',
    );


/*home page static page display*/
$flare_settings_controls['flare-enable-static-page'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-enable-static-page'],
        ),
        'control' => array(
            'label'                 =>  __( 'Enable Static Front Page', 'flare' ),
            'description'           =>  __( 'If you disable this the static page will be disappera form the home page and other section from customizer will reamin as it is', 'flare' ),
            'section'               => 'flare-layout-options',
            'type'                  => 'checkbox',
            'priority'              => 10,
        )
    );
    
/*layout-options option responsive lodader start*/
$flare_settings_controls['flare-default-layout'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-default-layout'],
        ),
        'control' => array(
            'label'                 =>  __( 'Default Layout', 'flare' ),
            'description'           =>  __( 'Layout for all archives, single posts and pages', 'flare' ),
            'section'               => 'flare-layout-options',
            'type'                  => 'select',
            'choices'               => array(
                'right-sidebar' => __( 'Content - Primary Sidebar', 'flare' ),
                'left-sidebar' => __( 'Primary Sidebar - Content', 'flare' ),
                'no-sidebar' => __( 'No Sidebar', 'flare' )
            ),
            'priority'              => 30,
            'active_callback'       => ''
        )
    );

$flare_settings_controls['flare-archive-layout'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-archive-layout'],
        ),
        'control' => array(
            'label'                 =>  esc_html__( 'Archive Layout', 'flare' ),
            'section'               => 'flare-layout-options',
            'type'                  => 'select',
            'choices'               => array(
                'excerpt-only' => esc_html__( 'Excerpt Only', 'flare' ),
                'thumbnail-and-excerpt' => esc_html__( 'Thumbnail and Excerpt', 'flare' ),
            ),
            'priority'              => 34,
        )
    );


$flare_settings_controls['flare-archive-image-align'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-archive-image-align'],
        ),
        'control' => array(
            'label'                 =>  esc_html__( 'Archive Image Alignment', 'flare' ),
            'section'               => 'flare-layout-options',
            'type'                  => 'select',
            'choices'               => array(
                'full' => esc_html__( 'Full', 'flare' ),
                'right' => esc_html__( 'Right', 'flare' ),
            ),
            'priority'              => 35,
            'description'              => esc_html__('This option only work if you have selected "Thumbnail and Excerpt" or "Thumbnail and Full Post" in Archive Layout options','flare'),
        )
    );

$flare_settings_controls['flare-number-of-words'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-number-of-words']
        ),
        'control' => array(
            'label'                 =>  __( 'Number Of Words For Excerpt', 'flare' ),
            'description'           =>  __( 'This will controll the excerpt length on listing page', 'flare' ),
            'section'               => 'flare-layout-options',
            'type'                  => 'number',
            'input_attrs' => array( 'min' => 1, 'max' => 200),
            'priority'              => 40,
            'active_callback'       => ''
        )
    );


$flare_settings_controls['flare-single-post-image-align'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-single-post-image-align'],
        ),
        'control' => array(
            'label'                 =>  __( 'Alignment Of Image In Single Post/Page', 'flare' ),
            'section'               => 'flare-layout-options',
            'type'                  => 'select',
            'choices'               => array(
                'full' => __( 'Full', 'flare' ),
                'right' => __( 'Right', 'flare' ),
                'left' => __( 'Left', 'flare' ),
                'no-image' => __( 'No image', 'flare' )
            ),
            'priority'              => 50,
            'description'           =>  __( 'Please note that this setting can be override from individual post/page', 'flare' ),
        )
    );