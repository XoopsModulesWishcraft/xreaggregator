<?php
// $Id: index.php,v 1.6 2005/06/26 15:38:28 mithyt2 Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Simon Roberts (AKA wishcraft)                                     //
// URL: http://www.chronolabs.org.au                                         //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

include '../../mainfile.php';
global $xoopsModuleConfig, $xoopsDB;
global $xoopsModule;
$module_handler =& xoops_gethandler('module');
$xoopsModule = $module_handler->getByDirName('xreaggregator');

if (!function_exists('xoops_sef'))
{
	function xoops_sef($datab, $char ='-')
	{
		$replacement_chars = array();
		$accepted = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","m","o","p","q",
				 "r","s","t","u","v","w","x","y","z","0","9","8","7","6","5","4","3","2","1");
		for($i=0;$i<256;$i++){
			if (!in_array(strtolower(chr($i)),$accepted))
				$replacement_chars[] = chr($i);
		}
		$return_data = (str_replace($replacement_chars,$char,$datab));
		#print $return_data . "<BR><BR>";
		return($return_data);
	
	}
}

$storytopic=0;
if ($xoopsModuleConfig['htaccess'])
{
	if(isset($_GET['id'])||isset($_GET['xreaggregator'])) {
		if (isset($_GET['xreaggregator'])){
			$sql = "SELECT xreaggregator_id FROM ".$xoopsDB->prefix('xreaggregator')." WHERE xreaggregator_name Like '".xoops_sef($_GET['xreaggregator'],'_')."'";
			$ret = $xoopsDB->query($sql);
			$row = $xoopsDB->fetchArray($ret);
			if ($row['topic_id']!=0){
				$storytopic=$row['topic_id'];
			}
		} else {
			$storytopic=intval($_GET['id']);
	
			$sql = "SELECT a.xreaggregator_name FROM ".$xoopsDB->prefix('xreaggregator')." a where a.xreaggregator_id = $storytopic";
			$ret = $xoopsDB->query($sql);
			$row = $xoopsDB->fetchArray($ret);
		
			header( "HTTP/1.1 301 Moved Permanently" ); 
			header( "Location: ".XOOPS_URL."/headlines/".xoops_sef($row['xreaggregator_name'])."/$storytopic");
	
		}
	} else {
		if(isset($_GET['id'])) {
			$storytopic=intval($_GET['id']);
	
			$sql = "SELECT a.xreaggregator_name FROM ".$xoopsDB->prefix('xreaggregator')." a where a.xreaggregator_id = $storytopic";
			$ret = $xoopsDB->query($sql);
			$row = $xoopsDB->fetchArray($ret);
		
			header( "HTTP/1.1 301 Moved Permanently" ); 
			header( "Location: ".XOOPS_URL."/headlines/".xoops_sef($row['xreaggregator_name'])."/$storytopic");
	
		} else {
	
			if (strpos(' '.$_SERVER['REQUEST_URI'],'odules/')>0){
				header( "HTTP/1.1 301 Moved Permanently" ); 
				header( "Location: ".XOOPS_URL."/headlines/");
			}
		}
	}
} else {
	$storytopic=intval($_GET['id']);
}

include 'include/functions.php';
$mlman =& xoops_getmodulehandler('mashables');
$hlman =& xoops_getmodulehandler('xreaggregator');
$hlid = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (defined('XOOPS_DOMAIN_ID')&&class_exists('XoopsFormCheckBoxDomains'))
{
	$criteria = new CriteriaCompo(new Criteria('domains', '%'.urlencode(XOOPS_URL).'%', 'LIKE'));
	$criteria->add(new Criteria('domains', '%|all|%', 'LIKE'), 'OR');
	$mashables =& $mlman->getObjects($criteria);
} else {
	$mashables =& $mlman->getObjects();
}

$xoopsOption['template_main'] = 'xreaggregator_index.html';
include XOOPS_ROOT_PATH.'/header.php';
if (!defined('XOOPS_DOMAIN_ID'))
{
	$xreaggregators =& $hlman->getObjects(new Criteria('xreaggregator_display', 1));
} else {
	$criteria= new CriteriaCompo(new Criteria('xreaggregator_domains', '%'.urlencode(XOOPS_URL).'%', 'LIKE'), 'OR');
	$criteria->add(new Criteria('xreaggregator_domains', '%|all|%', 'LIKE'), 'OR');
	$criteria_b = new CriteriaCompo(new Criteria('xreaggregator_display', 1), 'AND');
	$criteria->add($criteria_b);
	$xreaggregators =& $hlman->getObjects($criteria);
}
$count = count($mashables);
for ($i = 0; $i < $count; $i++) {
	$xoopsTpl->append('feed_mash', array('id' => $mashables[$i]->getVar('id'), 'name' => $mashables[$i]->getVar('name')));
}
$xoopsTpl->assign('lang_xreaggregators_mashables', _XAL_XREAGGREGATORS_MASHABLES);

$count = count($xreaggregators);
for ($i = 0; $i < $count; $i++) {
	$xoopsTpl->append('feed_sites', array('id' => $xreaggregators[$i]->getVar('xreaggregator_id'), 'name' => $xreaggregators[$i]->getVar('xreaggregator_name')));
}
$xoopsTpl->assign('lang_xreaggregators', _XAL_XREAGGREGATORS);

if ($hlid == 0&&is_object($xreaggregators[0])) {
	$hlid = $xreaggregators[0]->getVar('xreaggregator_id');
}
if ($hlid > 0) {
	$xreaggregator =& $hlman->get($hlid);
	$xoopsTpl->assign('xoops_pagetitle',$xreaggregator->getVar('xreaggregator_name'));
	if (is_object($xreaggregator)) {
		$renderer =& xreaggregator_getrenderer($xreaggregator);
		if (!$renderer->renderFeed()) {
			if ($xoopsConfig['debug_mode'] == 2) {
				$xoopsTpl->assign('xreaggregator', '<p>'.sprintf(_XAL_FAILGET, $xreaggregator->getVar('xreaggregator_name')).'<br />'.$renderer->getErrors().'</p>');
			}
		} else {
			$xoopsTpl->assign('xreaggregator', $renderer->getFeed());
		}
	}
}

$xoopsTpl->assign('feed_img', XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/rss.gif");
$xoopsTpl->assign('feed_url', XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/feed.php?id=$hlid");
include XOOPS_ROOT_PATH.'/footer.php';
?>