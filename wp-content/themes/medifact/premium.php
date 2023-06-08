<?php
$medifact_view_demo = esc_html( __( 'View Demo', 'medifact'));
$medifact_upgrade_to_pro = esc_html( __( 'Upgrade To Pro', 'medifact' ));

 
function medifact_theme_page() {
	$title = esc_html(__('Medifact Theme','medifact'));
	add_theme_page( 
		esc_html(__( 'Upgrade To Medifact Pro','medifact')),
		$title.'', 
		'edit_theme_options',
		'medifact_upgrade',
		'medifact_display_upgrade'
	);
}

add_action('admin_menu','medifact_theme_page');


function medifact_display_upgrade() {
  $theme_data = wp_get_theme('medifact'); 
    
    // Check for current viewing tab
    $tab = null;
    if ( isset( $_GET['tab'] ) ) {
        $tab = $_GET['tab'];
    } else {
        $tab = null;
    } 
     
    $pro_theme_url = 'http://www.hubblethemes.com/recommends/medifact-pro-details/';
    $pro_theme_demo = 'http://www.hubblethemes.com/recommends/medifact-pro-demo/';
    $doc_url  = 'http://hubblethemes.com/docs/medifact-doc/index.html';
    $support_url = 'https://wordpress.org/support/theme/medifact';   
    $rating_url = 'https://wordpress.org/support/theme/medifact/reviews/?filter=5';   
    
    $current_action_link =  admin_url( 'themes.php?page=medifact_upgrade&tab=pro_features' ); ?>
    <style>
	.about-wrap .about-text {
		margin: 0em 0px 0em 0  !important;;
		margin-bottom: 25px !important;
		min-height: 60px;
		color: #555d66;
	}
	.about-wrap {		
		max-width: 1200px;	
	}
	</style>
	<div class="construction-zone-wrapper about-wrap">
        <h1><?php printf(esc_html__('Welcome to %1$s - Version %2$s', 'medifact'), $theme_data->Name ,$theme_data->Version ); ?></h1><?php
       	printf( __('<div class="about-text"> Medifact as its named it is related to medical science related websites. Theme is responsive and fully editable free medical WordPress theme. It is Fully customizable and retina ready WordPress theme with SEO compatibility. The standard of them is based on the bootstrap and HTML 5. Theme supports fa icons. This theme can be used for hospital, Yoga, medical agency. pharma company, medical stores and much more. Theme can be also useful for dental, clinics, meditation and any type of healthcare services. You can develo your website without no coding skills within few miutes. You can add slider, services, callout, footer and sidebar widgets. Theme is SEO friendly and support gutenberg. Theme have responsive in design. Fast loading blogging is main feature of the theme. Theme can be use for not only medical blogger but also can be used by any kind of blogging website. Demo - http://www.hubblethemes.com/medifact-demo/  and you can find documentation files here - http://hubblethemes.com/docs/medifact-doc/index.html', 'medifact') ); ?>
       <p class="upgrade-btn"><a class="upgrade" href="<?php echo esc_url($pro_theme_url); ?>" target="_blank"><?php printf( __( 'Upgrade To %1s Pro', 'medifact'), $theme_data->Name ); ?></a></p>
       <p class="upgrade-btn"><a class="upgrade upgrade-btm" href="<?php echo esc_url($pro_theme_url); ?>" target="_blank"><?php printf( __( 'Premium Demo', 'medifact'), $theme_data->Name ); ?></a></p>

	   <h2 class="nav-tab-wrapper">
	        <a href="?page=medifact_upgrade" class="nav-tab<?php echo is_null($tab) ? ' nav-tab-active' : null; ?>"><?php echo $theme_data->Name; ?></a>
<a href="?page=medifact_upgrade&tab=pro_features" class="nav-tab<?php echo $tab == 'pro_features' ? ' nav-tab-active' : null; ?>"><?php esc_html_e( 'PRO Features', 'medifact' );  ?></a>
            <a href="?page=medifact_upgrade&tab=free_vs_pro" class="nav-tab<?php echo $tab == 'free_vs_pro' ? ' nav-tab-active' : null; ?>"><?php esc_html_e( 'Free VS PRO', 'medifact' ); ?></a>
	        <?php do_action( 'medifact_admin_more_tabs' ); ?>
	    </h2>      

        <?php if ( is_null( $tab ) ) { ?>
            <div class="theme_info info-tab-content">
                <div class="theme_info_column clearfix">
                    <div class="theme_info_left">
                        <div class="theme_link">
                            <h3><?php esc_html_e( 'Theme Customizer', 'medifact' ); ?></h3>
                            <p class="about"><?php printf(esc_html__('%s supports the Theme Customizer for all theme settings. Click "Customize" to start customize your site.', 'medifact'), $theme_data->Name); ?></p>
                            <p>
                                <a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary"><?php esc_html_e('Start Customize', 'medifact'); ?></a>
                            </p>
                        </div>
                        <div class="theme_link">
                            <h3><?php esc_html_e( 'Theme Documentation', 'medifact' ); ?></h3>
                            <p class="about"><?php printf(esc_html__('Need any help to setup and configure %s? Please have a look at our documentations instructions.', 'medifact'), $theme_data->Name); ?></p>
                            <p>
                                <a href="<?php echo esc_url($doc_url); ?>" target="_blank" class="button button-secondary"><?php esc_html_e(' Documentation', 'medifact'); ?></a>
                            </p>
                            <?php do_action( 'medifact_dashboard_theme_links' ); ?>
                        </div>  
                        <div class="theme_link">
                            <h3><?php esc_html_e( 'Having Trouble, Need Support?', 'medifact' ); ?></h3>
                            <p class="about"><?php printf(esc_html__('Support for %s WordPress theme is conducted through Genex free support ticket system.', 'medifact'), $theme_data->Name); ?></p>
                            <p>  
                                <a href="<?php echo esc_url($support_url); ?>" target="_blank" class="button button-secondary"><?php echo sprintf( esc_html('Create a support ticket', 'medifact'), $theme_data->Name); ?></a>
                            </p>
                        </div> 
						 <div class="theme_link">
                            <h3><?php esc_html_e( 'Please Rate Us', 'medifact' ); ?></h3>
                            <p class="about"><?php printf(esc_html__('We need your help to expand or and portoflio so please rate us on wordpress ', 'medifact'), $theme_data->Name); ?></p>
                            <p>  
                                <a href="<?php echo esc_url($rating_url); ?>" target="_blank" class="button button-secondary"><?php echo sprintf( esc_html('Rate This Theme', 'medifact'), $theme_data->Name); ?></a>
                            </p>
                        </div> 
                       
                    </div>  
                    <div class="theme_info_right">
                        <?php echo sprintf ( '<img src="'. get_template_directory_uri() .'/screenshot.png" alt="%1$s" />',__('Theme screenshot','medifact') ); ?>
                    </div>
                </div> 
            </div>
        <?php } ?>
	
        <?php if ( $tab == 'pro_features' ) { ?>
            <div class="pro-features-tab info-tab-content">
			 
				<div class="wrap clearfix">
			   <div class="one-third column clear">
<div class="icon-wrap"><i class="fa  fa-5x fa-cog"></i></div>
	<h3><?php echo esc_html(__( '3 Home Pages', 'medifact' )); ?></h3>
	<p><?php echo esc_html(__('Theme consits 3 Home page designs. You can choose anyone its your choice.', 'medifact' )); ?></p>
</div>
<div class="one-third column">
	<div class="icon-wrap"><i class="fa  fa-5x fa-th-large"></i></div>
	<h3><?php echo esc_html(__( '2 Header Preset', 'medifact' )); ?></h3>
	<p><?php echo esc_html(__('Theme have 2 tytpes of header preset so you can change and select your header design', 'medifact' )); ?></p>
</div>
<div class="one-third column">
	<div class="icon-wrap"><i class="fa  fa-5x fa-th"></i></div>
	<h3><?php echo esc_html(__( 'Maga Menu Support', 'medifact' )); ?></h3>
	<p><?php echo esc_html(__( 'Theme support Mega Menu So you can show navigation in great look.', 'medifact' )); ?></p>
</div>

<div class="one-third column clear">
	<div class="icon-wrap"><i class="fa  fa-5x fa-envelope"></i></div>
	<h3><?php echo esc_html(__( 'Contact Form 7', 'medifact' )); ?></h3>
	<p><?php echo esc_html(__( 'Medifact Pro support contact form 7 that mean you can easily add your contact form with theme design ', 'medifact' )); ?></p>
</div>
<div class="one-third column">
	<div class="icon-wrap"><i class="fa  fa-5x fa-list-alt"></i></div>
	<h3><?php echo esc_html(__( 'Projects Page', 'medifact' )); ?> </h3>
	<p><?php echo esc_html(__( 'Theme have amazing Projctes design Grid+ Masonry with you can use 2, 3, or 4 Columns layouts!', 'medifact' )); ?> </p>
</div>
<div class="one-third column">
	<div class="icon-wrap"><i class="fa  fa-5x fa-font"></i></div>
	<h3><?php echo esc_html(__( 'Typography', 'medifact' )); ?></h3>
	<p><?php echo esc_html(__('Theme loves typography, you can choose from over 500+ Google Fonts and Standard Fonts to customize your site!', 'medifact' )); ?></p>
</div>
<div class="one-third column clear">
	<div class="icon-wrap"><i class="fa  fa-5x fa-slideshare"></i></div>
	<h3><?php echo esc_html(__( 'Unlimitd Image Slides', 'medifact' )); ?></h3>
	<p><?php echo esc_html(__('Theme includes Flex slider . You can add unlimited slides on your home page', 'medifact' )); ?></p>
</div>
<div class="one-third column">
	<div class="icon-wrap"><i class="fa  fa-5x fa-user"></i></div>
	<h3><?php echo esc_html(__( 'Team Page', 'medifact' )); ?></h3>
	<p><?php echo esc_html(__('You can add unlimited team members with team deatil page and also display their social profiles ', 'medifact' )); ?></p>
</div>
<div class="one-third column">
	<div class="icon-wrap"><i class="fa  fa-5x fa-magic"></i></div>
	<h3><?php echo esc_html(__( 'Retina Ready', 'medifact' )); ?></h3>
	<p><?php echo esc_html(__( 'Theme is Retina Ready. So, Everything looks amazingly sharp and crisp on high resolution retina displays of all sizes!', 'medifact' )); ?></p>
</div>
<div class="one-third column clear">
	<div class="icon-wrap"><i class="fa  fa-5x fa-dashboard"></i></div>
	<h3><?php echo esc_html(__( 'Awesome Icons', 'medifact' )); ?></h3>
	<p><?php echo esc_html(__( ' Choose from over 2500+ icons are fully integrated into the theme ', 'medifact' )); ?></p>
</div>
<div class="one-third column">
	<div class="icon-wrap"><i class="fa  fa-5x fa-magic"></i></div>
	<h3><?php echo esc_html(__( 'Excellent Support', 'medifact' )); ?></h3>
	<p><?php echo esc_html(__( 'We truly care about our customers and themes performance. We assure you that you will get the best after sale support like never before!', 'medifact' ));
 ?></p>
</div>
<div class="one-third column">
	<div class="icon-wrap"><i class="fa  fa-5x fa-desktop"></i></div>
	<h3><?php echo esc_html(__( 'Responsive Layout', 'medifact' )); ?></h3>
	<p><?php echo esc_html( __('Theme is fully responsive and can adapt to any screen size. Resize your browser window to view it!', 'medifact' )); ?></p>
</div>
<div class="one-third column clear">
	<div class="icon-wrap"><i class="fa  fa-5x fa-rocket"></i></div>
	<h3><?php echo esc_html( __( 'Testimonials', 'medifact' ));?></h3>
	<p><?php echo  esc_html( __( 'Display your clients\' glowing comments about your business on your homepage. Showing a specific number of testimonials with use of testimonial widget. ', 'medifact' ));?></p>
</div>
<div class="one-third column">
	<div class="icon-wrap"><i class="fa  fa-5x fa-skype"></i></div>
	<h3><?php echo esc_html( __( 'Social Media', 'medifact' )); ?></h3>
	<p><?php echo esc_html( __( 'Want your users to stay in touch? No problem, Theme has Social Media icons all throughout the theme!', 'medifact' )); ?></p>
</div>
<div class="one-third column">
	<div class="icon-wrap"><i class="fa  fa-5x fa-map-marker"></i></div>
	<h3><?php echo esc_html( __( 'Add Timeline', 'medifact' )); ?></h3>
	<p><?php echo esc_html( __('This Theme includes Timeline shortcode, So you can easily display your company history to your visitors or  your clients', 'medifact' )); ?></p>
</div>

<div class="one-third column clear">
	<div class="icon-wrap"><i class="fa  fa-5x fa-edit"></i></div>
	<h3><?php echo esc_html( __( 'Customization', 'medifact' )); ?></h3>
	<p><?php echo esc_html( __('With advanced theme options, page options & extensive docs, its never been easier to customize a theme!', 'medifact' )); ?></p>
</div>
<div class="one-third column">
	<div class="icon-wrap"><i class="fa  fa-5x fa-check"></i></div>
	<h3><?php echo esc_html( __( 'Demo content', 'medifact' )); ?></h3>
	<p><?php echo esc_html( __('Theme includes single click demo content. You can quickly setup the site like our demo and get started easily!', 'medifact' )); ?></p>
</div>
<div class="one-third column">
	<div class="icon-wrap"><i class="fa  fa-5x fa-signal"></i></div>
	<h3><?php echo esc_html( __( 'Improvement', 'medifact' )); ?></h3>
	<p><?php echo esc_html( __('We love our theme and customers. We are committed to improve and add new features to Theme!', 'medifact' )); ?></p>
</div>

				</div>
			</div><?php   
		} ?>  

       <!-- Free VS PRO tab -->
        <?php if ( $tab == 'free_vs_pro' ) { ?>
            <div class="free-vs-pro-tab info-tab-content">
	            <div id="free_pro">
	                <table class="free-pro-table">
		                <thead>
			                <tr>
			                    <th></th>  
			                    <th><?php echo esc_html($theme_data->Name); ?> Lite</th>
			                    <th><?php echo esc_html($theme_data->Name); ?> PRO</th>
			                </tr>
		                </thead>
		                <tbody>
		                    <tr>
		                        <td><h3><?php _e('Responsive Design', 'medifact'); ?></h3></td>
		                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                    </tr>
		                    <tr>
		                        <td><h3><?php _e('Support', 'medifact'); ?></h3></td>
		                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                    </tr>		                    
		                    <tr>
		                        <td><h3><?php _e('Custom Logo Option', 'medifact'); ?></h3></td>
		                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                    </tr>
		                    <tr>
		                         <td><h3><?php _e('Social Links', 'medifact'); ?></h3></td>
		                         <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                         <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                    </tr>
		                    <tr>
		                    	 <td><h3><?php _e('Unlimited color option', 'medifact'); ?></h3></td>
		                    	 <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
		                    	 <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                    </tr>
		                    <tr>
		                    	 <td><h3><?php _e('3 Home page', 'medifact'); ?></h3></td>
		                    	 <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
		                    	 <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                    </tr>
							 <tr>
		                    	 <td><h3><?php _e('Header Presets', 'medifact'); ?></h3></td>
		                    	 <td class="only-pro"><?php _e('1', 'medifact'); ?></td>
		                    	 <td class="only-lite"><?php _e('4', 'medifact'); ?></td>
		                    </tr>	
		                     <tr>
		                    	 <td><h3><?php _e('Pre Defined Page Templates', 'medifact');?></h3></td>
		                    	 <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
		                    	 <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                    </tr>
		                    <tr>
		                    	<td><h3><?php _e('4 Portfolio Presets', 'medifact');?></h3></td>
		                    	<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
		                    	<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                    </tr>
		                    <tr>
		                    	<td><h3><?php _e('Team With Detail Page', 'medifact');?></h3></td>
		                    	<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
		                    	<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                    </tr>
		                    <tr>
		                    	<td><h3><?php _e('Redux Theme Option Panel', 'medifact');?></h3></td>
		                    	<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
		                    	<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                    </tr> 
							 
							<tr>
		                    	<td><h3><?php _e('Sticky Header Option', 'medifact');?></h3></td>
		                    	<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
		                    	<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                    </tr>
							<tr>
		                    	<td><h3><?php _e('Contact Form 7', 'medifact');?></h3></td>
		                    	<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
		                    	<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                    </tr>
							<tr>
		                    	<td><h3><?php _e('15+ Shortcodes', 'medifact');?></h3></td>
		                    	<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
		                    	<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                    </tr>
							<tr>
		                    	<td><h3><?php _e('Google Fonts', 'medifact');?></h3></td>
		                    	<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
		                    	<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                    </tr>
							 
		                     <tr>
		                    	<td><h3><?php _e('Multiple Service Layouts', 'medifact');?></h3></td>
		                    	<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
		                    	<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                    </tr>
							 <tr>
		                    	<td><h3><?php _e('Team Page', 'medifact');?></h3></td>
		                    	<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
		                    	<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                    </tr>
		                     <tr>
		                    	<td><h3><?php _e('Multiple Blog Layouts', 'medifact');?></h3></td>
		                    	<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
		                    	<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                    </tr>
		                     <tr>
		                    	<td><h3><?php _e('Page Animation', 'medifact');?></h3></td>
		                    	<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
		                    	<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                    </tr>
		                     <tr>
		                    	<td><h3><?php _e('Premium Priority Support', 'medifact');?></h3></td>
		                    	<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
		                    	<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
		                    </tr>
		                    
		                    <tr class="ti-about-page-text-center">
		                        <td><a href="<?php echo esc_url($pro_theme_demo); ?>" target="_blank" class="button button-primary button-hero"><?php printf( __( '%1s Pro Demo', 'medifact'), $theme_data->Name ); ?></a></td>
		                    	<td colspan="2"><a href="<?php echo esc_url($pro_theme_url); ?>" target="_blank" class="button button-primary button-hero"><?php printf( __( 'Upgrade To %1s Pro', 'medifact'), $theme_data->Name ); ?></a></td>
		                    </tr>
		                </tbody>
	                </table>			    
				</div>
			</div><?php 
		} ?>

    </div><?php
} ?>
