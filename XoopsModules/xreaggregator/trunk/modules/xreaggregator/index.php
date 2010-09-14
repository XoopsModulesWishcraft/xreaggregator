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
include_once 'include/functions.php';

$module_handler =& xoops_gethandler('module');
$GLOBALS['xoopsModule'] = $module_handler->getByDirName('xreaggregator');

$cid = intval($_REQUEST['cid']);
$clman =& xoops_getmodulehandler('categories');
if ($GLOBALS['xoopsModuleConfig']['support_multisite'])
{
$criteria = new CriteriaCompo(new Criteria('domains', '%"'.urlencode(XOOPS_URL).'"%', 'LIKE'), 'OR');
$criteria->add(new Criteria('domains', '%"all"%', 'LIKE'), 'OR');
$criteria->add(new Criteria('display', 1), 'AND');
} else {
$criteria = new CriteriaCompo(new Criteria('display', 1));
}
$cls = $clman->getObjects($criteria);

if ($cid==0)
if (is_object($cls[0]))
	$cid = $cls[0]->getVar('cid');

$storytopic=0;
if ($GLOBALS['xoopsModuleConfig']['htaccess'])
{
	if(isset($_GET['id'])||isset($_GET['xreaggregator'])) {
		if (isset($_GET['xreaggregator'])){
			$sql = "SELECT xreaggregator_id FROM ".$GLOBALS['xoopsDB']->prefix('xreaggregator')." WHERE xreaggregator_name Like '".xoops_sef($_GET['xreaggregator'],'_')."'";
			$ret = $GLOBALS['xoopsDB']->query($sql);
			$row = $GLOBALS['xoopsDB']->fetchArray($ret);
			if ($row['topic_id']!=0){
				$storytopic=$row['topic_id'];
			}
		} else {
			$storytopic=intval($_GET['id']);
			$cid=intval($_GET['cid']);
			$sql = "SELECT a.xreaggregator_name as xreaggregator_name FROM ".$GLOBALS['xoopsDB']->prefix('xreaggregator')." a where a.xreaggregator_id = $storytopic";
			$ret = $GLOBALS['xoopsDB']->query($sql);
			$row = $GLOBALS['xoopsDB']->fetchArray($ret);
			$url = XOOPS_URL."/" . $GLOBALS['xoopsModuleConfig']['baseurl'] . "/".xoops_sef($row['xreaggregator_name'])."/$storytopic,$cid".$GLOBALS['xoopsModuleConfig']['endofurl'];
		}
	} else {
		if(isset($_GET['id'])) {
			$storytopic=intval($_GET['id']);
			$cid=intval($_GET['cid']);
			$sql = "SELECT a.xreaggregator_name as xreaggregator_name FROM ".$GLOBALS['xoopsDB']->prefix('xreaggregator')." a where a.xreaggregator_id = $storytopic";
			$ret = $GLOBALS['xoopsDB']->query($sql);
			$row = $GLOBALS['xoopsDB']->fetchArray($ret);
			$url = XOOPS_URL."/" . $GLOBALS['xoopsModuleConfig']['baseurl'] . "/".xoops_sef($row['xreaggregator_name'])."/$storytopic,$cid".$GLOBALS['xoopsModuleConfig']['endofurl'];
		} else {
			$url = XOOPS_URL."/" . $GLOBALS['xoopsModuleConfig']['baseurl'] . "/";
		}
	}

	if (strlen($url)>0) {
		if (strpos($url, $_SERVER['REQUEST_URI'])==0) {
			header( "HTTP/1.1 301 Moved Permanently" ); 
			header( "Location: ".$url);
			exit(0);
		}
	}

} else {
	$storytopic=intval($_GET['id']);
}


$mlman =& xoops_getmodulehandler('mashables');
$hlman =& xoops_getmodulehandler('xreaggregator');

$hlid = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($GLOBALS['xoopsModuleConfig']['support_multisite'])
{

$criteria = new CriteriaCompo(new Criteria('domains', '%"'.urlencode(XOOPS_URL).'"%', 'LIKE'));
$criteria->add(new Criteria('domains', '%|all|%', 'LIKE'), 'OR');
$criteria->add(new Criteria('display', 1), 'AND');

if ($cid<>0)
	$criteria->add(new Criteria('cid', $cid), 'AND');
	$mashables =& $mlman->getObjects($criteria);
} else {
	$criteria =& new CriteriaCompo(new Criteria('display', 1), 'AND');
	if ($cid<>0)
		$criteria->add(new Criteria('cid', $cid));
	$mashables =& $mlman->getObjects($criteria);
}

$xoopsOption['template_main'] = 'xreaggregator_index.html';

include XOOPS_ROOT_PATH.'/header.php';

$criteria =& new CriteriaCompo(new Criteria('xreaggregator_display', 1), 'AND');
if ($cid<>0)
	$criteria->add(new Criteria('xreaggregator_cid', $cid));
if ($GLOBALS['xoopsModuleConfig']['support_multisite'])
{
	$criteria->add(new Criteria('xreaggregator_domains', '%'.urlencode(XOOPS_URL).'%', 'LIKE'), 'OR');
	$criteria->add(new Criteria('xreaggregator_domains', '%|all|%', 'LIKE'), 'OR');
}

$xreaggregators =& $hlman->getObjects($criteria);
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
			if ($GLOBALS['xoopsConfig']['debug_mode'] == 2) {
				$xoopsTpl->assign('xreaggregator', '<p>'.sprintf(_XAL_FAILGET, $xreaggregator->getVar('xreaggregator_name')).'<br />'.$renderer->getErrors().'</p>');
			}
		} else {
			$xoopsTpl->assign('xreaggregator', $renderer->getFeed());
		}
	}
}

foreach ($cls as $cl) {
	if ($cl->getVar('cid')==$cid)
		$xoopsTpl->append('categories', array('id' => 'current', 'name' => $cl->getVar('name'), 'cid' => $cl->getVar('cid')));
	else
		$xoopsTpl->append('categories', array('id' => '', 'name' => $cl->getVar('name'), 'cid' => $cl->getVar('cid'))); 
}

$xoopsTpl->assign('cid', $cid);
$xoopsTpl->assign('feed_img', XOOPS_URL."/modules/".$GLOBALS['xoopsModule']->getVar('dirname')."/images/rss.gif");
$xoopsTpl->assign('feed_url', XOOPS_URL."/modules/".$GLOBALS['xoopsModule']->getVar('dirname')."/feed.php?id=$hlid");

include XOOPS_ROOT_PATH.'/footer.php';

?>