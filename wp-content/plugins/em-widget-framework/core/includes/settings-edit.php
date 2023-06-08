<div class="wrap">
	<div id="icon-tools" class="icon32"><br /></div>
	<h2>Widget Framework - Edit</h2>
<?php if ( isset(self::$message['text']) ) : ?>
	<div class="<?php echo self::$message['type']; ?>">
		<p><strong><?php echo self::$message['text']; ?></strong></p>
	</div>
<?php endif; ?>
	<form method="post">
		<?php wp_nonce_field('edit-widget', '_em_widget_framework_nonce'); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="filepath">File path</label>
				</th>
				<td>
					<input id="filepath" class="regular-text" type="text" name="filepath" value="<?php echo self::get_widget_setting('filepath'); ?>" />
					<p class="description">The path to the widget file relative to the active theme's root - currently <?php echo str_replace(home_url(), '', get_stylesheet_directory_uri()) . '/'; ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="classname">Class name</label>
				</th>
				<td>
					<input id="classname" class="regular-text" type="text" name="classname" value="<?php echo self::get_widget_setting('classname'); ?>" />
					<p class="description">The class name of the custom widget (e.g. My_Custom_Widget).</p>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input id="submit" class="button button-primary" type="submit" name="submit" value="Edit Widget" />
		</p>
	</form>
</div>