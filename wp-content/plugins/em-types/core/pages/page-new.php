<div class="wrap">
	<div id="icon-tools" class="icon32"><br></div>
	<h2>Add New Type</h2>
	<div id="poststuff">
		<form id="em-types-form" method="post">
			<?php wp_nonce_field('em-types-new', 'em_types_nonce'); ?>
			<div id="titlediv">
				<div id="titlewrap">
					<label for="title" id="title-prompt-text"<?= self::get_value('name') != '' ? ' class="screen-reader-text"' : ''; ?>>Enter name (plural version) here</label>
					<input id="title" type="text" autocomplete="off" size="30" name="em_types[name]" value="<?= self::get_value('name'); ?>" />
				</div>
			</div>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="type">Type</label></th>
						<td>
							<select id="em-types-type" name="em_types[type]">
								<option value="">Select a Type</option>
								<option value="post-type"<?= self::get_value('type') == 'post-type' ? ' selected' : ''; ?>>Custom Post Type</option>
								<option value="taxonomy"<?= self::get_value('type') == 'taxonomy' ? ' selected' : ''; ?>>Custom Taxonomy</option>
							</select>
						</td>
					</tr>
					<tr valign="top"<?= ( !empty($type) ) ? '' : 'class="hidden"'; ?>>
						<th scope="row"><label for="slug">Slug</label></th>
						<td>
							<input type="text" id="em-types-slug" class="regular-text" name="em_types[slug]" value="<?= self::get_value('slug'); ?>" />
							<p class="description">Slugs should be singular and contain lowercase letters and dashes (-) only.</p>
						</td>
					</tr>
					<tr valign="top"<?= ( !empty($type) ) ? '' : 'class="hidden"'; ?>>
						<th scope="row"><label for="em-types-singular-name">Singular Name</label></th>
						<td><input type="text" id="em-types-singular-name" class="regular-text" name="em_types[singular_name]" value="<?= self::get_value('singular_name'); ?>" /></td>
					</tr>
					<tr valign="top" data-for="post-type"<?= ( !empty($type) && $type == 'post-type' ) ? '' : 'class="hidden"'; ?>>
						<th scope="row"><label for="menu-position">Menu Position</label></th>
						<td>
							<select id="em-types-menu-position" name="em_types[menu_position]">
								<option value="">Select a Menu Position</option>
								<?php foreach ( self::$menu_positions as $val => $text ) : ?>
								<option value="<?= $val; ?>"<?= self::get_value('menu_position') == $val ? ' selected' : ''; ?>><?= $text; ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr valign="top" data-for="post-type"<?= ( !empty($type) && $type == 'post-type' ) ? '' : 'class="hidden"'; ?>>
						<th scope="row"><label for="em-types-parent-page">Parent Page</label></th>
						<td>
							<?php wp_dropdown_pages(array(
										'id' => 'em-types-parent-page',
										'name' => 'em_types[parent_page]',
										'show_option_none' => 'Select a Parent Page',
										'option_none_value' => '',
										'selected' => self::get_value('parent_page'),
									)); ?>
						</td>
					</tr>
					<tr valign="top" data-for="post-type"<?= ( !empty($type) && $type == 'post-type' ) ? '' : 'class="hidden"'; ?>>
						<th scope="row"><label>Supports</label></th>
						<td>
							<?php foreach ( self::$supports as $item ) : ?>
							<p><label for="supports-<?= $item ?>">
								<input type="checkbox" name="em_types[supports][]" id="em-types-supports-<?= $item ?>" value="<?= $item; ?>"<?= in_array($item, self::get_value('supports', array())) ? ' checked' : ''; ?> />
								<?= ucwords(str_replace('-', ' ', $item)); ?></label></p>
							<?php endforeach; ?>
						</td>
					</tr>
					<tr valign="top" data-for="all"<?= ( !empty($type) ) ? '' : 'class="hidden"'; ?>>
						<th scope="row"><label>Public</label></th>
						<td>
							<label for="public">
								<input type="checkbox" id="em-types-public" name="em_types[public]" value="1"<?= self::get_value('public') == 1 ? ' checked' : ''; ?> />
								Yes</label>
						</td>
					</tr>
					<tr valign="top" data-for="all"<?= ( !empty($type) ) ? '' : 'class="hidden"'; ?>>
						<th scope="row"><label>Exclude From Search</label></th>
						<td>
							<label for="em-types-exclude-from-search">
								<input type="checkbox" id="em-typesexclude-from-search" name="em_types[exclude_from_search]" value="1"<?= self::get_value('exclude_from_search') == 1 ? ' checked' : ''; ?> />
								Yes</label>
						</td>
					</tr>
					<tr valign="top" data-for="all"<?= ( !empty($type) ) ? '' : 'class="hidden"'; ?>>
						<th scope="row"><label>Publicly Queryable</label></th>
						<td>
							<label for="em-types-publicly-queryable">
								<input type="checkbox" id="em-types-publicly-queryable" name="em_types[publicly_queryable]" value="1"<?= self::get_value('publicly_queryable') == 1 ? ' checked' : ''; ?> />
								Yes</label>
						</td>
					</tr>
					<tr valign="top" data-for="all"<?= ( !empty($type) ) ? '' : 'class="hidden"'; ?>>
						<th scope="row"><label>Show UI</label></th>
						<td>
							<label for="em-types-show-ui">
								<input type="checkbox" id="em-types-show-ui" name="em_types[show_ui]" value="1"<?= self::get_value('show_ui') == 1 ? ' checked' : ''; ?> />
								Yes</label>
						</td>
					</tr>
					<tr valign="top" data-for="all"<?= ( !empty($type) ) ? '' : 'class="hidden"'; ?>>
						<th scope="row"><label>Show in Nav Menus</label></th>
						<td>
							<label for="em-types-show-in-nav-menus">
								<input type="checkbox" id="em-types-show-in-nav-menus" name="em_types[show_in_nav_menus]" value="1"<?= self::get_value('show_in_nav_menus') == 1 ? ' checked' : ''; ?> />
								Yes</label>
						</td>
					</tr>
					<tr valign="top" data-for="post-type"<?= ( !empty($type) && $type == 'post-type' ) ? '' : 'class="hidden"'; ?>>
						<th scope="row"><label>Show in Menu</label></th>
						<td>
							<label for="em-types-show-in-menu">
								<input type="checkbox" id="em-types-show-in-menu" name="em_types[show_in_menu]" value="1"<?= self::get_value('show_in_menu') == 1 ? ' checked' : ''; ?> />
								Yes</label>
						</td>
					</tr>
					<tr valign="top" data-for="post-type"<?= ( !empty($type) && $type == 'post-type' ) ? '' : 'class="hidden"'; ?>>
						<th scope="row"><label>Show in Admin Bar</label></th>
						<td>
							<label for="em-types-show-in-admin-bar">
								<input type="checkbox" id="em-types-show-in-admin-bar" name="em_types[show_in_admin_bar]" value="1"<?= self::get_value('show_in_admin_bar') == 1 ? ' checked' : ''; ?> />
								Yes</label>
						</td>
					</tr>					
					<tr valign="top" data-for="taxonomy"<?= ( !empty($type) && $type == 'taxonomy' ) ? '' : 'class="hidden"'; ?>>
						<th scope="row"><label>Show Tagcloud</label></th>
						<td>
							<label for="em-types-show-tagcloud">
								<input type="checkbox" id="em-types-show-tagcloud" name="em_types[show_tagcloud]" value="1"<?= self::get_value('show_tagcloud') == 1 ? ' checked' : ''; ?> />
								Yes</label>
						</td>
					</tr>
					<tr valign="top" data-for="all"<?= ( !empty($type) ) ? '' : 'class="hidden"'; ?>>
						<th scope="row"><label>Hierarchical</label></th>
						<td>
							<label for="em-types-hierarchical">
								<input type="checkbox" id="em-types-hierarchical" name="em_types[hierarchical]" value="1"<?= self::get_value('hierarchical') == 1 ? ' checked' : ''; ?> />
								Yes</label>
						</td>
					</tr>
					<tr valign="top" data-for="post-type"<?= ( !empty($type) && $type == 'post-type' ) ? '' : 'class="hidden"'; ?>>
						<th scope="row"><label>Menu Icon</label> From <a href="https://developer.wordpress.org/resource/dashicons" target="_blank">Wordpress Dashicons</a></th>
						<td>
							<input type="text" id="em-types-menu-icon" class="regular-text" name="em_types[menu_icon]" value="<?= self::get_value('menu_icon'); ?>" />
						</td>
					</tr>
					<tr valign="top" data-for="taxonomy"<?= ( !empty($type) && $type == 'taxonomy' ) ? '' : 'class="hidden"'; ?>>
						<th scope="row"><label>Rewrite Rule</label></th>
						<td>
							<input type="text" id="em-types-tax-rewrite" class="regular-text" name="em_types[tax_rewrite]" value="<?= self::get_value('tax_rewrite'); ?>" />
							<br />
							<em>Example: product/category or category</em>
						</td>
					</tr>
					<?php $pts = get_post_types(array('_builtin' => false), 'objects'); ?>
					<tr valign="top" data-for="taxonomy"<?= ( !empty($type) && $type == 'taxonomy' ) ? '' : 'class="hidden"'; ?>>
						<th scope="row">Post Types</th>
						<td>
							<?php foreach ( $pts as $pt ) : ?>
							<p>
								<label for="em-types-pt-<?= $pt->name; ?>">
									<input type="checkbox" name="em_types[post_type][]" id="pt-<?= $pt->name; ?>" value="<?= $pt->name; ?>"<?= in_array($pt->name, self::get_value('post_type', array())) ? ' checked' : ''; ?> />
									<?= $pt->label; ?>
								</label>
							</p>
							<?php endforeach; ?>
							<?php if ( count($pts) == 0 ) : ?>
							No custom post types have been created.
							<?php endif; ?>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit"><input type="submit" value="Save Changes" class="button button-primary" /></p>
		</form>
	</div>
</div>