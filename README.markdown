# Facebook toolkit

* Version: 1.1
* Author: Joseph Denne (mail@josephdenne.com)
* Build Date: 20 March 2013
* Requirements: Symphony 2.2 or later

## Summary

This extension is designed to provide baseline functionality for Facebook applications and Facebook authenticating sites.

It provides an event that enables login functionality with Facebook, returning basic Facebook user object details:

- Facebook ID
- First name
- Last name
- Username
- Location
- Gender
- Birthday
- Email address

In addition to this it also returns the like status of a Facebook page. This enables the setup of like gating within Symphony for Facebook tabs.

### Roadmap

Additional functionality including pre-defined Facebook data lookups such as Friend lists and Facebook event triggers such as post to wall will be added in time.

## Context

There are two main uses for this extension:

1. The provision of "Login with Facebook" functionality on a site
2. The provision of a CMS backed Facebook application under facebook.com

1.
Piggy backing Facebook for login functionality on a site removes the requirement for potentially complex CRM within a build as you do not have to concern yourself with concepts such as user account validation, sign up forms and profile management.

In addition, because Facebook accounts are socially connected, the accuracy of the data that it provides is higher, going some way to combating issues such as spam.

Using Facebook for authentication does not remove your ability to collect user data for CRM. You can easily setup a section for the collection of the information that the Facebook user object provides, refreshing this each time a user returns to your site.

Note: this extension can happily live alongside the members extension, providing your users with a choice of login method

2.
As Facebook applications grow in complexity and scope, there is a growing need for a content management solution to enable content updates and user generated content moderation.

This extension enables Symphony in this context.

## Installation

1. Rename the extension folder to 'facebook_toolkit' and upload it to your Symphony 'extensions' folder
2. Enable it by selecting "Facebook toolkit", choosing "Enable" from the with-selected menu, then clicking "Apply"

If you get an error stating "Could not find Facebook SDK" you need to make sure the SDK exists in the extensions/facebook_toolkit/lib/facebook-php-sdk folder. If you installed the extension as a git submodule, you need to initialise and update submodules for the extension, by executing `git submodule update --init --recursive` from the root of your repo. 

Otherwise, download the latest version of the SDK from [https://github.com/facebook/facebook-php-sdk] and place it in the `extensions/facebook_toolkit/lib` directory. 

## Usage

You will need to have setup a companion Facebook application within Facebook. This is very straight forward and can be done here:

[https://developers.facebook.com/apps](https://developers.facebook.com/apps)

Once you have setup your application in Facebook:

1. Add your Facebook application ID and application secret to your preferences
2. Attach the event "Facebook login" to your page(s) or set it as a global event
3. Add the Facebook login button to your page or trigger the login event directly (examples below)

### Facebook login

You will need to add the following to all pages that require a user to be able to login:

	<div id="fb-root"></div>
	<script>
		window.fbAsyncInit = function() {
			FB.init({
				appId: '<xsl:value-of select="/data/params/facebook-application-id" />',
				cookie: true,
				xfbml: true,
				oauth: true
			});
			FB.Event.subscribe('auth.login', function(response) {
				// Called when a user logs in
				// Use this to redirect to another page (window.location= "/ano-page/";) or to refresh the current page to change login states
			});
		};
		(function() {
			var e = document.createElement('script'); e.async = true;
			e.src = document.location.protocol +
			  '//connect.facebook.net/en_US/all.js';
			document.getElementById('fb-root').appendChild(e);
		}());
		function login(){
			FB.login(null,{scope:'email,user_birthday'});
			return false;
		}
	</script>

There are two options for the manifestation of the login button:

1. Standard Facebook login button
2. Facebook login event

1.

	<div class="fb-login-button" perms="email,user_birthday" data-show-faces="false" data-width="200" data-max-rows="1"></div>

![Standard Facebook login button](http://josephdenne.com/workspace/images/screenshots/facebook-toolkit/standard-login-button.png)

You can change the text of this button by placing your own copy within the div:

	<div class="fb-login-button" perms="email,user_birthday" data-show-faces="false" data-width="200" data-max-rows="1">Sign in with Facebook</div>

![Modified text within the Facebook login button](http://josephdenne.com/workspace/images/screenshots/facebook-toolkit/modified-login-button.png)

Read more here: [http://developers.facebook.com/docs/reference/plugins/login/](http://developers.facebook.com/docs/reference/plugins/login/)

2.

This allows you to create your own styling for login buttons or to apply login to a link or other event:

	login()

For example:

	<a class="login" onclick="login()">Sign in with Facebook</a>

### Facebook like gate (for application tabs)

	<xsl:choose>
		<xsl:when test="/data/events/facebook/@page-liked = 'false'">
			Shown to the user when the page is not liked
		</xsl:when>
		<xsl:otherwise>
			Shown to the user when the page is liked
		</xsl:otherwise>
	</xsl:choose>

## XML output

### Non logged in user

Note that page can be liked and tracked without requiring login. 
No data about the user can be retrieved though.

	<facebook logged-in="false" page-liked="true" />

### Logged in user

	<facebook logged-in="true" page-liked="false" id="699540393">
		<firstname>Joseph</firstname>
		<lastname>Denne</lastname>
		<username>joseph.denne</username>
		<location>London, United Kingdom</location>
		<gender>male</gender>
		<birthday>11/28/1979</birthday>
		<email>joseph.denne@airlock.com</email>
	</facebook>

[CHANGES]

1.1

- Symphony 2.3 compatibility
- Adds Facebook API key for access via frontend page params
- Updates Facebook PHP SDK to 3.2.0, and includes it as a submodule
- Documentation updates