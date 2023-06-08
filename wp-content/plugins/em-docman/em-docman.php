<?php

/*	Plugin Name: EM Document Manager
	Description: Easily manage your documents
	Version: 0.1.1
	Author: eMagine
	Author URI: http://www.emagineusa.com/
	License: GPL2
	
	Copyright 2012 eMagine  (email : info@emagineusa.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

Em_Docman::init();

class Em_Docman
{
	// !@static string $version The current version of the plugin
	static $version = '0.0.7';
	
	// !@static string $pt_slug The custom post type slug
	static $pt_slug = 'document';
	
	// !@static string $tax_slug The custom taxonomy slug
	static $tax_slug = 'document-category';
	
	// !@static string $base_url The base url of the plugin
	static $base_url = '';
	
	// !@static string $base_path The document root of the plugin
	static $base_path = '';
	
	// !@static array $settings The saved settings of the plugin
	static $settings = array();
	
	// !@static string $upload_path The upload path relative to the absolute path of WordPress
	static $upload_path = 'docs';
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Initialize
	 *
	 *--------------------------------------------------------------------------------------*/
	 
	function init()
	{
		//! Setup class variables
		self::$base_url = plugin_dir_url(__FILE__);
		self::$base_path = plugin_dir_path(__FILE__);
		
		//! Global hooks		
		add_action('init', array(__CLASS__, 'register_types'));
		add_action('init', array(__CLASS__, 'add_shortcodes'));
		self::$settings = get_option('em_docman_settings');
		
		//! Include ACF Custom Fields
		include_once self::$base_path . 'core/includes/acf-docman-fields.php';
		
		if ( is_admin() )
		{	//! Admin-only hooks
			register_activation_hook(__FILE__, array(__CLASS__, 'activate'));
			add_action('init', array(__CLASS__, 'maybe_save_settings'));
			add_action('admin_init', array(__CLASS__, 'maybe_deactivate'));
			add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_admin_styles'));
			add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_admin_scripts'));
			add_action('admin_print_footer_scripts', array(__CLASS__, 'display_document_link_builder'));
			add_action('admin_print_footer_scripts', array(__CLASS__, 'display_shortcode_builder'));
			add_action('media_buttons', array(__CLASS__, 'media_buttons'), 11);
			add_action('admin_menu', array(__CLASS__, 'admin_menu'));
			add_filter('manage_edit-' . self::$pt_slug. '_columns', array(__CLASS__, 'manage_document_column_headers'));
			add_action('manage_' . self::$pt_slug . '_posts_custom_column', array(__CLASS__, 'manage_document_column_values'), 10, 2);
		}
		else
		{	//! Front-end hooks
			self::session_start();
			add_action('wp', array(__CLASS__, 'maybe_serve_download'));
			add_action('gform_after_submission', array(__CLASS__, 'gform_after_submission'), 10, 2);
			add_filter('gform_confirmation', array(__CLASS__, 'gform_confirmation'), 10, 4);
			add_action('wp_head', array(__CLASS__, 'meta_redirect_to_download'));
			add_filter('posts_results', array(__CLASS__, 'posts_results'), 10, 2);
			add_filter('gform_field_value_download', array(__CLASS__, 'set_download_field_value'));
		}
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Set the download field value (if applicable)
	 * @param string $value
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function set_download_field_value( $value )
	{
		// Only process if necessary values are set
		if ( ! isset($_GET['doc']) ) { return $value; }
		
		// Decode doc hash
		$args = wp_parse_args(json_decode(base64_decode($_GET['doc']), true), array(
			'fid' => 0,
			'rid' => 0,
			'did' => 0,
		));
		
		// Get objects
		$file = get_post($args['fid']);
		$reg_page = get_post($args['rid']);
		$doc = get_post($args['did']);
		
		// Only continue if we could get post objects for $file and $reg_page and $doc
		if ( ! isset($file->ID) || ! isset($reg_page->ID) || ! isset($doc->ID) ) { return $value; }
		
		return get_the_title($args['did']);
	}
	
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Activation function - make sure ACF is installed and initialize db
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function activate()
	{
		if ( ! class_exists('Acf') )
		{
			wp_die(__('<p><strong>Could not activate!</strong> Please install and activate Advanced Custom Fields before activating this plugin.<br /><a href="plugins.php">&laquo; Return to plugins</a></p>', 'em-docman'));
		}
		
		if ( get_option('em_docman_initialized') ) { return false; }
		
		// create acf post
		$post_id = wp_insert_post(array(
			'post_title' => 'Document Settings',
			'post_type' => 'acf',
			'post_status' => 'publish',		
		));
		
		if ( ! $post_id ) { return false; }
		
		// basic meta
		update_post_meta($post_id, 'allorany', 'all');
		update_post_meta($post_id, 'hide_on_screen', '');
		update_post_meta($post_id, 'layout', 'default');
		update_post_meta($post_id, 'position', 'normal');
		update_post_meta($post_id, 'rule', array(
			'param' => 'post_type',
			'operator' => '==',
			'value' => 'document',
			'order_no' => 0,
		));
		
		// declare fields
		//include_once self::$base_path . '/core/includes/acf-fields.php';
		
		// insert fields into db
		/*$next_field_id = $starting_field_id = get_option('acf_next_field_id', 1);
		foreach ( $fields as $k => &$field )
		{
			if ( $k > 0 )
			{
				$next_field_id = (int) $next_field_id + 1;
			}

			$field['order_no'] = $k;
			$field['key'] = 'field_' . $next_field_id;
			
			if ( $field['conditional_logic']['status'] == 1 )
			{*/
				/*
				if field uses conditional logic we need to recalculate the field id that is used.
				this is because our acf-fields.php file doesn't take into account any fields that may
				already exist in the db
				*/
			
				/*foreach ( $field['conditional_logic']['rules'] as $k => &$rule )
				{
					$id = (int) str_replace('field_', '', $rule['field']);
					$rule['field'] = 'field_' . ($starting_field_id + $id - 1);
				}
			}
			
			update_post_meta($post_id, $field['key'], $field);
		}
		
		update_option('acf_next_field_id', $next_field_id + 1);
		update_option('em_docman_initialized', '1');*/
		
		// create default plugin settings
		$list_template = '<h3 class="doc-title">{fileicon}{title}</h3>' . "\r\n"
							. '<ul class="doc-meta">' . "\r\n"
							. '   <li class="doc-date">Date: {publish_date}</li>' . "\r\n"
							. '   <li class="doc-type">Type: {filetype}</li>' . "\r\n"
							. '   <li class="doc-size">Size: {filesize}</li>' . "\r\n"
							. '</ul>' . "\r\n"
							. '<p class="doc-description">{short_description}</p>' . "\r\n"
							. '<p class="doc-actions"><a href="{detail_url}">More Info</a> | <a href="{download_url}">Download</a></p>';
		
		$detail_template = '<h1 class="doc-title">{fileicon}{title}</h1>' . "\r\n"
							. '<ul class="doc-meta">' . "\r\n"
							. '   <li class="doc-date">Date: {publish_date}</li>' . "\r\n"
							. '   <li class="doc-type">Type: {filetype}</li>' . "\r\n"
							. '   <li class="doc-size">Size: {filesize}</li>' . "\r\n"
							. '</ul>' . "\r\n"
							. '{full_description}' . "\r\n"
							. '<p class="doc-actions"><a href="{download_url}">Download</a></p>';
							
		update_option('em_docman_settings', array(
			'list_template' => $list_template,
			'detail_template' => $detail_template,
		));
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Add shortcodes
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function add_shortcodes()
	{
		add_shortcode('document_list', array(__CLASS__, 'shortcode_document_list'));
	}
	
	function admin_menu()
	{
		add_submenu_page('edit.php?post_type=' . self::$pt_slug, 'Document Manager Settings', 'Settings', 'activate_plugins', 'em-docman-settings', array(__CLASS__, 'settings_page'));
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Display deactivation notice - this will display if ACF is deactivated
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function display_deactivation_notice()
	{
		_e('<div class="error"><p>EM Document Manager has been deactivated because it requires Advanced Custom Fields.</p></div>', 'em-docman');
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Display document link builder
	 *
	 *--------------------------------------------------------------------------------------*/

	function display_document_link_builder()
	{
		$docs = new WP_Query('post_type=' . self::$pt_slug . '&posts_per_page=-1&orderby=title&order=ASC');
		?>

		<div id="em-docman-link-builder" style="display:none">
			<form id="em-docman-link-form">
				<h3><?php _e('Add Document Link', 'em-docman'); ?></h3>
				<label>Search</label>
				<input type="text" name="doc-search" value="" />
				<div class="form-actions top">
					<input class="button-primary" type="button" value="Insert Link" />
					<input class="button" type="button" onclick="tb_remove();" value="Cancel" />
				</div>
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<th width="20"></th>
						<th>Document Name</th>
						<th>Protected File</th>
					</tr>
					<?php while ( $docs->have_posts() ) : $docs->the_post(); ?>
					<?php $file = get_field('document_file_upload'); ?>
					<tr>
						<td width="20"><input type="radio" name="document" id="document-<?php echo get_the_ID(); ?>" value="<?php the_permalink(); ?>" /></td>
						<td><label for="document-<?php echo get_the_ID(); ?>"><?php the_title(); ?></label></td>
						<td class="filename"><?php echo basename($file['url']); ?></td>
					</tr>
					<?php endwhile; wp_reset_postdata(); ?>
				</table>
				<p>
					<label>Link Type</label>
					<select name="link_type">
						<option value="1">Detail Page</option>
						<option value="2">Direct Download</option>
					</select>
				</p>
				<div class="form-actions">
					<input class="button-primary" type="button" value="Insert Link" />
					<input class="button" type="button" onclick="tb_remove();" value="Cancel" />
				</div>
			</form>
		</div>
		
		<?php
	}	
	/*--------------------------------------------------------------------------------------
	 *
	 * Display settings saved notice
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function display_settings_saved_notice()
	{
		_e('<div class="updated"><p>Settings Saved</p></div>');
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Display shortcode builder
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function display_shortcode_builder()
	{
		?>
		 
		<div id="em-docman-shortcode-builder" style="display:none">
			<form id="em-docman-shortcode-form">
				<h3><?php _e('Add Document List', 'em-docman'); ?></h3>
				<dl>
					<dt><?php _e('Choose categories', 'em-docman'); ?></dt>
					<dd class="checkbox-columns">
						<?php
						$cats = get_terms(self::$tax_slug, 'hide_empty=0');
						$index = 1;
						
						foreach ( $cats as $cat ) :
							$id = self::$tax_slug . '-' . $cat->term_id;
						?>
						
						<label for="<?php echo $id; ?>"<?php echo ( $index % 2 == 0 ) ? ' class="last"' : ''; ?>>
							<input type="checkbox" name="cats[]" id="<?php echo $id; ?>" value="<?php echo $cat->term_id; ?>" />
							<span><?php _e($cat->name, self::$tax_slug); ?></span>
						</label>
						
						<?php
							$index ++;
						endforeach;
						?>
					</dd>
				</dl>
				<input class="button-primary" type="button" value="Insert" />
				<input class="button" type="button" onclick="tb_remove();" value="Cancel" />
			</form>
		</div>
		 
		<?php
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Enqueue any necessary admin scripts
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function enqueue_admin_scripts()
	{
		wp_enqueue_script('jquery');
		wp_enqueue_script('em-docman-admin', self::$base_url . 'core/js/admin.js', array('jquery'), self::$version);
		
		if ( isset($_GET['page']) && $_GET['page'] == 'em-docman-settings' )
		{
			wp_enqueue_script('em-docman-admin-settings', self::$base_url . 'core/js/settings.js', array('jquery'), self::$version);
		}
	}
	 
	/*--------------------------------------------------------------------------------------
	 *
	 * Enqueue any necessary admin styles
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function enqueue_admin_styles()
	{
		wp_enqueue_style('em-docman-admin', self::$base_url . 'core/css/admin.css', array(), self::$version);
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Get a file icon, caching it along the way
	 * @param string $extension
	 * @return string
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function get_file_icon( $extension )
	{
		$filepath = self::$base_path . 'core/icons/' . $extension . '.png';
		$fileurl = self::$base_url . 'core/icons/' . $extension . '.png';
		
		if ( ! is_writeable(dirname($filepath)) ) { return false; }
		if ( file_exists($filepath) ) { return $fileurl; }

		$resp = wp_remote_get('http://www.stdicon.com/neu/' . $extension);
		file_put_contents($filepath, $resp['body']);
		
		return $fileurl;
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * After registration form is submitted create some notes and redirect
	 * @param string $extension
	 * @return string
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function gform_after_submission( $entry, $form )
	{
		// Only process if necessary values are set
		if ( ! isset($_GET['doc']) ) { return; }
		
		// Decode doc hash
		$args = wp_parse_args(base64_decode($_GET['doc']), array(
			'fid' => 0,
			'rid' => 0,
			'did' => 0,
		));
		
		// Get objects
		$file = get_post($args['fid']);
		$reg_page = get_post($args['rid']);
		$doc = get_post($args['did']);
		
		// Only continue if we could get post objects for $file and $reg_page and $doc
		if ( ! isset($file->ID) || ! isset($reg_page->ID) || ! isset($doc->ID) ) { return; }
		
		$_SESSION['entry_id__' . $reg_page->ID] = $entry['id'];
		$_SESSION['is_registered__' . $reg_page->ID] = md5((( defined('AUTH_SALT') ) ? AUTH_SALT : '') . '_' . $reg_page->ID);
		
		$this_cookie = array();
		$this_cookie['entry_id__' . $reg_page->ID] = $entry['id'];
		$this_cookie['is_registered__' . $reg_page->ID] =  md5((( defined('AUTH_SALT') ) ? AUTH_SALT : '') . '_' . $reg_page->ID);
		
		if(isset($_COOKIE)  &&  isset($_COOKIE['fileperm'])):
			$current_global_cookie =  $_COOKIE['fileperm'];
			
			$current_global_cookie = unserialize( base64_decode($current_global_cookie) );
		else:
			$current_global_cookie  = array();
		endif;
		
		$current_global_cookie[$reg_page->ID] = $this_cookie;
		setcookie("fileperm",  base64_encode(serialize($current_global_cookie)), 0,  '/');
	}
	
	function gform_confirmation( $confirmation, $form, $lead, $is_ajax )
	{
		// Only process if necessary variables are set
		if ( ! isset($_GET['doc']) ) { return $confirmation; }
		
		$confirmation = array('redirect' => add_query_arg(array('doc' => $_GET['doc'], 'dl' => 1), $confirmation['redirect']));
		return $confirmation;
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Get a setting's value
	 * @param string $setting The setting name
	 * @param string $default The default value 
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function get_setting_value( $setting, $default = '')
	{
		if ( isset(self::$settings[$setting]) )
		{
			return stripslashes(self::$settings[$setting]);
		}
		
		return $default;
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Insert a note into the db
	 * @param string $entry_id
	 * @param string $filename
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function insert_note( $entry_id, $filename )
	{
		global $wpdb;
		
		// Insert a download note for this record
		$table = $wpdb->prefix . 'rg_lead_notes';
		$wpdb->insert($table, array(
			'lead_id' => $entry_id,
			'date_created' => date('Y-m-d h:i:s'),
			'value' => 'File Downloaded: ' . basename($filename)
		));
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Manage document column headers
	 * @param array $columns
	 * @return array
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function manage_document_column_headers( $columns )
	{
		unset(
			$columns['date'],
			$columns['wpseo-score'],
			$columns['wpseo-title'],
			$columns['wpseo-metadesc'],
			$columns['wpseo-focuskw']
		);
		
		$columns['file'] = 'Underlying File';
		$columns['require-reg'] = 'Require Registration';
		$columns['reg-page'] = 'Registration Page';
		$columns['num-dls'] = 'Times Downloaded';
		
		return $columns;
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Manage document column values
	 * @param string $column
	 * @param int $post_id
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function manage_document_column_values( $column, $post_id )
	{
		global $wpdb;
		
		switch ( $column )
		{
			case 'file' :
				$file = get_field('document_file_upload', $post_id);
				echo basename($file['url']);
			break;
			
			case 'require-reg' :
				$require_reg = get_field('document_require_registration', $post_id);
				echo ( $require_reg == 1 ) ? 'Y' : 'N';
				break;
				
			case 'reg-page' :
				$page = get_field('document_registration_page', $post_id);
				echo '/' . get_page_uri($page->ID);
				break;
				
			case 'num-dls' :
				$file = get_field('document_file_upload', $post_id);
				$table = $wpdb->prefix . 'rg_lead_notes';
				$num = $wpdb->get_var("
					SELECT COUNT(*)
					FROM $table
					WHERE value LIKE '%" . basename($file['url'] . "'")
				);
				echo (int) $num;
				break;
		}	
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Check if ACF is activated - if not deactivate Docman and display notice
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function maybe_deactivate()
	{
		if ( ! class_exists('Acf') )
		{
			deactivate_plugins('em-docman/em-docman.php', true);
			add_action('admin_notices', array(__CLASS__, 'display_deactivation_notice'));
		}
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Maybe save settings
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function maybe_save_settings()
	{
		if ( ! isset($_POST['_em_docman_nonce']) ) { return; }
		if ( check_admin_referer('save_settings', '_em_docman_nonce') )
		{
			$_POST['settings'] = array_map(create_function('$a', 'return trim($a, "\t\r\n ");'), $_POST['settings']);
			update_option('em_docman_settings', $_POST['settings']);
			self::$settings = $_POST['settings'];
			add_action('admin_notices', array(__CLASS__, 'display_settings_saved_notice'));
		}
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Validate that user can download a particular file,
	 * If not redirect them to registration page
	 *
	 *--------------------------------------------------------------------------------------*/
	 
	function maybe_serve_download()
	{
		global $wp_query;
		
		$dl = ( isset($_GET['dl']) && $_GET['dl'] == 1 ) ? true : false;
		
		if ( ! is_single() || ! $dl || get_post_type(get_the_ID()) != self::$pt_slug )
		{	// This is not a download page, don't do anything
			return;
		}
		
		// Get file properties
		$upload_path = ABSPATH . get_option('upload_path');
		$file = get_field('document_file_upload');
		$filename = basename($file['url']);
		$filepath = $upload_path . '/' . $filename;
		
		if ( ! file_exists($filepath) )
		{
			wp_die('File not found.');
		}
		
		$filesize = filesize($filepath);
		$fileinfo = pathinfo($filepath);
		$fake_filename = uniqid(true) . '.' . $fileinfo['extension'];
		
		if ( ! get_field('document_require_registration') )
		{	// File does not require registration, just serve it
			header('Content-Description: File Transfer');
			header('Content-Type: ' . get_post_mime_type($file['id']));
			header('Content-Disposition: attachment; filename=' . $fake_filename);
			header('Content-Length: ' . filesize($filepath));
			readfile($filepath);
			exit;
		}
		else
		{	// File requires registration
			$reg_page = get_field('document_registration_page');
			$cookie_name = 'is_registered__' . $reg_page->ID;
			$cookie_val = md5((( defined('AUTH_SALT') ) ? AUTH_SALT : '') . '_' . $reg_page->ID);
			
			// Added to deal with WP Engine and their non use of Sessions	
  		if(isset($_COOKIE)   &&  isset($_COOKIE['fileperm'])  ):
  		
  		  $goodtogo = false;
  		
    		//Decode our cookie into an array
    		$decoded = unserialize(base64_decode($_COOKIE['fileperm']));
    		if ( isset($decoded[$reg_page->ID]) && isset($decoded[$reg_page->ID][$cookie_name])	&& $decoded[$reg_page->ID][$cookie_name] == $cookie_val ):
    		  $goodtogo = 'letsdoit';
    		endif;
  		
  		endif;
			
			if ( (isset($_SESSION[$cookie_name]) && $_SESSION[$cookie_name] == $cookie_val) || $goodtogo == 'letsdoit' )
			{	// User has valid cookie, serve download
				self::insert_note($_SESSION['entry_id__' . $reg_page->ID], $file['url']);
				header('Content-Description: File Transfer');
				header('Content-Type: ' . get_post_mime_type($file['id']));
				header('Content-Disposition: attachment; filename=' . $fake_filename);
				header('Content-Length: ' . filesize($filepath));
				readfile($filepath);
				exit;
			}
			else
			{
				$data = json_encode(array(
					'rid' => $reg_page->ID,
					'did' => get_the_ID(),
					'fid' => $file['id']
				));
				
				$cookiesave = serialize(array( $file['id']));
				
				wp_redirect(add_query_arg('doc', base64_encode($data), get_page_link($reg_page->ID)), 302);
				exit;
			}
		}
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Add media buttons
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function media_buttons()
	{
		?>
		<a href="#TB_inline?width=480&inlineId=em-docman-shortcode-builder" class="thickbox button" id="em-docman-media-link" title="<?php _e('Add Document List', 'em-docman'); ?>"><span class="em-docman-media-icon"></span> <?php _e('Add Document List', 'em-docman'); ?></a>
		<a href="#TB_inline?width=480&inlineId=em-docman-link-builder" class="thickbox button" id="em-docman-media-link" title="<?php _e('Add Document Link', 'em-docman'); ?>"><span class="em-docman-media-icon"></span> <?php _e('Add Document Link', 'em-docman'); ?></a>
		<?php
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Redirect to download once on thank you page
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function meta_redirect_to_download()
	{
		if ( ! isset($_GET['doc']) || ! isset($_GET['dl']) ) { return; }
		
		$json = base64_decode($_GET['doc']);
		$args = wp_parse_args(json_decode($json), array(
			'did' => 0,
			'rid' => 0,
			'fid' => 0,
		));
		$doc = get_post($args['did']);
		
		if ( ! isset($doc->ID) ) { return false; }
		
		echo '<meta http-equiv="refresh" content="5;url=' . add_query_arg('dl', '1', get_permalink($args['did'])) . '" />';
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Parse template
	 * @param string $template The name of the template
	 * @param int $post_id
	 * @return string
	 *
	 *--------------------------------------------------------------------------------------*/
	 
	function parse_template( $template , $post_id )
	{
		// Get document categories
		$cats_obj = get_the_terms($post_id, self::$tax_slug);
		$cats = array();
		foreach ( (array) $cats_obj as $cat )
		{
			$cats[] = $cat->name;
		}
		
		// File
		$file = get_field('document_file_upload', $post_id);
		$is_external = get_field('is_external_link', $post_id);
		
		if ( $file === false && $is_external === false ) { return false; }
		
		// Additional file info
		$raw_filesize = filesize(get_attached_file($file['id']));
		$fileinfo = pathinfo($file['url']);
		
		// Get image
		$img_html = '';
		if ( $image = get_field('document_associated_image', $post_id) )
		{
			$img_html = '<img src="' . $image['url'] . '" alt="' . $image['alt'] . '" />';
		}
		
		$args = array(
			'{title}' => get_the_title($post_id),
			'{publish_date}' => get_the_time(get_option('date_format'), $post_id),
			'{categories}' => implode(', ', $cats),
			'{short_description}' => get_field('document_short_description', $post_id),
			'{full_description}' => get_field('document_full_description', $post_id),
			'{image}' => $img_html,
			'{fileicon}' => '<img class="doc-icon" alt="" height="24" src="' . self::get_file_icon($fileinfo['extension']) . '" />',
			'{filetype}' => get_post_mime_type($file['id']),
			'{filesize}' => size_format($raw_filesize, 1),
			'{require_registration}' => get_field('document_require_registration', $post_id) ? 'Yes' : 'No',
			'{download_url}' => ( $is_external ) ? esc_url(get_field('link_to_pdf', $post_id)) : get_permalink($post_id) . '?dl=1',
			'{detail_url}' => get_permalink($post_id),
			'{link_target}' => ( $is_external ) ? '_blank' : '_self'
		);
		
		$template = str_replace(array_keys($args), array_values($args), stripslashes(self::$settings[$template]));
		
		if ( $image )
		{
			$template = preg_replace('/{image class="(.*)"}/', '<img class="$1" alt="' . $image['alt'] . '" src="' . $image['url'] . '" />', $template);
		}
		else {
  		$template = preg_replace('/{image class="(.*)"}/', '', $template);
		}	
		
		return preg_replace('/{fileicon size="(\d*)"?}/', '<img class="doc-icon" alt="" height="$1" src="' . self::get_file_icon($fileinfo['extension']) . '" />', $template);
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Modify post content if on a document detail page
	 * @param array $posts An array of posts returned by the query
	 * @param object $query The current query object
	 * @return array $posts
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function posts_results( $posts, $query )
	{
		if ( ! $query->is_main_query() || count($posts) == 0 ) { return $posts; }
		
		$content = '';
		$post = $posts[0];
		$template = 'detail_template';
		$cats = get_the_terms($post->ID, self::$tax_slug);
		
		if ( is_array($cats) )
		{
			$cat = array_shift($cats);
			$tmpl_name = 'detail_template_' . $cat->term_id;
		
			if ( !empty(self::$settings[$tmpl_name]) )
			{
				$template = $tmpl_name;
			}
		}
				
		if ( is_single() && get_post_type($post->ID) == self::$pt_slug ) {
			$content .= self::parse_template($template, $post->ID);
		}
		
		if ( isset($_GET['doc']) && isset($_GET['dl']) )
		{	
			$json = base64_decode($_GET['doc']);
			$args = wp_parse_args(json_decode($json), array(
				'did' => 0,
				'fid' => 0,
				'rid' => 0,
			));
			$doc = get_post($args['did']);
			
			if ( isset($doc->ID) )
			{
				$content .= "\n\n" . '<a href="' . add_query_arg('dl', '1', get_permalink($args['did'])) . '">Download Now</a>';
			}
		}
		
		if ( ! empty($content) )
		{
			$posts[0]->post_content .= $content;
		}
		
		return $posts;
	}

	/*--------------------------------------------------------------------------------------
	 *
	 * Register any custom post types or taxonomies
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function register_types()
	{
		register_post_type(self::$pt_slug, array(
			'labels' => array(
				'name' => __('Documents', self::$pt_slug),
				'singular_name' => __('Document', self::$pt_slug),
				'add_new' => __('Add New', self::$pt_slug),
				'add_new_item' => __('Add New Document', self::$pt_slug),
				'edit_item' => __('Edit Document', self::$pt_slug),
				'new_item' => __('New Document', self::$pt_slug),
				'view_item' => __('View Document', self::$pt_slug),
				'search_items' => __('Search Documents', self::$pt_slug),
				'not_found' => __('No documents found', self::$pt_slug),
				'not_found_in_trash' => __('No documents found in trash', self::$pt_slug),
			),
			'public' => true,
			'menu_position' => 25,
			'supports' => array('title'),
		));
		
		register_taxonomy(self::$tax_slug, self::$pt_slug, array(
			'labels' => array(
				'name' => __('Categories', self::$tax_slug),
				'singular_name' => __('Category', self::$tax_slug),
			),
			'hierarchical' => true,
		));
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Settings page
	 *
	 *--------------------------------------------------------------------------------------*/
	
	function settings_page()
	{
		include_once self::$base_path . 'core/includes/settings.php';
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * [document_list] shortcode
	 *
	 *--------------------------------------------------------------------------------------*/
	 
	function shortcode_document_list( $atts, $content = null )
	{
		extract(shortcode_atts(array(
			'cats' => '',
		), $atts));
		
		$query_args = array(
			'posts_per_page' => -1,
			'post_type' => self::$pt_slug,
			'paged' => max(1, get_query_var('paged')),
		);
		$tmpl_name = '';
		
		if ( ! empty($cats) )
		{
			$cat_ids = explode(',', $cats);
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => self::$tax_slug,
					'field' => 'id',
					'terms' => $cat_ids,
					'operator' => 'IN',
				),
			);
			$tmpl_name = 'list_template_' . array_shift($cat_ids);
		}
		
		$docs = new WP_Query($query_args);
		$html = '';
		$template = 'list_template';
		
		if ( !empty(self::$settings[$tmpl_name]) )
		{
			$template = $tmpl_name;
		}
		
		if ( $docs->have_posts() )
		{
			$html .= '<dl class="document-list">';
		
			while ( $docs->have_posts() )
			{
				$docs->the_post();
				$html .= '<dd class="document clearfix">';
				$html .= self::parse_template($template, get_the_ID());
				$html .= '</dd>';
			}
			
			wp_reset_postdata();
			
			$html .= '</dl>';
		}
		
		return $html;
	}
	
	/*--------------------------------------------------------------------------------------
	 *
	 * Start session
	 *
	 *--------------------------------------------------------------------------------------*/

	 function session_start()
	 {
		 if ( isset($_SESSION) ) { return; }
		 
		 session_name('wp-session');
		 session_start();
	 }
}
