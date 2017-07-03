$ git tag
v0.2

<?php
/**
 * Plugin Name: Nosse
 * Plugin URI: https://www.aceschools-rd.net/
 * Description: Connections and code for Nosse social work system
 * Author: mockersACE
 * Author URI: https://github.com/mockersACE/Nosse
 * Version: 0.1
 * Text Domain: Nosse
 *
 * Copyright: (c) ACE Schools MAT
 *
 * License: Apache
 * License URI: http://www.apache.org/licenses/
 *
 * @author    mockersACE
 * @copyright Copyright (c) 2017 mockersACE
 * @license   http://www.apache.org/licenses/
 *
 */

 /** this line prevents direct access to the PHP file **/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/** setup auto updates from github**/
if( ! class_exists( 'Smashing_Updater' ) ){
	include_once( plugin_dir_path( __FILE__ ) . 'updater.php' );
}
$updater = new Smashing_Updater( __FILE__ );
$updater->set_username( 'mockersACE' );
$updater->set_repository( 'Nosse' );
$updater->initialize();


?>
