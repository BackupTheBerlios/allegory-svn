<?php

#
#	Users class
#

class KSettings {

	var $te;
	var $co;
	var $ca;
	var $unique;
	
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
		$this->unique = $configuration[general][uniquekey];
		return $configuration;
		}
		
	function getCats() {
		$db = KSettings::connect();
		$categories = $db->settings['categories'];
		$this->ca = $categories;
		return $categories;
		}
	
	
	
	function saveConfig($where, $what, $sub=false) {
		$db = KSettings::connect();
		$db->settings['configuration'][$where] = $what;
		$db->save();
		return true;
		}
}
?>