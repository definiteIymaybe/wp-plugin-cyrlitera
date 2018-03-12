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
			'html' => '<div class="wbcr-factory-page-group-header">' . '<strong>' . __('Кирилическая транслитерация.', 'cyrlitera') . '</strong>' . '<p>' . __('Конвертирует кирилические постоянные ссылки записей, стараниц, тегов, медиа и файлов на латиницу. Поддерживает Грузинский, Болгарский, Украинский, Русский язык. Пример: http://site.dev/последние-новости -> http://site.dev/poslednie-novosti', 'cyrlitera') . '</p>' . '</div>'
		);

		$options[] = array(
			'type' => 'checkbox',
			'way' => 'buttons',
			'name' => 'use_transliterations',
			'title' => __('Использовать транслитерацию', 'cyrlitera'),
			'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'green'),
			'hint' => __('Если включить эту опцию, постоянный url всех ваших старых статей и страниц будет преобразован в url с латинскими символами. Все новые страницы и записи, также будут иметь url на латинице.', 'cyrlitera'),
			'default' => false
		);
		$options[] = array(
			'type' => 'checkbox',
			'way' => 'buttons',
			'name' => 'use_transliterations_filename',
			'title' => __('Преобразовывать имена файлов', 'cyrlitera'),
			'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'green'),
			'hint' => __('Эта опция работает только для новых загруженных файлов, все загруженные файлы с кирилическими символами, будут преобразованы в имена с латинскими символами.', 'cyrlitera'),
			'default' => false
		);

		if( defined('LOADING_CYRLITERA_AS_ADDON') ) {
			$options[] = array(
				'type' => 'separator'
			);
		}

		return $options;
	}

	/**
	 * @param $form
	 * @param $page FactoryPages000_ImpressiveThemplate
	 * @return mixed
	 */
	function wbcr_cyrlitera_additionally_form_options($form, $page)
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

	add_filter('wbcr_clr_seo_form_options', 'wbcr_cyrlitera_additionally_form_options', 10, 2);