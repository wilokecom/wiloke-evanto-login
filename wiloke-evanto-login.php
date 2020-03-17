<?php
/**
 * Plugin Name: Wiloke Evanto Login
 * Description: Offer Evanto Login
 * Plugin URI: https://wiloke.com
 * Author: Wiloke
 * Author URI: https://wiloke.com
 * Version: 1.0
 */

require_once plugin_dir_path(__FILE__)."vendor/autoload.php";
define('WILOKE_EVANTO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WILOKE_EVANTO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WILOKE_EVANTO_LOGIN_VERSION', '1.0');

use WilokeEvantoLogin\Controllers\EvantoAuthentication;
use WilokeEvantoLogin\Controllers\AdminSettings;

if (is_admin()) {
    new AdminSettings;
}

new EvantoAuthentication;
