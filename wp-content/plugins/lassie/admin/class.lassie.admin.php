<?php

class Lassie_Admin {

  private $plugin_name; // The string used to uniquely identify this plugin
  private $version;     // The current version of this plugin

  // Initialize the class and set its properties
  public function __construct($plugin_name, $version) {
      $this->plugin_name = $plugin_name;
      $this->version = $version;
  }

  // Show the Lassie menu item in the admin menu
  public function show_menu_item() {
    add_menu_page(
      'Lassie',
      'Lassie',
      'manage_options',
      'lassie',
      array($this, 'show_admin_page'),
      'dashicons-hammer',
      59
    );
  }

  // Output the admin page view
  public static function show_admin_page() {
    include(plugin_dir_path(dirname(__FILE__)).'admin/views/index.admin.lassie.php');
  }

  public function show_admin_page_fields()
  {
  	add_settings_section("lassie_host", "API settings", null, "lassie");

    add_settings_field("lassie_url", "Lassie Host URL", array($this, "display_section_field"), "lassie", "lassie_host", array("lassie_url"));
    add_settings_field("lassie_api_key", "Model API-Key", array($this, "display_section_field"), "lassie", "lassie_host", array("lassie_api_key"));
    add_settings_field("lassie_api_secret", "Model API-Secret", array($this, "display_section_field"), "lassie", "lassie_host", array("lassie_api_secret"));

    register_setting("lassie_host", "lassie_url");
    register_setting("lassie_host", "lassie_api_key");
    register_setting("lassie_host", "lassie_api_secret");
  }

  public static function display_section_field($args) {
    echo '<input type="text" name="'.$args[0].'" id="'.$args[0].'" value="'.get_option($args[0]).'" />';
  }
}
?>
