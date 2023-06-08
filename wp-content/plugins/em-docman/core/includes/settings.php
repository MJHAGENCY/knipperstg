<?php $cats = get_terms('document-category', 'hide_empty=0'); ?>
<div class="wrap">
	<div class="icon32 icon32-posts-document"><br /></div>
	<h2><?php _e('Document Manager Settings', 'em-docman'); ?></h2>
	<form method="post">
		<?php wp_nonce_field('save_settings', '_em_docman_nonce'); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="doc-template"><?php _e('Template', 'em-docman'); ?></label></th>
				<td>
					<select name="settings[template]" id="doc-template">
						<option value="default"><?php _e('Default', 'em-docman'); ?></option>
						<?php foreach ( $cats as $cat ) : ?>
						<option value="<?= $cat->term_id; ?>"><?php _e($cat->name, 'document-category'); ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr class="tmpl-row" data-id="default" valign="top">
				<th scope="row"><label for="list-template"><?php _e('List Template', 'em-docman'); ?></label></th>
				<td><textarea rows="10" name="settings[list_template]" id="list-template" class="widefat code"><?php echo self::get_setting_value('list_template'); ?></textarea></td>
			</tr>
			<tr class="tmpl-row" data-id="default" valign="top">
				<th scope="row"><label for="detail-template"><?php _e('Detail Template', 'em-docman'); ?></label></th>
				<td><textarea rows="10" name="settings[detail_template]" id="detail-template" class="widefat code"><?php echo self::get_setting_value('detail_template'); ?></textarea></td>
			</tr>
			<?php foreach ( $cats as $cat ) : ?>
			<tr class="tmpl-row hidden" data-id="<?= $cat->term_id; ?>" valign="top">
				<th scope="row"><label for="list-template-<?= $cat->term_id; ?>"><?php _e('List Template', 'em-docman'); ?></label></th>
				<td><textarea rows="10" name="settings[list_template_<?= $cat->term_id; ?>]" id="list-template-<?= $cat->term_id; ?>" class="widefat code"><?php echo self::get_setting_value('list_template_' . $cat->term_id); ?></textarea></td>
			</tr>
			<tr class="tmpl-row hidden" data-id="<?= $cat->term_id; ?>" valign="top">
				<th scope="row"><label for="detail-template-<?= $cat->term_id; ?>"><?php _e('Detail Template', 'em-docman'); ?></label></th>
				<td><textarea rows="10" name="settings[detail_template_<?= $cat->term_id; ?>]" id="detail-template-<?= $cat->term_id; ?>" class="widefat code"><?php echo self::get_setting_value('detail_template_' . $cat->term_id); ?></textarea></td>
			</tr>
			<?php endforeach; ?>
			<tr valign="top">
				<td colspan="2">
					Key:
					<ul style="font-family:monospace;">
						<li><strong>{title}</strong> = <?php _e('The title of the document', 'em-docman'); ?>.</li>
						<li><strong>{publish_date}</strong> = <?php _e('The publish date of the document. Date will be formatted according to the <a target="_blank" href="options-general.php">date settings</a> of WordPress.', 'em-docman'); ?></li>
						<li><strong>{categories}</strong> = <?php _e('A comma delimitted list of categories that the document is assigned to.', 'em-docman'); ?></li>
						<li><strong>{short_description}</strong> = <?php _e('The short description of the document.', 'em-docman'); ?></li>
						<li><strong>{full_description}</strong> = <?php _e('The full description of the document.', 'em-docman'); ?></li>
						<li><strong>{image}</strong> = <?php _e('The image associated with the document', 'em-docman'); ?>.</li>
						<li><strong>{filetype}</strong> = <?php _e('The file type of the document (e.g. pdf, doc).', 'em-docman'); ?></li>
						<li><strong>{filesize}</strong> = <?php _e('The file size of the document.', 'em-docman'); ?></li>
						<li><strong>{fileicon size="16"}</strong> = <?php _e('An icon representing the file type of the document. An optional "size" argument is accepted.', 'em-docman'); ?></li>
						<li><strong>{require_registration}</strong> = <?php _e('A yes or no value describing if the document requires registration.', 'em-docman'); ?></li>
						<li><strong>{download_url}</strong> = <?php _e('The link to download the document. If registration is required, visitor will be redirected to registration page.', 'em-docman'); ?></li>
						<li><strong>{detail_url}</strong> = <?php _e('The link to the detail page of the document.', 'em-docman'); ?></li>
					</ul>
				</td>
			</tr>

		</table>
		<p class="submit"><input type="submit" id="submit" name="submit" value="<?php _e('Save Changes', 'em-docman'); ?>" class="button-primary" /></p>
	</form>
</div>