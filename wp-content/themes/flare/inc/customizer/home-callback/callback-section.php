<?php
global $flare_panels;
global $flare_sections;
global $flare_settings_controls;
global $flare_repeated_settings_controls;
global $flare_customizer_defaults;

/*defaults values callback options*/
$flare_customizer_defaults['flare-callback-enable'] = 0;
$flare_customizer_defaults['flare-callback-page'] = 0;
$flare_customizer_defaults['flare-home-callback-single-words'] = 40;
$flare_customizer_defaults['flare-home-callback-remove-button'] = 1;
$flare_customizer_defaults['flare-home-callback-button-text'] = esc_html__('View More','flare');
$flare_customizer_defaults['flare-home-callback-button-link'] = '';
/* Feature section Enable settings*/
$flare_sections['flare-callback-options'] =
    array(
        'priority'       => 160,
        'title'          => esc_html__( 'Home Callback Section', 'flare' ),
    );

/*callback section enable control*/
$flare_settings_controls['flare-callback-enable'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-callback-enable']
        ),
        'control' => array(
            'label'                 =>  esc_html__( 'Enable callback Section', 'flare' ),
            'description'           =>  esc_html__( 'Enable "callback Section" on checked', 'flare' ),
            'section'               => 'flare-callback-options',
            'type'                  => 'checkbox',
            'priority'              => 10,
            'active_callback'       => ''
        )
    );


    /*creating setting control for flare-callback-page start*/
    $flare_settings_controls['flare-callback-page'] =
        array(
                'setting' =>     array(
                    'default'              => $flare_customizer_defaults['flare-callback-page'],
                    ),
                'control' => array(
                    'label'                 =>  esc_html__( 'Select Page For callback Section', 'flare' ),
                    'description'           => '',
                    'section'               => 'flare-callback-options',
                    'type'                  => 'dropdown-pages',
                    'priority'              => 20
                )
        );
        
    /*String in max to be appear as description on callback*/
    $flare_settings_controls['flare-home-callback-single-words'] =
        array(
            'setting' =>     array(
                'default'              => $flare_customizer_defaults['flare-home-callback-single-words']
            ),
            'control' => array(
                'label'                 =>  esc_html__( 'Number Of Words', 'flare' ),
                'description'           =>  esc_html__( 'Give number of words you wish to be appear on home page callback section', 'flare' ),
                'section'               => 'flare-callback-options',
                'type'                  => 'number',
                'input_attrs' => array( 'min' => 1, 'max' => 200),
                'priority'              => 20,
                'active_callback'       => ''
            )
        );

        $flare_settings_controls['flare-home-callback-remove-button'] =
            array(
                'setting' =>     array(
                    'default'              => $flare_customizer_defaults['flare-home-callback-remove-button']
                ),
                'control' => array(
                    'label'                 =>  esc_html__( 'Enable Button', 'flare' ),
                    'section'               => 'flare-callback-options',
                    'type'                  => 'checkbox',
                    'priority'              => 30,
                    'active_callback'       => ''
                )
            );
