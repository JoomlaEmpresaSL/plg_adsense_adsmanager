<?php
/**
/*
 *      Adsense Plug-in for AdsManager
 *      @package Adsense Plug-in for AdsManager
 *      @version 0.8.3
 *      @author Joomla Empresa
 *      @copyright Copyright (C) 2012 Joomla Empresa. All rights reserved
 *      @license GNU/GPL v3 or later
 *      
 *      Contact us at info@joomlaempresa.com (http://www.joomlaempresa.es)
 *      
 *      This file is part of Adsense Plug-in for AdsManager.
 *      
 *          Adsense Plug-in for AdsManager is free software: you can redistribute it and/or modify
 *          it under the terms of the GNU General Public License as published by
 *          the Free Software Foundation, either version 3 of the License, or
 *          (at your option) any later version.
 *      
 *          Adsense Plug-in for AdsManager is distributed in the hope that it will be useful,
 *          but WITHOUT ANY WARRANTY; without even the implied warranty of
 *          MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *          GNU General Public License for more details.
 *      
 *          You should have received a copy of the GNU General Public License
 *          along with Adsense Plug-in for AdsManager.  If not, see <http://www.gnu.org/licenses/>.
 */
defined('_JEXEC') or die('Acesso restrito');

class AdsManagerAdsense {

	var $_db;
	var $tablaConfiguracion='#__adsmanager_fieldAdsense_conf';
	var $version = null;
	
	function getVersion()
	{
		if(version_compare(JVERSION, '2.5.0','ge')) {
			$data = JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_adsmanager'.DS.'aaa_adsmanager.xml') ? JInstaller::parseXMLInstallFile(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_adsmanager'.DS.'aaa_adsmanager.xml') : JInstaller::parseXMLInstallFile(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_adsmanager'.DS.'adsmanager.xml');
		}
		else {
		$data = JApplicationHelper::parseXMLInstallFile(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_adsmanager'.DS.'adsmanager.xml');
		}
		$this->version = substr($data['version'], 0, 3);
	}
	
	function getListDisplay($contentid,$field)
	{
		return AdsManagerAdsense::getDetailsDisplay($contentid,$field);
	}

	function getDetailsDisplay($contentid,$field)
	{
		$this->_db->setQuery("SELECT * FROM ".$this->tablaConfiguracion." WHERE fieldid = $field->fieldid");
		$conf =$this->_db->loadObject();
		$top=$conf->margen_top_adsense.'px';
		$right=$conf->margen_right_adsense.'px';
		$bottom=$conf->margen_bottom_adsense.'px';
		$left=$conf->margen_left_adsense.'px';
		$return="<div style='margin:$top $right $bottom $left'>";
		$return.=$conf->codigo;
		$return.="</div>";
		return $return;
	}

	function getFormDisplay($contentid,$field)
	{
		$name=$this->getFieldName().'_'.$field->fieldid;
		$return="<div id='$name' style='display:none'></div>";
		$return.="<script>document.getElementById('$name').parentNode.parentNode.style.display='none'</script>";
		return $return;
	}

	function onFormSave($contentid,$fieldid,$update)
	{
	}
	
	function onDelete($directory,$contentid = -1)
	{
	}
	
	function getEditFieldJavaScriptDisable()
	{
		$return = "elem=getObject('divAdsenseOptions');";
		$return .= "elem.style.visibility = 'hidden';";
		$return .= "elem.style.display = 'none';";
		$return .= "elem=getObject('codigo_adsense');";
		$return .= "elem.setAttribute('mosReq',0);";
		$return .= "elem=getObject('margen_top_adsense');";
		$return .= "elem.setAttribute('mosReq',0);";
		$return .= "elem=getObject('margen_right_adsense');";
		$return .= "elem.setAttribute('mosReq',0);";
		$return .= "elem=getObject('margen_bottom_adsense');";
		$return .= "elem.setAttribute('mosReq',0);";
		$return .= "elem=getObject('margen_left_adsense');";
		$return .= "elem.setAttribute('mosReq',0);";
		return $return; 
	}
	
	function getEditFieldJavaScriptActive()
	{
		$return = "disableAll();";
		$return .= "elem=getObject('divAdsenseOptions');";
		$return .= "elem.style.visibility = 'visible';";
		$return .= "elem.style.display = 'block';";
		$return .= "elem=getObject('codigo_adsense');";
		$return .= "elem.setAttribute('mosReq',1);";
		$return .= "elem=getObject('margen_top_adsense');";
		$return .= "elem.setAttribute('mosReq',1);";
		$return .= "elem=getObject('margen_right_adsense');";
		$return .= "elem.setAttribute('mosReq',1);";
		$return .= "elem=getObject('margen_bottom_adsense');";
		$return .= "elem.setAttribute('mosReq',1);";
		$return .= "elem=getObject('margen_left_adsense');";
		$return .= "elem.setAttribute('mosReq',1);";
		return $return; 
	}

	function getEditFieldOptions($fieldid)
	{
		$this->install();
		$this->_db->setQuery("SELECT * FROM ".$this->tablaConfiguracion." WHERE fieldid = '$fieldid'");
		$row = $this->_db->loadObject();
		
		$return = "<div id='divAdsenseOptions'>";
		$return .= "<table class='adminform'>";
		$return .= "<tr>";
		$return .= "<td width='20%'>Adsense Code</td>";
		$return .= "<td width='20%' align=left><textarea id='codigo_adsense' name='codigo_adsense' mosReq=1 mosLabel='Adsense Code' cols='40' rows='8' class='inputbox'>".@$row->codigo."</textarea></td>";
		$return .= "<td>&nbsp;</td>";
		$return .= "</tr>";
		$return .= "<tr>";
		$return .= "<td width='20%'>Margin Top</td>";
		$return .= "<td width='20%' align=left><input type='text' id='margen_top_adsense' name='margen_top_adsense' mosReq=1 mosLabel='Margin' class='inputbox' value='".@$row->margen_top_adsense."' /></td>";
		$return .= "<td>&nbsp;</td>";
		$return .= "</tr>";
		$return .= "<tr>";
		$return .= "<td width='20%'>Margin Right</td>";
		$return .= "<td width='20%' align=left><input type='text' id='margen_right_adsense' name='margen_right_adsense' mosReq=1 mosLabel='Margin' class='inputbox' value='".@$row->margen_right_adsense."' /></td>";
		$return .= "<td>&nbsp;</td>";
		$return .= "</tr>";
		$return .= "<tr>";
		$return .= "<td width='20%'>Margin Bottom</td>";
		$return .= "<td width='20%' align=left><input type='text' id='margen_bottom_adsense' name='margen_bottom_adsense' mosReq=1 mosLabel='Margin' class='inputbox' value='".@$row->margen_bottom_adsense."' /></td>";
		$return .= "<td>&nbsp;</td>";
		$return .= "</tr>";
		$return .= "<tr>";
		$return .= "<td width='20%'>Margin Left</td>";
		$return .= "<td width='20%' align=left><input type='text' id='margen_left_adsense' name='margen_left_adsense' mosReq=1 mosLabel='Margin' class='inputbox' value='".@$row->margen_left_adsense."' /></td>";
		$return .= "<td>&nbsp;</td>";
		$return .= "</tr>";
		$return .= "</table>";
		$return .="</div>";
		return $return;
	}

	function saveFieldOptions($fieldid)
	{
		$this->install();
	
		$codigo_adsense = JRequest::getVar("codigo_adsense",0,'post','STRING',JREQUEST_ALLOWRAW);
		$margen_top_adsense = JRequest::getInt("margen_top_adsense",0);
		$margen_right_adsense = JRequest::getInt("margen_right_adsense",0);
		$margen_bottom_adsense = JRequest::getInt("margen_bottom_adsense",0);
		$margen_left_adsense = JRequest::getInt("margen_left_adsense",0);
		$this->_db->setQuery("DELETE FROM ".$this->tablaConfiguracion." WHERE fieldid = ".(version_compare($this->version, '2.7', 'ge') ? (int)$fieldid->fieldid : (int)$fieldid));
		$this->_db->query();
		$this->_db->setQuery("INSERT INTO ".$this->tablaConfiguracion." VALUES (".(version_compare($this->version, '2.7', 'ge') ? (int)$fieldid->fieldid : (int)$fieldid).",'$codigo_adsense',$margen_top_adsense,$margen_right_adsense,$margen_bottom_adsense,$margen_left_adsense)");
		$this->_db->query();
		return;
	
	}
	
	function getFieldName()
	{
		return "Adsense";
	}
	
	function install()
	{
		$query = "CREATE TABLE IF NOT EXISTS `".$this->tablaConfiguracion."` ( ".
			  "`fieldid` int(10) unsigned default NULL, ".
			  "`codigo` TEXT default NULL, ".
			  "`margen_top_adsense` int(10) unsigned default '10', ".
			  "`margen_right_adsense` int(10) unsigned default '10', ".
			  "`margen_bottom_adsense` int(10) unsigned default '10', ".
			  "`margen_left_adsense` int(10) unsigned default '10', ".
			  "PRIMARY KEY  (`fieldid`) ".
			  "); ";
		$this->_db->setQuery($query);
		$this->_db->query();
	}
	function uninstall()
	{
		$query = "DROP TABLE `".$this->tablaConfiguracion."`";
		$this->_db->setQuery($query);
		$this->_db->query();
	}
	
	function __construct()
	{
		$this->getVersion();
		$db = JFactory::getDBO();
		$this->_db = $db;
	}
}

$plugins["adsense"] = new AdsManagerAdsense();
?>
