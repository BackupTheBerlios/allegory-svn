<?php

#
#	Users class
#

class KSettings {

	var $te;
	var $co;
	var $ca;
	
	function connect() {
		$db = new SettingsStorage('settings');
		return $db;
		}
		
	function getTemplates() {
		$db = KSettings::connect();
		$templates = $db->settings['templates'];
		$this->te = $templates;
		return $templates;
		}
		
	function getConfig() {
		$db = KSettings::connect();
		$configuration = $db->settings['configuration'];
		$this->co = $configuration;
		return $configuration;
		}
		
	function getCats() {
		$db = KSettings::connect();
		$categories = $db->settings['categories'];
		$this->ca = $categories;
		return $categories;
		}
	
}
?>