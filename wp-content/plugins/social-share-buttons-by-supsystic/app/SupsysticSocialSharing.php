<?php

/**
 * Class SupsysticSocialSharing
 */
class SupsysticSocialSharing
{
    private $environment;

    public function __construct()
    {
        if (!class_exists('Rsc_Autoloader', false)) {
            require dirname(dirname(__FILE__)) . '/vendor/Rsc/Autoloader.php';
            Rsc_Autoloader::register();
        }

        $pluginPath = dirname(dirname(__FILE__));
        $pluginName = 'sss';
        $pluginTitleName = 'Social Share by Supsystic';
        $pluginSlug = 'supsystic-social-sharing';
        $environment = new Rsc_Environment($pluginName, '2.2.7', $pluginPath);

        /* Configure */
        $environment->configure(
            array(
                'optimizations'    => 1,
                'environment'      => $this->getPluginEnvironment(),
                'default_module'   => 'projects',
                'lang_domain'      => 'social_sharing',
                'lang_path'        => plugin_basename(dirname(__FILE__)) . '/langs',
                'plugin_title_name' => $pluginTitleName,
                'plugin_slug' => $pluginSlug,
                'plugin_prefix'    => 'SocialSharing',
                'plugin_source'    => $pluginPath . '/src',
                'plugin_menu'      => array(
                    'page_title' => __(
                        $pluginTitleName,
                        $pluginSlug
                    ),
                    'menu_title' => __(
                        $pluginTitleName,
                        $pluginSlug
                    ),
                    'capability' => 'manage_options',
                    'menu_slug'  => $pluginSlug,
                    'icon_url'   => 'dashicons-share',
                    'position'   => '101.8',
                ),
                'shortcode_name'   => 'grid-gallery',
                'db_prefix'        => 'supsystic_ss_',
                'hooks_prefix'     => 'supsystic_ss_',
                'ajax_url'         => admin_url('admin-ajax.php'),
                'admin_url'        => admin_url(),
                'uploads_rw'       => true,
                'jpeg_quality'     => 95,
                'plugin_db_update' => true,
                'revision'         => 325,
                'welcome_page_was_showed' => get_option($pluginName . '_welcome_page_was_showed'),
                'promo_controller' => 'SocialSharing_Promo_Controller',
	              'pro_button_option_prefix'=>'pro_button_option_',
            )
        );

        $this->environment = $environment;
    }

	public function getEnvironment() {
		return $this->environment;
	}

    public function run()
    {
        $this->environment->run();
    }

    public function activate($bootstrap)
    {
        //if is multisite mode
        if (function_exists('is_multisite') && is_multisite()) {
            global $wpdb;
            $blog_id = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blog_id as $id) {
                if (switch_to_blog($id)) {
                    $this->createSchema();
					           restore_current_blog();
				        }
            }
        } else {
            $this->createSchema();
        }
    }

    public function db_table_exist($table) {
      global $wpdb;
      switch ($table) {
         case 'supsystic_ss_networks':
            $res = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}supsystic_ss_networks'");
         break;
         case 'supsystic_ss_projects':
            $res = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}supsystic_ss_projects'");
         break;
         case 'supsystic_ss_project_networks':
            $res = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}supsystic_ss_project_networks'");
         break;
         case 'supsystic_ss_shares':
            $res = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}supsystic_ss_shares'");
         break;
         case 'supsystic_ss_shares_object':
            $res = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}supsystic_ss_shares_object'");
         break;
         case 'supsystic_ss_views':
            $res = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}supsystic_ss_views'");
         break;
      }
      return !empty($res);
   }

    public function createSchema()
    {
        global $wpdb;
  	    if(get_option('sss'.'_installed')) return;

        if (!function_exists('dbDelta')) {
            require_once(ABSPATH.'wp-admin/includes/upgrade.php');
        }

        $wpdb->show_errors = false;

        $wpdb->query('SET FOREIGN_KEY_CHECKS=0;');

        if (!$this->db_table_exist('supsystic_ss_projects')) {
          $charset_collate = $wpdb->get_charset_collate();
    			$table_name = $wpdb->prefix . 'supsystic_ss_projects';
    			$sql = "CREATE TABLE IF NOT EXISTS $table_name (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(255) NOT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `settings` TEXT NOT NULL,
            PRIMARY KEY  (`id`)
          ) $charset_collate";
    			dbDelta( $sql );
        }

        if (!$this->db_table_exist('supsystic_ss_networks')) {
          $charset_collate = $wpdb->get_charset_collate();
    			$table_name = $wpdb->prefix . 'supsystic_ss_networks';
    			$sql = "CREATE TABLE IF NOT EXISTS $table_name (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) NOT NULL,
            `url` VARCHAR(255) NOT NULL,
            `class` VARCHAR(255) NOT NULL,
            `brand_primary` VARCHAR(7) NOT NULL DEFAULT '#000000',
            `brand_secondary` VARCHAR(7) NOT NULL DEFAULT '#ffffff',
            `total_shares` INT(11) UNSIGNED NULL DEFAULT '0',
            PRIMARY KEY  (`id`)
          ) $charset_collate";
    			dbDelta( $sql );

          $wpdb->insert($table_name, array(
    					'id' => 1, 'name' => 'Facebook',
    					'url' => 'http://www.facebook.com/sharer.php?u={url}',
    					'class' => 'facebook',
              'brand_primary' => '#3b5998', 'brand_secondary' => '#ffffff',
    					'total_shares' => 0,
    			));

          $wpdb->insert($table_name, array(
    					'id' => 2, 'name' => 'Twitter',
    					'url' => 'https://twitter.com/share?url={url}&text={title}',
    					'class' => 'twitter',
              'brand_primary' => '#55acee', 'brand_secondary' => '#ffffff',
    					'total_shares' => 0,
    			));

          $wpdb->insert($table_name, array(
    					'id' => 4, 'name' => 'VKontakte',
    					'url' => 'http://vk.com/share.php?url={url}',
    					'class' => 'vk',
              'brand_primary' => '#45668e', 'brand_secondary' => '#ffffff',
    					'total_shares' => 0,
    			));

          $wpdb->insert($table_name, array(
    					'id' => 5, 'name' => 'Like',
    					'url' => '#',
    					'class' => 'like',
              'brand_primary' => '#9b59b6', 'brand_secondary' => '#ffffff',
    					'total_shares' => 0,
    			));

          $wpdb->insert($table_name, array(
    					'id' => 6, 'name' => 'Reddit',
    					'url' => 'http://reddit.com/submit?url={url}&title={title}',
    					'class' => 'reddit',
              'brand_primary' => '#cee3f8', 'brand_secondary' => '#ffffff',
    					'total_shares' => 0,
    			));

          $wpdb->insert($table_name, array(
    					'id' => 7, 'name' => 'Pinterest',
    					'url' => 'http://pinterest.com/pin/create/link/?url={url}&description={title}',
    					'class' => 'pinterest',
              'brand_primary' => '#cc2127', 'brand_secondary' => '#ffffff',
    					'total_shares' => 0,
    			));

          $wpdb->insert($table_name, array(
    					'id' => 11, 'name' => 'Livejournal',
    					'url' => 'http://www.livejournal.com/update.bml?subject={title}&event={url}',
    					'class' => 'livejournal',
              'brand_primary' => '#3399ff', 'brand_secondary' => '#ffffff',
    					'total_shares' => 0,
    			));

          $wpdb->insert($table_name, array(
    					'id' => 12, 'name' => 'Odnoklassniki',
    					'url' => 'https://connect.ok.ru/offer?url={url}&title={title}',
    					'class' => 'odnoklassniki',
              'brand_primary' => '#3399ff', 'brand_secondary' => '#ffffff',
    					'total_shares' => 0,
    			));

          $wpdb->insert($table_name, array(
    					'id' => 13, 'name' => 'Linkedin',
    					'url' => 'https://www.linkedin.com/shareArticle?mini=true&title={title}&url={url}',
    					'class' => 'linkedin',
              'brand_primary' => '#3399ff', 'brand_secondary' => '#ffffff',
    					'total_shares' => 0,
    			));

          $wpdb->insert($table_name, array(
    					'id' => 14, 'name' => 'Print',
    					'url' => '#',
    					'class' => 'print',
              'brand_primary' => '#3399ff', 'brand_secondary' => '#ffffff',
    					'total_shares' => 0,
    			));

          $wpdb->insert($table_name, array(
    					'id' => 15, 'name' => 'Add Bookmark',
    					'url' => '#',
    					'class' => 'bookmark',
              'brand_primary' => '#3399ff', 'brand_secondary' => '#ffffff',
    					'total_shares' => 0,
    			));

          $wpdb->insert($table_name, array(
    					'id' => 16, 'name' => 'Mail',
    					'url' => '#',
    					'class' => 'mail',
              'brand_primary' => '#3399ff', 'brand_secondary' => '#ffffff',
    					'total_shares' => 0,
    			));

          $wpdb->insert($table_name, array(
    					'id' => 17, 'name' => 'Evernote',
    					'url' => 'https://www.evernote.com/clip.action?url={url}&title={title}',
    					'class' => 'evernote',
              'brand_primary' => '#6ba92f', 'brand_secondary' => '#ffffff',
    					'total_shares' => 0,
    			));

          $wpdb->insert($table_name, array(
    					'id' => 18, 'name' => 'WhatsApp',
    					'url' => 'https://web.whatsapp.com/send?text={url}',
    					'class' => 'whatsapp',
              'brand_primary' => '#43C353', 'brand_secondary' => '#ffffff',
    					'total_shares' => 0,
    			));

          $wpdb->insert($table_name, array(
    					'id' => 19, 'name' => 'Tumblr',
    					'url' => 'http://www.tumblr.com/share/link?url={url}&name={title}&description={description}',
    					'class' => 'tumblr',
              'brand_primary' => '#36465D', 'brand_secondary' => '#ffffff',
    					'total_shares' => 0,
    			));

        }

        if (!$this->db_table_exist('supsystic_ss_project_networks')) {
          $charset_collate = $wpdb->get_charset_collate();
    			$table_name = $wpdb->prefix . 'supsystic_ss_project_networks';
          $tn1 = 'FK__' . $wpdb->prefix . 'supsystic_ss_projects';
          $tn2 = 'FK__' . $wpdb->prefix . 'supsystic_ss_networks';
          $tn3 = $wpdb->prefix . 'supsystic_ss_networks';
          $tn4 = $wpdb->prefix . 'supsystic_ss_projects';
    			$sql = "CREATE TABLE IF NOT EXISTS $table_name (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `project_id` INT(11) UNSIGNED NULL DEFAULT NULL,
            `network_id` INT(11) UNSIGNED NULL DEFAULT NULL,
            `position` INT(11) UNSIGNED NULL DEFAULT '0',
            `title` VARCHAR(255) NULL DEFAULT NULL,
            `text` VARCHAR(255) NULL DEFAULT NULL,
            `tooltip` VARCHAR(255) NULL DEFAULT NULL,
            `text_format` VARCHAR(255) NULL DEFAULT NULL,
            `profile_name` VARCHAR(255) NULL DEFAULT NULL,
            `icon_image` INT(11) UNSIGNED NULL DEFAULT NULL,
            `use_short_url`  bit(1) NULL DEFAULT NULL,
            `mail_to_default` VARCHAR(255) NULL DEFAULT NULL,
            PRIMARY KEY  (`id`),
            INDEX $tn1 (`project_id`),
            INDEX $tn2 (`network_id`),
            CONSTRAINT $tn2 FOREIGN KEY (`network_id`) REFERENCES $tn3 (`id`),
            CONSTRAINT $tn1 FOREIGN KEY (`project_id`) REFERENCES $tn4 (`id`) ON DELETE CASCADE
          ) $charset_collate";
    			dbDelta( $sql );
        }

        if (!$this->db_table_exist('supsystic_ss_shares')) {
          $charset_collate = $wpdb->get_charset_collate();
    			$table_name = $wpdb->prefix . 'supsystic_ss_shares';
          $tn1 = 'FK_' . $wpdb->prefix . 'supsystic_ss_shares_networks';
          $tn2 = 'FK_' . $wpdb->prefix . 'supsystic_ss_shares_projects';
          $tn3 = $wpdb->prefix . 'supsystic_ss_networks';
          $tn4 = $wpdb->prefix . 'supsystic_ss_projects';

    			$sql = "CREATE TABLE IF NOT EXISTS $table_name (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `network_id` INT(11) UNSIGNED NULL DEFAULT NULL,
            `project_id` INT(11) UNSIGNED NULL DEFAULT NULL,
            `post_id` INT(11) UNSIGNED NULL DEFAULT NULL,
            `timestamp` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            INDEX $tn1 (`network_id`),
            INDEX $tn2 (`project_id`),
            CONSTRAINT $tn1 FOREIGN KEY (`network_id`) REFERENCES $tn3 (`id`) ON DELETE CASCADE,
            CONSTRAINT $tn2 FOREIGN KEY (`project_id`) REFERENCES $tn4 (`id`) ON DELETE CASCADE
          ) $charset_collate";
    			dbDelta( $sql );
        }

        if (!$this->db_table_exist('supsystic_ss_views')) {
          $charset_collate = $wpdb->get_charset_collate();
    			$table_name = $wpdb->prefix . 'supsystic_ss_views';
          $tn1 = 'FK___' . $wpdb->prefix . 'supsystic_ss_views_projects';
          $tn2 = $wpdb->prefix . 'supsystic_ss_projects';

    			$sql = "CREATE TABLE IF NOT EXISTS $table_name (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `project_id` INT(11) UNSIGNED NULL DEFAULT NULL,
            `post_id` INT(11) UNSIGNED NULL DEFAULT NULL,
            `timestamp` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            INDEX $tn1 (`project_id`),
            CONSTRAINT $tn1 FOREIGN KEY (`project_id`) REFERENCES $tn2 (`id`) ON DELETE CASCADE
          ) $charset_collate";
    			dbDelta( $sql );
        }

        $wpdb->query('SET FOREIGN_KEY_CHECKS=1;');

        $wpdb->show_errors = true;
        update_option('sss'.'_installed', 1);

  }

	public function dropOptions()
	{
		delete_option('sss'.'_installed');
		delete_option('sss'.'_updated');
		delete_option('sss'.'_promo_shown');
		delete_option('_social_sharing_rev');
    // global $wpdb;
    // $wpdb->query('SET FOREIGN_KEY_CHECKS=0;');
    // $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}supsystic_ss_networks");
    // $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}supsystic_ss_project_networks");
    // $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}supsystic_ss_projects");
    // $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}supsystic_ss_views");
    // $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}supsystic_ss_shares");
    // $wpdb->query('SET FOREIGN_KEY_CHECKS=1;');
	}
    public function deactivate($bootstrap)
    {
        register_deactivation_hook($bootstrap, array($this, 'dropOptions'));
    }

    protected function getPluginEnvironment()
    {
        $environment = Rsc_Environment::ENV_PRODUCTION;

        if ((defined('WP_DEBUG') && WP_DEBUG) || (defined(
                    'SUPSYSTIC_SS_DEBUG'
                ) && SUPSYSTIC_SS_DEBUG)
        ) {
            $environment = Rsc_Environment::ENV_DEVELOPMENT;
        }

        return $environment;
    }
}
