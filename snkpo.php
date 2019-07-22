<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://ridwan-arifandi.com
 * @since             1.0.0
 * @package           Snkpo
 *
 * @wordpress-plugin
 * Plugin Name:       Sankosha - Product Order
 * Plugin URI:        https://ridwan-arifandi.com
 * Description:       Create custom product post type, with order function for sankosha.
 * Version:           1.0.0
 * Author:            Ridwan Arifandi
 * Author URI:        https://ridwan-arifandi.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       snkpo
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SNKPO_VERSION', 	'1.0.0' );
define( 'SNKPO_PATH', 		plugin_dir_path(__FILE__) );
define( 'SNKPO_URL', 		plugin_dir_url(__FILE__) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-snkpo-activator.php
 */
function activate_snkpo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-snkpo-activator.php';
	Snkpo_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-snkpo-deactivator.php
 */
function deactivate_snkpo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-snkpo-deactivator.php';
	Snkpo_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_snkpo' );
register_deactivation_hook( __FILE__, 'deactivate_snkpo' );

require_once( 'vendor/autoload.php' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-snkpo.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_snkpo() {

	$plugin = new Snkpo();
	$plugin->run();

}

if(!function_exists('__debug')) :
    function __debug()
    {
		if(
			isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) &&
			!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
			strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') :

			$bt     = debug_backtrace();
			$caller = array_shift($bt); ?>
			<pre class='debug'><?php
			print_r([
				"file"  => $caller["file"],
				"line"  => $caller["line"],
				"args"  => func_get_args()
			]); ?>
			</pre>
			<?php	

		else:
			do_action('qm/debug', func_get_args());
		endif;
    }
endif;
run_snkpo();
