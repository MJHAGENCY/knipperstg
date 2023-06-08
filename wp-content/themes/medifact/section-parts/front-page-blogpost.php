<?php 
// To display Blog Post section on front page
  
$medifact_blog_title =  get_theme_mod('medifact_blog_title');  
$medifact_blog_section = get_theme_mod('medifact_blog_section_hideshow','show');

if ($medifact_blog_section =='show') { 
?>

<!-- ====== blog starts ====== -->
    <section class="blog section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="all-title">
                        <?php if($medifact_blog_title !="")
                        {?>
                        <h3><?php echo esc_html(get_theme_mod('medifact_blog_title')); ?></h3>
                        <div class="title-border">
                            <span class="fa fa-stethoscope" aria-hidden="true"></span>
                        </div>
                        <?php 
                        } ?>
                    </div>
                </div>
            </div>
            <div class="row center-grid">
            <?php 
                $latest_blog_posts = new WP_Query( array( 'posts_per_page' => 3 ) );
                if ( $latest_blog_posts->have_posts() ) : 
                    while ( $latest_blog_posts->have_posts() ) : $latest_blog_posts->the_post(); 
            ?>
                    <div class="col-md-4 col-sm-6">
                        <article class="blog-item">
							 <div class="post-img">
                               <?php if(has_post_thumbnail()) : ?>
                                    <img src="<?php the_post_thumbnail_url('medifact-blog-front-thumbnail', array('class' => 'img-responsive')); ?>" alt="blog">
                                <?php endif; ?>
                            </div>
							
							<div class="post-content">
                                <h4>
                                    <a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a>
                                </h4>
                                <ul class="post-meta">
                                    <li>
                                        <i class="fa fa-calendar"></i>
                                       <?php the_time( get_option( 'date_format' ) ); ?>
                                    </li>
                                    <li>
                                        <i class="fa fa-user"></i>
                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php the_author();?></a>
                                    </li>
                                    <li>
                                        <i class="fa fa-comments"></i>
                                        <?php comments_number( __('0 Comment', 'medifact'), __('1 Comment', 'medifact'), __('% Comments', 'medifact') ); ?>
                                    </li>
                                </ul>
                                <?php the_excerpt();?>
                                  <a href="<?php the_permalink() ?>" class="btn host-btn">
                                    <?php echo esc_html__('Read More', 'medifact' ); ?>
                                    <i class="fa fa-long-arrow-right i-round"></i>
                                </a>
                            </div>
						</article>
                    </div>
                <?php 
                    endwhile; 
                endif; ?>
            </div>
        </div>
    </section>
    <!-- ====== blog ends ====== -->
<?php } ?>
<!-- post ends-->

