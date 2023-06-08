<?php
if ( ! function_exists( 'flare_before_footer' ) ) :
    /**
     * Footer content
     *
     * @since flare 1.0.0
     *
     * @param null
     * @return false | void
     *
     */
    function flare_before_footer() {
    ?>
    <?php if (  is_front_page() && !is_home() ) { ?>
        </main><!-- #main -->
            </div><!-- #primary -->
            </div>
    <?php } ?>

        <!-- *****************************************
             Footer section starts
    ****************************************** -->
    <footer id="colophon" class="wrapper site-footer" role="contentinfo">
    <?php
    }
endif;
add_action( 'flare_action_before_footer', 'flare_before_footer', 10 );

if ( ! function_exists( 'flare_widget_before_footer' ) ) :
    /**
     * Footer content
     *
     * @since flare 1.0.0
     *
     * @param null
     * @return false | void
     *
     */
    function flare_widget_before_footer() {
        global $flare_customizer_all_values;
        $flare_footer_widgets_number = $flare_customizer_all_values['flare-footer-sidebar-number'];
        if( $flare_footer_widgets_number <= 0 ){
            return false;
        }
        if( !is_active_sidebar( 'footer-col-one' ) && !is_active_sidebar( 'footer-col-two' ) && !is_active_sidebar( 'footer-col-three' ) && !is_active_sidebar( 'footer-col-four' )){
            return false;
        }
        if( 1 == $flare_footer_widgets_number ){
                $col = 'col-md-12';
            }
        elseif( 2 == $flare_footer_widgets_number ){
            $col = 'col-md-6 col-sm-6';
        }
        elseif( 3 == $flare_footer_widgets_number ){
            $col = 'col-md-4 col-sm-6';
        }
        elseif( 4 == $flare_footer_widgets_number ){
            $col = 'col-md-3 col-sm-6';
        }
        else{
            $col = 'col-md-3';
        }
        ?>
        <div class="footer-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <?php if( is_active_sidebar( 'footer-col-one' ) && $flare_footer_widgets_number > 0 ) : ?>
                            <div class="contact-list <?php echo esc_attr( $col );?>">
                                <?php dynamic_sidebar( 'footer-col-one' ); ?>
                            </div>
                        <?php endif; ?>
                        <?php if( is_active_sidebar( 'footer-col-two' ) && $flare_footer_widgets_number > 1 ) : ?>
                            <div class="contact-list <?php echo esc_attr( $col );?>">
                                <?php dynamic_sidebar( 'footer-col-two' ); ?>
                            </div>
                        <?php endif; ?>
                        <?php if( is_active_sidebar( 'footer-col-three' ) && $flare_footer_widgets_number > 2 ) : ?>
                            <div class="contact-list <?php echo esc_attr( $col );?>">
                                <?php dynamic_sidebar( 'footer-col-three' ); ?>
                            </div>
                        <?php endif; ?>
                        <?php if( is_active_sidebar( 'footer-col-four' ) && $flare_footer_widgets_number > 3 ) : ?>
                            <div class="contact-list <?php echo esc_attr( $col );?>">
                                <?php dynamic_sidebar( 'footer-col-four' ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
            </div>
            </div>   
         </div>   </div>
    <?php
    }
endif;
add_action( 'flare_action_widget_before_footer', 'flare_widget_before_footer', 10 );

if ( ! function_exists( 'flare_footer' ) ) :
    /**
     * Footer content
     *
     * @since flare 1.0.0
     *
     * @param null
     * @return null
     *
     */
    function flare_footer() {
        global $flare_customizer_all_values;
        ?>
                 <div class="clearfix"></div>
                <div class="full-width">
                    <div class="social-links-n-copy-right text-center">
                    <?php
                        if( has_nav_menu( 'social' ) ){
                    ?>
                        <div class="social-widget salient-social-section social-icon-only top-tooltip">
                            <?php
                                wp_nav_menu( array( 'theme_location' => 'social', 'link_before' => '<span>',
                                    'link_after'=>'</span>' , 'menu_id' => 'social-menu','fallback_cb' => false, ) );
                            ?>
                        </div>
                    <?php
                        }
                    ?>
                    <!-- footer site info -->
                        <div class="copy-right text-center">
                            <?php
                            if(isset($flare_customizer_all_values['flare-copyright-text'])){
                                echo wp_kses_post( $flare_customizer_all_values['flare-copyright-text'] );
                            }
                            ?>
                            <?php
                             if( 1 == $flare_customizer_all_values['flare-enable-theme-name']){
                                ?>
                            <span class="sep"> | </span>
                            <?php printf( esc_html__( 'Theme: %1$s by %2$s', 'flare' ), 'Flare', '<a href="http://salientthemes.com/" target = "_blank" rel="designer">salientthemes </a>' ); ?>
                            <?php
                            }
                            ?>
                        </div>
                    </div><!-- .social-links-n-copy-right -->
                </div><!-- .col-md-12 -->
           
    </footer><!-- #colophon -->
    <!-- *****************************************
             Footer section ends
    ****************************************** -->
    <?php
    }
endif;
add_action( 'flare_action_footer', 'flare_footer', 10 );

if ( ! function_exists( 'flare_page_end' ) ) :
    /**
     * Page end
     *
     * @since flare 1.0.0
     *
     * @param null
     * @return null
     *
     */
    function flare_page_end() {
    global $flare_customizer_all_values;
        ?>
        </div><!-- #page -->
    <?php
     if( isset( $flare_customizer_all_values['flare-enable-back-to-top'] )  && 1 == $flare_customizer_all_values['flare-enable-back-to-top']) {
        ?>
            <a id="gotop" class="back-tonav" href="#page"><i class="fa fa-angle-up"></i></a>
        <?php
        }
    }
endif;
add_action( 'flare_action_after', 'flare_page_end', 10 );



