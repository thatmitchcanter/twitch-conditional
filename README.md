# Twitch Conditional

A WordPress plugin that allows a user to conditionally load content based on whether or not a Twitch streamer is broadcasting live.

## About This Plugin

This plugin has a very simple task: to check Twitch to see if a streamer is broadcasting live (not hosting, actual broadcasting).  Depending on whether or not the streamer is online, different parts of content can be displayed via shortcodes.

## Shortcode Usage

	[twitch_live twitchname='username'] ... [/twitch_live]

This will show content if the streamer is live.

	[twitch_offline twitchname='username'] ... [/twitch_offline]

This will show content if the streamer is NOT live.

## Client ID + Secret Key Update

Twitch now requires a Client ID AND Secret ID to be sent with all JSON requests through OAuth Authentication.  That means everyone that tries to use this plugin needs to go to https://dev.twitch.tv/console/apps to generate a new 'Application'.  Once you've done that and entered the keys (client and secret) into the plugin's options page, all of the requests will finally begin to work again.

### Todo

* Re-Do of template tag with more options.
* Stream Details Available in Shortcodes or Template Tags (Title, Game, Viewers, Etc).
* Default Content with designs (Cards, Banners, etc) to provide easier use of the plugin.

### Changelog

1.0.0 - Initial Release

1.1.0 - Swapped file_get_contents for cURL, added API Key and Documentation.

1.2.0 - Swapped cURL calls for wp_remote_get. Added Shortcodes for [twitch_is_live] and [twitch_is_not_live]

2.0 - Updated for Twitch New Api. Renamed Shortcodes for [twitch_live] and [twitch_offline]. Removed Twitch Object Template Tag for revamping in future release.