# Facebook toolkit

* Version: 1.0
* Author: Joseph Denne (me@josephdenne.com)
* Build Date: 19th November 2011
* Requirements: Symphony 2.2 or later

## Summary

This extension is designed to provide baseline functionality for Facebook applications and Facebook authenticating sites.

It provides an event that enables login functioanlity with Facebook, returning basic Facebook user object details:

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
Piggy backing Facebook for login functionality on a site removes the requirement for potentually complex CRM within a build as you do not have to concern yourself with concepts such as user account validation, sign up forms and profile management.

In addition, because Facebook accounts are socially connected, the accuracy of the data that it provides is higher, going some way to combating issues such as spam.

Using Facebook for authentication does not remove your ability to collect user data for CRM. You can easilly setup a section for the collection of the information that the Facebook user object provides, refreshing this each time a user returns to your site.

Note: this extension can happily live alongside the members extension, providing your users with a choice of login method

2.
As Facebook applications growin complexitity and scope, there is a growing need for a content management solution to enable content updates and user generated content moderation.

This extension enables Symphony in this context.

## Installation

** Note: The latest version can alway be grabbed with "git clone git@github.com:josephdenne/facebook_toolkit.git"

1. Rename the extension folder to 'facebook_toolkit' and upload it to your Symphony 'extensions' folder
2. Enable it by selecting "Facebook toolkit", choosing "Enable" from the with-selected menu, then clicking "Apply"

## Usage

You will need to have setup a companion Facebook applicaiton within Facebook. This is very straight forward and can be done here:

[https://developers.facebook.com/apps](https://developers.facebook.com/apps)

Once you have setup your application in Facebook:

1. Add your Facebook applicaiton ID and application secret to your preferences
2. Attach the event "Facebook login" to your page(s) or set it as a global event
3. Add the Facebook login button to your page or trigger the login event directly (exampels below)

### Facebook login

You will need to add the following to all pages that require a user to be able to login:

	<div id="fb-root"></div>
	<script>
		window.fbAsyncInit = function() {
			FB.init({
				appId: 'YOUR APPLICATION ID',
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

	<div class="fb-login-button" data-show-faces="false" data-width="200" data-max-rows="1"></div>

![Standard Facebook login button](http://josephdenne.com/workspace/images/screenshots/facebook-toolkit/standard-login-button.png)

You can change the text of this button by placing your own copy within the div:

	<div class="fb-login-button" data-show-faces="false" data-width="200" data-max-rows="1">Sign in with Facebook</div>

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

	<facebook logged-in="false" page-liked="false" />

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