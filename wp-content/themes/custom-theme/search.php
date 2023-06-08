<?php get_header(); ?>	
	<?php Em_Client::get_masthead(); ?>
	<div class="main">
		<div class="inner clearfix">
			<article class="content">
				<?php 
					Em_Parts::get_page_title(); 
					Em_Client::get_search_results(); 
				?>
			</article>
		</div>
	</div>
<?php get_footer(); ?>
