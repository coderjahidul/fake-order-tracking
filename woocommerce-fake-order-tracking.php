<?php
/**
 * Plugin Name: WooCommerce Fake Order Tracking
 * Plugin URI: https://github.com/coderjahidul/woocommerce-fake-order-tracking
 * Description: Checks DSR and suggests payment method. Saves DSR report in admin order page.
 * Version: 1.0.0
 * Author: MD: Jahidul Islam Sabuz
 * Author URI: https://github.com/coderjahidul
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: woocommerce-fake-order-tracking
 * 
 * @package WooCommerce Fake Order Tracking
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @link https://github.com/coderjahidul
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define( 'CDC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CDC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Enqueue CSS and JS only on the checkout page
add_action('wp_enqueue_scripts', function() {
    if ( is_checkout() ) {
        // Enqueue custom JS
        wp_enqueue_script(
            'cdc-checkout',
            plugin_dir_url(__FILE__) . 'assets/js/checkout.js', // fixed extra slash
            ['jquery'],
            '1.0',
            true
        );

        // Localize script for AJAX
        wp_localize_script('cdc-checkout', 'cdc_ajax_object', [
            'ajax_url' => admin_url('admin-ajax.php'),
        ]);
    }
});

add_action('admin_enqueue_scripts', function($hook) {
    // Optionally limit to WooCommerce order page
    // if ( 'post.php' === $hook && isset($_GET['post']) && get_post_type($_GET['post']) === 'shop_order' ) {
        wp_enqueue_style(
            'cdc-admin-style',
            plugin_dir_url(__FILE__) . 'assets/css/admin-style.css',
            [],
            '1.0'
        );
    // }
});


require_once CDC_PLUGIN_DIR . '/includes/class-cdc-loader.php';


function cdc_run_plugin() {
    $plugin = new CDC_Loader();
    $plugin->run();
}

if ( class_exists( 'CDC_Loader' ) ) {
    cdc_run_plugin();
}

