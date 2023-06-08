<div class="wrap">
	<div id="icon-tools" class="icon32"><br /></div>
	<h2>Widget Framework <a class="add-new-h2" href="<?php echo add_query_arg('action', 'add', '?page=' . $_GET['page']); ?>">Add New</a></h2>
<?php if ( isset($_GET['message']) ) : ?>
	<div class="updated">
		<p>
			<strong>Widget <?php echo $_GET['message']; ?> successfully</strong>
		</p>
	</div>
<?php endif; ?>

	<?php
	$list_table = new Em_Widget_Framework_List_Table();
	$list_table->prepare_items();
	$list_table->display();
	?>
</div>