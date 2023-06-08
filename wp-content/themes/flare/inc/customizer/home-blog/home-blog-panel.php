<?php
global $flare_panels;
global $flare_sections;
global $flare_settings_controls;
global $flare_repeated_settings_controls;
global $flare_customizer_defaults;


$flare_customizer_defaults['flare-home-blog-enable'] = 0;
$flare_customizer_defaults['flare-home-blog-title'] = __('FROM OUR BLOG','flare');
$flare_customizer_defaults['flare-home-blog-single-words'] = 30;
$flare_customizer_defaults['flare-home-blog-category'] = 0;
$flare_customizer_defaults['flare-home-blog-button-text'] = __('Browse more','flare');
$flare_customizer_defaults['flare-home-blog-button-link'] = home_url( '/blog' );

$flare_sections['flare-home-blog-panel'] =
    array(
        'priority'       => 190,
        'title'          => __( 'Home blog Section', 'flare' ),
   	);

$flare_settings_controls['flare-home-blog-enable'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-home-blog-enable']
        ),
        'control' => array(
            'label'                 =>  __( 'Enable Blog', 'flare' ),
            'description'           => __( 'Enable "Blog Section" on checked' , 'flare' ),
            'section'               => 'flare-home-blog-panel',
            'type'                  => 'checkbox',
            'priority'              => 10,
            'active_callback'       => ''
        )
    );


$flare_settings_controls['flare-home-blog-title'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-home-blog-title']
        ),
        'control' => array(
            'label'                 =>  __( 'Main Title', 'flare' ),
            'section'               => 'flare-home-blog-panel',
            'type'                  => 'text',
            'priority'              => 20,
            'active_callback'       => ''
        )
    );


    /*String in max to be appear as description on blog*/
    $flare_settings_controls['flare-home-blog-single-words'] =
        array(
            'setting' =>     array(
                'default'              => $flare_customizer_defaults['flare-home-blog-single-words']
            ),
            'control' => array(
                'label'                 =>  __( 'Number Of Words', 'flare' ),
                'description'           =>  __( 'Give number of words you wish to be appear on home page blog section sticky post/ feature post', 'flare' ),
                'section'               => 'flare-home-blog-panel',
                'type'                  => 'number',
                'priority'              => 40,
                'input_attrs' => array( 'min' => 1, 'max' => 200),
                'active_callback'       => ''
            )
        );

/*creating setting control for flare-fs-Category start*/
$flare_settings_controls['flare-home-blog-category'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-home-blog-category']
        ),
        'control' => array(
            'label'                 =>  __( 'Select Category For Blog', 'flare' ),
            'description'           =>  __( 'Blog will only displayed from this category', 'flare' ),
            'section'               => 'flare-home-blog-panel',
            'type'                  => 'category_dropdown',
            'priority'              => 60,
            'active_callback'       => ''
        )
    );


    $flare_settings_controls['flare-home-blog-button-text'] =
        array(
            'setting' =>     array(
                'default'              => $flare_customizer_defaults['flare-home-blog-button-text']
            ),
            'control' => array(
                'label'                 =>  __( 'Browse All Button Text', 'flare' ),
                'section'               => 'flare-home-blog-panel',
                'type'                  => 'text',
                'priority'              => 70,
                'active_callback'       => ''
            )
        );

    $flare_settings_controls['flare-home-blog-button-link'] =
        array(
            'setting' =>     array(
                'default'              => $flare_customizer_defaults['flare-home-blog-button-link']
            ),
            'control' => array(
                'label'                 =>  __( 'Browse All Button Link', 'flare' ),
                'section'               => 'flare-home-blog-panel',
                'type'                  => 'url',
                'priority'              => 80,
                'active_callback'       => ''
            )
        );