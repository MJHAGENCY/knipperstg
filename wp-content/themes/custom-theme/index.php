<?php get_header(); ?>	
	<?php Em_Client::get_masthead(); ?>
	<div class="main">
		<div class="inner clearfix">
			<?php get_sidebar('callouts'); ?>
			<article class="content">
				<?php while ( have_posts() ) : the_post();
					the_content();			
				endwhile; ?>
				<?php if ( is_singular('news-item') ) the_field( 'opts_pr_footer', 'options' ); ?>
				<?php if ( is_404() ) the_field( 'opts_404_text', 'options' ); ?>
			</article>
		</div>
	</div>
<?php get_footer(); ?>
