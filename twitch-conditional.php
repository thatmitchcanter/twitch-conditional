<?php
/**
 * @twitch-conditional
 * Plugin Name:       Twitch Conditional
 * Description:       Allows a template tag to check a Twitch stream and display content is the streamer is live.
 * Version:           1.0.0
 * Author:            Mitch Canter
 * Author URI:        http://www.mitchcanter.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       twitch-conditional
 */

function twitch_is_live( $twitchname = null ) {

	if ($twitchname == null) {
		return false;
	} else {
		$twitchlive = json_decode(@file_get_contents('https://api.twitch.tv/kraken/streams/'.$twitchname), true);
		$twitchonline = $twitchlive["stream"];

		if ( $twitchonline ) {
			return true;
		} else {
			return false;
		}		
	}
}