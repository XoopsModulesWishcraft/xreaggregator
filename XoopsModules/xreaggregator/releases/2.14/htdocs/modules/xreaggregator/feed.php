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

	function vsort($array, $id="id", $sort_ascending=true, $is_object_array = false) {
        $temp_array = array();
        while(count($array)>0) {
            $lowest_id = 0;
            $index=0;
            if($is_object_array){
                foreach ($array as $item) {
                    if (isset($item->$id)) {
                                        if ($array[$lowest_id]->$id) {
                        if ($item->$id<$array[$lowest_id]->$id) {
                            $lowest_id = $index;
                        }
                        }
                                    }
                    $index++;
                }
            }else{
                foreach ($array as $item) {
                    if (isset($item[$id])) {
                        if ($array[$lowest_id][$id]) {
                        if ($item[$id]<$array[$lowest_id][$id]) {
                            $lowest_id = $index;
                        }
                        }
                                    }
                    $index++;
                }                              
            }
            $temp_array[] = $array[$lowest_id];
            $array = array_merge(array_slice($array, 0,$lowest_id), array_slice($array, $lowest_id+1));
        }
                if ($sort_ascending) {
            return $temp_array;
                } else {
                    return array_reverse($temp_array);
                }
    }


include '../../mainfile.php';
include_once XOOPS_ROOT_PATH.'/class/template.php';
global $xoopsModuleConfig, $xoopsModule, $xoopsDB;
error_reporting(E_ALL);
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
	if(isset($_GET['mid'])||isset($_GET['mashable'])) {
		if (isset($_GET['mashable'])){
			$sql = "SELECT id FROM ".$xoopsDB->prefix('xreaggregator_mashables')." WHERE name Like '".xoops_sef($_GET['mashables'],'_')."'";
			$ret = $xoopsDB->query($sql);
			$row = $xoopsDB->fetchArray($ret);
			if ($row['id']!=0){
				$mashtopic=$row['id'];
			}
		} else {
			$mashtopic=intval($_GET['id']);
	
			$sql = "SELECT a.name FROM ".$xoopsDB->prefix('xreaggregator_mashables')." a where a.id = $mashtopic";
			$ret = $xoopsDB->query($sql);
			$row = $xoopsDB->fetchArray($ret);
			if (strpos($_SERVER['REQUEST_URI'],'odules/')>0)
			{

				header( "HTTP/1.1 301 Moved Permanently" ); 
				header( "Location: ".XOOPS_URL."/headlines/".xoops_sef($row['name'])."/mashable,$mashtopic.rss");
			}	
		}
	} elseif(isset($_GET['id'])||isset($_GET['xreaggregator'])) {
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
			if (strpos($_SERVER['REQUEST_URI'],'odules/')>0)
			{

				header( "HTTP/1.1 301 Moved Permanently" ); 
				header( "Location: ".XOOPS_URL."/headlines/".xoops_sef($row['xreaggregator_name'])."/feed,$storytopic.rss");
			}	
		}
	} else {
		if(isset($_GET['id'])) {
			$storytopic=intval($_GET['id']);
	
			$sql = "SELECT a.xreaggregator_name FROM ".$xoopsDB->prefix('xreaggregator')." a where a.xreaggregator_id = $storytopic";
			$ret = $xoopsDB->query($sql);
			$row = $xoopsDB->fetchArray($ret);
			if (strpos($_SERVER['REQUEST_URI'],'odules/')>0)
			{
		
				header( "HTTP/1.1 301 Moved Permanently" ); 
				if (strlen($row['xreaggregator_name'])>0)
				{
					header( "Location: ".XOOPS_URL."/headlines/".xoops_sef($row['xreaggregator_name'])."/feed,$storytopic.rss");
				} else {
					header( "Location: ".XOOPS_URL."/headlines/feed,$storytopic.rss");
				}
			}
			
		} else {
			if (strpos($_SERVER['REQUEST_URI'],'odules/')>0)
			{
				if (strpos(' '.$_SERVER['REQUEST_URI'],'odules/')>0){
					header( "HTTP/1.1 301 Moved Permanently" ); 
					header( "Location: ".XOOPS_URL."/headlines/feed.rss");
				}
			}
		}

	}
} else {
	$mashtopic=intval($_GET['mid']);
	$storytopic=intval($_GET['id']);
}

$tpl = new XoopsTpl();
$tpl->xoops_setCaching($xoopsModuleConfig['docache']);
$tpl->xoops_setCacheTime($xoopsModuleConfig['rss_cachetime']*60);
if (isset($_GET['ie']))
{
	$ie = true;
} else {
	$ie = false;
}


$compile_id = $storytopic.$ie;
$xoopsCachedTemplateId = 'rss_'.$mashtopic.'_'.$storytopic.'_'.$xoopsModule->getVar('dirname').'|'.md5(str_replace(XOOPS_URL, '', $GLOBALS['xoopsRequestUri']));
if (!$tpl->is_cached('db:xreaggregator_rss.html', $xoopsCachedTemplateId, $compile_id)) {

	include 'include/functions.php';
	$hlman =& xoops_getmodulehandler('xreaggregator');
	$hlid = $storytopic;
	if ($hlid > 0) {
		$xreaggregator =& $hlman->get($hlid);
		if (is_object($xreaggregator)) {
			$renderer =& xreaggregator_getrenderer($xreaggregator);
			$rss = $renderer->renderRSS();
			$tpl->assign('items', $rss['items']);
			$tpl->assign('channel', $rss['channel']);
			$tpl->assign('images', $rss['images']);						
		}
		
	} else {
		global $xoopsDB, $xoopsModuleConfig;
		if (!defined('XOOPS_DOMAIN_ID'))
		{
			$criteria= new CriteriaCompo(new Criteria('xreaggregator_display', 1));
		} else {
			$criteria= new CriteriaCompo(new Criteria('xreaggregator_domains', '%'.XOOPS_URL.'%', 'LIKE'), 'OR');
			$criteria->add(new Criteria('xreaggregator_domains', '%|all%', 'LIKE'), 'OR');
			$criteria_b = new CriteriaCompo(new Criteria('xreaggregator_display', 1), 'AND');
			$criteria->add($criteria_b);
		}
		if ($mashtopic!=0)
		{
			$mlman =& xoops_getmodulehandler('mashables');
			$ml =& $mlman->get($mashtopic);
			$criteria->add(new Criteria('xreaggregator_id', "('".implode("','", $ml->getVar('groups'))."')", 'IN'), 'AND');
		}

		$xreaggregators =& $hlman->getObjects($criteria);
		$items = array();
		foreach($xreaggregators as $xreaggregator)
		{
		
			if (is_object($xreaggregator)) {
				$renderer =& xreaggregator_getrenderer($xreaggregator);
				$rss = $renderer->renderRSS();
				if ($mashtopic!=0)
				{
					$max = floor(10 / (count($ml->getVar('groups'))))+1;
				} elseif (count($rss['items'])>$xoopsModuleConfig['snoop_quantity'])
				{
					$max = ($xoopsModuleConfig['snoop_quantity']==0)?2:$xoopsModuleConfig['snoop_quantity'];
				} else {
					$max = sizeof($rss['items']);
				}				
				for ($i=0;$i<$max;$i++)
				{
					$items[$j] = $rss['items'][$i];
					$j++;
				}
			}	
		}


		if (is_a($ml, 'xreaggregatorMashables'))
			$channel['title'] = $xoopsConfig['sitename'].' :: '.$ml->getVar('name').' :: RSS Mashable';
		else
			$channel['title'] = $xoopsConfig['sitename'].' :: RSS Re-aggregation';
		$channel['link'] = XOOPS_URL;
		$channel['description'] = $xoopsConfig['slogan'];
		$channel['lastbuilddate'] = gmdate("D, d M Y H:i:s", time())." UTC";		
		$channel['category'] = "Feed Snoop";
		$channel['managingEditor'] = $xoopsConfig['adminmail'];
		$channel['webMaster'] = $xoopsConfig['adminmail'];		
		
		$image['link'] = XOOPS_URL;
		$image['url'] = XOOPS_URL."/images/logo.gif";
		$image['title'] = $xoopsConfig['sitename'].' :: RSS Re-aggregation';
		
		$items = vsort($items, "pubdate", true, true);
		$tpl->assign('items', $items);
		$tpl->assign('channel', $channel);
		$tpl->assign('images', $image);						
	}
	$tpl->assign('ie', $ie);							
}

$charset = !empty($xoopsModuleConfig['rss_utf8'])?$xoopsModuleConfig['rss_utf8']:'UTF-8';
header ('Content-Type:text/xml; charset='.$charset);
$tpl->display('db:xreaggregator_rss.html', $xoopsCachedTemplateId, $compile_id);
?>