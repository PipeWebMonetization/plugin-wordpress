<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Pipe_Web_Monetization
 * @subpackage Pipe_Web_Monetization/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two hooks to
 * enqueue the admin-facing stylesheet and JavaScript.
 * As you add hooks and methods, update this description.
 *
 * @package    Pipe_Web_Monetization
 * @subpackage Pipe_Web_Monetization/admin
 * @author     Your Name <email@example.com>
 */
class Pipe_Web_Monetization_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $pipe_web_monetization    The ID of this plugin.
	 */
	private $pipe_web_monetization;

	/**
	 * The unique prefix of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_prefix    The string used to uniquely prefix technical functions of this plugin.
	 */
	private $plugin_prefix;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $pipe_web_monetization       The name of this plugin.
	 * @param      string $plugin_prefix    The unique prefix of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $pipe_web_monetization, $plugin_prefix, $version ) {

		$this->pipe_web_monetization   = $pipe_web_monetization;
		$this->plugin_prefix = $plugin_prefix;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_styles( $hook_suffix ) {

		wp_enqueue_style( $this->pipe_web_monetization, plugin_dir_url( __FILE__ ) . 'css/pipe-web-monetization-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_scripts( $hook_suffix ) {

		wp_enqueue_script( $this->pipe_web_monetization, plugin_dir_url( __FILE__ ) . 'js/pipe-web-monetization-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script($this->pipe_web_monetization, 'images_variables', array( 
            'icon_delete_url' => plugin_dir_url( dirname(__FILE__) ) . 'img/icon_delete.svg'
        ));
	}
}
?>
