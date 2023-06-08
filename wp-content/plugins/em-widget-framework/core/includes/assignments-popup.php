<?php
global $wp_post_types;
$excludes = array('attachment', 'revision', 'nav_menu_item', 'acf');
?>
<div id="em-assignments-widget-popup">
	<form>
		<div class="actions">
			<input type="submit" class="button-primary" value="Submit" />
		</div>
<?php
foreach ( $wp_post_types as $pt ) :
	if ( in_array($pt->name, $excludes)) { continue; }
?>
		<div class="accordion-head">
			<a href="javascript:;"><?php echo $pt->label; ?></a>
			<input type="checkbox" class="assignments-type" name="assignments-type[]" value="<?php echo $pt->name; ?>" />
		</div>
		<div class="accordion-content">
			<ul>
<?php
	if ( isset($pt->hierarchical) && $pt->hierarchical )
	{
		wp_list_pages(array(
			'title_li' => '',
			'post_type' => $pt->name,
		));
	}
	else
	{
		$myposts = get_posts(array(
			'posts_per_page' => -1,
			'post_type' => $pt->name,
			'orderby' => 'title',
			'order' => 'ASC',
		));
		
		foreach ( (array) $myposts as $mypost ) :
?>
				<li class="page_item page-item-<?php echo $mypost->ID; ?>">
					<a href="<?php echo get_permalink($mypost->ID); ?>"><?php echo get_the_title($mypost->ID); ?></a>
				</li>
<?php
		endforeach;
	}
?>
			</ul>
		</div>
<?php
endforeach;
?>
		<div class="accordion-head">
			<a href="javascript:;">Special Pages</a>
			<input type="checkbox" class="assignments-type" name="assignments-type[]" value="em-special" />
		</div>
		<div class="accordion-content">
			<ul>
				<li class="page_item page-item-search"><a href="javascript:;">Search Results</a></li>
				<li class="page_item page-item-notfound"><a href="javascript:;">Page Not Found (404)</a></li>
			</ul>
		</div>
		<div class="actions">
			<input type="submit" class="button-primary" value="Submit" />
		</div>
	</form>
</div>
