<?php
/**
 * @twitchcraft
 * Plugin Name:       TwitchCraft
 * Description:       A WordPress plugin that allows a user to conditionally load content based on whether or not a Twitch streamer is broadcasting live.
 * Version:           2.0
 * Author:            Mitch Canter
 * Author URI:        http://www.mitchcanter.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       twitchcraft
 */

/*
 Twitch IS live Shortcode
 * [twitch_live user='username']
 * Displays content the channel IS live. Shortcode Usage
 */
function twitch_shortcodes() {
	add_shortcode( 'twitch_live', 'twitch_is_live_shortcode' );
	add_shortcode( 'twitch_offline', 'twitch_is_not_live_shortcode' );
}

add_action( 'init', 'twitch_shortcodes' );

function twitch_is_live_shortcode( $atts = array(), $content = null ) {
	$atts = array_change_key_case( (array) $atts, CASE_LOWER );

	$twitch_is_live_atts = shortcode_atts(
		array( 'user' => 'lofilion' ),
		$atts
	);

	$client_id     = get_option( 'twitch_client_id' );
	$client_id     = $client_id['client_id'];
	$client_secret = get_option( 'twitch_client_id' );
	$client_secret = $client_secret['client_secret'];

	if ( strlen( $client_id ) < 1 ) {

		print 'No Client ID Specified!';
		return false;

	} elseif ( strlen( $client_secret ) < 1 ) {

		print 'No Client Secret Specified!';
		return false;

	} else {

		// all systems go. let's get an access token!
		$url = 'https://id.twitch.tv/oauth2/token?client_id=' . $client_id . '&client_secret=' . $client_secret . '&grant_type=client_credentials';

		$response = wp_remote_post( $url );
		if ( ! is_wp_error( $response ) ) {

			$stream_obj = json_decode( $response['body'] );
			if ( $stream_obj ) {

				// grab the token. store the token. love the token.
				$token = $stream_obj->access_token;

				// all systems go.  grab the user data!
				$user = $atts['user'];

				$url  = 'https://api.twitch.tv/helix/streams?user_login=' . $user;
				$args = array(
					'headers' => array(
						'client-id'     => $client_id,
						'Authorization' => 'Bearer ' . $token,
					),
				);

				$response = wp_remote_get( $url, $args );
				if ( ! is_wp_error( $response ) ) {

					$stream = json_decode( $response['body'] );

					if ( $stream->data ) {
						return $content;
					} else {
						return false;
					}
				} else {
					print 'Unable to access Twitch API.';
					return false;
				}
			} else {

				echo 'Unable to Authenticate.';
				return false;

			}
		} else {

			print 'Unable to access Twitch API.';
			 return false;

		}
	}

}

/*
 * Twitch IS NOT live Shortcode
 * [twitch_offline user='username']
 * Displays content the channel IS NOT live. Shortcode Usage
 */

function twitch_is_not_live_shortcode( $atts = array(), $content = null ) {
	$atts = array_change_key_case( (array) $atts, CASE_LOWER );

	$twitch_is_live_atts = shortcode_atts(
		array( 'user' => 'lofilion' ),
		$atts
	);

	$client_id     = get_option( 'twitch_client_id' );
	$client_id     = $client_id['client_id'];
	$client_secret = get_option( 'twitch_client_id' );
	$client_secret = $client_secret['client_secret'];

	if ( strlen( $client_id ) < 1 ) {

		print 'No Client ID Specified!';
		return false;

	} elseif ( strlen( $client_secret ) < 1 ) {

		print 'No Client Secret Specified!';
		return false;

	} else {

		// all systems go. let's get an access token!
		$url = 'https://id.twitch.tv/oauth2/token?client_id=' . $client_id . '&client_secret=' . $client_secret . '&grant_type=client_credentials';

		$response = wp_remote_post( $url );
		if ( ! is_wp_error( $response ) ) {

			$stream_obj = json_decode( $response['body'] );
			if ( $stream_obj ) {

				// grab the token. store the token. love the token.
				$token = $stream_obj->access_token;

				// all systems go.  grab the user data!
				$user = $atts['user'];

				$url  = 'https://api.twitch.tv/helix/streams?user_login=' . $user;
				$args = array(
					'headers' => array(
						'client-id'     => $client_id,
						'Authorization' => 'Bearer ' . $token,
					),
				);

				$response = wp_remote_get( $url, $args );
				if ( ! is_wp_error( $response ) ) {

					$stream = json_decode( $response['body'] );

					if ( ! $stream->data ) {
						return $content;
					} else {
						return false;
					}
				} else {
					print 'Unable to access Twitch API.';
					return false;
				}
			} else {

				echo 'Unable to Authenticate.';
				return false;

			}
		} else {

			print 'Unable to access Twitch API.';
			 return false;

		}
	}

}


/**
 * Options Panel
 *
 */

class TwitchConditionalSettings {
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Start up
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {
		// This page will be under "Settings".
		add_options_page(
			'Settings Admin',
			'Twitch Conditional',
			'manage_options',
			'twitch-conditional-settings',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		// Set class property.
		$this->options = get_option( 'twitch_client_id' );
		?>
		<div class="wrap">
			<form method="post" action="options.php">
			<?php
				// This prints out all hidden setting fields.
				settings_fields( 'twitch_options' );
				do_settings_sections( 'twitch-conditional-settings' );
				submit_button();
			?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init() {
		register_setting(
			'twitch_options', // Option group.
			'twitch_client_id', // Option name.
			array( $this, 'sanitize' ) // Sanitize.
		);

		add_settings_section(
			'twitch_section_id', // ID.
			'Twitch Conditional Settings', // clientid.
			array( $this, 'print_section_info' ), // Callback.
			'twitch-conditional-settings' // Page.
		);

		add_settings_field(
			'client_id',
			'Client ID',
			array( $this, 'clientid_callback' ),
			'twitch-conditional-settings',
			'twitch_section_id'
		);

		add_settings_field(
			'client_secret',
			'Client Secret',
			array( $this, 'clientsecret_callback' ),
			'twitch-conditional-settings',
			'twitch_section_id'
		);

	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys.
	 */
	public function sanitize( $input ) {
		$new_input = array();

		if ( isset( $input['client_id'] ) ) {
			$new_input['client_id'] = sanitize_text_field( $input['client_id'] );
		}

		if ( isset( $input['client_secret'] ) ) {
			$new_input['client_secret'] = sanitize_text_field( $input['client_secret'] );
		}

		return $new_input;
	}

	/**
	 * Print the Section text
	 */
	public function print_section_info() {
		print 'Please visit <a href="https://www.twitch.tv/settings/connections">your Twitch "Connections" Settings page</a> and register a new Developer Application.  Once you have done this, enter your Client ID Below.  Twitch requires a client ID with any API Requests.';
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function clientid_callback() {
		printf(
			'<input type="text" id="client_id" name="twitch_client_id[client_id]" value="%s" />',
			isset( $this->options['client_id'] ) ? esc_attr( $this->options['client_id'] ) : ''
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function clientsecret_callback() {
		printf(
			'<input type="text" id="client_secret" name="twitch_client_id[client_secret]" value="%s" />',
			isset( $this->options['client_secret'] ) ? esc_attr( $this->options['client_secret'] ) : ''
		);
	}
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_twitchcraft_block_init() {
	register_block_type(
		__DIR__ . '/build',
		array(
			'render_callback' => 'render_block_twitchcraft',
		)
	);
}
add_action( 'init', 'create_block_twitchcraft_block_init' );

/**
 * Displays the content on the frontend based on if a channel is live or not.
 *
 * @param array  $attributes The attributes passed by the block.
 * @param string $content The block content.
 * @return string
 */
function render_block_twitchcraft( $attributes, $content ) {
	ob_start();
	$channel = is_channel_live( $attributes['userName'] );
	if ( isset( $channel['is_live'] ) ) {
		?>
	<div><?php echo wp_kses_post( $attributes['liveContent'] ); ?></div>
		<?php
	} else {
		?>
	<div><?php echo wp_kses_post( $attributes['offlineContent'] ); ?></div>
		<?php
	}
	return ob_get_clean();
}


if ( is_admin() ) {
	$my_settings_page = new TwitchConditionalSettings();
}

/**
 * Check if the Twitch Channel is currently live.
 *
 * @param string $username The channel name.
 * @param array  $twich_data an array of data with error information and channel status.
 */
function is_channel_live( $username ) {

	$twitch_data   = array();
	$client_id     = get_option( 'twitch_client_id' );
	$client_id     = $client_id['client_id'];
	$client_secret = get_option( 'twitch_client_id' );
	$client_secret = $client_secret['client_secret'];

	if ( strlen( $client_id ) < 1 ) {

		print 'No Client ID Specified!';
		return false;

	} elseif ( strlen( $client_secret ) < 1 ) {

		print 'No Client Secret Specified!';
		return false;

	} else {

		// all systems go. let's get an access token!
		$url = sprintf( 'https://id.twitch.tv/oauth2/token?client_id=%s&client_secret=%s&grant_type=client_credentials', $client_id, $client_secret );

		$response = wp_remote_post( $url );
		if ( ! is_wp_error( $response ) ) {

			$stream_obj = json_decode( $response['body'] );
			if ( $stream_obj ) {
				// grab the token. store the token. love the token.
				$token = $stream_obj->access_token;

				$url  = sprintf( 'https://api.twitch.tv/helix/streams?user_login=%s', $username );
				$args = array(
					'headers' => array(
						'client-id'     => $client_id,
						'Authorization' => 'Bearer ' . $token,
					),
				);

				$response = wp_remote_get( $url, $args );
				if ( ! is_wp_error( $response ) ) {
					$stream = json_decode( $response['body'] );

					if ( ! empty( $stream->data ) ) {
						$twitch_data['is_live'] = true;
					}
				} else {
					$twitch_data = array(
						'message' => __( 'Unable to access Twitch API', 'twitchcraft' ),
						'error'   => true,
					);
				}
			} else {
				$twitch_data = array(
					'message' => __( 'Unable to Authenticate.', 'twitchcraft' ),
					'error'   => true,
				);
			}
		} else {
			$twitch_data = array(
				'message' => __( 'Unable to access Twitch API.', 'twitchcraft' ),
				'error'   => true,
			);
		}
	}
	return $twitch_data;
}
