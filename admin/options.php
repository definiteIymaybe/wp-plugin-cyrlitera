<?php
	/**
	 * Options for additionally form
	 * @author Webcraftic <wordpress.webraftic@gmail.com>
	 * @copyright (c) 21.01.2018, Webcraftic
	 * @version 1.0
	 */

	// Exit if accessed directly
	if( !defined('ABSPATH') ) {
		exit;
	}

	/**
	 * @return array
	 */
	function wbcr_cyrlitera_get_plugin_options()
	{
		$options = array();

		$options[] = array(
			'type' => 'html',
			'html' => '<div class="wbcr-factory-page-group-header">' . '<strong>' . __('Transliteration of Cyrillic alphabet.', 'cyrlitera') . '</strong>' . '<p>' . __('Converts Cyrillic permalinks of post, pages, taxonomies and media files to the Latin alphabet. Supports Russian, Ukrainian, Georgian, Bulgarian languages. Example: http://site.dev/последние-новости -> http://site.dev/poslednie-novosti', 'cyrlitera') . '</p>' . '</div>'
		);

		$options[] = array(
			'type' => 'checkbox',
			'way' => 'buttons',
			'name' => 'use_transliterations',
			'title' => __('Use transliteration', 'cyrlitera'),
			'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'green'),
			'hint' => __('If you enable this option, the permanent URLs of all previously published posts and pages will be converted into URLs with Latin characters. All new pages and posts will also have a URL in the Latin alphabet.', 'cyrlitera'),
			'default' => false
		);
		$options[] = array(
			'type' => 'checkbox',
			'way' => 'buttons',
			'name' => 'use_transliterations_filename',
			'title' => __('Convert file names', 'cyrlitera'),
			'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'green'),
			'hint' => __('This option works only for new media library files. All Cyrillic names of the downloaded files will be converted to names with Latin characters.', 'cyrlitera'),
			'default' => false
		);

		return $options;
	}

	/**
	 * @param $form
	 * @param $page Wbcr_FactoryPages000_ImpressiveThemplate
	 * @return mixed
	 */
	function wbcr_cyrlitera_seo_form_options($form, $page)
	{
		if( empty($form) ) {
			return $form;
		}

		$options = wbcr_cyrlitera_get_plugin_options();

		foreach(array_reverse($options) as $option) {
			array_unshift($form[0]['items'], $option);
		}

		return $form;
	}

	add_filter('wbcr_clr_seo_form_options', 'wbcr_cyrlitera_seo_form_options', 10, 2);