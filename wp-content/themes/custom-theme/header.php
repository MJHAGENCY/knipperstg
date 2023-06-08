<?php header('X-UA-Compatible: IE=edge,chrome=1'); ?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="designer" content="www.emagine.com" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title><?php wp_title(''); ?></title>
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="apple-touch-icon" href="/icons/apple-touch-icon.png" />
	<link rel="apple-touch-icon" sizes="57x57" href="/icons/apple-touch-icon-57x57.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="/icons/apple-touch-icon-72x72.png" />
	<link rel="apple-touch-icon" sizes="76x76" href="/icons/apple-touch-icon-76x76.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="/icons/apple-touch-icon-114x114.png" />
	<link rel="apple-touch-icon" sizes="120x120" href="/icons/apple-touch-icon-120x120.png" />
	<link rel="apple-touch-icon" sizes="144x144" href="/icons/apple-touch-icon-144x144.png" />
	<link rel="apple-touch-icon" sizes="152x152" href="/icons/apple-touch-icon-152x152.png" />
	<link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon-180x180.png" />
	<link rel="sitemap" href="<?php echo home_url('sitemap_index.xml'); ?>" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<header class="sticky wicky">
		<div class="inner clearfix">
			<div class="fifty inline top">
				<?php Em_Parts::get_logo(); ?>
			</div>
			<div class="fifty inline top righttext menu-height">
				<?php
					//! MAIN MENU
					if ( has_nav_menu('main-menu') ) : ?>
						<div class="menu-wrapper inline">
							<a class="hamburger" id="mainMenuTrigger">Menu <i class="fa fa-2x fa-bars middle"></i></a>
							<?
							//! FOR A CUSTOM WALKER MENU:
							//! UNCOMMENT INCLUDE_ONCE AND WALKER PARAM BELOW
							//! OTHERWISE DELETE
							include_once 'includes/custom_theme_expanding_walker.php';
							wp_nav_menu(array(
								'theme_location'  => 'main-menu',
								'menu'            => '',
								'container'       => 'nav',
								'container_class' => 'main-menu-container clearfix',
								'items_wrap' 	  => '<menu class="%2$s">%3$s</menu>',
								'menu_class'      => 'main-menu clearfix',
								'walker'          => new custom_theme_expanding_walker()
							)); ?>
						</div><?
					endif;	
				?>

				<div class="inline divider">|</div>
				
					<?php
					//! SITE SEARCH				
					get_search_form(true);
					?>
					
			</div>
		</div>
	</header>
