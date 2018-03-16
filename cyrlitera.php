<?php
	/**
	 * Plugin Name: Cyrlitera - transliteration of Cyrillic into Latin alphabet
	 * Plugin URI: https://wordpress.org/plugins/cyrlitera/
	 * Description: Disable admin notices plugin gives you the option to hide updates warnings and inline notices in the admin panel.
	 * Author: Webcraftic <wordpress.webraftic@gmail.com>
	 * Version: 1.0.1
	 * Text Domain: cyrlitera
	 * Domain Path: /languages/
	 */

	// Exit if accessed directly
	if( !defined('ABSPATH') ) {
		exit;
	}

	if( defined('WCTR_PLUGIN_ACTIVE') || (defined('WCL_PLUGIN_ACTIVE') && !defined('LOADING_CYRLITERA_AS_ADDON')) ) {
		function wbcr_cyrlitera_admin_notice_error()
		{
			?>
			<div class="notice notice-error">
				<p><?php _e('We found that you have the "Clearfy - disable unused features" plugin installed, this plugin already has disable comments functions, so you can deactivate plugin "Cyrilic transliteration"!', 'cyrlitera'); ?></p>
			</div>
		<?php
		}

		add_action('admin_notices', 'wbcr_cyrlitera_admin_notice_error');

		return;
	} else {

		define('WCTR_PLUGIN_ACTIVE', true);
		define('WCTR_PLUGIN_DIR', dirname(__FILE__));
		define('WCTR_PLUGIN_BASE', plugin_basename(__FILE__));
		define('WCTR_PLUGIN_URL', plugins_url(null, __FILE__));

		#comp remove
		// the following constants are used to debug features of diffrent builds
		// on developer machines before compiling the plugin

		// build: free, premium, ultimate
		if( !defined('BUILD_TYPE') ) {
			define('BUILD_TYPE', 'free');
		}
		// language: en_US, ru_RU
		if( !defined('LANG_TYPE') ) {
			define('LANG_TYPE', 'en_EN');
		}
		// license: free, paid
		if( !defined('LICENSE_TYPE') ) {
			define('LICENSE_TYPE', 'free');
		}

		// wordpress language
		if( !defined('WPLANG') ) {
			define('WPLANG', LANG_TYPE);
		}
		// the compiler library provides a set of functions like onp_build and onp_license
		// to check how the plugin work for diffrent builds on developer machines

		if( !defined('LOADING_CYRLITERA_AS_ADDON') ) {
			require('libs/onepress/compiler/boot.php');
			// creating a plugin via the factory
		}
		// #fix compiller bug new Factory000_Plugin
		#endcomp
		
		if( !defined('LOADING_CYRLITERA_AS_ADDON') ) {
			require_once(WCTR_PLUGIN_DIR . '/libs/factory/core/boot.php');
		}

		require_once(WCTR_PLUGIN_DIR . '/includes/class.helpers.php');
		require_once(WCTR_PLUGIN_DIR . '/includes/class.plugin.php');

		if( !defined('LOADING_CYRLITERA_AS_ADDON') ) {

			new WCTR_Plugin(__FILE__, array(
				'prefix' => 'wbcr_cyrlitera_',
				'plugin_name' => 'wbcr_cyrlitera',
				'plugin_title' => __('Webcraftic cyrilic transliteration', 'cyrlitera'),
				'plugin_version' => '1.0.1',
				'required_php_version' => '5.2',
				'required_wp_version' => '4.2',
				'plugin_build' => BUILD_TYPE,
				'updates' => WCTR_PLUGIN_DIR . '/updates/'
			));
		}
	}