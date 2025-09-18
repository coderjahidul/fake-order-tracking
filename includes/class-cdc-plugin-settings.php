<?php
class CDC_Plugin_Settings {
    private $option_group = 'cdc_settings_group';

    public function __construct() {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_settings_page() {
        add_options_page(
            'Fake Order Tracking Settings',
            'Fake Order Tracking',
            'manage_options',
            'fake-order-tracking',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings() {

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
    }

    public function render_settings_page() {
        // Load WP styles for postboxes to look like the screenshot
        ?>
        <div class="wrap">
            <h1>Fake Order Tracking — Configure</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields($this->option_group);
                do_settings_sections($this->option_group);
                ?>

                <div id="cdc-settings-metaboxes" class="metabox-holder columns-2">
                    <div class="postbox-container" style="width:65%;">
                        <div class="postbox">
                            <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Show/Hide</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                            <h2 class="hndle"><span>Steadfast Courier Integrations</span></h2>
                            <div class="inside">
                                <table class="form-table">
                                    <tr valign="top">
                                        <th scope="row">Steadfast Email</th>
                                        <td>
                                            <input type="email" name="cdc_steadfast_email" value="<?php echo esc_attr(get_option('cdc_steadfast_email', '')); ?>" class="regular-text"/>
                                            <p class="description">Email used for Steadfast API/FTP.</p>
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <th scope="row">Steadfast Password</th>
                                        <td>
                                            <input type="password" name="cdc_steadfast_password" value="<?php echo esc_attr(get_option('cdc_steadfast_password', '')); ?>" class="regular-text"/>
                                            <p class="description">Stored encrypted by WordPress options table if needed. Consider additional encryption if required.</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="postbox">
                            <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Show/Hide</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                            <h2 class="hndle"><span>RedX Courier Integrations</span></h2>
                            <div class="inside">
                                <table class="form-table">
                                    <tr valign="top">
                                        <th scope="row">RedX Email</th>
                                        <td>
                                            <input type="email" name="cdc_redx_email" value="<?php echo esc_attr(get_option('cdc_redx_email', '')); ?>" class="regular-text"/>
                                            <p class="description">Email for RedX integration.</p>
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <th scope="row">RedX Access Token</th>
                                        <td>
                                            <input type="password" name="cdc_redx_access_token" value="<?php echo esc_attr(get_option('cdc_redx_access_token', '')); ?>" class="regular-text"/>
                                            <p class="description">RedX accessToken (treat like a secret).</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="inside">
                                <?php submit_button(); ?>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <style>
            /* small tweak to ensure postboxes look spaced like your screenshot */
            #cdc-settings-metaboxes .postbox { margin-bottom: 18px; }
            .postbox .hndle { cursor: default; }
        </style>
        <?php
    }
}