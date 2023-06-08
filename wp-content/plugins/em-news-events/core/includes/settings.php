<?php $is_news = ( $_GET['post_type'] == self::$news_slug ) ? TRUE : FALSE; ?>
<div class="wrap">
	<div class="icon32" id="icon-options-general"><br /></div>
	<h2><?php _e(($is_news ? 'News' : 'Event') . ' Settings', 'em-news-events'); ?></h2>
	<form method="post">
		<?php wp_nonce_field('save_settings', '_em_news_events_nonce'); ?>
		<table class="form-table">
			<?php if ( $is_news ) : $cats = get_terms(self::$news_tax_slug, array('hide_empty' => 0, 'parent' => 0)); ?>
			<tr valign="top">
				<th scope="row"><label for="news-template"><?php _e('Template', 'em-news-events'); ?></label></th>
				<td>
					<select name="settings[template]" id="news-template">
						<option value="default"><?php _e('Default', 'em-news-events'); ?></option>
						<?php foreach ( $cats as $cat ) : ?>
						<option value="<?= $cat->term_id; ?>"><?php _e($cat->name, self::$news_tax_slug); ?></option>
						
						  <?php if ( $cat_children = get_term_children($cat->term_id, self::$news_tax_slug) ) : ?>
          	    <?php foreach ( $cat_children as $cc ) : $child = get_term_by( 'id', $cc, self::$news_tax_slug ); ?>
                  <option value="<?= $child->term_id; ?>"> &mdash; <?php _e($child->name, self::$news_tax_slug); ?></option>
          	    <?php endforeach; ?>
              <?php endif; ?>
						
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<?php endif; ?>
			<tr valign="top">
				<?php $field = ( $is_news ) ? 'news' : 'event'; ?>
				<th scope="row"><label for="<?= $field; ?>-parent"><?php _e('Parent Page', 'em-news-events'); ?></label></th>
				<td>
				<?php
				wp_dropdown_pages(array(
					'selected' => self::get_setting_value($field . '_parent'),
					'name' => 'settings[' . $field . '_parent' . ']',
				));
				?>
				</td>
			</tr>
			<tr class="tmpl-row" data-id="default" valign="top">
				<?php $field = ( $is_news ) ? 'news_list_template' : 'event_list_template'; ?>
				<th scope="row"><label for="list-template"><?php _e('List Template', 'em-news-events'); ?></label></th>
				<td><textarea rows="10" name="settings[<?= $field; ?>]" id="list-template" class="widefat code"><?php echo self::get_setting_value($field); ?></textarea></td>
			</tr>
			<tr class="tmpl-row" data-id="default" valign="top">
				<?php $field = ( $is_news ) ? 'news_detail_template' : 'event_detail_template'; ?>
				<th scope="row"><label for="detail-template"><?php _e('Detail Template', 'em-news-events'); ?></label></th>
				<td><textarea rows="10" name="settings[<?= $field; ?>]" id="detail-template" class="widefat code"><?php echo self::get_setting_value($field); ?></textarea></td>
			</tr>
			<?php if ( isset($cats) ) :
						foreach ( (array) $cats as $cat ) : ?>
			<tr class="tmpl-row hidden" data-id="<?= $cat->term_id; ?>" valign="top">
				<?php $field = ( $is_news ) ? 'news_list_template_' . $cat->term_id : 'event_list_template_' . $cat->term_id; ?>
				<th scope="row"><label for="list-template-<?= $cat->term_id; ?>"><?php _e('List Template', 'em-docman'); ?></label></th>
				<td><textarea rows="10" name="settings[<?= $field; ?>]" id="list-template-<?= $cat->term_id; ?>" class="widefat code"><?php echo self::get_setting_value($field); ?></textarea></td>
			</tr>
			<tr class="tmpl-row hidden" data-id="<?= $cat->term_id; ?>" valign="top">
				<?php $field = ( $is_news ) ? 'event_detail_template_' . $cat->term_id : 'event_detail_template_' . $cat->term_id; ?>
				<th scope="row"><label for="detail-template-<?= $cat->term_id; ?>"><?php _e('Detail Template', 'em-docman'); ?></label></th>
				<td><textarea rows="10" name="settings[<?= $field; ?>]" id="detail-template-<?= $cat->term_id; ?>" class="widefat code"><?php echo self::get_setting_value($field); ?></textarea></td>
			</tr>
			<?php 	endforeach;
					endif; ?>
			<tr valign="top">
				<td colspan="2">
					Key:
					<ul style="font-family:monospace;">
						<li><strong>{title}</strong> = <?php _e('The title of the ' . ($is_news ? 'news item' : 'event'), 'em-news-events'); ?>.</li>
						<li><strong>{excerpt}</strong> = <?php _e('The excerpt of the ' . ($is_news ? 'news item' : 'event') . '.', 'em-news-events'); ?></li>
						<li><strong>{full_text}</strong> = <?php _e('The full text of the ' . ($is_news ? 'news item' : 'event') . '.', 'em-news-events'); ?></li>
						<li><strong>{image class="alignleft"}</strong> = <?php _e('The associated image for the ' . ($is_news ? 'news item' : 'event') . '. The class attribute is optional.', 'em-news-events'); ?></li>
						<li><strong>{url}</strong> = <?php _e('The url of the ' . ($is_news ? 'news item' : 'event') . '.', 'em-news-events'); ?></li>
						<li><strong>{link_target}</strong> = <?php _e('The link target (e.g. "_blank" or "_self").', 'em-news-events'); ?></li>
						<?php if ( !$is_news ) : ?>
						<li><strong>{event_date}</strong> = <?php _e('The date(s) of the event.', 'em-news-events'); ?></li>
						<li><strong>{event_location}</strong> = <?php _e('The location of the event.', 'em-news-events'); ?></li>
						<?php else : ?>
						<li><strong>{publish_date}</strong> = <?php _e('The publish date of the ' . ($is_news ? 'news item' : 'event') . '.', 'em-news-events'); ?></li>
						<?php endif; ?>						
					</ul>
				</td>
			</tr>

		</table>
		<p class="submit"><input type="submit" id="submit" name="submit" value="<?php _e('Save Changes', 'em-docman'); ?>" class="button-primary" /></p>
	</form>
</div>