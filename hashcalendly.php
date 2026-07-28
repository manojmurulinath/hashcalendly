<?php
/**
 * Plugin Name: HashCalendly
 * Plugin URI:  https://manoj.co
 * Description: Turns any #calendly link across your site into a Calendly booking popup. Set your Calendly link once in Settings → HashCalendly.
 * Version:     1.0.0
 * Author:      Manoj Murulinath
 * Author URI:  https://manoj.co
 * License:     GPL-2.0+
 * Text Domain: hashcalendly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

define( 'HASHCALENDLY_VERSION', '1.0.0' );
define( 'HASHCALENDLY_OPTION_KEY', 'hashcalendly_link' );

/**
 * ---------------------------------------------------------------------
 * Settings page: Settings → HashCalendly
 * ---------------------------------------------------------------------
 */
add_action( 'admin_menu', 'hashcalendly_add_settings_page' );
function hashcalendly_add_settings_page() {
	add_options_page(
		'HashCalendly Settings',
		'HashCalendly',
		'manage_options',
		'hashcalendly',
		'hashcalendly_render_settings_page'
	);
}

add_action( 'admin_init', 'hashcalendly_register_settings' );
function hashcalendly_register_settings() {
	register_setting(
		'hashcalendly_settings_group',
		HASHCALENDLY_OPTION_KEY,
		array(
			'type'              => 'string',
			'sanitize_callback' => 'hashcalendly_sanitize_link',
			'default'           => '',
		)
	);
}

/**
 * Accepts either "your-org/event-type" or a full Calendly URL and
 * normalizes it down to "your-org/event-type".
 */
function hashcalendly_sanitize_link( $value ) {
	$value = trim( $value );

	// Strip protocol/domain if a full URL was pasted in.
	$value = preg_replace( '#^https?://(www\.)?calendly\.com/#i', '', $value );
	$value = trim( $value, '/' );

	// Allow letters, numbers, dashes, underscores and slashes (Calendly paths can be nested).
	$value = preg_replace( '#[^a-zA-Z0-9\-_/]#', '', $value );

	return $value;
}

function hashcalendly_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$current_link = get_option( HASHCALENDLY_OPTION_KEY, '' );
	?>
	<div class="wrap">
		<h1>HashCalendly Settings</h1>
		<p>Enter your Calendly link below. Once saved, any link on your site with <code>href="#calendly"</code> will open this event's booking popup.</p>

		<form method="post" action="options.php">
			<?php settings_fields( 'hashcalendly_settings_group' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row">
						<label for="hashcalendly_link">Calendly link</label>
					</th>
					<td>
						<input
							type="text"
							id="hashcalendly_link"
							name="<?php echo esc_attr( HASHCALENDLY_OPTION_KEY ); ?>"
							value="<?php echo esc_attr( $current_link ); ?>"
							class="regular-text"
							placeholder="your-org/your-event-type"
						/>
						<p class="description">
							e.g. <code>manoj/intro-call</code> — or just paste the full Calendly URL, it'll be cleaned up automatically.
						</p>
					</td>
				</tr>
			</table>
			<?php submit_button( 'Save Settings' ); ?>
		</form>

		<?php if ( ! empty( $current_link ) ) : ?>
			<hr />
			<h2>Usage</h2>
			<p>Use <code>#calendly</code> as the URL/link target anywhere on your site — WordPress menus, page builder buttons, raw HTML links, etc. Example:</p>
			<pre style="background:#f0f0f1;padding:10px;max-width:500px;">&lt;a href="#calendly"&gt;Book a call&lt;/a&gt;</pre>
			<p>Currently active event: <strong><?php echo esc_html( $current_link ); ?></strong></p>
		<?php else : ?>
			<hr />
			<p><em>No Calendly link saved yet — #calendly links will not open a popup until you save one above.</em></p>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Add a "Settings" link on the Plugins list page for convenience.
 */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'hashcalendly_settings_link' );
function hashcalendly_settings_link( $links ) {
	$settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=hashcalendly' ) ) . '">Settings</a>';
	array_unshift( $links, $settings_link );
	return $links;
}

/**
 * ---------------------------------------------------------------------
 * Front-end script + stylesheet: only load if a link is configured.
 * ---------------------------------------------------------------------
 */
add_action( 'wp_enqueue_scripts', 'hashcalendly_enqueue_assets' );
function hashcalendly_enqueue_assets() {
	$cal_link = get_option( HASHCALENDLY_OPTION_KEY, '' );

	if ( empty( $cal_link ) ) {
		return; // Nothing configured yet — don't load anything.
	}

	// Calendly's own popup CSS (badge/close button, popup frame chrome).
	wp_enqueue_style(
		'hashcalendly-widget',
		'https://assets.calendly.com/assets/external/widget.css',
		array(),
		HASHCALENDLY_VERSION
	);

	wp_enqueue_script(
		'hashcalendly',
		plugins_url( 'assets/hashcalendly.js', __FILE__ ),
		array(),
		HASHCALENDLY_VERSION,
		true
	);

	wp_localize_script(
		'hashcalendly',
		'HashCalendlyConfig',
		array(
			'calendlyUrl' => 'https://calendly.com/' . $cal_link,
		)
	);
}
