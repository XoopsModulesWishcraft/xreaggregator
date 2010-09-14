<?php
if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}
/**
 * Class for Mashables
 * @author Simon Roberts (simon@chronolabs.coop)
 * @copyright copyright (c) 2000-2009 XOOPS.org
 * @package kernel
 */
class xreaggregatorMashables extends XoopsObject
{

    function xreaggregatorMashables($id = null)
    {
   		$this->initVar('id', XOBJ_DTYPE_INT, null, false);
   		$this->initVar('cid', XOBJ_DTYPE_INT, null, false);		
		$this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('groups', XOBJ_DTYPE_ARRAY, null, true);
        $this->initVar('domains', XOBJ_DTYPE_ARRAY, null, true);	
		$this->initVar('keywords', XOBJ_DTYPE_TXTBOX, null, false);			
   		$this->initVar('weight', XOBJ_DTYPE_INT, null, false);		
   		$this->initVar('display', XOBJ_DTYPE_INT, null, false);		
   		$this->initVar('random', XOBJ_DTYPE_INT, null, false);				
    }
	
}


/**
* XOOPS Mashables handler class.
* This class is responsible for providing data access mechanisms to the data source
* of XOOPS user class objects.
*
* @author  Simon Roberts <simon@chronolabs.coop>
* @package kernel
*/
class xreaggregatorMashablesHandler extends XoopsObjectHandler
{
	function xreaggregatorMashablesHandler(&$db)
	{
		$this->db =& $db;
	}
	
	function delete_id($id)
	{
		$sql = "DELETE FROM ".$GLOBALS['xoopsDB']->prefix('xreaggregator_mashables')." WHERE id = $id";
		$resa = $GLOBALS['xoopsDB']->queryF($sql);
		if ($resa==true)
			return true;
		else
			return false;
	}
	
	function &getInstance(&$db)
	{
		static $instance;
		if (!isset($instance)) {
			$instance = new xreaggregatorMashablesHandler($db);
		}
		return $instance;
	}

	function &create()
	{
		return new xreaggregatorMashables();
	}

	function &get($id)
	{
		$id = intval($id);
		if ($id > 0) {
			$sql = 'SELECT * FROM '.$this->db->prefix('xreaggregator_mashables').' WHERE id='.$id;
			if (!$result = $this->db->query($sql)) {
				return false;
			}
			
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$xreaggregator = new xreaggregatorMashables();
				$xreaggregator->assignVars($this->db->fetchArray($result));
				return $xreaggregator;
			}
		}
		return false;
	}

	function insert(&$xreaggregator)
	{
		if (!is_a($xreaggregator, 'xreaggregatorMashables')) {
			return false;
		}
		if (!$xreaggregator->cleanVars()) {
			return false;
		}
		foreach ($xreaggregator->cleanVars as $k => $v) {
			${$k} = $v;
		}
		if (empty($id)) {
			$id = $this->db->genId('XREAGGREGATOR_MASHABLE_ID_SEQ');
			$sql = 'INSERT INTO '.$this->db->prefix('xreaggregator_mashables').' (id, cid, name, random, display, weight, domains, groups, keywords) VALUES ('.$id.', '.$cid.', '.$this->db->quoteString($name).', '.$random.', '.$display.', '.$weight.', '.$this->db->quoteString($domains).', '.$this->db->quoteString($groups).', '.$this->db->quoteString($keywords).')';
		} else {
			$sql = 'UPDATE '.$this->db->prefix('xreaggregator_mashables').' SET name='.$this->db->quoteString($name).', random='.$random.', display='.$display.', weight='.$weight.', domains='.$this->db->quoteString($domains).', groups='.$this->db->quoteString($groups).', cid = '.$cid.', keywords = '.$this->db->quoteString($keywords).' WHERE id='.$id;
		}

		if (!$this->db->queryF($sql)) {
			return false;
		}
		if (empty($id)) {
			$id = $this->db->getInsertId();
		}
		$xreaggregator->assignVar('id', $id);
		return $id;
	}

	function delete(&$xreaggregator)
	{
		if (!is_a($xreaggregator, 'xreaggregatorMashables')) {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE id = %u", $this->db->prefix('xreaggregator_mashables'), $xreaggregator->getVar('id'));
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}

	function &getObjects($criteria = null)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM '.$this->db->prefix('xreaggregator_mashables');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
			$sql .= ' ORDER BY weight '.$criteria->getOrder();
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$result = $this->db->query($sql, $limit, $start);
		if (!$result) {
			return $ret;
		}
		while ($myrow = $this->db->fetchArray($result)) {
			$xreaggregator = new xreaggregatorMashables();
			$xreaggregator->assignVars($myrow);
			$ret[] =& $xreaggregator;
			unset($xreaggregator);
		}
		return $ret;
	}

	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('xreaggregator_mashables');
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
