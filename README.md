# HashCalendly

Turns any '#calendly' link on your WordPress site into a Calendly booking popup.

Built by [Manoj Murulinath](https://manoj.co) — one of a handful of small
WordPress plugins being open-sourced from years of hands-on WordPress and
webops work. Sibling plugin to [HashCal](https://manoj.co), same idea for
Calendly instead of Cal.com.

## What it does

Set your Calendly link once in the plugin settings. After that, any link
anywhere on your site with `href="#calendly"` will pop open that event's
Calendly booking widget instead of navigating anywhere — no per-page setup,
no shortcodes, no extra plugins.

This solves the usual pain of wiring up Calendly inside a page builder
(Salient, Elementor, etc.), where you'd otherwise have to drop the embed
script into a custom HTML widget on every single page or button.

Works with:
- WordPress menu items
- Page builder buttons
- Raw HTML links (`<a href="#calendly">Book a call</a>`)

## Installation

1. In WordPress admin, go to **Plugins → Add New → Upload Plugin**
2. Upload `hashcalendly.zip`
3. Click **Install Now**, then **Activate**

## Setup

1. Go to **Settings → HashCalendly**
2. Enter your Calendly link in either format:
   - Short form: `your-org/your-event-type`
   - Full URL: `https://calendly.com/your-org/your-event-type`
   (Either way, it's cleaned up automatically on save.)
3. Click **Save Settings**

## Usage

Anywhere you can set a link URL, use `#calendly`:

```html
<a href="#calendly">Book a call</a>
```

- **WordPress menu item:** set the URL field to `#calendly`
- **Page builder button:** set the link/URL field to `#calendly`
- If your builder strips a bare `#calendly` down to something odd, try
  `https://yourdomain.com/#calendly` instead — the plugin matches both forms.

## Notes

- The plugin's script and stylesheet only load on pages if a Calendly
  link has actually been saved in settings — no link configured means
  nothing extra is loaded at all.
- Uses Calendly's official popup widget (`initPopupWidget`), loaded
  on demand only when a `#calendly` link is actually clicked — not on
  every page load.

## Uninstalling

- **Deactivating** the plugin (Plugins screen) turns it off but keeps
  your saved Calendly link — reactivating brings it right back.
- **Deleting** the plugin (Deactivate, then Delete) removes the saved
  setting automatically via `uninstall.php`. No manual cleanup needed.

## Version

1.0.0

## Author

Manoj Murulinath — [manoj.co](https://manoj.co)
