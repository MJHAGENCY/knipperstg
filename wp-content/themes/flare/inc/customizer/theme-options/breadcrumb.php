<?php
global $flare_sections;
global $flare_settings_controls;
global $flare_repeated_settings_controls;
global $flare_customizer_defaults;

/*defaults values*/
$flare_customizer_defaults['flare-enable-breadcrumb'] = 1;

$flare_sections['flare-breadcrumb-options'] =
    array(
        'priority'       => 30,
        'title'          => __( 'Breadcrumb Options', 'flare' ),
        'panel'          => 'flare-theme-options',
    );

$flare_settings_controls['flare-enable-breadcrumb'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-enable-breadcrumb'],
        ),
        'control' => array(
            'label'                 =>  __( 'Enable Breadcrumb', 'flare' ),
            'section'               => 'flare-breadcrumb-options',
            'type'                  => 'checkbox',
            'priority'              => 10,
        )
    );