<?php
/**
 * Plugin Name:     Child Free Plugin
 * Description:     Custom functionality for Child Free By Choice
 * Author:          Waleed Zaman
 * Text Domain:     childfree
 * Version:         1.0.0
 *
 * @package         Childfree
 */

define('WZ_CHILDFREE_VERSION', '1.0.0');
define('WZ_CHILDFREE_DIR', plugin_dir_path(__FILE__));
define('WZ_CHILDFREE_URL', plugin_dir_url(__FILE__));

require 'vendor/autoload.php';

// Run the plugin only when WooCommerce is loaded.
add_action('woocommerce_loaded', 'childfree');
function childfree()
{
    return \WZ\ChildFree\App::get_instance();
}

require_once WZ_CHILDFREE_DIR . 'src/Actions/Candidate/customCandidateHandler.php';
require_once WZ_CHILDFREE_DIR . 'src/Actions/Physicians/customPhysicianHandler.php';
require_once WZ_CHILDFREE_DIR . 'src/Actions/Candidate/resend_verification.php';