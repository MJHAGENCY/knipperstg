<?php	global $wp_registered_sidebars;
		
		$sidebars_widgets = $sidebars_widgets_unsorted = self::filter_widgets(wp_get_sidebars_widgets());
		$sidebars = $wp_registered_sidebars;
		
		// Check if custom sorting is saved
		$widgets_sorted = get_post_meta($post->ID, 'widgets', true);
		$use_custom_sorting = FALSE;
		
		if ( !empty($widgets_sorted) )
		{
			$sidebars_widgets = $widgets_sorted;
			$use_custom_sorting = TRUE;
		}

		wp_nonce_field('em-widget-framework', 'em_widget_framework_save_page_widgets_nonce'); ?>
		<p>
			<input type="checkbox" id="em_widget_framework_use_custom_sorting" name="em_widget_framework_use_custom_sorting" value="1"<?= ( $use_custom_sorting ) ? ' checked' : ''; ?> />
			<label for="em_widget_framework_use_custom_sorting">Use custom sorting</label>
		</p>
		<div class="em-show-hide<?= ( $use_custom_sorting ) ? '' : ' hidden'; ?>">
<?php	foreach ( $sidebars_widgets as $sidebar => $widgets ) :
			if ( !array_key_exists($sidebar, $sidebars) ) continue; ?>
		<p><strong><?= $sidebars[$sidebar]['name']; ?></strong></p>
		<ul class="em-page-widgets-list">
			<?php foreach ( $widgets as $widget ) :
						// make sure widget hasn't been deleted or made inactive through widgets admin
						if ( !in_array($widget, $sidebars_widgets_unsorted[$sidebar]) ) continue;
						
						// get the widget id
						preg_match('/([^0-9]+)-([0-9]+)/', $widget, $matches);
				
						if ( ! isset($matches[1]) || ! isset($matches[2]) ) continue;
						
						// get the widget from the options table
						$the_widget = get_option('widget_' . $matches[1]);
						$widget_settings = $the_widget[$matches[2]]; ?>
			<li class="widget">
				<div class="widget-top">
					<div class="widget-title"><?= $widget_settings['title']; ?></div>
					<input type="hidden" name="em_widget_framework_widgets[<?= $sidebar; ?>][]" value="<?= $widget; ?>" />
				</div>
			</li>
			<?php	endforeach; ?>
		</ul>
<?php	endforeach; ?>
		</div>