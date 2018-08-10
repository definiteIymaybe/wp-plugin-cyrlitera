<?php
	/**
	 * Transliteration core class
	 * @author Webcraftic <wordpress.webraftic@gmail.com>
	 * @copyright (c) 19.02.2018, Webcraftic
	 * @version 1.0
	 */

	// Exit if accessed directly
	if( !defined('ABSPATH') ) {
		exit;
	}

	if( !class_exists('WCTR_Plugin') ) {

		if( !class_exists('WCTR_PluginFactory') ) {
			if( defined('LOADING_CYRLITERA_AS_ADDON') ) {
				class WCTR_PluginFactory {

				}
			} else {
				class WCTR_PluginFactory extends Wbcr_Factory000_Plugin {

				}
			}
		}
		
		class WCTR_Plugin extends WCTR_PluginFactory {

			/**
			 * @var Wbcr_Factory000_Plugin
			 */
			private static $app;

			/**
			 * @var bool
			 */
			private $as_addon;
			
			private $network_active;

			/**
			 * @param string $plugin_path
			 * @param array $data
			 * @throws Exception
			 */
			public function __construct($plugin_path, $data)
			{
				$this->network_active = ( is_multisite() && array_key_exists( WCTR_PLUGIN_BASE, (array) get_site_option( 'active_sitewide_plugins' ) ) );
				$this->as_addon = isset($data['as_addon']);

				if( $this->as_addon ) {
					$plugin_parent = isset($data['plugin_parent'])
						? $data['plugin_parent']
						: null;

					if( !($plugin_parent instanceof Wbcr_Factory000_Plugin) ) {
						throw new Exception('An invalid instance of the class was passed.');
					}

					self::$app = $plugin_parent;
				} else {
					self::$app = $this;
				}

				if( !$this->as_addon ) {
					parent::__construct($plugin_path, $data);
				}

				$this->setTextDomain();
				$this->setModules();

				$this->globalScripts();

				if( is_admin() ) {
					$this->adminScripts();
				}
			}

			/**
			 * @return Wbcr_Factory000_Plugin
			 */
			public static function app()
			{
				return self::$app;
			}

			protected function setTextDomain()
			{

				load_plugin_textdomain('cyrlitera', false, dirname(WCTR_PLUGIN_BASE) . '/languages/');
			}
			
			protected function setModules()
			{
				if( !$this->as_addon ) {
					self::app()->load(array(
						array('libs/factory/bootstrap', 'factory_bootstrap_000', 'admin'),
						array('libs/factory/forms', 'factory_forms_000', 'admin'),
						array('libs/factory/pages', 'factory_pages_000', 'admin'),
						array('libs/factory/clearfy', 'factory_clearfy_000', 'all'),
						array('libs/factory/notices', 'factory_notices_000', 'admin')
					));
				}
			}
			
			public function isNetworkActive() {
				if ( $this->network_active ) {
					return true;
				}
				return false;
			}

			protected function initActivation()
			{
				if( !$this->as_addon ) {
					include_once(WCTR_PLUGIN_DIR . '/admin/activation.php');
					self::app()->registerActivation('WCTR_Activation');
				}
			}

			private function registerPages()
			{
				if( $this->as_addon ) {
					return;
				}
				
				if ( $this->isNetworkActive() and ! is_network_admin() ) {
					return;
				}

				self::app()->registerPage('WCTR_CyrliteraPage', WCTR_PLUGIN_DIR . '/admin/pages/cyrlitera.php');
				self::app()->registerPage('WCTR_MoreFeaturesPage', WCTR_PLUGIN_DIR . '/admin/pages/more-features.php');
			}
			
			private function adminScripts()
			{
				require_once(WCTR_PLUGIN_DIR . '/admin/boot.php');
				require_once(WCTR_PLUGIN_DIR . '/admin/options.php');

				$this->initActivation();
				$this->registerPages();
			}
			
			private function globalScripts()
			{
				require_once(WCTR_PLUGIN_DIR . '/includes/classes/class.configurate-cyrlitera.php');
				new WCTR_ConfigСyrlitera(self::$app);
			}
			
			/**
			 * Откатывает изменения в урлах
			 */
			public function rollback() {
				global $wpdb;

				$posts = $wpdb->get_results("SELECT p.ID, p.post_name, m.meta_value as old_post_name FROM {$wpdb->posts} p
						LEFT JOIN {$wpdb->postmeta} m
						ON p.ID = m.post_id
						WHERE p.post_status
						IN ('publish', 'future', 'private') AND m.meta_key='wbcr_wp_old_slug' AND m.meta_value IS NOT NULL");

				foreach((array)$posts as $post) {
					if( $post->post_name != $post->old_post_name ) {
						$wpdb->update($wpdb->posts, array('post_name' => $post->old_post_name), array('ID' => $post->ID), array('%s'), array('%d'));
						delete_post_meta($post->ID, 'wbcr_wp_old_slug');
					}
				}

				$terms = $wpdb->get_results("SELECT t.term_id, t.slug, o.option_value as old_term_slug FROM {$wpdb->terms} t
						LEFT JOIN {$wpdb->options} o
						ON o.option_name=concat('wbcr_wp_term_',t.term_id, '_old_slug')
						WHERE o.option_value IS NOT NULL");

				foreach((array)$terms as $term) {
					if( $term->slug != $term->old_term_slug ) {
						$wpdb->update($wpdb->terms, array('slug' => $term->old_term_slug), array('term_id' => $term->term_id), array('%s'), array('%d'));
						delete_option('wbcr_wp_term_' . $term->term_id . '_old_slug');
					}
				}
			}
			
			/**
			 * Конвертирует урлы
			 */
			public function convert() {
				global $wpdb;

				$posts = $wpdb->get_results("SELECT ID, post_name FROM {$wpdb->posts} WHERE post_name REGEXP('[^_A-Za-z0-9\-]+') AND post_status IN ('publish', 'future', 'private')");

				foreach((array)$posts as $post) {
					$sanitized_name = WCTR_Helper::sanitizeTitle(urldecode($post->post_name));

					if( $post->post_name != $sanitized_name ) {
						add_post_meta($post->ID, 'wbcr_wp_old_slug', $post->post_name);

						$wpdb->update($wpdb->posts, array('post_name' => $sanitized_name), array('ID' => $post->ID), array('%s'), array('%d'));
					}
				}

				$terms = $wpdb->get_results("SELECT term_id, slug FROM {$wpdb->terms} WHERE slug REGEXP('[^_A-Za-z0-9\-]+') ");

				foreach((array)$terms as $term) {
					$sanitized_slug = WCTR_Helper::sanitizeTitle(urldecode($term->slug));

					if( $term->slug != $sanitized_slug ) {
						update_option('wbcr_wp_term_' . $term->term_id . '_old_slug', $term->slug, false);
						$wpdb->update($wpdb->terms, array('slug' => $sanitized_slug), array('term_id' => $term->term_id), array('%s'), array('%d'));
					}
				}

			}
			
			/**
			 * Получает список блогов для мультисайт версии
			 */
			public function getBlogs() {
				global $wpdb;
				$blogs = $wpdb->get_col( $wpdb->prepare( "SELECT blog_id FROM $wpdb->blogs WHERE site_id = %d AND public = '1' AND archived = '0' AND deleted = '0'", $wpdb->siteid ) );
				return $blogs;
			}
		}
	}
