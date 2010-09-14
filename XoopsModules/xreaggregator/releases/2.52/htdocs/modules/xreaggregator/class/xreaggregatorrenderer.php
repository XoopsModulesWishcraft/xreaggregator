<?php
// $Id: xreaggregatorrenderer.php,v 1.02    2008/06/10 15:05:30 wishcraft Exp $
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
if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

include_once XOOPS_ROOT_PATH.'/class/template.php';
include_once XOOPS_ROOT_PATH.'/modules/xreaggregator/language/'.$GLOBALS['xoopsConfig']['language'].'/main.php';

class xreaggregatorRenderer
{
	// holds reference to xreaggregator class object
	var $_hl;

	var $_tpl;

	var $_feed;
	
	var $_block;

	var $_errors = array();

	// RSS2 SAX parser
	var $_parser;


	function xreaggregatorRenderer(&$xreaggregator)
	{
		$this->_hl =& $xreaggregator;
		$this->_tpl = new XoopsTpl();
	}

	function updateCache()
	{
		switch ($GLOBALS['xoopsModuleConfig']['get_method']) {
		case "fopen":
	
			if (!$fp = fopen($this->_hl->getVar('xreaggregator_rssurl'), 'r')) {
				$this->_setErrors('Could not open file: '.$this->_hl->getVar('xreaggregator_rssurl'));
				return false;
			}
			$data = '';
			while (!feof ($fp)) {
				$data .= fgets($fp, 4096);
			}
			fclose ($fp);
			break;
		
		default:
		
			$cookies = XOOPS_ROOT_PATH.'/uploads/xreaggregator_'.md5($this->_hl->getVar('xreaggregator_rssurl')).'.cookie'; 
			if (!$ch = curl_init($this->_hl->getVar('xreaggregator_rssurl'))) {
				$this->_setErrors('Could not intialise CURL file: '.$this->_hl->getVar('xreaggregator_rssurl'));
				return false;
			}

			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_USERAGENT, $GLOBALS['xoopsModuleConfig']['user_agent']); 
			$data = curl_exec($ch); 
			$this->_setErrors(curl_error($ch));
			curl_close($ch); 
			unlink($cookies);
			
		}
		$this->_hl->setVar('xreaggregator_xml', $this->convertToUtf8($data));
		$this->_hl->setVar('xreaggregator_updated', time());
		$xreaggregator_handler =& xoops_getmodulehandler('xreaggregator', 'xreaggregator');
		return $xreaggregator_handler->insert($this->_hl);
	}

	function renderFeed($force_update = false)
	{
		if ($force_update || $this->_hl->cacheExpired()) {
			if (!$this->updateCache()) {
				return false;
			}
		}
		if (!$this->_parse()) {
			return false;
		}
		$this->_tpl->clear_all_assign();
		$this->_tpl->assign('xoops_url', XOOPS_URL);
		$channel_data =& $this->_parser->getChannelData();
		array_walk($channel_data, array($this, 'convertFromUtf8'));
		$this->_tpl->assign_by_ref('channel', $channel_data);
		if ($this->_hl->getVar('xreaggregator_mainimg') == 1) {
			$image_data =& $this->_parser->getImageData();
			array_walk($image_data, array($this, 'convertFromUtf8'));
			$this->_tpl->assign_by_ref('image', $image_data);
		}
		if ($this->_hl->getVar('xreaggregator_mainfull') == 1) {
			$this->_tpl->assign('show_full', true);
		} else {
			$this->_tpl->assign('show_full', false);
		}
		$items =& $this->_parser->getItems();
		$count = count($items);
		$max = ($count > $this->_hl->getVar('xreaggregator_mainmax')) ? $this->_hl->getVar('xreaggregator_mainmax') : $count;
		for ($i = 0; $i < $max; $i++) {
			array_walk($items[$i], array($this, 'convertFromUtf8'));
			$this->_tpl->append_by_ref('items', $items[$i]);
		}
		$this->_tpl->assign(array('lang_lastbuild' => _XAL_LASTBUILD, 'lang_language' => _XAL_LANGUAGE, 'lang_description' => _XAL_DESCRIPTION, 'lang_webmaster' => _XAL_WEBMASTER, 'lang_category' => _XAL_CATEGORY, 'lang_generator' => _XAL_GENERATOR, 'lang_title' => _XAL_TITLE, 'lang_pubdate' => _XAL_PUBDATE, 'lang_description' => _XAL_DESCRIPTION, 'lang_more' => _MORE));
		$this->_feed =& $this->_tpl->fetch('db:xreaggregator_feed.html');
		return true;
	}
	
	function renderRSS($force_update = false)
	{
		if ($force_update || $this->_hl->cacheExpired()) {
			if (!$this->updateCache()) {
				return false;
			}
		}

		if (!$this->_parse()) {
			return false;
		}
		
		$channel_data =& $this->_parser->getChannelData();
		$image_data =& $this->_parser->getImageData();
		$items =& $this->_parser->getItems();
		
		$category_handler =& xoops_getmodulehandler('categories', 'xreaggregator');
		$category = $category_handler->get($this->_hl->getVar('xreaggregator_cid'));
		
		foreach($items as $id => $item) {
			if (is_object($category))
				$items[$id]['category'] = htmlspecialchars($category->getVar('name'));
			$items[$id]['guid'] = $this->xoops_stripeKey(md5($item['title'].$item['description'].$item['category'].$item['link']), 6, 32, 0);
		}

		$channel_data = $this->xr_htmlspecialchars($this->xr_htmlspecialchars_decode($channel_data));
		$image_data = $this->xr_htmlspecialchars($this->xr_htmlspecialchars_decode($image_data));
		$items = $this->xr_htmlspecialchars($this->xr_htmlspecialchars_decode($items));

		return array("items" => $items, "image" => $image_data, "channel" => $channel_data);
	}
	
	function xoops_stripeKey($xoops_key, $num = 6, $length = 32, $uu = 0)
    {
        $strip = floor(strlen($xoops_key) / 6);
        for ($i = 0; $i < strlen($xoops_key); $i++) {
            if ($i < $length) {
                $uu++;
                if ($uu == $strip) {
                    $ret .= substr($xoops_key, $i, 1) . '-';
                    $uu = 0;
                } else {
                    if (substr($xoops_key, $i, 1) != '-') {
                        $ret .= substr($xoops_key, $i, 1);
                    } else {
                        $uu--;
                    }
                }
            }
        }
        $ret = str_replace('--', '-', $ret);
        if (substr($ret, 0, 1) == '-') {
            $ret = substr($ret, 2, strlen($ret));
        }
        if (substr($ret, strlen($ret) - 1, 1) == '-') {
            $ret = substr($ret, 0, strlen($ret) - 1);
        }
        return $ret;
    }
	
	function xr_htmlspecialchars($obj_items)
	{
		foreach($obj_items as $key => $item)
		{
			if (is_array($item))
			{
				$obj_items[$key] = $this->xr_htmlspecialchars($item);
			} else {
				$obj_items[$key] = htmlspecialchars($item);
			}
		}

		return $obj_items;	
	}
	
	function xr_htmlspecialchars_decode($obj_items)
	{
		foreach($obj_items as $key => $item)
		{
			if (is_array($item))
			{
				$obj_items[$key] = $this->xr_htmlspecialchars_decode($item);
			} else {
				$obj_items[$key] = htmlspecialchars_decode($item);
			}
		}

		return $obj_items;	
	}
	
	function renderBlock($options, $force_update = false)
	{
		if ($force_update || $this->_hl->cacheExpired()) {
			if (!$this->updateCache()) {
				return false;
			}
		}
		if (!$this->_parse()) {
			return false;
		}
		$this->_tpl->clear_all_assign();
		$this->_tpl->assign('xoops_url', XOOPS_URL);
		$channel_data =& $this->_parser->getChannelData();
		array_walk($channel_data, array($this, 'convertFromUtf8'));
		$this->_tpl->assign_by_ref('channel', $channel_data);
		if ($this->_hl->getVar('xreaggregator_blockimg') == 1) {
			$image_data =& $this->_parser->getImageData();
			array_walk($image_data, array($this, 'convertFromUtf8'));
			$this->_tpl->assign_by_ref('image', $image_data);
		}
		$items =& $this->_parser->getItems();
		$count = count($items);
		$max = ($count > $this->_hl->getVar('xreaggregator_blockmax')) ? $this->_hl->getVar('xreaggregator_blockmax') : $count;
		for ($i = 0; $i < $max; $i++) {
			array_walk($items[$i], array($this, 'convertFromUtf8'));
			array_walk($items[$i], array($this, 'trimLength'), (intval($options[1])==0)?35:intval($options[1]));
			$this->_tpl->append_by_ref('items', $items[$i]);
		}
		$this->_tpl->assign(array('site_name' => $this->trimLength($this->_hl->getVar('xreaggregator_name'),'',(intval($options[1])==0)?35:intval($options[1])), 'site_url' => $this->_hl->getVar('xreaggregator_url'), 'site_id' => $this->_hl->getVar('xreaggregator_id')));
		$this->_block =& $this->_tpl->fetch('file:'.XOOPS_ROOT_PATH.'/modules/xreaggregator/blocks/xreaggregator_block.html');
		return true;
	}


	
	function &_parse()
	{
		if (isset($this->_parser)) {
			return true;
		}
		include_once XOOPS_ROOT_PATH.'/class/xml/rss/xmlrss2parser.php';
		$this->_parser = new XoopsXmlRss2Parser($this->_hl->getVar('xreaggregator_xml'));
		switch ($this->_hl->getVar('xreaggregator_encoding')) {
		case 'utf-8':
			$this->_parser->useUtfEncoding();
			break;
		case 'us-ascii':
			$this->_parser->useAsciiEncoding();
			break;
		default:
			$this->_parser->useIsoEncoding();
			break;
		}
		$result = $this->_parser->parse();
		if (!$result) {
			$this->_setErrors($this->_parser->getErrors(false));
			unset($this->_parser);
			return false;
		}
		return true;
	}

	function &getFeed()
	{
		return $this->_feed;
	}

	function &getBlock()
	{
		return $this->_block;
	}

	function _setErrors($err)
	{
		$this->_errors[] = $err;
	}

	function &getErrors($ashtml = true)
	{
		if (!$ashtml) {
			return $this->_errors;
		} else {
		$ret = '';
		if (count($this->_errors) > 0) {
			foreach ($this->_errors as $error) {
				$ret .= $error.'<br />';
			}
		}
		return $ret;
		}
	}

	// abstract
	// overide this method in /language/your_language/xreaggregatorrenderer.php
	// this method is called by the array_walk function
	// return void
	function convertFromUtf8(&$value, $key)
	{
	}
	
	// abstract
	// overide this method in /language/your_language/xreaggregatorrenderer.php
	// this method is called by the array_walk function
	// return void
	function trimLength(&$value, $key, $length)
	{
		if ($key=='title')
			if (strlen($value)>$length-3) {
				for($uu=$length-3;$uu>1;$uu--){
					if (substr($value,$uu,1)==' ') {
						$value = trim(substr($value,0, $uu)).'...';
						$uu=-1;
					}
				}
			}
				
		if (empty($key)) {
			return $value;
		}
	}

	// abstract
	// overide this method in /language/your_language/xreaggregatorrenderer.php
	// return string
	function &convertToUtf8(&$xmlfile)
	{
		return $xmlfile;
	}
}
?>