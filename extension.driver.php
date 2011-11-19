<?php

	Class extension_facebook_toolkit extends Extension {

		public function about(){
			return array('name' => 'Facebook toolkit',
						 'version' => '1.0 beta',
						 'release-date' => '2011-11-19',
						 'author' => array('name' => 'Joseph Denne',
										   'website' => 'http://josephdenne.com/',
										   'email' => 'me@josephdenne.com')
				 		);
		}

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
			);
		}

		public function uninstall() {
			Symphony::Configuration()->remove('facebook');
			$this->_Parent->saveConfig();
		}

		public function cbAppendPreferences($context){

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
	}

