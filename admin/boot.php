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

	function wbcr_cyrlitera_rating_widget_url($page_url, $plugin_name)
	{
		if( $plugin_name == WCTR_Plugin::app()->getPluginName() ) {
			return 'https://goo.gl/68ucHp';
		}

		return $page_url;
	}

	add_filter('wbcr_factory_imppage_rating_widget_url', 'wbcr_cyrlitera_rating_widget_url', 10, 2);

	function wbcr_cyrlitera_group_options($options)
	{
		$options[] = array(
			'name' => 'use_transliterations',
			'title' => __('Использовать транслитерацию', 'cyrlitera'),
			'tags' => array(),
			'values' => array('hide_admin_notices' => 'only_selected')
		);

		$options[] = array(
			'name' => 'use_transliterations_filename',
			'title' => __('Преобразовывать имена файлов', 'cyrlitera'),
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



