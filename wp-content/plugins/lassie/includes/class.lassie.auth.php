<?php

class Lassie_Auth {

  private $plugin_name;
  private $version;

  // Initialize the class and set its properties
  public function __construct($plugin_name, $version) {
      $this->plugin_name = $plugin_name;
      $this->version = $version;
  }

  public function lassie_authenticate($user, $username, $password) {
    // Guard: Check if the username or password is empty, or continue.
    if ($username == '' || $password == '') return;

    // Check the username and password, and return if there is no result
    $result = FALSE;

    try {
      $result = Lassie::getPersonKeys($username, $password);
    } catch (Exception $e) {
      // empty
    }

    if (isset($result->error) || $result == FALSE) return;

    // Get the WP-user with the retrieved targetID
    $user = new WP_User('', $result->target_id);

    // Create a new WP-user if it does not yet exist
    if($user->ID == 0) {
      $userdata = array(
          'user_login' => $result->target_id,
          'user_email' => $result->responsible_email,
          'display_name' => $result->responsible_name,
          'user_pass'   =>  NULL
      );

      $wp_user = wp_insert_user($userdata);
      $user = new WP_User($wp_user);
    }

    update_user_meta($user->ID, 'api-key', $result->api_key);
    update_user_meta($user->ID, 'api-secret', $result->api_secret);

    // Return the selected user to log in correctly
    return $user;
  }


}
?>
