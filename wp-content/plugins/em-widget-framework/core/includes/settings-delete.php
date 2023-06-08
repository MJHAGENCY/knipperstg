<div class="wrap">
	<div id="icon-tools" class="icon32"><br /></div>
	<h2>Widget Framework - Delete</h2>
	<form method="post">
		<?php wp_nonce_field('delete-widget', '_em_widget_framework_nonce'); ?>
		<h3>Are you sure you want to delete this widget?</h3>
		<p><?php echo self::get_widget_setting('classname'); ?></p>
		<p class="submit">
			<input id="submit" class="button button-primary" type="submit" name="submit" value="Delete Widget" />
		</p>
	</form>
</div>