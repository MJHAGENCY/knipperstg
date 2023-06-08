<?php
global $flare_panels;
global $flare_sections;
global $flare_settings_controls;
global $flare_repeated_settings_controls;
global $flare_customizer_defaults;

/*defaults values feature portfolio options*/
$flare_customizer_defaults['flare-home-testimonial-enable'] = 0;
$flare_customizer_defaults['flare-home-testimonial-main-title'] =  __('VOICE OF CLIENTS','flare');
$flare_customizer_defaults['flare-home-testimonial-number'] = 2;
$flare_customizer_defaults['flare-home-testimonial-single-words'] = 30;
$flare_customizer_defaults['flare-home-testimonial-selection'] = 'from-page';
$flare_customizer_defaults['flare-home-testimonial-slider-mode'] = 'fade';
$flare_customizer_defaults['flare-home-testimonial-slider-time'] = 2;
$flare_customizer_defaults['flare-home-testimonial-slider-pause-time'] = 7;
$flare_customizer_defaults['flare-home-testimonial-pages'] = 0;


/*creating panel for fonts-setting*/

$flare_sections['flare-home-testimonial'] =
    array(
        'title'          => __( 'Home Testimonial Section', 'flare' ),
        'priority'       => 160
   	);

$flare_settings_controls['flare-home-testimonial-enable'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-home-testimonial-enable']
        ),
        'control' => array(
            'label'                 =>  __( 'Enable Testimonial', 'flare' ),
            'description'           => __( 'Enable "Testimonial selection" on checked', 'flare' ),
            'section'               => 'flare-home-testimonial',
            'type'                  => 'checkbox',
            'priority'              => 2,
            'active_callback'       => ''
        )
    );



/*Testimonial Title control*/
$flare_settings_controls['flare-home-testimonial-main-title'] =
array(
    'setting' =>     array(
        'default'              => $flare_customizer_defaults['flare-home-testimonial-main-title']
    ),
    'control' => array(
        'label'                 =>  __( 'Main Title', 'flare' ),
        'section'               => 'flare-home-testimonial',
        'type'                  => 'text',
        'priority'              => 5,
        'active_callback'       => ''
    )
);

/*No of Testimonial needed*/
$flare_settings_controls['flare-home-testimonial-number'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-home-testimonial-number']
        ),
        'control' => array(
            'label'                 =>  __( 'Number Of Testimonial/s', 'flare' ),
            'description'           => __( 'Choose number of Testimonial to be displayed', 'flare' ),
            'section'               => 'flare-home-testimonial',
            'type'                  => 'select',
            'choices'               => array(
                1 => __( '1', 'flare' ),
                2 => __( '2', 'flare' ),
                3 => __( '3', 'flare' ),
            ),
            'priority'              => 10,
            'active_callback'       => ''
        )
    );

/*String in max to be appear as description on testimonial*/
$flare_settings_controls['flare-home-testimonial-single-words'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-home-testimonial-single-words']
        ),
        'control' => array(
            'label'                 =>  __( 'Single Testimonial- Number Of Words', 'flare' ),
            'description'           =>  __( 'If you do not have selected from - Custom', 'flare' ),
            'section'               => 'flare-home-testimonial',
            'type'                  => 'number',
            'input_attrs' => array( 'min' => 1, 'max' => 200),
            'priority'              => 20,
            'active_callback'       => ''
        )
    );

/*creating setting control for flare-home-testimonial-page start*/
$flare_repeated_settings_controls['flare-home-testimonial-pages'] =
    array(
        'repeated' => 3,
        'flare-home-testimonial-pages-ids' => array(
            'setting' =>     array(
                'default'              => $flare_customizer_defaults['flare-home-testimonial-pages'],
            ),
            'control' => array(
                'label'                 =>  __( 'Select Page For Testimonial %s', 'flare' ),
                'section'               => 'flare-home-testimonial',
                'type'                  => 'dropdown-pages',
                'priority'              => 90,
                'description'           => ''
            )
        ),
        'flare-home-testimonial-pages-divider' => array(
            'control' => array(
                'section'               => 'flare-home-testimonial',
                'type'                  => 'message',
                'priority'              => 90,
                'description'           => '<hr />'
            )
        )
    );

