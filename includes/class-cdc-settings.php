<?php 
class CDC_Settings {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_settings_page() {
        add_options_page(
            'Customer DSR Settings',
            'Customer DSR Checker',
            'manage_options',
            'customer-dsr-checker',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings() {
        register_setting('cdc_settings_group', 'cdc_api_url');
        register_setting('cdc_settings_group', 'cdc_api_key');
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>Customer DSR API Configure</h1>
            <form method="post" action="options.php">
                <?php settings_fields('cdc_settings_group'); ?>
                <?php do_settings_sections('cdc_settings_group'); 
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">API URL</th>
                        <td><input type="text" name="cdc_api_url" value="<?php echo esc_attr(get_option('cdc_api_url', 'https://fraudchecker.link/api/v1/qc/')); ?>" size="50"/></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">API Key</th>
                        <td><input type="text" name="cdc_api_key" value="<?php echo esc_attr(get_option('cdc_api_key', '')); ?>" size="50"/></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}