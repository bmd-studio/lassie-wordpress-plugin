<?php
/**
 * @package Lassie
 * @version 0.2
 */
/*
Plugin Name: Lassie
Plugin URI: http://lassie.moeilijkedingen.nl
Description: This plugin connects to the API of the Lassie administration system and adds several functionalities such as member login, subscription, and listing of events, payments and transactions.
Author: Bureau Moeilijke Dingen
Version: 0.2
Author URI: http://www.moeilijkedingen.nl
*/

require plugin_dir_path(__FILE__).'includes/class.lassie.php';

function run_lassie() {
	$lassie = new Lassie();
	$lassie->run();
}
run_lassie();

?>
