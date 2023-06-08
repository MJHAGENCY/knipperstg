<?php
global $flare_panels;
global $flare_sections;
global $flare_settings_controls;
global $flare_repeated_settings_controls;
global $flare_customizer_defaults;

/*defaults values feature portfolio options*/
$flare_customizer_defaults['flare-feature-slider-enable'] = 0;
$flare_customizer_defaults['flare-featured-slider-number'] = 2;
$flare_customizer_defaults['flare-featured-slider-selection'] = 'from-page';
$flare_customizer_defaults['flare-featured-slider-pages'] = 0;
$flare_customizer_defaults['flare-fs-single-words'] = 30;
$flare_customizer_defaults['flare-fs-enable-arrow'] = 1;
$flare_customizer_defaults['flare-fs-enable-button'] = 1;
$flare_customizer_defaults['flare-fs-button-text'] = __('View More','flare');
/*creating panel for fonts-setting*/

$flare_sections['flare-featured-slider'] =
    array(
        'priority'       => 150,
        'title'          => __( 'Home Main Slider', 'flare' ),
   	);


/*Feature section enable control*/
$flare_settings_controls['flare-feature-slider-enable'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-feature-slider-enable']
        ),
        'control' => array(
            'label'                 =>  __( 'Enable Feature Slider', 'flare' ),
            'section'               => 'flare-featured-slider',
        	'description'    		=> __( 'Enable "Feature slider" on checked' , 'flare' ),
            'type'                  => 'checkbox',
            'priority'              => 5,
            'active_callback'       => ''
        )
    );

/*No of feature slider selection*/
$flare_settings_controls['flare-featured-slider-number'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-featured-slider-number']
        ),
        'control' => array(
            'label'                 =>  __( 'Number Of Slider', 'flare' ),
            'section'               => 'flare-featured-slider',
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

/*creating setting control for flare-fs-page start*/
$flare_repeated_settings_controls['flare-featured-slider-pages'] =
    array(
        'repeated' => 3,
        'flare-fs-pages-ids' => array(
            'setting' =>     array(
                'default'              => $flare_customizer_defaults['flare-featured-slider-pages'],
            ),
            'control' => array(
                'label'                 =>  __( 'Select Page For Slide %s', 'flare' ),
                'section'               => 'flare-featured-slider',
                'type'                  => 'dropdown-pages',
                'priority'              => 60,
                'description'           => ''
            )
        ),
    );


$flare_settings_controls['flare-fs-single-words'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-fs-single-words']
        ),
        'control' => array(
            'label'                 =>  __( 'Single Slider- Number Of Words', 'flare' ),
            'description'           =>  __( 'If you do not have selected from - Custom', 'flare' ),
            'section'               => 'flare-featured-slider',
            'type'                  => 'number',
            'priority'              => 5,
            'active_callback'       => '',
            'input_attrs' => array( 'min' => 1, 'max' => 200),
        )
    );

$flare_settings_controls['flare-fs-enable-arrow'] =
    array(
        'setting' =>     array(
            'default'              => $flare_customizer_defaults['flare-fs-enable-arrow']
        ),
        'control' => array(
            'label'                 =>  __( 'Enable Arrow', 'flare' ),
            'section'               => 'flare-featured-slider',
            'type'                  => 'checkbox',
            'priority'              => 50,
            'active_callback'       => ''
        )
    );
    $flare_settings_controls['flare-fs-enable-button'] =
        array(
            'setting' =>     array(
                'default'              => $flare_customizer_defaults['flare-fs-enable-button']
            ),
            'control' => array(
                'label'                 =>  __( 'Enable Button', 'flare' ),
                'section'               => 'flare-featured-slider',
                'type'                  => 'checkbox',
                'priority'              => 85,
                'active_callback'       => ''
            )
        );
