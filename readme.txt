=== Fake Order Tracking ===
Contributors: imjol
Tags: woocommerce, fraud detection, order validation, customer dsr, fake order tracking, payment gateway control
Requires at least: 5.5
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Prevent fake orders and reduce risks in your WooCommerce store. Automatically check a customer’s Delivery Success Rate (DSR) and adjust payment options such as Cash on Delivery (COD) accordingly.

== Description ==

**Fake Order Tracking** helps WooCommerce store owners prevent fraudulent and risky orders by verifying the customer’s Delivery Success Rate (DSR) in real time.  

Using the customer’s phone number, the plugin connects with an external API to fetch their delivery history and success rate. Based on this information, the plugin can automatically enable or disable certain payment gateways at checkout. For example, if the customer has a poor DSR, *Cash on Delivery (COD)* can be disabled, forcing prepaid payment methods.  

Additionally, the plugin stores DSR check results with each order and provides an easy-to-use admin interface for reviewing customer history.

### 🔑 Key Features

- ✅ Real-time DSR check via external API.
- ✅ Dynamic payment gateway control at checkout.
- ✅ Disable Cash on Delivery for high-risk customers.
- ✅ Admin dashboard view with DSR data per order.
- ✅ API URL and API Key configuration in plugin settings.
- ✅ Helps reduce returns, cancellations, and fake orders.

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/fake-order-tracking`, or install via the WordPress Plugins screen.
2. Activate the plugin through the *Plugins* menu in WordPress.
3. Go to **Settings → Fake Order Tracking** to enter your API URL and API Key.
4. Once configured, the plugin will validate phone numbers during checkout and adjust payment gateways automatically.

== Usage ==

1. During checkout, the customer enters their phone number.
2. The plugin queries the configured API for their DSR (Delivery Success Rate).
3. Based on the result:
   - If the customer has a **low DSR**, *Cash on Delivery* will be hidden, and only prepaid methods will remain.
   - If the customer has a **good DSR**, *Cash on Delivery* remains available.
4. The DSR check result is logged and stored with the order for admin review.

== Frequently Asked Questions ==

= What is Delivery Success Rate (DSR)? =
The Delivery Success Rate (DSR) measures how often a customer successfully accepts and completes deliveries. A low DSR indicates a higher risk of fake or cancelled orders.

= Will this plugin work with all payment gateways? =
Yes. It dynamically controls WooCommerce payment gateways and is compatible with any installed gateway.

= Does this plugin save DSR data? =
Yes. Each order stores the DSR check result, which is visible to admins in the order details page.

= What API does this plugin use? =
You must configure your own fraud detection API that returns DSR data based on phone numbers. Enter your API URL and key in the settings.

== Screenshots ==

1. Plugin settings page where you configure API URL and key.
2. WooCommerce order details showing the customer’s DSR.
3. Checkout page with adjusted payment methods based on risk.

== Changelog ==

= 1.0.0 =
* Initial release.
* DSR check integration via API.
* Dynamic payment gateway control.
* Admin order view with DSR logs.

== Upgrade Notice ==

= 1.0.0 =
Initial release. Configure API credentials in **Settings → Fake Order Tracking** before use.

== License ==

This plugin is licensed under the GPLv2 or later.
