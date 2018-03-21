<?php
	/**
	 * Admin boot
	 * @author Webcraftic <wordpress.webraftic@gmail.com>
	 * @copyright Webcraftic 25.05.2017
	 * @version 1.0
	 */

	// Exit if accessed directly
	if( !defined('ABSPATH') ) {
		exit;
	}

	/**
	 * @return array
	 */
	function wbcr_cyrlitera_get_conflict_notices_error()
	{
		$notices = array();
		$plugin_title = WCTR_Plugin::app()->getPluginTitle();

		$default_notice = $plugin_title . ': ' . __('We found that you have the plugin %s installed. The functions of this plugin already exist in %s. Please deactivate plugin %s to avoid conflicts between plugins functions.', 'cyrlitera');
		$default_notice .= ' ' . __('If you do not want to deactivate the plugin %s for some reason, we strongly recommend do not use the same plugins functions at the same time!', 'cyrlitera');

		if( is_plugin_active('wp-translitera/wp-translitera.php') ) {
			$notices[] = sprintf($default_notice, 'WP Translitera', $plugin_title, 'WP Translitera', 'WP Translitera');
		}

		if( is_plugin_active('cyr3lat/cyr-to-lat.php') ) {
			$notices[] = sprintf($default_notice, 'Cyr to Lat enhanced', $plugin_title, 'Cyr to Lat enhanced', 'Cyr to Lat enhanced');
		}

		if( is_plugin_active('cyr2lat/cyr-to-lat.php') ) {
			$notices[] = sprintf($default_notice, 'Cyr to Lat', $plugin_title, 'Cyr to Lat', 'Cyr to Lat');
		}

		if( is_plugin_active('cyr-and-lat/cyr-and-lat.php') ) {
			$notices[] = sprintf($default_notice, 'Cyr-And-Lat', $plugin_title, 'Cyr-And-Lat', 'Cyr-And-Lat');
		}

		if( is_plugin_active('rustolat/rus-to-lat.php') ) {
			$notices[] = sprintf($default_notice, 'RusToLat', $plugin_title, 'RusToLat', 'RusToLat');
		}

		if( is_plugin_active('rus-to-lat-advanced/ru-translit.php') ) {
			$notices[] = sprintf($default_notice, 'Rus filename and link translit', $plugin_title, 'Rus filename and link translit', 'Rus filename and link translit');
		}

		return $notices;
	}

	add_filter('wbcr_clr_seo_page_warnings', 'wbcr_cyrlitera_get_conflict_notices_error');

	/**
	 * Ошибки совместимости с похожими плагинами
	 */
	function wbcr_cyrlitera_admin_conflict_notices_error()
	{
		$notices = wbcr_cyrlitera_get_conflict_notices_error();

		if( empty($notices) ) {
			return;
		}

		?>
		<div id="wbcr-cyrlitera-conflict-error" class="notice notice-error is-dismissible">
			<?php foreach((array)$notices as $notice): ?>
				<p>
					<?= $notice ?>
				</p>
			<?php endforeach; ?>
		</div>
	<?php
	}

	add_action('admin_notices', 'wbcr_cyrlitera_admin_conflict_notices_error');

	function wbcr_cyrlitera_rating_widget_url($page_url, $plugin_name)
	{
		if( $plugin_name == WCTR_Plugin::app()->getPluginName() ) {
			return 'https://goo.gl/68ucHp';
		}

		return $page_url;
	}

	add_filter('wbcr_factory_pages_000_imppage_rating_widget_url', 'wbcr_cyrlitera_rating_widget_url', 10, 2);

	function wbcr_cyrlitera_group_options($options)
	{
		$options[] = array(
			'name' => 'use_transliterations',
			'title' => __('Use transliteration', 'cyrlitera'),
			'tags' => array(),
			'values' => array('hide_admin_notices' => 'only_selected')
		);

		$options[] = array(
			'name' => 'use_transliterations_filename',
			'title' => __('Convert file names', 'cyrlitera'),
			'tags' => array()
		);

		return $options;
	}

	add_filter("wbcr_clearfy_group_options", 'wbcr_cyrlitera_group_options');

	function wbcr_cyrlitera_set_plugin_meta($links, $file)
	{
		if( $file == WCTR_PLUGIN_BASE ) {
			$links[] = '<a href="https://goo.gl/TcMcS4" style="color: #FF5722;font-weight: bold;" target="_blank">' . __('Get ultimate plugin free', 'cyrlitera') . '</a>';
		}

		return $links;
	}

	if( !defined('LOADING_CYRLITERA_AS_ADDON') ) {
		add_filter('plugin_row_meta', 'wbcr_cyrlitera_set_plugin_meta', 10, 2);
	}



