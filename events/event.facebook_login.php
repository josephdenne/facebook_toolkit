<?php

	if(!defined('__IN_SYMPHONY__')) die('<h2>Error</h2><p>You cannot directly access this file</p>');

	require_once(DOCROOT . '/extensions/facebook_toolkit/lib/facebook-php-sdk/src/facebook.php');

	Class eventFacebook_Login extends Event {

		public static function about() {

			$description = new XMLElement('p', 'This is an event that displays basic Facebook user object details (Facebook ID, first name, last name, username, location, gender, birthday and email address) if the person viewing the site have been authenticated via Facebook. It also returns the like status of a Facebook page, enabling like gating within Faebook tabs. It is designed as a baseline extention for Facebook applications and Facebook authenticating sites.');

			return array(
						 'name' => 'Facebook Login',
						 'author' => array('name' => 'Joseph Denne',
										   'website' => 'http://josephdenne.com',
										   'email' => 'me@josephdenne.com'),
						 'version' => '1.0',
						 'release-date' => '2011-11-19',
						 'trigger-condition' => 'A valid Facebook session');
		}

		public function load() {
			return $this->__trigger();
		}

		public static function documentation() {
			return "<p>This is an event that displays basic Facebook user object details (Facebook ID, first name, last name, username, location, gender, birthday and email address) if the person viewing the site have been authenticated via Facebook. It also returns the like status of a Facebook page, enabling like gating within Faebook tabs. It is designed as a baseline extention for Facebook applications and Facebook authenticating sites.</p>
					<p>Your applicaiton ID and application secret must be set wihtin the Symphony system preferences.</p>";
		}

		protected function __trigger() {

			// Create the Facebook application instance
			$facebook = new Facebook(array(
				'appId'  => Symphony::Configuration()->get('application_id', 'facebook'),
				'secret' => Symphony::Configuration()->get('application_secret', 'facebook'),
			));

			// Get the Facebook user ID
			$user = $facebook->getUser();

			if ($user) {
				try {
					// Proceed knowing you have a logged in user who's authenticated.
					$user_profile = $facebook->api('/me');
				}
				catch (FacebookApiException $e) {
					error_log($e);
					$user = null;
				}
			}

			// Like gate
			$signed_request = $facebook->getSignedRequest();

			function parsePageSignedRequest() {

				if (isset($_REQUEST['signed_request'])) {

					$encoded_sig = null;
					$payload = null;
					list($encoded_sig, $payload) = explode('.', $_REQUEST['signed_request'], 2);
					$sig = base64_decode(strtr($encoded_sig, '-_', '+/'));
					$data = json_decode(base64_decode(strtr($payload, '-_', '+/'), true));
					return $data;
				}
				return false;
			}

			if($signed_request = parsePageSignedRequest()) {
				$pageliked = (bool)$signed_request->page->liked;
			}
			
			$result = new XMLElement('facebook');
			
			$result->setAttribute('logged-in', ($user) ? 'true' : 'false');
			$result->setAttribute('page-liked', ($pageliked) ? 'true' : 'false');
			
			// User object tracking
			if ($user) {

				$result->setAttributeArray(array('id' => $user_profile['id']));

				$fields = array(
					'firstname' => new XMLElement('firstname', $user_profile['first_name']),
					'lastname' => new XMLElement('lastname', $user_profile['last_name']),
					'username' => new XMLElement('username', $user_profile['username']),
					'location' => new XMLElement('location', $user_profile['location']['name']),
					'gender' => new XMLElement('gender', $user_profile['gender']),
					'birthday' => new XMLElement('birthday', $user_profile['birthday']),
					'email' => new XMLElement('email', $user_profile['email'])
				);

				foreach($fields as $f) $result->appendChild($f);
			}
			
			return $result;
		}
	}

