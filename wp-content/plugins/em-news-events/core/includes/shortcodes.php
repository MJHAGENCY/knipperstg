<div class="wrap">
	<div class="icon32" id="icon-options-general"><br /></div>
	<h2><?php _e('Shortcodes', 'em-news-events'); ?></h2>
	
	<?php if ( $_GET['post_type'] == self::$event_slug ) : ?>
	
	<br />
	
	<h3>Events</h3>
	<table class="form-table">
		<tr>
			<th scope="row"><strong>All Events</strong></th>
			<td><span style="font-family:monospace">[events]</span></td>
		</tr>
		<?php $cats = get_terms(self::$event_tax_slug, array('hide_empty' => 0, 'parent' => 0));
				foreach ( $cats as $cat ) : ?>
		<tr>
			<th scope="row"><strong><?= $cat->name; ?></strong></th>
			<td><span style="font-family:monospace">[events type="<?= $cat->term_id; ?>"]</span></td>
		</tr>
		
		<?php if ( $cat_children = get_term_children($cat->term_id, self::$event_tax_slug) ) : ?>
	    <?php foreach ( $cat_children as $cc ) : $child = get_term_by( 'id', $cc, self::$event_tax_slug ); ?>
	      <tr>
		      <th scope="row"><strong> &mdash; <?= $child->name; ?></strong></th>
          <td><span style="font-family:monospace">[news_items type="<?= $child->term_id; ?>"]</span></td>
        </tr>
	    <?php endforeach; ?>
    <?php endif; ?>
		
		<?php endforeach; ?>
	</table>
	
	<p>You can prevent events from disappearing after they expire by adding <code>is_archive="1"</code> to your shortcode.</p> 
	<?php else : ?>
	
	<br />
	
	<h3>News Items</h3>
	<table class="form-table">
		<?php $cats = get_terms(self::$news_tax_slug, array('hide_empty' => 0, 'parent' => 0));
				foreach ( $cats as $cat ) : ?>
		<tr>
			<th scope="row"><strong><?= $cat->name; ?></strong></th>
			<td><span style="font-family:monospace">[news_items type="<?= $cat->term_id; ?>"]</span></td>
		</tr>
		
		<?php if ( $cat_children = get_term_children($cat->term_id, self::$news_tax_slug) ) : ?>
	    <?php foreach ( $cat_children as $cc ) : $child = get_term_by( 'id', $cc, self::$news_tax_slug ); ?>
	      <tr>
		      <th scope="row"><strong> &mdash; <?= $child->name; ?></strong></th>
          <td><span style="font-family:monospace">[news_items type="<?= $child->term_id; ?>"]</span></td>
        </tr>
	    <?php endforeach; ?>
    <?php endif; ?>
		
		<?php endforeach; ?>
	</table>
	
	<?php endif; ?>
	
	<p>You can limit the number of <?= ($_GET['post_type'] == self::$event_slug) ? 'events' : 'news items'; ?> that display by adding <code>number="x"</code>
		where x is the number of items you want to display.</p>
	<p>If you would like to display <?= ($_GET['post_type'] == self::$event_slug) ? 'events' : 'news items'; ?> on multiple pages, you can do so by adding <code>paged=true</code>. The number of <?= ($_GET['post_type'] == self::$event_slug) ? 'events' : 'news items'; ?> per page is then defined by <code>number="x"</code>.</p>
</div>