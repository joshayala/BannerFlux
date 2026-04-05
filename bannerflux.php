<?php

/**
 * Plugin Name: BannerFlux
 * Plugin URI:  https://github.com/joshayala/BannerFlux
 * Description: Displays a customizable announcement banner across the site.
 * Version:     1.0.0
 * Author:      Joshua Ayala
 * Author URI:  https://joshuaayala.com
 * License:     GPL2
 */

// Enqueue Font Awesome from CDN
add_action('wp_enqueue_scripts', 'jab_enqueue_scripts');
function jab_enqueue_scripts()
{
    if (get_option('jab_enabled') && get_option('jab_message')) {
        wp_enqueue_style(
            'font-awesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
            [],
            '6.5.0'
        );
    }
}

// Add menu item to WP Admin
add_action('admin_menu', 'jab_add_admin_menu');
function jab_add_admin_menu()
{
    add_options_page(
        'BannerFlux',
        'BannerFlux',
        'manage_options',
        'bannerflux',
        'jab_settings_page'
    );
}

// Register settings
add_action('admin_init', 'jab_settings_init');
function jab_settings_init()
{
    register_setting('jab_settings', 'jab_message');
    register_setting('jab_settings', 'jab_enabled');
    register_setting('jab_settings', 'jab_type');
    register_setting('jab_settings', 'jab_link_url');
    register_setting('jab_settings', 'jab_link_label');
}

// Render the settings page
function jab_settings_page()
{ ?>
    <div class="wrap">
        <h1>BannerFlux</h1>
        <form method="post" action="options.php">
            <?php settings_fields('jab_settings'); ?>
            <table class="form-table">
                <tr>
                    <th>Enable Banner</th>
                    <td>
                        <input type="checkbox" name="jab_enabled" value="1"
                            <?php checked(1, get_option('jab_enabled'), true); ?> />
                    </td>
                </tr>
                <tr>
                    <th>Banner Type</th>
                    <td>
                        <select name="jab_type">
                            <?php
                            $current_type = get_option('jab_type', 'info');
                            $types = [
                                'info'    => 'Info',
                                'warning' => 'Warning',
                                'error'   => 'Error',
                            ];
                            foreach ($types as $value => $label) {
                                echo "<option value='{$value}'" . selected($current_type, $value, false) . ">{$label}</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Message</th>
                    <td>
                        <input type="text" name="jab_message" style="width:400px"
                            value="<?php echo esc_attr(get_option('jab_message')); ?>" />
                    </td>
                </tr>
                <tr>
                    <th>Link URL</th>
                    <td>
                        <input type="url" name="jab_link_url" style="width:400px"
                            placeholder="https://example.com"
                            value="<?php echo esc_attr(get_option('jab_link_url')); ?>" />
                        <p class="description">Optional. Leave blank to show no link.</p>
                    </td>
                </tr>
                <tr>
                    <th>Link Label</th>
                    <td>
                        <input type="text" name="jab_link_label" style="width:200px"
                            placeholder="Learn More"
                            value="<?php echo esc_attr(get_option('jab_link_label')); ?>" />
                        <p class="description">Defaults to "Learn More" if left blank.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php }

// Inject front-end styles
add_action('wp_head', 'jab_inject_styles');
function jab_inject_styles()
{ ?>
    <style>
        .jab-banner {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 20px;
            font-size: 15px;
            width: 100%;
            z-index: 9999;
            font-family: sans-serif;
        }

        .jab-banner.jab-info {
            background: #1a73e8;
            color: #ffffff;
        }

        .jab-banner.jab-warning {
            background: #f9a825;
            color: #1a1a1a;
        }

        .jab-banner.jab-error {
            background: #d32f2f;
            color: #ffffff;
        }

        .jab-banner .jab-icon {
            font-size: 18px;
            line-height: 1;
        }

        .jab-banner .jab-divider {
            opacity: 0.6;
        }

        .jab-banner .jab-link {
            color: inherit;
            font-weight: 600;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .jab-banner .jab-link:hover {
            opacity: 0.8;
        }
    </style>
<?php }

// Display banner on front end
add_action('wp_body_open', 'jab_display_banner');
function jab_display_banner()
{
    if (get_option('jab_enabled') && get_option('jab_message')) {
        $message     = esc_html(get_option('jab_message'));
        $type        = esc_attr(get_option('jab_type', 'info'));
        $link_url    = esc_url(get_option('jab_link_url'));
        $link_label  = esc_html(get_option('jab_link_label'));
        $link_label  = ! empty($link_label) ? $link_label : 'Learn More';

        $icons = [
            'info'    => '<i class="fa-solid fa-circle-info"></i>',
            'warning' => '<i class="fa-solid fa-triangle-exclamation"></i>',
            'error'   => '<i class="fa-solid fa-circle-xmark"></i>',
        ];

        $icon = isset($icons[$type]) ? $icons[$type] : $icons['info'];

        $link_html = '';
        if (! empty($link_url)) {
            $link_html = "<span class='jab-divider'>|</span><a class='jab-link' href='{$link_url}'>{$link_label}</a>";
        }

        echo "
            <div class='jab-banner jab-{$type}'>
                <span class='jab-icon'>{$icon}</span>
                <span>{$message}</span>
                {$link_html}
            </div>
        ";
    }
}
