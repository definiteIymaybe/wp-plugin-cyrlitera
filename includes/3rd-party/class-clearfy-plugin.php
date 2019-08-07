<?php
/**
 * Local Google Analytic
 *
 * @author        Alexander Kovalev <alex.kovalevv@gmail.com>
 * @copyright (c) 2018 Webraftic Ltd
 * @version       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WCTR_Plugin {

	/**
	 * @see self::app()
	 * @var WCL_Plugin
	 */
	private static $app;

	/**
	 * Конструктор
	 *
	 * Применяет конструктор родительского класса и записывает экземпляр текущего класса в свойство $app.
	 * Подробнее о свойстве $app см. self::app()
	 *
	 * @param string $plugin_path
	 * @param array  $data
	 *
	 * @throws Exception
	 */
	public function __construct() {
		if ( ! class_exists( 'WCL_Plugin' ) ) {
			throw new Exception( 'Plugin Clearfy is not installed!' );
		}

		self::$app = WCL_Plugin::app();

		$this->global_scripts();

		if ( is_admin() ) {
			$this->admin_scripts();
		}
	}

	/**
	 * @author Alexander Kovalev <alex.kovalevv@gmail.com>
	 * @since  1.0.0
	 * @throws \Exception
	 */
	private function register_pages() {
		self::app()->registerPage( 'WCTR_CyrliteraPage', WCTR_PLUGIN_DIR . '/admin/pages/class-page-cyrlitera.php' );
	}

	/**
	 * Регистрирует рекламные объявления от студии Webcraftic
	 *
	 * @author Alexander Kovalev <alex.kovalevv@gmail.com>
	 * @since  1.2.0
	 */
	private function register_adverts_blocks() {
		global $wdan_adverts;

		$wdan_adverts = new WBCR\Factory_Adverts_000\Base( __FILE__, array_merge( $this->plugin_data, [
			'dashboard_widget' => true, // show dashboard widget (default: false)
			'right_sidebar'    => true, // show adverts sidebar (default: false)
			'notice'           => false, // show notice message (default: false)
		] ) );
	}

	/**
	 * @author Alexander Kovalev <alex.kovalevv@gmail.com>
	 * @throws \Exception
	 */
	private function admin_scripts() {
		require_once( WCTR_PLUGIN_DIR . '/admin/boot.php' );
		require_once( WCTR_PLUGIN_DIR . '/admin/options.php' );

		$this->register_pages();
		$this->register_adverts_blocks();
	}

	/**
	 * @author Alexander Kovalev <alex.kovalevv@gmail.com>
	 */
	private function global_scripts() {
		require_once( WCTR_PLUGIN_DIR . '/includes/classes/class-configurate-cyrlitera.php' );
		new WCTR_ConfigurateCyrlitera( self::$app );
	}
}