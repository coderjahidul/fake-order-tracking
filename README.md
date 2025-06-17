=== WooCommerce Fake Order Tracking ===
Contributors: grocoder
Tags: woocommerce, fraud detection, order validation, customer dsr, fake order tracking, payment gateway control
Requires at least: 5.5
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Prevent fake orders and reduce risk in your WooCommerce store. Automatically check customer delivery success rate (DSR) and adjust payment options accordingly.

== Description ==

**WooCommerce Fake Order Tracking** protects your store from risky and fake orders by checking the customer’s Delivery Success Rate (DSR) in real time.

This plugin interacts with a fraud detection API using the customer’s phone number, evaluates their order history, and automatically adjusts available payment methods during checkout. For customers with a low DSR, the plugin can force advanced delivery charge payment by disabling Cash on Delivery (COD). If the customer has a good DSR, COD remains enabled.

Additionally, the plugin logs and stores DSR check results with each order and provides an admin dashboard view for reviewing DSR data per customer.

### 🔑 Features

- ✅ Real-time delivery success rate check using external API.
- ✅ Show or hide payment gateways based on DSR.
- ✅ Disable Cash on Delivery for high-risk customers.
- ✅ Admin view for DSR report in WooCommerce order edit screen.
- ✅ Easy-to-configure API URL and key in plugin settings.
- ✅ Helps reduce fake or return orders, saving time and money.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/woocommerce-fake-order-tracking` directory, or install the plugin via the WordPress plugin repository.
2. Activate the plugin through the ‘Plugins’ menu in WordPress.
3. Go to **Settings > Fake Order Tracking** to enter your API URL and API Key.
4. The plugin will now start validating customer phone numbers during checkout and adjust payment gateways accordingly.

== Usage ==

1. When a customer enters their phone number at checkout, the plugin sends a request to the configured API to fetch their DSR.
2. If the customer has a **low success rate**, the plugin will:
   - Hide the **Cash on Delivery** option.
   - Show a **prepaid delivery method** (configured via your WooCommerce payment gateways).
3. If the customer has a **good DSR**, the **Cash on Delivery** option is shown normally.
4. The DSR result is stored with the order and can be viewed from the order edit screen in the admin panel.

== Frequently Asked Questions ==

= What is DSR (Delivery Success Rate)? =
The Delivery Success Rate (DSR) is a measure of how often a customer successfully accepts and completes deliveries. It's used to assess risk.

= Will this plugin work with all payment gateways? =
Yes, it dynamically enables or disables WooCommerce payment gateways based on the DSR logic.

= Does this plugin store DSR data? =
Yes, the DSR check result is saved with each order and is viewable by the store admin.

= What API is used to fetch the DSR? =
You must configure your API that return DSR based on a phone number. Use the plugin settings page to enter your API URL and key.

== Screenshots ==

1. Plugin settings page where you enter your API URL and key.
2. The DSR report is visible in WooCommerce order details.
3. Checkout with payment options adjusted based on DSR result.

== Changelog ==

= 1.0.0 =
* Initial release.
* DSR check via API.
* Dynamic payment gateway control.
* Admin order view with DSR log.

== Upgrade Notice ==

= 1.0.0 =
Initial release. Please configure API credentials in the plugin settings before use.

== License ==

This plugin is licensed under the GPLv2 or later.


