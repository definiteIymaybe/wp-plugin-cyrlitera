<?php
	/**
	 * This class configures hide admin notices
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
				if( $this->getOption('use_transliterations') ) {
					add_filter('sanitize_title', 'WCTR_Helper::sanitizeTitle', 9);
				}
				if( $this->getOption('use_transliterations_filename') ) {
					add_filter('sanitize_file_name', 'WCTR_Helper::sanitizeTitle');
				}
			}
		}
	}