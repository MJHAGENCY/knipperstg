 <?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Medifact
*/
$medifact_footer_section = get_theme_mod('medifact_footer_section_hideshow','show');
if ($medifact_footer_section =='show') { 
  
?>
    <!-- ====== footer starts ====== -->
    <section class="footer footer-blue">
        <div class="container">
            <div class="footer-content">
                <div class="row center-grid">
                    <div class="col-md-3 col-sm-6">
                        <?php dynamic_sidebar('medifact-footer-widget-area-1'); ?>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <?php dynamic_sidebar('medifact-footer-widget-area-2'); ?>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <?php dynamic_sidebar('medifact-footer-widget-area-3'); ?>
                    </div>
                    <div class="col-md-3 col-sm-6">
                       <?php dynamic_sidebar('medifact-footer-widget-area-4'); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-copyright">
            <?php if( get_theme_mod('medifact-footer_title') ) : ?>
                <p>
                    <?php echo wp_kses_post( html_entity_decode(get_theme_mod('medifact-footer_title'))); ?>
                </p>
            <?php else : ?>
				<p>
					<?php
					/* translators: 1: poweredby, 2: link, 3: span tag closed  */
					printf( esc_html__( ' %1$sPowered by %2$s%3$s', 'medifact' ), '<span>', '<a href="'. esc_url( __( 'https://wordpress.org/', 'medifact' ) ) .'" target="_blank">WordPress.</a>', '</span>' );
					/* translators: 1: poweredby, 2: link, 3: span tag closed  */
					printf( esc_html__( ' Theme: Medifact by: %1$sDesign By %2$s%3$s', 'medifact' ), '<span>', '<a href="'. esc_url( __( 'http://hubblethemes.com', 'medifact' ) ) .'" target="_blank">hubblethemes</a>', '</span>' );
					?>
				</p>
			<?php endif;  ?>
        </div>
    </section>
<?php } ?>
<?php wp_footer(); ?>
</div>
</body>
</html>