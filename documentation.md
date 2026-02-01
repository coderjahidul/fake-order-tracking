# Fake Order Tracking Documentation

## Overview
**Fake Order Tracking** is a WooCommerce plugin designed to identify high-risk customers and reduce fraudulent orders by verifying their **Delivery Success Rate (DSR)** in real-time. By analyzing a customer's phone number against courier records (Steadfast and RedX), the plugin can automatically restrict risky payment methods like Cash on Delivery (COD).

---

## Features
- **Real-time DSR Validation**: Automatically fetches delivery history from major couriers during checkout.
- **Dynamic Payment Control**: Disables Cash on Delivery (COD) for customers with a low DSR (default is ≤ 50%).
- **Multi-Courier Support**: Integrates with **Steadfast Courier** and **RedX** for comprehensive coverage.
- **Admin Insights**: View detailed DSR logs directly within the WooCommerce Order Edit screen.
- **Manual Refresh**: Admins can manually refresh DSR data for any order to get the latest status.
- **AJAX-Powered**: Seamless performance without page reloads.

---

## Installation
1. Upload the `fake-order-tracking` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Navigate to **Settings → Fake Order Tracking** to configure your courier credentials.

---

## Configuration

### Courier Integrations
To fetch DSR data, you need to provide credentials for the supported couriers:

#### 1. Steadfast Courier
- **Email**: Your Steadfast courier account email.
- **Password**: Your Steadfast courier account password.
*Note: The plugin uses these to securely authenticate and fetch fraud check data.*

#### 2. RedX Courier
- **Email**: Your RedX account email.
- **Access Token**: Your RedX API Access Token (found in your RedX developer settings).

---

## How It Works

### At Checkout
1. When a customer enters their phone number on the checkout page, an AJAX request is triggered.
2. The plugin queries the Steadfast and RedX APIs to retrieve the customer's delivery history (total parcels vs. delivered parcels).
3. The **DSR Percentage** is calculated as:  
   `(Total Delivered / Total Parcels) * 100`
4. If the DSR is **50% or lower**, the **Cash on Delivery (COD)** payment method is automatically hidden to ensure payment security.

### In Admin
1. For every order created, the DSR data (Total Parcels, Delivered, Cancelled) is saved in the order metadata.
2. Admins can see a dedicated **DSR Report** metabox in the right sidebar of the Order Edit page.
3. If an order has outdated info, click **"Refresh DSR"** to pull the latest data.

---

## Data Breakdown
The DSR report shows:
- **Total Parcels**: All recorded deliveries for this phone number.
- **Total Delivered**: Successfully completed deliveries.
- **Total Cancelled**: Returned or cancelled orders.
- **DSR Score**: Percentage of successful deliveries.

---

## Developer Information
- **Hooks**:
    - `woocommerce_available_payment_gateways`: Used to filter available gateways based on DSR.
    - `woocommerce_checkout_create_order`: Used to attach DSR data to new orders.
- **AJAX Actions**:
    - `cdc_dsr_get_score`: Used during checkout.
    - `cdc_dsr_refresh`: Used in the admin area.

---

## Support & Updates
For updates and bug reports, please visit the [GitHub Repository](https://github.com/coderjahidul/fake-order-tracking).
