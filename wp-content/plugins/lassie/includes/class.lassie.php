<?php

class Lassie {

  protected $plugin_name;
  protected $version;
  protected $loader;

  private static $lassie_api;
  private static $person_api;

  public function __construct() {
		$this->plugin_name = 'Lassie';
		$this->version = '2.0.0';
		$this->load_dependencies();
		$this->define_admin_hooks();
    $this->define_auth_hooks();
	}

  // Load the required dependencies for this plugin
  private function load_dependencies() {
    require_once plugin_dir_path(dirname(__FILE__)).'includes/class.lassie.loader.php';
    require_once plugin_dir_path(dirname(__FILE__)).'includes/lassie-api/Lassie.php';
    require_once plugin_dir_path(dirname(__FILE__)).'includes/class.lassie.auth.php';
    require_once plugin_dir_path(dirname(__FILE__)).'admin/class.lassie.admin.php';
    $this->loader = new Lassie_Loader();
  }

  // Register all hooks related to the admin area functionality of the plugin
  private function define_admin_hooks() {
		$plugin_admin = new Lassie_Admin($this->get_plugin_name(), $this->get_version());
    $this->loader->add_action('admin_menu', $plugin_admin, 'show_menu_item');
    $this->loader->add_action('admin_init', $plugin_admin, 'show_admin_page_fields');
	}

  private function define_auth_hooks() {
		$lassie_auth = new Lassie_Auth($this->get_plugin_name(), $this->get_version());
    $this->loader->add_filter('authenticate', $lassie_auth, 'lassie_authenticate', 10, 3);
	}

  // Run the loader to execute all of the hooks with Wordpress
  public function run() {
		$this->loader->run();
	}

  // Get the plugin name
  public function get_plugin_name() {
		return $this->plugin_name;
	}

  // Get the hooks- and filters-loader
  public function get_loader() {
		return $this->loader;
	}

  // Get the current version
  public function get_version() {
		return $this->version;
	}

  // Create the API-hook for shared calls
  public static function getLassieApi() {
    if (empty(self::$lassie_api)) {
      self::$lassie_api = new Lassie\Instance(
        get_option('lassie_url'),
        get_option('lassie_api_key'),
        get_option('lassie_api_secret'),
      );
    }

    return self::$lassie_api;
  }

  public static function getPersonKeys($username, $password) {
    $lassieInstance = self::getLassieApi();
    $response = $lassieInstance->performRequest('POST', 'auth/login', [
      'username' => $username,
      'password' => $password
    ]);

    return $response;
  }

  // Create the API-hook for person-calls
  public static function getPersonApi() {
    if (empty(self::$person_api)) {
      $user = wp_get_current_user();
      self::$person_api = new Lassie\Instance(
        get_option('lassie_url'),
        get_user_meta($user->ID, 'api-key', true),
        get_user_meta($user->ID, 'api-secret', true),
      );
    }

    return self::$person_api;
  }
}

?>
