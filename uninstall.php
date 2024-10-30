<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       Hlc-software.eu
 * @since      1.0.0
 *
 * @package    Hlc_Sql_Window
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

function delTree($dir) { 
  if(!isset($dir)){
    return;
  }
  $files = array_diff(scandir($dir), array('.','..')); 
  foreach ($files as $file) { 
    (is_dir("{$dir}/{$file}")) ? delTree("{$dir}/{$file}") : unlink("{$dir}/{$file}"); 
  } 
  //return rmdir($dir);
  return; 
} 

// Delete Queries table
if(! isset($pwdb)){
  global $wpdb; 
}
$wpdb->query( 'drop table hlc_Queries');   

$dir = ABSPATH . 'hlc_export';
if (file_exists($dir)) {
  //delTree($dir);
}


