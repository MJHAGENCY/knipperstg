<?php
/**
 * Medifact Theme Customizer
 *
*/

function medifact_customize_register( $wp_customize ) {

	// Medifact theme choice options
	if (!function_exists('medifact_section_choice_option')) :
		function medifact_section_choice_option()
		{
			$medifact_section_choice_option = array(
				'show' => esc_html__('Show', 'medifact'),
				'hide' => esc_html__('Hide', 'medifact')
			);
			return apply_filters('medifact_section_choice_option', $medifact_section_choice_option);
		}
	endif;
	
	/**
	 * Sanitizing the select callback example
	 *
	 */
	if ( !function_exists('medifact_sanitize_select') ) :
		function medifact_sanitize_select( $input, $setting ) {

			// Ensure input is a slug.
			$input = sanitize_text_field( $input );

			// Get list of choices from the control associated with the setting.
			$choices = $setting->manager->get_control( $setting->id )->choices;

			// If the input is a valid key, return it; otherwise, return the default.
			return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
		}
	endif;
	
	
	
		/**
	 * Important Link
	*/
	 class medifact_theme_info_text extends WP_Customize_Control{
        public function render_content(){  ?>
            <span class="customize-control-title">
                <?php echo esc_html( $this->label ); ?>
            </span>
            <?php if($this->description){ ?>
                <span class="description customize-control-description">
                <?php echo wp_kses_post($this->description); ?>
                </span>
            <?php }
        }
    }
	
	 
	
	$wp_customize->add_section( 'medifact_implink_section', array(
	  'title'       => esc_html__( 'Important Links', 'medifact' ),
	  'priority'      => 200
	) );

	    $wp_customize->add_setting( 'medifact_imp_links', array(
	      'sanitize_callback' => 'medifact_text_sanitize'
	    ));

	    $wp_customize->add_control( new medifact_theme_info_text( $wp_customize,'medifact_imp_links', array(
	        'settings'    => 'medifact_imp_links',
	        'section'   => 'medifact_implink_section',
	        'description' => '<a class="implink" href="http://hubblethemes.com/docs/medifact-doc/index.html" target="_blank">'.esc_html__('Documentation', 'medifact').'</a><a class="implink" href="http://www.hubblethemes.com/recommends/medifact-pro-details/" target="_blank">'.esc_html__('Live Demo', 'medifact').'</a><a class="implink" href="https://wordpress.org/support/theme/medifact" target="_blank">'.esc_html__('Support Forum', 'medifact').'</a>',
	      )
	    ));

	    $wp_customize->add_setting( 'medifact_rate_us', array(
	      'sanitize_callback' => 'medifact_text_sanitize'
	    ));

	    $wp_customize->add_control( new medifact_theme_info_text( $wp_customize, 'medifact_rate_us', array(
	          'settings'    => 'medifact_rate_us',
	          'section'   => 'medifact_implink_section',
              /* translators: 1.text 2.theme */
	          'description' => sprintf(__( 'Please do rate our theme if you liked it %1$s', 'medifact'), '<a class="implink" href="https://wordpress.org/support/theme/medifact/reviews/?filter=5" target="_blank">'.esc_html__('Rate/Review','medifact').'</a>' ),
	        )
	    ));

	    

	/**
	 * Drop-down Pages sanitization callback example.
	 *
	 * - Sanitization: dropdown-pages
	 * - Control: dropdown-pages
	 * 
	 * Sanitization callback for 'dropdown-pages' type controls. This callback sanitizes `$page_id`
	 * as an absolute integer, and then validates that $input is the ID of a published page.
	 * 
	 * @see absint() https://developer.wordpress.org/reference/functions/absint/
	 * @see get_post_status() https://developer.wordpress.org/reference/functions/get_post_status/
	 *
	 * @param int                  $page    Page ID.
	 * @param WP_Customize_Setting $setting Setting instance.
	 * @return int|string Page ID if the page is published; otherwise, the setting default.
	 */
	function medifact_sanitize_dropdown_pages( $page_id, $setting ) {
		// Ensure $input is an absolute integer.
		$page_id = absint( $page_id );
		
		// If $page_id is an ID of a published page, return it; otherwise, return the default.
		return ( 'publish' == get_post_status( $page_id ) ? $page_id : $setting->default );
	}

	/** Front Page Section Settings starts **/	

	$wp_customize->add_panel('frontpage', 
	    array(
            'title' => esc_html__('Medifact Options', 'medifact'),        
			'description' => '',                                        
			'priority' => 3,
		)
	);

	// Header info
    $wp_customize->add_section('medifact_header_section', 
    	array(
		    'title'   => esc_html__('Top Header Info Section', 'medifact'),
		    'description' => '',
		    'panel' => 'frontpage',
		    'priority'    => 130
        )
    );
	
    $wp_customize->add_setting('medifact_header_faq_url', 
    	array(
		    'default'   => '',
		    'type'      => 'theme_mod',
		    'sanitize_callback' => 'esc_url_raw'
        )
    );

    $wp_customize->add_control('medifact_header_faq_url', 
    	array(
		    'label'   => esc_html__('FAQ URL', 'medifact'),
		    'section' => 'medifact_header_section',
		    'priority'  => 3
        )
    );

    $wp_customize->add_setting('medifact_header_helpdesk', 
    	array(
		    'default'   =>  '',
		    'type'      => 'theme_mod',
		    'sanitize_callback' => 'esc_url_raw'
        )
    );

    $wp_customize->add_control('medifact_header_helpdesk', 
    	array(
		    'label'   => esc_html__('Helpdesk URL', 'medifact'),
		    'section' => 'medifact_header_section',
		    'priority'  => 7
        )
    );

	$wp_customize->add_setting('medifact_header_support', 
		array(
		    'default'   => '',
		    'type'      => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw'
        )
	);

    $wp_customize->add_control('medifact_header_support', 
    	array(
		    'label'   => esc_html__('Support URL', 'medifact'),
		    'section' => 'medifact_header_section',
		    'priority'  => 9
        )
    );

    $wp_customize->add_setting('medifact_header_openting_hours',
        array(
		    'default'   => '',
		    'type'      => 'theme_mod',
		    'sanitize_callback' => 'sanitize_text_field'
        )
    );

    $wp_customize->add_control('medifact_header_openting_hours', 
    	array(
		    'label'   => esc_html__('Opening Hours', 'medifact'),
		    'section' => 'medifact_header_section',
		    'priority'  => 11
        )
    );
	$wp_customize->add_setting('medifact_header_social_link_1', 
    	array(
		    'default'   =>  '',
		    'type'      => 'theme_mod',
		    'sanitize_callback' => 'esc_url_raw'
        )
    );

    $wp_customize->add_control('medifact_header_social_link_1', 
    	array(
		    'label'   => esc_html__('Facebook URL', 'medifact'),
		    'section' => 'medifact_header_section',
		    'priority'  => 12
        )
    );

	$wp_customize->add_setting('medifact_header_social_link_2', 
		array(
		    'default'   => '',
		    'type'      => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw'
        )
	);

    $wp_customize->add_control('medifact_header_social_link_2', 
    	array(
		    'label'   => esc_html__('Twitter URL', 'medifact'),
		    'section' => 'medifact_header_section',
		    'priority'  => 14
        )
    );

    $wp_customize->add_setting('medifact_header_social_link_3',
        array(
		    'default'   => '',
		    'type'      => 'theme_mod',
		    'sanitize_callback' => 'esc_url_raw'
        )
    );

    $wp_customize->add_control('medifact_header_social_link_3', 
    	array(
		    'label'   => esc_html__('Linkedin URL', 'medifact'),
		    'section' => 'medifact_header_section',
		    'priority'  => 16
        )
    );

    $wp_customize->add_setting('medifact_header_social_link_4',
        array(
		    'default'   => '',
		    'type'      => 'theme_mod',
		    'sanitize_callback' => 'esc_url_raw'
        )
    );

    $wp_customize->add_control('medifact_header_social_link_4', 
    	array(
		    'label'   => esc_html__('Instagram URL', 'medifact'),
		    'section' => 'medifact_header_section',
		    'priority'  => 18
        )
    );


     

	/** Slider Section Settings starts **/
   
    // Panel - Slider Section 1
    $wp_customize->add_section('sliderinfo', 
        array(
	        'title'   => esc_html__('Home Slider Section', 'medifact'),
	        'description' => '',
		    'panel' => 'frontpage',
	        'priority'    => 140
        )
    );

	
    $slider_no = 3;
	for( $i = 1; $i <= $slider_no; $i++ ) {
		$medifact_slider_page =   'medifact_slider_page_' .$i;
		$medifact_slider_btntxt = 'medifact_slider_btntxt_' . $i;
		$medifact_slider_btnurl = 'medifact_slider_btnurl_' .$i;
		 

		$wp_customize->add_setting( $medifact_slider_page,
			array(
				'default'           => 1,
				'sanitize_callback' => 'medifact_sanitize_dropdown_pages',
			)
		);

		$wp_customize->add_control( $medifact_slider_page,
			array(
				'label'    			=> esc_html__( 'Slider Page ', 'medifact' ) .$i,
				'section'  			=> 'sliderinfo',
				'type'     			=> 'dropdown-pages',
				'priority' 			=> 100,
			)
		);

		$wp_customize->add_setting( $medifact_slider_btntxt,
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control( $medifact_slider_btntxt,
			array(
				'label'    			=> esc_html__( 'Slider Button Text','medifact' ),
				'section'  			=> 'sliderinfo',
				'type'     			=> 'text',
				'priority' 			=> 100,
			)
		);
		
		$wp_customize->add_setting( $medifact_slider_btnurl,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control( $medifact_slider_btnurl,
			array(
				'label'    			=> esc_html__( 'Button URL', 'medifact' ),
				'section'  			=> 'sliderinfo',
				'priority' 			=> 100,
			)
		);
    }
    /** Slider Section Settings end **/
		
		$wp_customize->add_section('services',              
			array(
				'title' => esc_html__('Home service Section', 'medifact'),          
				'description' => '',             
				'panel' => 'frontpage',		 
				'priority' => 150,
			)
		);
			
		$wp_customize->add_setting('medifact_services_section_hideshow',
			array(
				'default' => 'hide',
				'sanitize_callback' => 'medifact_sanitize_select',
			)
		);

		$medifact_services_section_hide_show_option = medifact_section_choice_option();

		$wp_customize->add_control('medifact_services_section_hideshow',
			array(
				'type' => 'radio',
				'label' => esc_html__('Services Option', 'medifact'),
				'description' => esc_html__('Show/hide option Section.', 'medifact'),
				'section' => 'services',
				'choices' => $medifact_services_section_hide_show_option,
				'priority' => 1
			)
		);
 
    // services title
    $wp_customize->add_setting('medifact-services_title', 
    	array(
		    'default'   => '',
		    'type'      => 'theme_mod',
		    'sanitize_callback'	=> 'sanitize_text_field'
        )
    );

    $wp_customize->add_control('medifact-services_title', 
    	array(
		    'label'   => esc_html__('Service Section Title', 'medifact'),
		    'section' => 'services',
		    'priority'  => 3
        )
    );

    $wp_customize->add_setting(
		'medifact-services_subtitle',
		array(
			'default'   => '',
			'type'      => 'theme_mod',
			'sanitize_callback'	=> 'sanitize_text_field'
		)
	);

    $wp_customize->add_control(
		'medifact-services_subtitle',
		array(
			'label'   => esc_html__('Service Section Subtitle', 'medifact'),
			'section' => 'services',	  
			'priority'  => 4
		)
	);
	
    $services_no = 6;
	for( $i = 1; $i <= $services_no; $i++ ) {
		$medifact_servicespage = 'medifact_services_page_' . $i;
		$serviceicon = 'medifact_page_service_icon_' . $i;
		
		// Setting - Feature Icon
		$wp_customize->add_setting( $serviceicon,
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control( $serviceicon,
			array(
				'label'    			=> esc_html__( 'Service Icon ', 'medifact' ).$i,
				'description' =>  __('Select a icon in this list <a target="_blank" href="https://fontawesome.com/v4.7.0/icons/">Font Awesome icons</a> and enter the class name','medifact'),
				'section'  			=> 'services',
				'type'     			=> 'text',
				'priority' 			=> 100,
			)
		);
		
		$wp_customize->add_setting( $medifact_servicespage,
			array(
				'default'           => 1,
				'sanitize_callback' => 'medifact_sanitize_dropdown_pages',
			)
		);

		$wp_customize->add_control( $medifact_servicespage,
			array(
				'label'    			=> esc_html__( 'Services Page ', 'medifact' ) .$i,
				'section'  			=> 'services',
				'type'     			=> 'dropdown-pages',
				'priority' 			=> 100,
			)
		);
    }
    
    
    //  Projects section
	$wp_customize->add_section('projects',              
		array(
			'title' => esc_html__('Home Project Section', 'medifact'),          
			'description' => '',             
			'panel' => 'frontpage',		 
			'priority' => 160,
		)
	);
		
	$wp_customize->add_setting('medifact_projects_section_hideshow',
	    array(
	        'default' => 'hide',
	        'sanitize_callback' => 'medifact_sanitize_select',
	    )
    );

    $medifact_projects_section_hide_show_option = medifact_section_choice_option();

    $wp_customize->add_control('medifact_projects_section_hideshow',
		array(
		    'type' => 'radio',
		    'label' => esc_html__('Projects Option', 'medifact'),
		    'description' => esc_html__('Show/hide option Section.', 'medifact'),
		    'section' => 'projects',
		    'choices' => $medifact_projects_section_hide_show_option,
		    'priority' => 1
		)
	);
    // projects title
    $wp_customize->add_setting('medifact-projects_title', 
    	array(
		    'default'   => '',
		    'type'      => 'theme_mod',
		    'sanitize_callback'	=> 'sanitize_text_field'
        )
    );

    $wp_customize->add_control('medifact-projects_title', 
    	array(
		    'label'   => esc_html__('Projects Section Title', 'medifact'),
		    'section' => 'projects',
		    'priority'  => 3
        )
    );
	
    $projects_no = 6;
	for( $i = 1; $i <= $projects_no; $i++ ) {
		$medifact_projectspage = 'medifact_projects_page_' . $i;
		
		$wp_customize->add_setting( $medifact_projectspage,
			array(
				'default'           => 1,
				'sanitize_callback' => 'medifact_sanitize_dropdown_pages',
			)
		);

		$wp_customize->add_control( $medifact_projectspage,
			array(
				'label'    			=> esc_html__( 'Projects Page ', 'medifact' ) .$i,
				'section'  			=> 'projects',
				'type'     			=> 'dropdown-pages',
				'priority' 			=> 100,
			)
		);
    }

    // Blog section
	$wp_customize->add_section('medifact-blog_info', 
		array(
		    'title'   => esc_html__('Home Blog Section', 'medifact'),
		    'description' => '',
			'panel' => 'frontpage',
		    'priority'    => 170
        )
	);
	
	$wp_customize->add_setting('medifact_blog_section_hideshow',
	    array(
	        'default' => 'show',
	        'sanitize_callback' => 'medifact_sanitize_select',
	    )
	);
    
    $medifact_blog_section_hide_show_option = medifact_section_choice_option();
    
    $wp_customize->add_control('medifact_blog_section_hideshow',
	    array(
	        'type' => 'radio',
	        'label' => esc_html__('Blog Option', 'medifact'),
	        'description' => esc_html__('Show/hide option for Blog Section.', 'medifact'),
	        'section' => 'medifact-blog_info',
	        'choices' => $medifact_blog_section_hide_show_option,
	        'priority' => 1
	    )
    );
	
	$wp_customize->add_setting('medifact_blog_title', 
		array(
		    'default'   => '',
		    'type'      => 'theme_mod',
			'sanitize_callback'	=> 'sanitize_text_field'
        )
	);

    $wp_customize->add_control('medifact_blog_title', 
    	array(
            'label'   => esc_html__('Blog Title', 'medifact'),
            'section' => 'medifact-blog_info',
            'priority'  => 1
        )
    );
    
    // clients logo  info
	$wp_customize->add_section(
		'clients_logo', 
		array(
			'title'   => esc_html__('Home Clients logo Section', 'medifact'),
			'description' => '',
			'panel' => 'frontpage', 
			'priority'        => 170
		)
	);

	$wp_customize->add_setting(
		'medifact_clients_section_hideshow',
		array(
			'default' => 'hide',
			'sanitize_callback' => 'medifact_sanitize_select',
		)
	);

	$medifact_section_choice_option = medifact_section_choice_option();
	$wp_customize->add_control(
		'medifact_clients_section_hideshow',
		array(
			'type' => 'radio',
			'label' => esc_html__('Clients-logo', 'medifact'),
			'description' => esc_html__('Show/hide option for Clients-logo Section.', 'medifact'),
			'section' => 'clients_logo',
			'choices' => $medifact_section_choice_option,
			'priority' => 5
		)
	);

	$client_no = 6;
	for( $i = 1; $i <= $client_no; $i++ ) 
	{
		$medifact_client_logo = 'medifact_client_logo_' . $i;   

		$wp_customize->add_setting( 
			$medifact_client_logo,
			array(
				'default'           => 1,
				'sanitize_callback' => 'medifact_sanitize_dropdown_pages',
			)
		);
		$wp_customize->add_control( 
			$medifact_client_logo,
			array(
				'label'    			=> esc_html__( 'Client Page ', 'medifact' ) .$i,
				'section'  			=> 'clients_logo',
				'type'     			=> 'dropdown-pages',
				'priority' 			=> 100,
			)
		);

	}
  

    // Footer Section
	
    $wp_customize->add_section('medifact-footer_info', 
    	array(
		    'title'   => esc_html__('Footer Section', 'medifact'),
		    'description' => '',
		    'panel' => 'frontpage',
		    'priority'    => 170
        )
    );
	
	$wp_customize->add_setting('medifact_footer_section_hideshow',
	    array(
	        'default' => 'show',
	        'sanitize_callback' => 'medifact_sanitize_select',
	    )
    );
    
    $medifact_footer_section_hide_show_option = medifact_section_choice_option();
    
    $wp_customize->add_control('medifact_footer_section_hideshow',
	    array(
	        'type' => 'radio',
	        'label' => esc_html__('Footer Option', 'medifact'),
	        'description' => esc_html__('Show/hide option for Footer Section.', 'medifact'),
	        'section' => 'medifact-footer_info',
	        'choices' => $medifact_footer_section_hide_show_option,
	        'priority' => 1
	    )
    );

	$wp_customize->add_setting('medifact-footer_title', 
		array(
		    'default'   => '',
		    'type'      => 'theme_mod',
		    'sanitize_callback'	=> 'wp_kses_post'
        )
	);

    $wp_customize->add_control('medifact-footer_title', 
    	array(
		    'label'   => esc_html__('Copyright', 'medifact'),
		    'section' => 'medifact-footer_info',
		    'type'      => 'textarea',
		    'priority'  => 1
        )
    );
	/** Front Page Section Settings end **/	
}
add_action( 'customize_register', 'medifact_customize_register' );

