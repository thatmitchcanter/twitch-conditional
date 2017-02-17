# Twitch Conditional

A WordPress plugin that allows a user to conditionally load content based on whether or not a Twitch streamer is broadcasting live.

## About This Plugin

This plugin has a very simple task: to check Twitch to see if a streamer is broadcasting live (not hosting, actual broadcasting).  Depending on whether or not the streamer is online, different parts of content can be displayed.

## How It Works

I like WordPress. I like the way it does conditional tags, and I wanted my plugin to mimic that.

    <?php if (twitch_is_live('username')) {
        // CODE FOR IF USER IS ONLINE
    } else {
        // CODE FOR IF USER IS NOT STREAMING
    } ?>
    
That's it. Simply change the username mentioned in the conditional. If a username is not set, the plugin will return an error message.

## Client ID Update

Twitch now requires a Client ID to be sent with all JSON requests.  That means everyone that tries to use this plugin needs to go to https://www.twitch.tv/settings/connections to generate a new 'Developer Application'.  Once you've done that, however, and entered it into the plugin's options page, all of the requests will finally begin to work again.

## Usage

Add the above sample conditional to your template in order to utilize its functionality.  You can also use:

    <?php if (twitch_is_live('username')) :
        // Code Goes Here
    endif; ?>    

### Sample Code

    <?php if (twitch_is_live('username')) { ?>
        <p><a href="##twitch_url##">Currently Streaming Live!</a></p>
    <?php } else { ?>
        <p>This Twitch user is currently offline!</p>
    <?php } ?>

### Todo

* Shortcodes for in-content use