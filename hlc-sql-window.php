<?php

/**
 * @link              hlc-software.eu/free-plugins/sql-window/
 * @since             1.0.0
 * @package           Hlc_Sql_Window
 *
 * @wordpress-plugin
 * Plugin Name:       HLC_sql_window
 * Plugin URI:        http://hlc-software.eu/free-plugins/sql-window/
 * Description:       This plugin provides an SQL Command page.
 * Version:           1.0.1
 * Author:            HLC-Software, Tom Trigkas <hlcsoftware.eu@gmail.com>
 * Author URI:        http://hlc-software.eu
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hlc-sql-window
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-hlc-sql-window-activator.php
 */
function activate_hlc_sql_window() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hlc-sql-window-activator.php';
	Hlc_Sql_Window_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-hlc-sql-window-deactivator.php
 */
function deactivate_hlc_sql_window() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hlc-sql-window-deactivator.php';
	Hlc_Sql_Window_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_hlc_sql_window' );
register_deactivation_hook( __FILE__, 'deactivate_hlc_sql_window' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-hlc-sql-window.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_hlc_sql_window() {

	$plugin = new Hlc_Sql_Window();
	$plugin->run();

}
run_hlc_sql_window();
