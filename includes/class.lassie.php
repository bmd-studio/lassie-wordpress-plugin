<?php

class Lassie {

  protected $plugin_name;
  protected $version;
  protected $loader;

  private static $model_api;
  private static $person_api;
  private static $person_auth_api;

  public function __construct() {
		$this->plugin_name = 'Lassie';
		$this->version = '0.1';
		$this->load_dependencies();
		$this->define_admin_hooks();
    $this->define_auth_hooks();
	}

  // Load the required dependencies for this plugin
  private function load_dependencies() {
    require_once plugin_dir_path(dirname(__FILE__)).'includes/class.lassie.loader.php';
    require_once plugin_dir_path(dirname(__FILE__)).'includes/class.lassie.api.php';
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


  /**
  * Get the result of a model-api request.
  * $model      The name of the model
  * $method     The method you want to execute
  * $arguments  Array of arguments you want to pass to the method
  */
  public static function getModel($model, $method, $arguments = null) {
    $result_arr = self::getModelApi()->get('model', array(
      'name' => $model,
      'method' => $method,
      'arguments' => $arguments,
      'format' => 'json',
    ));

    return $result_arr;
  }

  /**
  * Get the personal API-keys based on the login credentials provided by the
  * user. This call is used by the lassie_authenticate hook in the default
  * Wordpress-login.
  * $username     The loginname provided by the user
  * $password     The password provided by the user
  */
  public static function getPersonKeys($username, $password) {
    $result_arr = self::getPersonAuthApi()->post('person_create_api', array(
      'username' => $username,
      'password' => $password
    ));

    return $result_arr;
  }

  /**
  * Get the information of the logged in user from the Lassie instance through
  * the API-keys that are retrieved during login.
  */
  public static function getPerson() {
    $result_arr = self::getPersonApi()->get('person_information');

    return $result_arr;
  }

  /**
  * Update the information of the logged in user from the Lassie instance through
  * the API-keys that are retrieved during login.
  */
  public static function updatePerson($updateFields) {
    $result_arr = self::getPersonApi()->post('person_update', $updateFields);

    return $result_arr;
  }

  /**
  * Get a limited amount of payment transactions for the logged in user through
  * the API-keys that are retrieved during login.
  * $selection    Determines the associated payments you want to fetch:
  *               'first'     Payments from first account balance
  *               'second'    Payments from second account balance
  *               'other'     Remaining payments such as events and memberships
  *               'all'       All payments associated with this person
  */
  public static function getTransactions($selection = "all") {
    $result_arr = self::getPersonApi()->get('person_payments', array(
      'selection' => $selection
    ));

    return $result_arr;
  }


  // Create the API-hook for model-calls
  public static function getModelApi() {
    if (empty(self::$model_api)) {
      self::$model_api = new Lassie_Api(array(
        'host' => get_option('lassie_url'),
        'api_key' => get_option('lassie_api_model_key'),
        'api_secret' => get_option('lassie_api_model_secret'),
      ));
    }

    return self::$model_api;
  }

  // Create the API-hook for person-calls
  public static function getPersonApi() {
    if (empty(self::$person_api)) {
      $user = wp_get_current_user();
      self::$person_api = new Lassie_Api(array(
        'host' => get_option('lassie_url'),
        'api_key' => get_user_meta($user->ID, 'api-key', true),
        'api_secret' => get_user_meta($user->ID, 'api-secret', true),
      ));
    }

    return self::$person_api;
  }

  // Create the API-hook for person authentication-calls
  public static function getPersonAuthApi() {
    if (empty(self::$person_auth_api)) {
      self::$person_auth_api = new Lassie_Api(array(
        'host' => get_option('lassie_url'),
        'api_key' => get_option('lassie_api_person_auth_key'),
        'api_secret' => get_option('lassie_api_person_auth_secret'),
      ));
    }

    return self::$person_auth_api;
  }

}

?>
