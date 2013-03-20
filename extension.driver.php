<?php

	Class extension_facebook_toolkit extends Extension {

		public function getSubscribedDelegates() {
			return array(
				array(
					'page' => '/system/preferences/',
					'delegate' => 'AddCustomPreferenceFieldsets',
					'callback' => 'cbAppendPreferences'
				),

				array(
					'page' => '/system/preferences/',
					'delegate' => 'Save',
					'callback' => 'cbSavePreferences'
				),

				array(
					'page' => '/blueprints/events/new/',
					'delegate' => 'AppendEventFilter',
					'callback' => 'cbAddFilterToEventEditor'
				),

				array(
					'page' => '/blueprints/events/edit/',
					'delegate' => 'AppendEventFilter',
					'callback' => 'cbAddFilterToEventEditor'
				),

				array(
					'page' => '/blueprints/events/new/',
					'delegate' => 'AppendEventFilterDocumentation',
					'callback' => 'cbAppendEventFilterDocumentation'
				),

				array(
					'page' => '/blueprints/events/edit/',
					'delegate' => 'AppendEventFilterDocumentation',
					'callback' => 'cbAppendEventFilterDocumentation'
				),

				array(
					'page' => '/frontend/',
					'delegate' => 'FrontendParamsResolve',
					'callback' => 'appendAppParams'
				),
			);
		}

		public function install() {
			if (!file_exists(DOCROOT . '/extensions/facebook_toolkit/lib/facebook-php-sdk/src/facebook.php')) {

				if(isset(Administration::instance()->Page)) {

					Administration::instance()->Page->pageAlert(__('Could not find Facebook SDK at "extensions/facebook_toolkit/lib/facebook-php-sdk/src/facebook.php". See readme for more info.'));
				}
				return false;
			}
			return true;
		}

		public function uninstall() {

			Symphony::Configuration()->remove('facebook');
			return Symphony::Configuration()->write();
		}

		public function cbAppendPreferences($context) {

			$group = new XMLElement('fieldset');
			$group->setAttribute('class', 'settings');
			$group->appendChild(new XMLElement('legend', __('Facebook application details')));

			$div = new XMLElement('div');
			$div->setAttribute('class', 'group');

			$label = Widget::Label(__('Application ID'));
			$label->appendChild(Widget::Input('settings[facebook][application_id]', Symphony::Configuration()->get('application_id', 'facebook')));
			$div->appendChild($label);

			$label = Widget::Label(__('Application secret'));
			$label->appendChild(Widget::Input('settings[facebook][application_secret]', Symphony::Configuration()->get('application_secret', 'facebook')));
			$div->appendChild($label);
			$group->appendChild($div);

			$context['wrapper']->appendChild($group);
		}
		
		public function appendAppParams($context) {

			// Adds the app key to Symphony page params. 
			$context['params']['facebook-application-id'] = Symphony::Configuration()->get('application_id', 'facebook');

			// No reason to have the application secret output as part of page params, but leaving this here anyway. 
			//$context['params']['facebook-app-secret'] = Symphony::Configuration()->get('application_secret', 'facebook');
		}
	}