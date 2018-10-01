<?php

	/**
	 * The page Settings.
	 *
	 * @since 1.0.0
	 */

	// Exit if accessed directly
	if( !defined('ABSPATH') ) {
		exit;
	}

	class WCTR_CyrliteraPage extends Wbcr_FactoryPages000_ImpressiveThemplate {

		/**
		 * The id of the page in the admin menu.
		 *
		 * Mainly used to navigate between pages.
		 * @see FactoryPages000_AdminPage
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $id = "transliteration";
		public $page_menu_dashicon = 'dashicons-testimonial';
		public $available_for_multisite = true;

		/**
		 * @param Wbcr_Factory000_Plugin $plugin
		 */
		public function __construct(Wbcr_Factory000_Plugin $plugin)
		{
			$this->menu_title = __('Transliteration', 'cyrlitera');

			if( !defined('LOADING_CYRLITERA_AS_ADDON') ) {
				$this->internal = false;
				$this->menu_target = 'options-general.php';
				$this->add_link_to_plugin_actions = true;
			}

			parent::__construct($plugin);

			$this->plugin = $plugin;
		}

		public function getMenuTitle()
		{
			return defined('LOADING_CYRLITERA_AS_ADDON')
				? __('Transliteration', 'cyrlitera')
				: __('General', 'cyrlitera');
		}

		/**
		 * Requests assets (js and css) for the page.
		 *
		 * @see Wbcr_FactoryPages000_AdminPage
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function assets($scripts, $styles)
		{
			parent::assets($scripts, $styles);

			// Add Clearfy styles for HMWP pages
			if( defined('WBCR_CLEARFY_PLUGIN_ACTIVE') ) {
				$this->styles->add(WCL_PLUGIN_URL . '/admin/assets/css/general.css');
			}
		}

		/*protected function afterFormSave()
		{
			$use_transliterations = $this->plugin->getOption('use_transliterations');
			$transliterate_existing_slugs = $this->plugin->getOption('transliterate_existing_slugs');

			if( !$use_transliterations || $transliterate_existing_slugs ) {
				return;
			}
			WCTR_Helper::convertExistingSlugs();
			$this->plugin->updateOption('transliterate_existing_slugs', 1);
		}*/

		/**
		 * Permalinks options.
		 *
		 * @since 1.0.0
		 * @return mixed[]
		 */
		public function getOptions()
		{
			$options = wbcr_cyrlitera_get_plugin_options();

			$formOptions = array();

			$formOptions[] = array(
				'type' => 'form-group',
				'items' => $options,
				//'cssClass' => 'postbox'
			);

			return apply_filters('wbcr_cyrlitera_general_form_options', $formOptions, $this);
		}
	}
