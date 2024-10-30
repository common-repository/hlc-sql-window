<?php

/**
 * Fired during plugin deactivation
 *
 * @link       Hlc-software.eu
 * @since      1.0.0
 *
 * @package    Hlc_Sql_Window
 * @subpackage Hlc_Sql_Window/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Hlc_Sql_Window
 * @subpackage Hlc_Sql_Window/includes
 * @author     HLC-Software, Tom Trigkas <hlcsoftware.eu@gmail.com>
 */
class Hlc_Sql_Window_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
    $postid = get_option('hlcsqldatawindow');
    if( isset($postid)){
      wp_delete_post($postid, TRUE);
    }
    delete_option('hlcsqldatawindow');

    $postid = get_option('hlcsqlwindow');
    if( isset($postid)){
      wp_delete_post($postid, TRUE);
    }
    delete_option('hlcsqlwindow');
   

	}
}
