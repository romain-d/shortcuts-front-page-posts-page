<?php
/**
 * Plugin Name: Shortcuts for Front Page and Posts Page
 * Version:     1.0.1
 * Plugin URI:  https://romaindorr.fr
 * Description: Very simple WordPress plugin to add in admin page screen 2 shortcuts for Front Page and Posts Page
 * Author:      Romain DORR
 * Author URI:  https://romaindorr.fr
 * Domain Path: languages
 * Text Domain: shortcuts-front-page-posts-page
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

defined( 'ABSPATH' ) or die( 'No direct load !' );

load_plugin_textdomain( 'shortcuts-front-page-posts-page', false, basename( dirname( __FILE__ ) ) . '/languages' );

// Constants
define( 'SHORTCUTS_FRONT_PAGE_POSTS_PAGE_DIR', plugin_dir_path( __FILE__ ) );
define( 'SHORTCUTS_FRONT_PAGE_POSTS_PAGE_URL', plugin_dir_url( __FILE__ ) );
define( 'SHORTCUTS_FRONT_PAGE_POSTS_PAGE_VERSION', '1.0.1' );

add_action( 'plugins_loaded', 'shortcuts_front_page_posts_page_init' );
function shortcuts_front_page_posts_page_init() {
	// i18n
	load_plugin_textdomain( 'shortcuts-front-page-posts-page', false, basename( SHORTCUTS_FRONT_PAGE_POSTS_PAGE_DIR ) . '/languages' );

	$instance = Shortcuts_Front_Page_Posts_Page::getInstance();
	$instance->hooks();
}

class Shortcuts_Front_Page_Posts_Page {
	/**
	 * @var self
	 */
	protected static $instance;

	protected function __construct() {
	}

	/**
	 * @return self
	 */
	final public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new static;
		}
		return self::$instance;
	}

	/**
	 * Hooks
	 *
	 * @return void
	 */
	public function hooks() {
		add_filter( 'views_edit-page', array( __CLASS__, 'add_shortcuts' ) );
	}

	/**
	 * Display Shortcuts
	 *
	 * @return array
	 */
	public static function add_shortcuts( $views ) {
		// Check if option exist
		$show_on_front = get_option( 'show_on_front' );
		if ( empty( $show_on_front ) || 'page' != $show_on_front ) {
			return $views;
		}

		return array_merge( $views, self::get_edit_links() );
	}

	/**
	 * Get Edit Links for Front page and Posts Page
	 *
	 * @return array
	 */
	public static function get_edit_links() {
		$edit_links = array();

		$page_on_front = get_option( 'page_on_front' );
		$page_for_posts = get_option( 'page_for_posts' );

		if ( empty( $page_on_front ) && empty( $page_for_posts ) ) {
			return $edit_links;
		}

		if ( ! empty( $page_on_front ) ) {
			$edit_links['front-page'] = '<a href="' . get_edit_post_link( $page_on_front ) . '">' . esc_html__( 'Front Page', 'shortcuts-front-page-posts-page' ) . '</a>';
		}

		if ( ! empty( $page_for_posts ) ) {
			$edit_links['posts-page'] = '<a href="' . get_edit_post_link( $page_for_posts ) . '">' . esc_html__( 'Posts Page', 'shortcuts-front-page-posts-page' ) . '</a>';
		}

		return apply_filters( 'shortcuts-front-page-posts-page-edit-links', $edit_links );
	}
}
