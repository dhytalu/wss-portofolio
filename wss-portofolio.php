<?php
/**
 * The plugin WSS Portofolio
 *
 * @link              github.com/adityathok/wss-portofolio
 * @since             1.0.0
 * @package           wss-portofolio
 *
 * @wordpress-plugin
 * Plugin Name:       WSS Portofolio
 * Plugin URI:        github.com/adityathok/wss-portofolio
 * Description:       Dapatkan daftar portofolio dari websweetstudio
 * Version:           1.0.0
 * Author:            Adityahok
 * Author URI:        github.com/adityathok
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       api-velocity
 * Domain Path:       /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if (!defined('WSS_PORTOFOLIO_VERSION')) define('WSS_PORTOFOLIO_VERSION', '0.0.1'); 
if (!defined('WSS_PORTOFOLIO_PLUGIN_DIR')) define('WSS_PORTOFOLIO_PLUGIN_DIR', plugin_dir_path(__FILE__));
if (!defined('WSS_PORTOFOLIO_PLUGIN_URL')) define('WSS_PORTOFOLIO_PLUGIN_URL', plugin_dir_url(__FILE__)); 

require plugin_dir_path(__FILE__) . 'inc/class-wss-portofolio-post.php';
require plugin_dir_path(__FILE__) . 'inc/class-wss-page-import.php';
require plugin_dir_path(__FILE__) . 'inc/class-wss-page-settings.php';
require plugin_dir_path(__FILE__) . 'inc/class-wss-ajax-import.php';