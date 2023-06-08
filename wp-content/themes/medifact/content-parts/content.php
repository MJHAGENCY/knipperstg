 <?php
/**
 * @package Medifact
*/
?>
<article class="blog-detail-item">
	<?php if(has_post_thumbnail()) : ?>
		<div class="post-img">
			<?php the_post_thumbnail('medifact-page-thumbnail', array('class' => 'img-responsive')); ?>
		</div>
	<?php endif; ?>
	<div class="post-content">
		<ul class="post-meta">
			<li>
				<i class="fa fa-calendar"></i>
				<?php 
					the_time(get_option( 'date_format' )); 
				?>
			</li>
			<li>
				<i class="fa fa-user"></i>
				<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="post-admin">
					<?php the_author();?>
				</a>
			</li>
			<li>
				<i class="fa fa-comments"></i>
				<?php comments_number( __('0 Comment', 'medifact'), __('1 Comment', 'medifact'), __('% Comments', 'medifact') ); ?>
			</li>
			 
		</ul>
		<h4><a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?> </a></h4>
        <?php the_excerpt(); ?> 
	</div>
	 
</article>
 