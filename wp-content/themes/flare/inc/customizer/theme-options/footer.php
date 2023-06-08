<?php
global $flare_sections;
global $flare_settings_controls;
global $flare_repeated_settings_controls;
global $flare_customizer_defaults;

/*defaults values*/
$flare_customizer_defaults['flare-footer-sidebar-number'] = 2;
$flare_customizer_defaults['flare-copyright-text'] = __('Copyright &copy; All right reserved','flare');
$flare_customizer_defaults['flare-enable-theme-name'] = 1;

$flare_sections['flare-footer-options'] =
    array(
        'priority'       => 60,
        'title'          => __( 'Footer Options', 'flare' ),
        'panel'          => 'flare-theme-options'
    );

    $flare_settings_controls['flare-footer-sidebar-number'] =
        array(
            'setting' =>     array(
                'default'              => $flare_customizer_defaults['flare-footer-sidebar-number'],
            ),
            'control' => array(
                'label'                 =>  __( 'Number of Sidebars In Footer Area', 'flare' ),
                'section'               => 'flare-footer-options',
                'type'                  => 'select',
                'choices'               => array(
                    0 => __( 'Disable footer sidebar area', 'flare' ),
                    1 => __( '1', 'flare' ),
                    2 => __( '2', 'flare' ),
                ),
                'priority'              => 10,
                'description'           => '',
                'active_callback'       => ''
            )
        );



$flare_settings_controls['flare-copyright-text'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-copyright-text'],
        ),
        'control' => array(
            'label'                 =>  __( 'Copyright Text', 'flare' ),
            'section'               => 'flare-footer-options',
            'type'                  => 'text_html',
            'priority'              => 20,
        )
    );