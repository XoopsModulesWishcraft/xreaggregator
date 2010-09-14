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
error_reporting(0);
$storytopic=0;
if ($GLOBALS['xoopsModuleConfig']['htaccess'])
{
	if(isset($_GET['mid'])||isset($_GET['mashable'])) {
		if (isset($_GET['mashable'])){
			$sql = "SELECT id FROM ".$GLOBALS['xoopsDB']->prefix('xreaggregator_mashables')." WHERE name Like '".xoops_sef($_GET['mashables'],'_')."'";
			$ret = $GLOBALS['xoopsDB']->query($sql);
			$row = $GLOBALS['xoopsDB']->fetchArray($ret);
			if ($row['id']!=0){
				$mashtopic=$row['id'];
			}
		} else {
			$mashtopic=intval($_GET['id']);
	
			$sql = "SELECT a.name as name FROM ".$GLOBALS['xoopsDB']->prefix('xreaggregator_mashables')." a where a.id = $mashtopic";
			$ret = $GLOBALS['xoopsDB']->query($sql);
			$row = $GLOBALS['xoopsDB']->fetchArray($ret);
			$url = XOOPS_URL."/" . $GLOBALS['xoopsModuleConfig']['baseurl'] . "/".xoops_sef($row['name'])."/mashable,$mashtopic" . $GLOBALS['xoopsModuleConfig']['endofurl_rss'];
		}
	} elseif(isset($_GET['cid'])) {
		$cid=intval($_GET['cid']);
		$url = XOOPS_URL."/" . $GLOBALS['xoopsModuleConfig']['baseurl'] . "/feed,cat,$cid" . $GLOBALS['xoopsModuleConfig']['endofurl_rss'];
	} elseif(isset($_GET['id'])||isset($_GET['xreaggregator'])) {
		if (isset($_GET['xreaggregator'])){
			$sql = "SELECT xreaggregator_id FROM ".$GLOBALS['xoopsDB']->prefix('xreaggregator')." WHERE xreaggregator_name Like '".xoops_sef($_GET['xreaggregator'],'_')."'";
			$ret = $GLOBALS['xoopsDB']->query($sql);
			$row = $GLOBALS['xoopsDB']->fetchArray($ret);
			if ($row['topic_id']!=0){
				$storytopic=$row['topic_id'];
			}
		} else {
			$storytopic=intval($_GET['id']);
	
			$sql = "SELECT a.xreaggregator_name as xreaggregator_name FROM ".$GLOBALS['xoopsDB']->prefix('xreaggregator')." a where a.xreaggregator_id = $storytopic";
			$ret = $GLOBALS['xoopsDB']->query($sql);
			$row = $GLOBALS['xoopsDB']->fetchArray($ret);
			$url = XOOPS_URL."/" . $GLOBALS['xoopsModuleConfig']['baseurl'] . "/".xoops_sef($row['xreaggregator_name'])."/feed,$storytopic" . $GLOBALS['xoopsModuleConfig']['endofurl_rss'];
		}
	} else {
		if(isset($_GET['id'])) {
			$storytopic=intval($_GET['id']);
	
			$sql = "SELECT a.xreaggregator_name as xreaggregator_name, a.xreaggregator_cid FROM ".$GLOBALS['xoopsDB']->prefix('xreaggregator')." a where a.xreaggregator_id = $storytopic";
			$ret = $GLOBALS['xoopsDB']->query($sql);
			$row = $GLOBALS['xoopsDB']->fetchArray($ret);
			if (strlen($row['xreaggregator_name'])>0)
			{
				$url = XOOPS_URL."/" . $GLOBALS['xoopsModuleConfig']['baseurl'] . "/".xoops_sef($row['xreaggregator_name'])."/feed,$storytopic" . $GLOBALS['xoopsModuleConfig']['endofurl_rss'];
			} else {
				$url = XOOPS_URL."/" . $GLOBALS['xoopsModuleConfig']['baseurl'] . "/feed,$storytopic,".$row['xreaggregator_cid']."" . $GLOBALS['xoopsModuleConfig']['endofurl_rss'];
			}
			
		} else {
			$url = XOOPS_URL."/" . $GLOBALS['xoopsModuleConfig']['baseurl'] . "/feed" . $GLOBALS['xoopsModuleConfig']['endofurl_rss'];
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
	$mashtopic=intval($_GET['mid']);
	$storytopic=intval($_GET['id']);
	$cid=intval($_GET['cid']);	
}

$tpl = new XoopsTpl();
$tpl->xoops_setCaching($GLOBALS['xoopsModuleConfig']['docache']);
$tpl->xoops_setCacheTime($GLOBALS['xoopsModuleConfig']['rss_cachetime']*60);
if (isset($_GET['ie']))
{
	$ie = true;
} else {
	$ie = false;
}


$compile_id = '1'.$storytopic.'0'.$mashtopic.'1'.$cid.$ie;
$xoopsCachedTemplateId = 'rss_'.$mashtopic.'_'.$storytopic.'_'.$cid.'_'.$GLOBALS['xoopsModule']->getVar('dirname').'|'.md5($GLOBALS['xoopsRequestUri']);
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
		if (!$GLOBALS['xoopsModuleConfig']['support_multisite'])
		{
			$criteria= new CriteriaCompo(new Criteria('xreaggregator_display', 1));
		} else {
			$criteria= new CriteriaCompo(new Criteria('xreaggregator_domains', '%'.urlencode(XOOPS_URL).'%', 'LIKE'), 'OR');
			$criteria->add(new Criteria('xreaggregator_domains', '%|all%', 'LIKE'), 'OR');
			$criteria_b = new CriteriaCompo(new Criteria('xreaggregator_display', 1), 'AND');
			$criteria->add($criteria_b);
		}
		if ($mashtopic!=0)
		{
			$mlman =& xoops_getmodulehandler('mashables');
			$ml =& $mlman->get($mashtopic);
			$criteria->add(new Criteria('xreaggregator_id', "('".implode("','", $ml->getVar('groups'))."')", 'IN'), 'AND');
		} elseif ($cid!=0) {
			$xreaggregators_cid =& $hlman->getObjects(new Criteria('xreaggregator_cid', $cid));			
			foreach($xreaggregators_cid as $ccid)
				$idin[] = $ccid->getVar('xreaggregator_id');

			$criteria->add(new Criteria('xreaggregator_id', "('".implode("','", $idin)."')", 'IN'), 'AND');
				
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
				} elseif (count($rss['items'])>$GLOBALS['xoopsModuleConfig']['snoop_quantity'])
				{
					$max = ($GLOBALS['xoopsModuleConfig']['snoop_quantity']==0)?2:$GLOBALS['xoopsModuleConfig']['snoop_quantity'];
				} else {
					$max = sizeof($rss['items']);
				}			
				if ($mashtopic==0){
					for ($i=0;$i<$max;$i++)
					{
						$items[$j] = $rss['items'][$i];
						$j++;
					}
				} else {
					if (strlen($ml->getVar('keywords'))>0)
					{
						$fi=0;
						foreach($rss['items'] as $item) {
							$done=false;
							if ($fi<$max)
							foreach(explode(',', $ml->getVar('keywords')) as $keyword) {
								if (strpos(' '.strtolower($item['title']), strtolower(trim($keyword)))>0||strpos(' '.strtolower($item['description']), strtolower(trim($keyword)))>0) {
									if ($done==false) {
										$done = true;
										$items[$j] = $item;
										$fi++;
										$j++;										
									}
								}
							}
						}
					} else {
						for ($i=0;$i<$max;$i++)
						{
							$items[$j] = $rss['items'][$i];
							$j++;
						}
					}
				}
			}	
		}


		if (is_a($ml, 'xreaggregatorMashables'))
			$channel['title'] = htmlspecialchars($GLOBALS['xoopsConfig']['sitename'].' :: '.$ml->getVar('name').' :: RSS Mashable');
		else
			$channel['title'] = htmlspecialchars($GLOBALS['xoopsConfig']['sitename'].' :: RSS Re-aggregation');
		$channel['link'] = XOOPS_URL;
		$channel['description'] = htmlspecialchars($GLOBALS['xoopsConfig']['slogan']);
		$channel['lastbuilddate'] = gmdate("D, d M Y H:i:s", time())." UTC";		
		$channel['category'] = "Feed Snoop";
		$channel['managingEditor'] = $GLOBALS['xoopsConfig']['adminmail'];
		$channel['webMaster'] = $GLOBALS['xoopsConfig']['adminmail'];		
		
		$image['link'] = XOOPS_URL;
		$image['url'] = XOOPS_URL."/images/logo.gif";
		$image['title'] = htmlspecialchars($GLOBALS['xoopsConfig']['sitename'].' :: RSS Re-aggregation');
		
		$items = vsort($items, "pubdate", true, true);
		$tpl->assign('items', $items);
		$tpl->assign('channel', $channel);
		$tpl->assign('images', $image);						
	}
	$tpl->assign('ie', $ie);							
}

$charset = !empty($GLOBALS['xoopsModuleConfig']['rss_utf8'])?$GLOBALS['xoopsModuleConfig']['rss_utf8']:'UTF-8';
header ('Content-Type:text/xml; charset='.$charset);
$tpl->display('db:xreaggregator_rss.html', $xoopsCachedTemplateId, $compile_id);
?>