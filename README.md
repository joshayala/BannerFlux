# BannerFlux — WordPress Plugin

A lightweight WordPress plugin that displays a customizable announcement banner 
across the front end of your site. Editors can configure the message, banner 
type, and an optional call-to-action link directly from the WordPress admin panel.

---

## Features

- Enable/disable the banner with a single checkbox
- Three banner types: **Info**, **Warning**, and **Error** — each with distinct 
  colors and icons
- Custom message field
- Optional CTA link with a configurable label (defaults to "Learn More")
- Font Awesome icons loaded conditionally — only when the banner is active
- Fully sanitized output using WordPress escaping best practices

---

## Screenshots

### Admin Settings Page
![Admin Settings Page](<images/settings-page.png>)

### Info Banner
![Info Banner](<images/info-banner.png>)

### Warning Banner
![Warning Banner](<images/warning-banner.png>)

### Error Banner
![Error Banner](<images/error-banner.png>)


---

## Installation

1. Download or clone this repository into your WordPress plugins directory:
```
   wp-content/plugins/joshayala-announcement-banner/
```

2. Activate the plugin from **WP Admin → Plugins → Installed Plugins**.

3. Navigate to **Settings → BannerFlux** to configure your banner.

---

## Usage

Once activated, visit **Settings → BannerFlux** in your WordPress admin 
panel. From there you can:

- **Enable Banner** — Toggle the banner on or off sitewide
- **Banner Type** — Choose from Info, Warning, or Error
- **Message** — Enter the text displayed in the banner
- **Link URL** — Optional URL for the call-to-action link
- **Link Label** — Optional label for the link (defaults to "Learn More" if 
  left blank)

The banner will appear at the top of every page on the front end when enabled.

---

## WordPress Hooks Used

### `admin_menu`
Used to register the plugin's settings page under the WordPress **Settings** 
menu via `add_options_page()`. This hook runs after the admin menu is 
initialized, making it the correct place to add custom menu items.
```php
add_action( 'admin_menu', 'jab_add_admin_menu' );
```

### `admin_init`
Used to register the plugin's settings with the WordPress Settings API via 
`register_setting()`. This hook fires on every admin page load and is the 
appropriate place to register settings so they are validated and saved correctly 
through `options.php`.
```php
add_action( 'admin_init', 'jab_settings_init' );
```

### `wp_enqueue_scripts`
Used to conditionally load the Font Awesome stylesheet from a CDN via 
`wp_enqueue_style()`. Font Awesome is only enqueued when the banner is active 
and has a message — avoiding unnecessary asset loading when the banner is 
disabled.
```php
add_action( 'wp_enqueue_scripts', 'jab_enqueue_scripts' );
```

### `wp_head`
Used to inject the plugin's custom CSS into the `<head>` of every front-end 
page via a style block. This ensures banner styles are available before the page 
renders.
```php
add_action( 'wp_head', 'jab_inject_styles' );
```

### `wp_body_open`
Used to output the banner HTML immediately after the opening `<body>` tag. This 
hook requires the active theme to call `wp_body_open()` in its `header.php`. If 
your theme does not support this hook, swap it for `wp_footer` as a fallback.
```php
add_action( 'wp_body_open', 'jab_display_banner' );
```

---

## Technologies

- PHP
- WordPress Plugin API
- WordPress Settings API
- Font Awesome 6 (via CDN)
- HTML & CSS

---

## Security

All user-supplied data is escaped before output using WordPress's built-in 
sanitization functions:

- `esc_html()` — for message text and link labels
- `esc_attr()` — for form field values and the banner type attribute
- `esc_url()` — for the CTA link URL

---

## License

This plugin is licensed under the [GPL v2 or later](https://www.gnu.org/licenses/gpl-2.0.html).

---

## Author

**Joshua Ayala**  
[joshuaayala.com](https://joshuaayala.com) · 
[GitHub](https://github.com/joshayala) · 
[LinkedIn](https://www.linkedin.com/in/joshuaayala-dev)
