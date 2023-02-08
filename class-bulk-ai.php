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

if ( ! defined( __NAMESPACE__ . '\URL' ) ) {
	define( __NAMESPACE__ . '\URL', plugin_dir_url( __FILE__ ) );
}

if ( ! class_exists( '\Vk_custom_libs\Settings' ) ) {
	require_once namespace\PATH . 'includes/vk_libraries/class_vk_admin_settings.php';
}

if ( ! class_exists( '\Vk_custom_libs\Templates' ) ) {
	require_once namespace\PATH . 'includes/vk_libraries/class_vk_template.php';
}

require_once namespace\PATH . 'includes/class-bulk-ai-template.php';
require_once namespace\PATH . 'includes/class-bulk-ai-list-table.php';

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

		$bulk_ai_template = new Bulk_AI_Template();

		register_activation_hook( __FILE__, array( $this, 'activate_plugin' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate_plugin' ) );

		add_action( 'admin_menu', array( $this, 'register_pages' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_assets' ) );

		add_action( 'init', array( $this, 'register_bulkai_template_post_type' ) );

		add_action( 'admin_post_bulk_ai_create_template', array( $bulk_ai_template, 'create_template' ) );
		add_action( 'admin_post_bulk_ai_update_template', array( $bulk_ai_template, 'update_template' ) );
		add_action( 'admin_post_bulk_ai_delete_template', array( $bulk_ai_template, 'delete_template' ) );

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
			'bulk-ai-page',
			array( $this, 'load_templates_dashboard' ),
			'',
			5
		);

		add_submenu_page(
			'bulk-ai-page',
			'Templates',
			'Templates',
			'manage_options',
			'bulk-ai-page',
			array( $this, 'load_templates_dashboard' ),
			1
		);

		add_submenu_page(
			'bulk-ai-page',
			'Settings',
			'Settings',
			'manage_options',
			'bulk-ai-settings-page',
			array( $this, 'load_settings_dashboard' ),
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
	 * Echo the templates views.
	 */
	public function load_templates_dashboard(): void {

		$template = new Template();
		$request  = $this->get_request();

		switch ( $request['type'] ) {

			case 'template-list':
				$list_table = new Bulk_AI_List_Table();
				$view       = $template->load( namespace\PATH . 'templates/templates-dashboard.php', array( 'list_table' => $list_table ) );
				break;

			case 'new-template-form':
				$view = $template->load( namespace\PATH . 'templates/new-template-form.php' );
				break;

			case 'edit-template-form':
				$template_data             = get_post( $request['template-id'], 'ARRAY_A' );
				$sections                  = get_post_meta( $template_data['ID'], 'sections' );
				$template_data['sections'] = json_decode( $sections[0], true );

				$view = $template->load( namespace\PATH . 'templates/edit-template-form.php', array( 'template_data' => $template_data ) );
				break;

			default:
				$view = 'Error, contact tech support';
				break;
		}

		//phpcs:ignore
		echo $view;

	}

	/**
	 * Add the Bulk AI Template post type.
	 */
	public function register_bulkai_template_post_type(): void {

		$args = array(
			'public' => true,
		);

		register_post_type( 'bulk-ai-template', $args );

	}

	/**
	 * Enqueue style sheets and scripts.
	 *
	 * @param strin $page the page that is being loaded.
	 */
	public function add_assets( $page ): void {

		if ( 'toplevel_page_bulk-ai-page' !== $page ) {

			return;

		}

		wp_enqueue_style(
			'bulk-ai-styles',
			namespace\URL . 'assets/css/styles.css',
			array(),
			'1.0.0',
			'all'
		);

		wp_enqueue_script(
			'bulk-ai-script',
			namespace\URL . 'assets/js/script.js',
			array( 'jquery' ),
			'1.0.0',
			true
		);

	}

	/**
	 * Return the request type and some extra data if needed.
	 */
	private function get_request(): array {

		$request         = array();
		$request['type'] = '';

		if ( ! isset( $_GET['view'] ) ) {

			$request['type'] = 'template-list';
			return $request;

		}

		if ( 'new-template-form' === $_GET['view'] ) {

			if ( empty( $_GET['bulk-ai-nonce'] ) ) {

				return $request;

			}

			$nonce = sanitize_text_field( wp_unslash( $_GET['bulk-ai-nonce'] ) );

			if ( wp_verify_nonce( $nonce, 'bulk-ai-show-new-template-form' ) ) {

				$request['type'] = 'new-template-form';
				return $request;

			}

			return $request;
		}

		if ( 'edit-template-form' === $_GET['view'] ) {

			if ( empty( $_GET['template-id'] ) || ! is_numeric( $_GET['template-id'] ) ) {

				return $request;

			}

			$template_id = sanitize_text_field( wp_unslash( $_GET['template-id'] ) );

			if ( empty( $_GET['bulk-ai-nonce'] ) ) {

				return $request;

			}

			$nonce = sanitize_text_field( wp_unslash( $_GET['bulk-ai-nonce'] ) );

			if ( wp_verify_nonce( $nonce, 'bulk-ai-show-edit-template-form' . $template_id ) ) {

				$request['type']        = 'edit-template-form';
				$request['template-id'] = $template_id;
				return $request;

			}

			return $request;
		}

		return $request;

	}

}

$bulk_ai = Bulk_AI::get_instance();
