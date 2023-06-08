<?php
global $flare_panels;
global $flare_sections;
global $flare_settings_controls;
global $flare_repeated_settings_controls;
global $flare_customizer_defaults;

/*defaults values feature portfolio options*/
$flare_customizer_defaults['flare-home-service-enable'] = 0;
$flare_customizer_defaults['flare-home-service-title'] = __('OUR SERVICES','flare');
$flare_customizer_defaults['flare-home-page-service-single-words'] = 30;
$flare_customizer_defaults['flare-home-service-button-text'] = __('View More','flare');
$flare_customizer_defaults['flare-home-service-selection'] = 'from-page';
$flare_customizer_defaults['flare-home-service-number'] = 3;
$flare_customizer_defaults['flare-home-service-page-icon'] = 'fa-desktop';
$flare_customizer_defaults['flare-home-service-pages'] = 0;


/*creating panel for fonts-setting*/

$flare_sections['flare-home-service'] =
    array(
        'title'          => __( 'Home Service Section', 'flare' ),
        'priority'       => 160
   	);

$flare_settings_controls['flare-home-service-enable'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-home-service-enable']
        ),
        'control' => array(
            'label'                 =>  __( 'Enable Service', 'flare' ),
            'section'               => 'flare-home-service',
            'type'                  => 'checkbox',
            'priority'              => 10,
            'active_callback'       => ''
        )
    );


$flare_settings_controls['flare-home-service-title'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-home-service-title']
        ),
        'control' => array(
            'label'                 =>  __( 'Main Title', 'flare' ),
            'section'               => 'flare-home-service',
            'type'                  => 'text',
            'priority'              => 20,
            'active_callback'       => ''
        )
    );

$flare_settings_controls['flare-home-page-service-single-words'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-home-page-service-single-words']
        ),
        'control' => array(
            'label'                 =>  __( 'Number Of Words in Single Column Content', 'flare' ),
            'description'           =>  __( 'If you do not have selected from - Custom', 'flare' ),
            'section'               => 'flare-home-service',
            'type'                  => 'number',
            'priority'              => 30,
            'input_attrs' => array( 'min' => 1, 'max' => 200),
            'active_callback'       => ''
        )
    );

$flare_repeated_settings_controls['flare-home-service-font-icon'] =
    array(
        'repeated' => 3,
        'flare-home-service-page-icon' => array(
            'setting' =>     array(
                'default'              => $flare_customizer_defaults['flare-home-service-page-icon'],
            ),
            'control' => array(
                'label'                 =>  __( 'Icon %s', 'flare' ),
                'section'               => 'flare-home-service',
                'type'                  => 'text',
                'priority'              => 40,
                'description'           => sprintf( __( 'Use font awesome icon: Eg: %1$s. %2$s See more here %3$s', 'flare' ), 'fa-desktop','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
            )
        ),
    );


/*creating setting control for flare-home-service-page start*/
$flare_repeated_settings_controls['flare-home-service-pages'] =
    array(
        'repeated' => 3,
        'flare-home-service-pages-ids' => array(
            'setting' =>     array(
                'default'              => $flare_customizer_defaults['flare-home-service-pages'],
            ),
            'control' => array(
                'label'                 =>  __( 'Select Page For Service %s', 'flare' ),
                'section'               => 'flare-home-service',
                'type'                  => 'dropdown-pages',
                'priority'              => 60,
                'description'           => ''
            )
        ),
    );