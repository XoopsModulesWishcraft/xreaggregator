<?php
// $Id: xreaggregator.php,v 1.02    2008/06/10 15:05:30 wishcraft Exp $
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

class xreaggregatorxreaggregator extends XoopsObject
{

	function xreaggregatorxreaggregator()
	{
		$this->XoopsObject();
		$this->initVar('xreaggregator_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('xreaggregator_cid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('xreaggregator_name', XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar('xreaggregator_url', XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar('xreaggregator_rssurl', XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar('xreaggregator_cachetime', XOBJ_DTYPE_INT, 600, false);
		$this->initVar('xreaggregator_asblock', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('xreaggregator_display', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('xreaggregator_encoding', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('xreaggregator_weight', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('xreaggregator_mainimg', XOBJ_DTYPE_INT, 1, false);
		$this->initVar('xreaggregator_mainfull', XOBJ_DTYPE_INT, 1, false);
		$this->initVar('xreaggregator_mainmax', XOBJ_DTYPE_INT, 10, false);
		$this->initVar('xreaggregator_blockimg', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('xreaggregator_blockmax', XOBJ_DTYPE_INT, 10, false);
		$this->initVar('xreaggregator_xml', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('xreaggregator_updated', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('xreaggregator_domains', XOBJ_DTYPE_OTHER, null, false);
	}

	function cacheExpired()
	{
		if (time() - $this->getVar('xreaggregator_updated') > $this->getVar('xreaggregator_cachetime')) {
			return true;
		}
		return false;
	}
}

class xreaggregatorxreaggregatorHandler
{
	var $db;

	function xreaggregatorxreaggregatorHandler(&$db)
	{
		$this->db =& $db;
	}

	function &getInstance(&$db)
	{
		static $instance;
		if (!isset($instance)) {
			$instance = new xreaggregatorxreaggregatorHandler($db);
		}
		return $instance;
	}

	function &create()
	{
		return new xreaggregatorxreaggregator();
	}

	function &get($id)
	{
		$id = intval($id);
		if ($id > 0) {
			$sql = 'SELECT * FROM '.$this->db->prefix('xreaggregator').' WHERE xreaggregator_id='.$id;
			if (!$result = $this->db->query($sql)) {
				return false;
			}
			
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$xreaggregator = new xreaggregatorxreaggregator();
				$xreaggregator->assignVars($this->db->fetchArray($result));
				return $xreaggregator;
			}
		}
		return false;
	}

	function insert(&$xreaggregator)
	{
		if (strtolower(get_class($xreaggregator)) != 'xreaggregatorxreaggregator') {
			return false;
		}
		if (!$xreaggregator->cleanVars()) {
			return false;
		}
		foreach ($xreaggregator->cleanVars as $k => $v) {
			${$k} = $v;
		}
		if (empty($xreaggregator_id)) {
			$xreaggregator_id = $this->db->genId('xreaggregator_XREAGGREGATOR_id_seq');
			$sql = 'INSERT INTO '.$this->db->prefix('xreaggregator').' (xreaggregator_id, xreaggregator_cid, xreaggregator_name, xreaggregator_url, xreaggregator_rssurl, xreaggregator_encoding, xreaggregator_cachetime, xreaggregator_asblock, xreaggregator_display, xreaggregator_weight, xreaggregator_mainimg, xreaggregator_mainfull, xreaggregator_mainmax, xreaggregator_blockimg, xreaggregator_blockmax, xreaggregator_xml, xreaggregator_updated, xreaggregator_domains) VALUES ('.$xreaggregator_id.', '.$xreaggregator_cid.', '.$this->db->quoteString($xreaggregator_name).', '.$this->db->quoteString($xreaggregator_url).', '.$this->db->quoteString($xreaggregator_rssurl).', '.$this->db->quoteString($xreaggregator_encoding).', '.$xreaggregator_cachetime.', '.$xreaggregator_asblock.', '.$xreaggregator_display.', '.$xreaggregator_weight.', '.$xreaggregator_mainimg.', '.$xreaggregator_mainfull.', '.$xreaggregator_mainmax.', '.$xreaggregator_blockimg.', '.$xreaggregator_blockmax.', '.$this->db->quoteString($xreaggregator_xml).', '.time().', '.$this->db->quoteString($xreaggregator_domains).')';
		} else {
			$sql = 'UPDATE '.$this->db->prefix('xreaggregator').' SET xreaggregator_name='.$this->db->quoteString($xreaggregator_name).', xreaggregator_url='.$this->db->quoteString($xreaggregator_url).', xreaggregator_rssurl='.$this->db->quoteString($xreaggregator_rssurl).', xreaggregator_encoding='.$this->db->quoteString($xreaggregator_encoding).', xreaggregator_cachetime='.$xreaggregator_cachetime.', xreaggregator_asblock='.$xreaggregator_asblock.', xreaggregator_display='.$xreaggregator_display.', xreaggregator_weight='.$xreaggregator_weight.', xreaggregator_mainimg='.$xreaggregator_mainimg.', xreaggregator_mainfull='.$xreaggregator_mainfull.', xreaggregator_mainmax='.$xreaggregator_mainmax.', xreaggregator_blockimg='.$xreaggregator_blockimg.', xreaggregator_blockmax='.$xreaggregator_blockmax.', xreaggregator_xml = '.$this->db->quoteString($xreaggregator_xml).', xreaggregator_updated='.$xreaggregator_updated.', xreaggregator_domains='.$this->db->quoteString($xreaggregator_domains).', xreaggregator_cid='.$xreaggregator_cid.' WHERE xreaggregator_id='.$xreaggregator_id;
		}
		if (!$result = $this->db->queryF($sql)) {
			return false;
		}
		if (empty($xreaggregator_id)) {
			$xreaggregator_id = $this->db->getInsertId();
		}
		$xreaggregator->assignVar('xreaggregator_id', $xreaggregator_id);
		return $xreaggregator_id;
	}

	function delete(&$xreaggregator)
	{
		if (get_class($xreaggregator) != 'xreaggregatorxreaggregator') {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE xreaggregator_id = %u", $this->db->prefix('xreaggregator'), $xreaggregator->getVar('xreaggregator_id'));
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}

	function &getObjects($criteria = null)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM '.$this->db->prefix('xreaggregator');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
			$sql .= ' ORDER BY xreaggregator_weight '.$criteria->getOrder();
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$result = $this->db->query($sql, $limit, $start);
		if (!$result) {
			return $ret;
		}
		while ($myrow = $this->db->fetchArray($result)) {
			$xreaggregator = new xreaggregatorxreaggregator();
			$xreaggregator->assignVars($myrow);
			$ret[] =& $xreaggregator;
			unset($xreaggregator);
		}
		return $ret;
	}

	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('xreaggregator');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		if (!$result =& $this->db->query($sql)) {
			return 0;
		}
		list($count) = $this->db->fetchRow($result);
		return $count;
	}
}
?>