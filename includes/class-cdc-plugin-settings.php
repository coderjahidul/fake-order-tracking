<?php
class CDC_Plugin_Settings
{
    private $option_group = 'cdc_settings_group';

    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_settings_page()
    {
        add_options_page(
            'Fake Order Tracking Settings',
            'Fake Order Tracking',
            'manage_options',
            'fake-order-tracking',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings()
    {

        // New: Steadfast email + password
        register_setting(
            $this->option_group,
            'cdc_steadfast_email',
            [
                'sanitize_callback' => 'sanitize_email',
                'default' => ''
            ]
        );

        register_setting(
            $this->option_group,
            'cdc_steadfast_password',
            [
                'sanitize_callback' => 'sanitize_text_field',
                'default' => ''
            ]
        );

        // New: RedX email + access token
        register_setting(
            $this->option_group,
            'cdc_redx_email',
            [
                'sanitize_callback' => 'sanitize_email',
                'default' => ''
            ]
        );

        register_setting(
            $this->option_group,
            'cdc_redx_access_token',
            [
                'sanitize_callback' => 'sanitize_text_field',
                'default' => ''
            ]
        );

        register_setting(
            $this->option_group,
            'cdc_dsr_threshold',
            [
                'sanitize_callback' => 'absint',
                'default' => 50
            ]
        );
    }

    public function render_settings_page()
    {
        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'settings';
        ?>
        <div class="wrap cdc-settings-wrap">
            <h1 class="wp-heading-inline">Fake Order Tracking</h1>
            <hr class="wp-header-end">

            <nav class="nav-tab-wrapper">
                <a href="?page=fake-order-tracking&tab=settings"
                    class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons dashicons-admin-settings"></span> Settings
                </a>
                <a href="?page=fake-order-tracking&tab=documentation"
                    class="nav-tab <?php echo $active_tab == 'documentation' ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons dashicons-editor-help"></span> Documentation
                </a>
            </nav>

            <div class="tab-content" style="margin-top: 20px;">
                <?php if ($active_tab == 'settings'): ?>
                    <form method="post" action="options.php">
                        <?php
                        settings_fields($this->option_group);
                        ?>

                        <div id="cdc-settings-metaboxes" class="metabox-holder">
                            <div class="postbox-container" style="width: 100%; max-width: 800px;">

                                <!-- Steadfast Section -->
                                <div class="postbox cdc-premium-box">
                                    <div class="postbox-header">
                                        <h2 class="hndle ui-sortable-handle">
                                            <span class="dashicons dashicons-external"></span> Steadfast Courier Integration
                                        </h2>
                                    </div>
                                    <div class="inside">
                                        <table class="form-table">
                                            <tr valign="top">
                                                <th scope="row">Steadfast Email</th>
                                                <td>
                                                    <input type="email" name="cdc_steadfast_email"
                                                        value="<?php echo esc_attr(get_option('cdc_steadfast_email', '')); ?>"
                                                        class="regular-text" placeholder="your-email@steadfast.com.bd" />
                                                    <p class="description">Email used for your Steadfast account.</p>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <th scope="row">Steadfast Password</th>
                                                <td>
                                                    <input type="password" name="cdc_steadfast_password"
                                                        value="<?php echo esc_attr(get_option('cdc_steadfast_password', '')); ?>"
                                                        class="regular-text" />
                                                    <p class="description">Password for your Steadfast account.</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <!-- RedX Section -->
                                <div class="postbox cdc-premium-box">
                                    <div class="postbox-header">
                                        <h2 class="hndle ui-sortable-handle">
                                            <span class="dashicons dashicons-external"></span> RedX Courier Integration
                                        </h2>
                                    </div>
                                    <div class="inside">
                                        <table class="form-table">
                                            <tr valign="top">
                                                <th scope="row">RedX Email</th>
                                                <td>
                                                    <input type="email" name="cdc_redx_email"
                                                        value="<?php echo esc_attr(get_option('cdc_redx_email', '')); ?>"
                                                        class="regular-text" placeholder="your-email@redx.com.bd" />
                                                    <p class="description">Email for RedX integration.</p>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <th scope="row">RedX Access Token</th>
                                                <td>
                                                    <input type="password" name="cdc_redx_access_token"
                                                        value="<?php echo esc_attr(get_option('cdc_redx_access_token', '')); ?>"
                                                        class="regular-text" />
                                                    <p class="description">RedX accessToken (treat like a secret API Key).</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <!-- DSR Configuration Section -->
                                <div class="postbox cdc-premium-box">
                                    <div class="postbox-header">
                                        <h2 class="hndle ui-sortable-handle">
                                            <span class="dashicons dashicons-performance"></span> DSR Configuration
                                        </h2>
                                    </div>
                                    <div class="inside">
                                        <table class="form-table">
                                            <tr valign="top">
                                                <th scope="row">DSR Threshold (%)</th>
                                                <td>
                                                    <input type="number" name="cdc_dsr_threshold"
                                                        value="<?php echo esc_attr(get_option('cdc_dsr_threshold', 50)); ?>"
                                                        class="small-text" min="0" max="100" /> %
                                                    <p class="description">If a customer's Delivery Success Rate is **equal to or
                                                        lower** than this percentage, Cash on Delivery (COD) will be hidden.</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <?php submit_button('Save Configuration', 'primary large'); ?>
                            </div>
                        </div>
                    </form>

                <?php elseif ($active_tab == 'documentation'): ?>
                    <div class="cdc-documentation-container"
                        style="max-width: 900px; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                        <div class="doc-header"
                            style="border-bottom: 2px solid #f0f0f1; margin-bottom: 25px; padding-bottom: 15px;">
                            <h2 style="margin: 0; color: #1d2327;">Plugin Guide & Documentation</h2>
                            <p style="color: #646970; margin-top: 5px;">Everything you need to know about Fake Order Tracking.</p>
                        </div>

                        <div class="doc-section">
                            <h3><span class="dashicons dashicons-info"></span> What is DSR?</h3>
                            <p><strong>Delivery Success Rate (DSR)</strong> is the percentage of successfully delivered orders
                                compared to total orders. This plugin fetches this data using the customer's phone number from
                                courier databases.</p>
                            <div class="dsr-formula"
                                style="background: #f6f7f7; padding: 15px; border-left: 4px solid #2271b1; font-family: monospace; display: inline-block; margin: 10px 0;">
                                DSR = (Delivered Parcels / Total Parcels) * 100
                            </div>
                        </div>

                        <div class="doc-section" style="margin-top: 30px;">
                            <h3><span class="dashicons dashicons-shield"></span> How Fraud Prevention Works</h3>
                            <ul style="list-style-type: disc; margin-left: 20px;">
                                <li><strong>Real-time Analytics:</strong> When a customer enters their phone number on the checkout
                                    page, the plugin instantly calculates their DSR score.</li>
                                <li><strong>Automatic Restriction:</strong> If a customer's DSR is <strong>at or below your
                                        configured threshold</strong> (default 50%), the plugin automatically hides the <strong>Cash
                                        on Delivery (COD)</strong> option.</li>
                                <li><strong>Prepaid Enforcement:</strong> High-risk customers are forced to pay via Bkash, Nagad, or
                                    other prepaid methods, significantly reducing return shipping costs.</li>
                            </ul>
                        </div>

                        <div class="doc-section" style="margin-top: 30px;">
                            <h3><span class="dashicons dashicons-visibility"></span> Admin Order Insights</h3>
                            <p>Inside every WooCommerce order, you will find a <strong>"Customer Delivery Success Rate"</strong>
                                box. This displays:</p>
                            <table class="widefat fixed striped" style="box-shadow: none; border: 1px solid #dcdcde;">
                                <thead>
                                    <tr>
                                        <th>Metric</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Total Parcels</strong></td>
                                        <td>Total number of orders attempted by this customer across all stores.</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Parcels Delivered</strong></td>
                                        <td>Successful deliveries verified by couriers.</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Cancelled Parcels</strong></td>
                                        <td>Orders that were returned, cancelled, or refused.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="doc-footer"
                            style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #f0f0f1; text-align: center;">
                            <p>Need more help? Check the <a href="https://github.com/coderjahidul/fake-order-tracking"
                                    target="_blank">GitHub Repository</a> for updates.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <style>
            .cdc-settings-wrap h1 {
                font-weight: 700;
                color: #1d2327;
                margin-bottom: 20px;
            }

            .nav-tab-wrapper {
                border-bottom: 2px solid #dcdcde;
                margin-bottom: 0;
            }

            .nav-tab {
                background: #e0e0e0;
                border: none;
                border-radius: 4px 4px 0 0;
                margin-right: 5px;
                transition: all 0.2s ease-in-out;
            }

            .nav-tab-active {
                background: #fff !important;
                border-top: 3px solid #2271b1 !important;
                color: #1d2327 !important;
                border-bottom: 2px solid #fff;
                position: relative;
                bottom: -2px;
            }

            .nav-tab .dashicons {
                vertical-align: middle;
                margin-top: -4px;
                margin-right: 3px;
            }

            .cdc-premium-box {
                border-radius: 8px;
                overflow: hidden;
                border: 1px solid #dcdcde;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
                margin-bottom: 25px;
            }

            .cdc-premium-box .postbox-header {
                background: #f9f9f9;
                border-bottom: 1px solid #dcdcde;
            }

            .cdc-premium-box .hndle {
                padding: 15px !important;
                font-size: 16px !important;
            }

            .cdc-premium-box .hndle .dashicons {
                color: #2271b1;
                margin-right: 8px;
            }

            .cdc-premium-box .inside {
                padding: 0 20px 20px;
            }

            .doc-section h3 {
                font-size: 18px;
                display: flex;
                align-items: center;
                color: #1d2327;
            }

            .doc-section h3 .dashicons {
                margin-right: 10px;
                color: #2271b1;
                font-size: 22px;
                width: 22px;
                height: 22px;
            }

            .cdc-settings-wrap .button-primary {
                background: #2271b1;
                border-color: #2271b1;
                box-shadow: 0 2px 4px rgba(34, 113, 177, 0.2);
                transition: all 0.2s;
                padding: 0 25px;
                line-height: 2.8;
                min-height: 40px;
                font-weight: 600;
            }

            .cdc-settings-wrap .button-primary:hover {
                background: #135e96;
                border-color: #135e96;
                transform: translateY(-1px);
                box-shadow: 0 4px 8px rgba(34, 113, 177, 0.3);
            }
        </style>
        <?php
    }
}
