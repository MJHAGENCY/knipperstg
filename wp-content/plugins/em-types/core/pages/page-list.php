<?php
$typesListTable = new Em_Types_List_Table();
$typesListTable->prepare_items();
?>
<div class="wrap">
	<div id="icon-tools" class="icon32"><br></div>
	<h2>Types <a class="add-new-h2" href="<?= remove_query_arg('message', add_query_arg('action', 'new')); ?>">Add New</a></h2>
	<?php $typesListTable->display(); ?>
</div>