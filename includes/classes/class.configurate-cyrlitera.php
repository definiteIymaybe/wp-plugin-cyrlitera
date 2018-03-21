<?php
	/**
	 * This class configures cyrlitera
	 * @author Webcraftic <wordpress.webraftic@gmail.com>
	 * @copyright (c) 2017 Webraftic Ltd
	 * @version 1.0
	 */

	// Exit if accessed directly
	if( !defined('ABSPATH') ) {
		exit;
	}

	class WCTR_ConfigСyrlitera extends Wbcr_FactoryClearfy000_Configurate {

		public function registerActionsAndFilters()
		{
			if( is_admin() || (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) ) {
				if( $this->getOption('use_transliteration') ) {
					if( !$this->getOption('use_force_transliteration') ) {
						add_filter('sanitize_title', 'WCTR_Helper::sanitizeTitle', 0);
					} else {
						add_filter('sanitize_title', array($this, 'forceSanitizeTitle'), 99, 2);
					}
				}
				if( $this->getOption('use_transliteration_filename') ) {
					add_filter('sanitize_file_name', array($this, 'sanitizeFileName'));
				}
			}

			if( !is_admin() ) {
				add_action('wp', array($this, 'redirectFromOldUrls'), $this->wpForoIsActivated()
					? 11
					: 10);
			}
		}

		/**
		 * @param string $title обработанный заголовок
		 * @param string $raw_title не обработанный заголовок
		 * @return string
		 */
		public function forceSanitizeTitle($title, $raw_title)
		{
			$title = WCTR_Helper::sanitizeTitle($raw_title);
			$force_transliterate = sanitize_title_with_dashes($title);

			return $force_transliterate;
		}

		/**
		 * @param string $title
		 * @return string
		 */
		public function sanitizeFileName($title)
		{
			$origin_title = $title;

			$title = WCTR_Helper::transliterate($title);

			if( $this->getOption('filename_to_lowercase') ) {
				$title = strtolower($title);
			}

			return apply_filters('wbcr_cyrlitera_sanitize_filename', $title, $origin_title);
		}

		/**
		 * @return bool
		 */
		protected function wpForoIsActivated()
		{
			$activeplugins = get_option('active_plugins');
			if( gettype($activeplugins) != 'array' ) {
				$activeplugins = array();
			}

			return in_array("wpforo/wpforo.php", $activeplugins);
		}

		/**
		 * Перенаправление со старых url, которые были уже преобразованы
		 */
		public function redirectFromOldUrls()
		{
			if( !WbcrFactoryClearfy000_Helpers::isPermalink() ) {
				return;
			}
			$is404 = is_404();

			if( $this->wpForoIsActivated() ) {
				global $wpforo;
				if( $is404 || $wpforo->current_object['is_404'] ) {
					$is404 = true;
				}
			}

			if( $is404 ) {
				if( $this->getOption('redirect_from_old_urls') ) {
					$current_url = urldecode($_SERVER['REQUEST_URI']);
					$new_url = WCTR_Helper::transliterate($current_url, true);

					if( $current_url != $new_url ) {
						wp_redirect($new_url, 301);
					}
				}
			}
		}
	}