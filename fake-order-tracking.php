<?php
/**
 * Plugin Name: Fake Order Tracking
 * Plugin URI: https://github.com/coderjahidul/fake-order-tracking
 * Description: Checks DSR and suggests payment method. Saves DSR report in admin order page.
 * Version: 1.0.0
 * Author: MD: Jahidul Islam Sabuz
 * Author URI: https://github.com/coderjahidul
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: fake-order-tracking
 * 
 * @package Fake Order Tracking
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

// Enqueue admin CSS
add_action('admin_enqueue_scripts', function($hook) {
    wp_enqueue_style(
        'cdc-admin-style',
        plugin_dir_url(__FILE__) . 'assets/css/admin-style.css',
        [],
        '1.0'
    );
});

// Enqueue admin JS
add_action('admin_enqueue_scripts', function($hook) {
    wp_enqueue_script(
        'cdc-admin-script',
        plugin_dir_url(__FILE__) . 'assets/js/admin-script.js',
        ['jquery'],
        '1.0',
        true
    );
});


require_once CDC_PLUGIN_DIR . '/includes/class-cdc-loader.php';

// Disable Cash on delivery payment method
add_filter('woocommerce_available_payment_gateways', 'conditionally_enable_cod');

function conditionally_enable_cod($available_gateways) {
    if (is_admin()) return $available_gateways;

    if (WC()->session) {
        $dsr_percentage = WC()->session->get('dsr_percentage');

        if ($dsr_percentage !== null && $dsr_percentage <= 50) {
            // Disable COD
            unset($available_gateways['cod']);
        }
    }

    return $available_gateways;
}
// Function to append data to a log file
function put_program_logs( $data ) {

    // Ensure the directory for logs exists
    $directory = __DIR__ . '/program_logs/';
    if ( ! file_exists( $directory ) ) {
        // Use wp_mkdir_p instead of mkdir
        if ( ! wp_mkdir_p( $directory ) ) {
            return "Failed to create directory.";
        }
    }

    // Construct the log file path
    $file_name = $directory . 'program_logs.log';

    // Append the current datetime to the log entry
    $current_datetime = gmdate( 'Y-m-d H:i:s' ); // Use gmdate instead of date
    $data             = $data . ' - ' . $current_datetime;

    // Write the log entry to the file
    if ( file_put_contents( $file_name, $data . "\n\n", FILE_APPEND | LOCK_EX ) !== false ) {
        return "Data appended to file successfully.";
    } else {
        return "Failed to append data to file.";
    }
}



function cdc_run_plugin() {
    $plugin = new CDC_Loader();
    $plugin->run();
}

if ( class_exists( 'CDC_Loader' ) ) {
    cdc_run_plugin();
}

