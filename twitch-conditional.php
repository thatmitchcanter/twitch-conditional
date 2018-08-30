<?php
/**
 * @twitch-conditional
 * Plugin Name:       Twitch Conditional
 * Description:       Allows a template tag to check a Twitch stream and display content is the streamer is live.
 * Version:           1.2.0
 * Author:            Mitch Canter
 * Author URI:        http://www.mitchcanter.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       twitch-conditional
 * Twitch IS live Template Tag
 *
 *
 *
 * <?php if (twitch_is_live('username')) { CONTENT } ; ?>
 * Displays content the channel IS live. Template Tag Usage
 */

function twitch_is_live( $twitchname = null) {

	$client_id = get_option('twitch_client_id');
	$client_id = $client_id['clientid'];

	if (strlen($client_id) < 1) {
		print "No Client ID Specified!";
		return false;
	} elseif ($twitchname == null) {
		print "No Twitch Name Specified!";
		return false;
	} else {

    $url = 'https://api.twitch.tv/kraken/streams/'.$twitchname.'?client_id='. $client_id;

		$args = array(
		    'sslverify'   => false,
		);

		$response = wp_remote_get($url, $args);
		if (!is_wp_error($response)) {

			$streamObj = json_decode($response['body']);

			if ( $streamObj->stream ) {
		    return true;
			} else {
				return false;
			}

		} else {
			print "Unable to access Twitch API.";
			return false;
		}

	}
}

/*
 * Twitch IS live Shortcode
 * [twitch_is_live twitchname='username']
 * Displays content the channel IS live. Shortcode Usage
 */

function twitch_is_live_shortcode( $atts = [], $content = null ) {
	$atts = array_change_key_case((array)$atts, CASE_LOWER);

	$twitch_is_live_atts = shortcode_atts(
		[
				'twitchname' => 'thatmitchcanter'
		], $atts);

	$client_id = get_option('twitch_client_id');
	$client_id = $client_id['clientid'];

	if (strlen($client_id) < 1) {
		print "No Client ID Specified!";
		return false;
	} elseif ($twitch_is_live_atts['twitchname'] == null) {
		print "No Twitch Name Specified!";
		return false;
	} else {

    $url = 'https://api.twitch.tv/kraken/streams/'.$twitch_is_live_atts['twitchname'].'?client_id='. $client_id;

		$args = array(
		    'sslverify'   => false,
		);

		$response = wp_remote_get($url, $args);
		if (!is_wp_error($response)) {

			$streamObj = json_decode($response['body']);

			if ( $streamObj->stream ) {
		    return $content;
			} else {
				return false;
			}

		} else {
			print "Unable to access Twitch API.";
			return false;
		}


	}

}

/*
 * Twitch IS NOT live Shortcode
 * [twitch_is_not_live twitchname='username']
 * Displays content the channel IS NOT live. Shortcode Usage
 */

function twitch_is_not_live_shortcode( $atts = [], $content = null ) {
	$atts = array_change_key_case((array)$atts, CASE_LOWER);

	$twitch_is_not_live_atts = shortcode_atts(
		[
				'twitchname' => 'thatmitchcanter'
		], $atts);

	$client_id = get_option('twitch_client_id');
	$client_id = $client_id['clientid'];

	if (strlen($client_id) < 1) {
		print "No Client ID Specified!";
		return false;
	} elseif ($twitch_is_not_live_atts['twitchname'] == null) {
		print "No Twitch Name Specified!";
		return false;
	} else {

    $url = 'https://api.twitch.tv/kraken/streams/'.$twitch_is_not_live_atts['twitchname'].'?client_id='. $client_id;

		$args = array(
		    'sslverify'   => false,
		);

		$response = wp_remote_get($url, $args);
		if (!is_wp_error($response)) {

			$streamObj = json_decode($response['body']);

			if ( !$streamObj->stream ) {
		    return $content;
			} else {
				return false;
			}

		} else {
			print "Unable to access Twitch API.";
			return false;
		}


	}

}

function twitch_shortcodes()
{
		add_shortcode('twitch_is_live', 'twitch_is_live_shortcode');
    add_shortcode('twitch_is_not_live', 'twitch_is_not_live_shortcode');
}

add_action('init', 'twitch_shortcodes');

/*
 * Twitch Object
 * Gathers the Twitch Object in a Variable if the user is live.
 */

 function twitch_object( $twitchname = null) {

 	$client_id = get_option('twitch_client_id');
 	$client_id = $client_id['clientid'];

 	if (strlen($client_id) < 1) {
 		print "No Client ID Specified!";
 		return false;
 	} elseif ($twitchname == null) {
 		print "No Twitch Name Specified!";
 		return false;
 	} else {

     $url = 'https://api.twitch.tv/kraken/streams/'.$twitchname.'?client_id='. $client_id;

 		$args = array(
 		    'sslverify'   => false,
 		);

 		$response = wp_remote_get($url, $args);
 		if (!is_wp_error($response)) {

 			$streamObj = json_decode($response['body']);

 			if ( $streamObj->stream ) {
 		    return $streamObj->stream;
 			} else {
 				return false;
 			}

 		} else {
 			print "Unable to access Twitch API.";
 			return false;
 		}

 	}
 }


/**
 * Options Panel
 */

class TwitchConditionalSettings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
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
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'twitch_client_id' );
        ?>
        <div class="wrap">
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
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
    public function page_init()
    {
        register_setting(
            'twitch_options', // Option group
            'twitch_client_id', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'twitch_section_id', // ID
            'Twitch Conditional Settings', // clientid
            array( $this, 'print_section_info' ), // Callback
            'twitch-conditional-settings' // Page
        );

        add_settings_field(
            'client_ID',
            'Client ID',
            array( $this, 'clientid_callback' ),
            'twitch-conditional-settings',
            'twitch_section_id'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();

        if( isset( $input['clientid'] ) )
            $new_input['clientid'] = sanitize_text_field( $input['clientid'] );

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Please visit <a href="https://www.twitch.tv/settings/connections">your Twitch "Connections" Settings page</a> and register a new Developer Application.  Once you have done this, enter your Client ID Below.  Twitch requires a client ID with any API Requests.';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function id_number_callback()
    {
        printf(
            '<input type="text" id="id_number" name="twitch_client_id[id_number]" value="%s" />',
            isset( $this->options['id_number'] ) ? esc_attr( $this->options['id_number']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function clientid_callback()
    {
        printf(
            '<input type="text" id="clientid" name="twitch_client_id[clientid]" value="%s" />',
            isset( $this->options['clientid'] ) ? esc_attr( $this->options['clientid']) : ''
        );
    }
}

if( is_admin() )
    $my_settings_page = new TwitchConditionalSettings();
