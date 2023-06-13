<?php
/* ----------------------------------------------------------------------
 * attributevaluesBacklinkPlugin.php : 
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2010 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * ----------------------------------------------------------------------
 */
 
	class attributevaluesBacklinkPlugin extends BaseApplicationPlugin {
		# -------------------------------------------------------
		protected $description = 'attributevaluesBacklink';
		# -------------------------------------------------------
		private $opo_config;
		private $ops_plugin_path;
		# -------------------------------------------------------
		public function __construct($ps_plugin_path) {
			$this->ops_plugin_path = $ps_plugin_path;
			$this->description = _t('attributevaluesBacklink');
			parent::__construct();
			$conf_file = $ps_plugin_path.'/conf/attributevaluesBacklink.conf';
			$this->opo_config = Configuration::load($conf_file);
			
			// Note : simple import from conf
			// $this->opo_config->getAssoc('associative_array')
		}
		# -------------------------------------------------------
		/**
		 * Override checkStatus() to return true - the statisticsViewerPlugin always initializes ok... (part to complete)
		 */
		public function checkStatus() {
			return array(
				'description' => $this->getDescription(),
				'errors' => array(),
				'warnings' => array(),
				'available' => ((bool)$this->opo_config->get('enabled'))
			);
		}
		# -------------------------------------------------------
		/**
		 * Insert activity menu
		 */	 
		public function hookRenderSideNav($pa_menu_bar) {
			//print var_export($pa_menu_bar, true);
			//die();
			$vs_module = $this->getRequest()->getModulePath();
			if(strpos($vs_module, 'editor/') === false) {
				return $pa_menu_bar;
			}
			$vs_controller = $this->getRequest()->getController();
			switch($vs_controller) {
				case "StorageLocationEditor":
					$vs_id_name = "location_id";
					break;
				case "ObjectEditor":
				defaut:
					$vs_id_name = "object_id";
					break;
			}
			$pa_menu_bar['attributevaluesBacklink'] = array(
				'displayName' => 'Références',
				'default' => array(
					'module' => 'attributevaluesBacklink',
					'controller' => 'Display',
					'action' => 'Index'
				), 
				'requires' => array(),
				'useActionInPath' => '1',
				'parameters' => array('parameter:'.$vs_id_name => '',),
				'hideIfNoAccess' => '1'
			);
			
			return $pa_menu_bar;
		}
		# -------------------------------------------------------
		/**
		 * Add plugin user actions
		 */
		static function getRoleActionList() {
			return array(
				'can_use_attributevaluesBacklink' => array(
						'label' => _t('Can use attributevaluesBacklink plugin'),
						'description' => _t('Can use attributevaluesBacklink plugin')
					)
			);
		}
		
	}
?>
