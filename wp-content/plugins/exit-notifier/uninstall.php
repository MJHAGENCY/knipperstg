<?php

/**
 *
 * This file runs when the plugin in uninstalled (deleted).
 * This will not run when the plugin is deactivated.
 * Ideally you will add all your clean-up scripts here
 * that will clean-up unused meta, options, etc. in the database.
 *
 */

// If plugin is not being uninstalled, exit (do nothing)
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete options if remove_settings_on_uninstall is set.
if (get_option('exitbox_remove_settings_on_uninstall','on') === 'on') {
	// Content
	delete_option("exitbox_title_field");
	delete_option("exitbox_body_text");
	delete_option("exitbox_go_button_text");
	delete_option("exitbox_include_url");
	delete_option("exitbox_cancel_button_text");

	//Alternate Content
	delete_option("exitbox_alt_classname");
	delete_option("exitbox_alt_title_field");
	delete_option("exitbox_alt_body_text");
	delete_option("exitbox_alt_go_button_text");
	delete_option("exitbox_alt_include_url");
	delete_option("exitbox_alt_cancel_button_text");

	// Custom Content
	delete_option('exitbox_activate_custom_content');

	// Behavior
	delete_option("exitbox_alt_cancel_button_text");
	delete_option("exitbox_apply_to_all_offsite_links");
	delete_option("exitbox_jquery_selector_field");
	delete_option("exitbox_css_exclusion_class");
	delete_option("exitbox_relnofollow");
	delete_option("exitbox_debugtoconsole");

	// Form Behavior
	delete_option('exitbox_enable_notifier_for_forms');
	delete_option('exitbox_apply_to_all_offsite_forms');
	delete_option('exitbox_jquery_form_selector_field');

	// Display
	delete_option("exitbox_theme");
	delete_option("exitbox_backgroundcolor");
	delete_option("exitbox_blurbackground");
	delete_option("exitbox_size");
	delete_option("exitbox_showAnimation");
	delete_option("exitbox_hideAnimation");
	delete_option("exitbox_visual_indication");

	// Timeout
	delete_option("exitbox_enable_timeout");
	delete_option("exitbox_continue_or_cancel");
	delete_option("exitbox_timeout_seconds");
	delete_option("exitbox_enable_progressbar");
	delete_option("exitbox_timeout_statement");

	// Custom CSS
	delete_option("exitbox_custom_css");
	delete_option("exitbox_advanced_custom_css");
	delete_option("exitbox_addclasses");
	delete_option("exitbox_classestoadd");

	// Advanced
	delete_option("exitbox_remove_settings_on_uninstall");
}
