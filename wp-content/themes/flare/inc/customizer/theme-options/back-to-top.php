<?php
global $flare_sections;
global $flare_settings_controls;
global $flare_repeated_settings_controls;
global $flare_customizer_defaults;

/*defaults values*/
$flare_customizer_defaults['flare-enable-back-to-top'] = 1;

$flare_sections['flare-back-to-top-options'] =
    array(
        'priority'       => 80,
        'title'          => __( 'Back To Top Options', 'flare' ),
        'panel'          => 'flare-theme-options'
    );

$flare_settings_controls['flare-enable-back-to-top'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-enable-back-to-top'],
        ),
        'control' => array(
            'label'                 =>  __( 'Enable Back To Top', 'flare' ),
            'section'               => 'flare-back-to-top-options',
            'type'                  => 'checkbox',
            'priority'              => 50,
        )
    );