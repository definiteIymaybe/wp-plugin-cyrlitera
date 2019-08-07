<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Transliteration core class
 *
 * @author        Alex Kovalev <alex.kovalevv@gmail.com>
 * @copyright (c) 19.02.2018, Webcraftic
 */
class WCTR_Plugin extends Wbcr_Factory000_Plugin {

	/**
	 * @see self::app()
	 * @var Wbcr_Factory000_Plugin
	 */
	private static $app;

	/**
	 * @since  1.1.0
	 * @var array
	 */
	private $plugin_data;

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
	public function __construct( $plugin_path, $data ) {
		parent::__construct( $plugin_path, $data );

		self::$app         = $this;
		$this->plugin_data = $data;

		$this->global_scripts();

		if ( is_admin() ) {
			$this->admin_scripts();
		}
	}

	/**
	 * Статический метод для быстрого доступа к интерфейсу плагина.
	 *
	 * Позволяет разработчику глобально получить доступ к экземпляру класса плагина в любом месте
	 * плагина, но при этом разработчик не может вносить изменения в основной класс плагина.
	 *
	 * Используется для получения настроек плагина, информации о плагине, для доступа к вспомогательным
	 * классам.
	 *
	 * @return \Wbcr_Factory000_Plugin|\WCTR_Plugin
	 */
	public static function app() {
		return self::$app;
	}

	/**
	 * @author Alexander Kovalev <alex.kovalevv@gmail.com>
	 * @since  1.0.0
	 */
	protected function init_activation() {
		if ( ! $this->as_addon ) {
			include_once( WCTR_PLUGIN_DIR . '/admin/activation.php' );
			self::app()->registerActivation( 'WCTR_Activation' );
		}
	}

	/**
	 * @author Alexander Kovalev <alex.kovalevv@gmail.com>
	 * @since  1.0.0
	 * @throws \Exception
	 */
	private function register_pages() {
		self::app()->registerPage( 'WCTR_CyrliteraPage', WCTR_PLUGIN_DIR . '/admin/pages/cyrlitera.php' );
		self::app()->registerPage( 'WCTR_MoreFeaturesPage', WCTR_PLUGIN_DIR . '/admin/pages/more-features.php' );
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

		$this->init_activation();
		$this->register_pages();
		$this->register_adverts_blocks();
	}

	private function global_scripts() {
		require_once( WCTR_PLUGIN_DIR . '/includes/classes/class-configurate-cyrlitera.php' );
		new WCTR_ConfigurateCyrlitera( self::$app );
	}
}

