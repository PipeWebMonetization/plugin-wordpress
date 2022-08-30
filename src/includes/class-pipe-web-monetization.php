<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Pipe_Web_Monetization
 * @subpackage Pipe_Web_Monetization/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Pipe_Web_Monetization
 * @subpackage Pipe_Web_Monetization/includes
 * @author     Your Name <email@example.com>
 */
class Pipe_Web_Monetization {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Pipe_Web_Monetization_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $pipe_web_monetization    The string used to uniquely identify this plugin.
	 */
	protected $pipe_web_monetization;

	/**
	 * The unique prefix of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_prefix    The string used to uniquely prefix technical functions of this plugin.
	 */
	protected $plugin_prefix;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'PIPE_WEB_MONETIZATION_VERSION' ) ) {

			$this->version = PIPE_WEB_MONETIZATION_VERSION;

		} else {

			$this->version = '1.0.0';

		}

		$this->pipe_web_monetization = 'pipe-web-monetization';
		$this->plugin_prefix = 'pfx_';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
     
		add_action('admin_menu', array($this, 'create_main_menu'));
	}

	public function create_main_menu() {
        add_menu_page( 
			'Pipe Web Monetization', 
			'Pipe Web Monetization', 
			'manage_options', 
			'pipe-web-monetization-admin', 
			array($this, 'pointers_table'), 
			plugin_dir_url( dirname(__FILE__) ) . 'img/logo_menu.png');
    }

	public function pointers_table() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		  }
		
		  $default_tab = null;
		  $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
		
		  ?>
		  <div class="wrap">
			
		 	<div class="div-main-title"><span><?php echo esc_html( get_admin_page_title() ); ?></span></div>

			<nav class="nav-tab-wrapper">
				<a href="?page=pipe-web-monetization-admin" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">Settings</a>
				<a href="?page=pipe-web-monetization-admin&tab=pointers" class="nav-tab <?php if($tab==='pointers'):?>nav-tab-active<?php endif; ?>">Payment Pointers</a>
			</nav>
		
			<div class="tab-content">
				<?php 
					switch($tab) :
						case 'pointers':
							include_once(plugin_dir_path(  dirname(__FILE__)  ) . 'admin/index.php');
							load_pointers_table();
							break;
						default:
							include_once(plugin_dir_path(  dirname(__FILE__)  ) . 'admin/index.php');
							load_config_tab();
							break;
					endswitch; 
				?>
			</div>
		  </div>
		  <?php
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Pipe_Web_Monetization_Loader. Orchestrates the hooks of the plugin.
	 * - Pipe_Web_Monetization_i18n. Defines internationalization functionality.
	 * - Pipe_Web_Monetization_Admin. Defines all hooks for the admin area.
	 * - Pipe_Web_Monetization_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pipe-web-monetization-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pipe-web-monetization-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-pipe-web-monetization-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-pipe-web-monetization-public.php';

		$this->loader = new Pipe_Web_Monetization_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Pipe_Web_Monetization_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Pipe_Web_Monetization_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Pipe_Web_Monetization_Admin( $this->get_pipe_web_monetization(), $this->get_plugin_prefix(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Pipe_Web_Monetization_Public( $this->get_pipe_web_monetization(), $this->get_plugin_prefix(), $this->get_version() );
		$this->loader->add_action( 'get_payment_pointers', $plugin_public, 'get_payment_pointers' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	
		// Shortcode name must be the same as in shortcode_atts() third parameter.
		$this->loader->add_shortcode( $this->get_plugin_prefix() . 'shortcode', $plugin_public, 'pipe_web_monetization_shortcode_func' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_pipe_web_monetization() {
		return $this->pipe_web_monetization;
	}

	/**
	 * The unique prefix of the plugin used to uniquely prefix technical functions.
	 *
	 * @since     1.0.0
	 * @return    string    The prefix of the plugin.
	 */
	public function get_plugin_prefix() {
		return $this->plugin_prefix;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Pipe_Web_Monetization_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
