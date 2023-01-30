<?php
/**
 * Plugin Name: Bulk AI
 * Description: Custom plugin that creates posts using AI
 * Version: 1.0.0
 * Author: Victor Crespo
 * Author URI: https://victorcrespo.net
 *
 * @package WordPress
 */

namespace bulk_ai;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! defined( __NAMESPACE__ . '\PATH' ) ) {
	define( __NAMESPACE__ . '\PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! class_exists( '\Vk_custom_libs\Settings' ) ) {
	require_once namespace\PATH . 'includes/vk_libraries/class_vk_admin_settings.php';
}

if ( ! class_exists( '\Vk_custom_libs\Templates' ) ) {
	require_once namespace\PATH . 'includes/vk_libraries/class_vk_template.php';
}

use Vk_custom_libs\Template;
use Vk_custom_libs\Settings;

/**
 * This is the main class
 */
class Bulk_AI {

	/**
	 * Has the class been instantiated?
	 *
	 * @var bool
	 */
	private static $instance = false;

	/**
	 * Add hooks here
	 */
	private function __construct() {

		register_activation_hook( __FILE__, array( $this, 'activate_plugin' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate_plugin' ) );

		add_action( 'admin_menu', array( $this, 'register_pages' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );

	}

	/**
	 * Instantiate the class
	 */
	public static function get_instance(): self {

		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Run on plugin activation
	 */
	public function activate_plugin(): void {

		add_option( 'bulk-ai-api-token', '', '', 'no' );
	}

	/**
	 * Run on plugin deactivation
	 */
	public function deactivate_plugin(): void {

		delete_option( 'bulk-ai-api-token' );
	}

	/**
	 * Menu pages registration
	 */
	public function register_pages(): void {

		add_menu_page(
			'Bulk AI',
			'Bulk AI',
			'manage_options',
			'bulk-ai-settings-page',
			array( $this, 'load_settings_dashboard' ),
			'',
			5
		);

		add_submenu_page(
			'bulk-ai-settings-page',
			'Bulk AI submenu 1',
			'Bulk ai submenu 1',
			'manage_options',
			'bulk-ai-settings-page',
			array( $this, 'load_settings_dashboard' ),
			1
		);

		add_submenu_page(
			'bulk-ai-settings-page',
			'Bulk AI submenu 2',
			'Bulk ai submenu 2',
			'manage_options',
			'bulk-ai-settings-submenu-page',
			array( $this, 'load_templates_dashboard' ),
			2
		);

	}

	/**
	 * Settings registration
	 */
	public function register_settings(): void {

		$settings_sections = array(
			'bulk-ai-api-settings-section' => array(
				'section_title' => 'API Settings',
				'settings'      => array(
					'bulk-ai-api-token' => array(
						'field_label' => 'Token:',
					),
				),
			),
		);

		$settings = new Settings();
		$settings->add_settings_sections( $settings_sections, 'bulk-ai-settings-page', 'bulk-ai-settings-group' );

	}

	/**
	 * Echo the settings form.
	 */
	public function load_settings_dashboard(): void {

		$template = new Template();

		$view = $template->load( namespace\PATH . 'templates/settings-dashboard.php' );
		//phpcs:ignore
		echo $view;

	}

	/**
	 * Echo the templates list.
	 */
	public function load_templates_dashboard(): void {

		echo 'Templates dashboard';

	}

}

$bulk_ai = Bulk_AI::get_instance();
