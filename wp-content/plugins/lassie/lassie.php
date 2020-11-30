<?php
/**
 * @package Lassie
 * @version 2.0.0
 */
/*
Plugin Name: Lassie
Plugin URI: https://github.com/bmd-studio/lassie-wordpress-plugin
Description: This plugin connects to the API of the Lassie administration system and adds several functionalities such as member login, subscription, and listing of events, payments and transactions.
Author: BMD Studio
Version: 2.0.0
Author URI: https://bmd.studio/
*/

require plugin_dir_path(__FILE__).'includes/class.lassie.php';

function run_lassie() {
	$lassie = new Lassie();
	$lassie->run();
}
run_lassie();

?>
