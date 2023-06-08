<?php
global $flare_sections;
global $flare_settings_controls;
global $flare_repeated_settings_controls;
global $flare_customizer_defaults;

/*defaults values*/
$flare_customizer_defaults['flare-enable-top-header'] = 0;
$flare_customizer_defaults['flare-top-header-email'] = '';
$flare_customizer_defaults['flare-top-header-address'] = '';
$flare_customizer_defaults['flare-top-header-number'] = '';

$flare_sections['flare-top-header-options'] =
    array(
        'priority'       => 30,
        'title'          => esc_html__( 'Top Header Options', 'flare' ),
        'panel'          => 'flare-theme-options',
    );

$flare_settings_controls['flare-enable-top-header'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-enable-top-header'],
        ),
        'control' => array(
            'label'                 =>  esc_html__( 'Enable Top Header Section', 'flare' ),
            'section'               => 'flare-top-header-options',
            'type'                  => 'checkbox',
            'priority'              => 10,
        )
    );

$flare_settings_controls['flare-top-header-email'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-top-header-email'],
        ),
        'control' => array(
            'label'                 =>  esc_html__( 'Contact Email', 'flare' ),
            'section'               => 'flare-top-header-options',
            'type'                  => 'text',
            'priority'              => 30,
        )
    );

$flare_settings_controls['flare-top-header-address'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-top-header-address'],
        ),
        'control' => array(
            'label'                 =>  esc_html__( 'Contact Location', 'flare' ),
            'section'               => 'flare-top-header-options',
            'type'                  => 'text',
            'priority'              => 50,
        )
    );

$flare_settings_controls['flare-top-header-number'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-top-header-number'],
        ),
        'control' => array(
            'label'                 =>  esc_html__( 'Contact Telephone Number', 'flare' ),
            'section'               => 'flare-top-header-options',
            'type'                  => 'text',
            'priority'              => 40,
        )
    );