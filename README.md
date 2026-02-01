# Fake Order Tracking for WooCommerce

**Fake Order Tracking** is a light-weight but powerful tool to prevent fraudulent orders in your WooCommerce store. It checks the **Delivery Success Rate (DSR)** of customers based on their phone numbers and dynamically hides risky payment methods like Cash on Delivery.

## 🚀 Key Features
- **Real-time DSR Check**: Integrated with Steadfast and RedX Courier APIs.
- **Fraud Prevention**: Disables COD for customers with a high return rate.
- **Admin Visibility**: Displays DSR scores directly in the WooCommerce order details.
- **Lightweight & Fast**: Optimized AJAX requests for a smooth checkout experience.

## 🛠 Installation
1. Download or clone this repository to `/wp-content/plugins/fake-order-tracking`.
2. Activate the plugin in WordPress.
3. Go to **Settings > Fake Order Tracking** and enter your courier credentials.

## 🔧 Configuration
The plugin requires credentials for the following couriers:
- **Steadfast Courier**: Email and Password.
- **RedX Courier**: Email and Access Token.

## 📖 Full Documentation
For detailed guides on setup, logic, and developer hooks, please refer to [documentation.md](documentation.md).

## 📄 License
This plugin is licensed under the GPL-2.0.