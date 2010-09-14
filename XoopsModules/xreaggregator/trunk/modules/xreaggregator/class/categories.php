<?php
if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}
/**
 * Class for Categories
 * @author Simon Roberts (simon@chronolabs.coop)
 * @copyright copyright (c) 2000-2009 XOOPS.org
 * @package kernel
 */

class xreaggregatorCategories extends XoopsObject
{

    function xreaggregatorCategories($id = null)
    {
   		$this->initVar('cid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 128);
   		$this->initVar('weight', XOBJ_DTYPE_INT, null, false);	
   		$this->initVar('display', XOBJ_DTYPE_INT, null, false);				
		$this->initVar('domains', XOBJ_DTYPE_ARRAY);
    }
}

/**
* XOOPS Categories handler class.
* This class is responsible for providing data access mechanisms to the data source
* of XOOPS user class objects.
*
* @author  Simon Roberts <simon@chronolabs.coop>
* @package kernel
*/

class xreaggregatorCategoriesHandler extends XoopsObjectHandler
{
	function xreaggregatorCategoriesHandler(&$db)
	{
		$this->db =& $db;
	}

	function delete_id($id)
	{
		$sql = "DELETE FROM ".$GLOBALS['xoopsDB']->prefix('xreaggregator_categories')." WHERE cid = $id";
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
			$instance = new xreaggregatorCategoriesHandler($db);
		}
		return $instance;
	}

	function &create()
	{
		return new xreaggregatorCategories();
	}

	function &get($id)
	{
		$id = intval($id);
		if ($id > 0) {
			$sql = 'SELECT * FROM '.$this->db->prefix('xreaggregator_categories').' WHERE cid='.$id;
			if (!$result = $this->db->query($sql)) {
				return false;
			}

			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$xreaggregator = new xreaggregatorCategories();
				$xreaggregator->assignVars($this->db->fetchArray($result));
				return $xreaggregator;
			}
		}

		return false;
	}

	function insert(&$xreaggregator)
	{
		if (!is_a($xreaggregator, 'xreaggregatorCategories')) {
			return false;
		}
		if (!$xreaggregator->cleanVars()) {
			return false;
		}
		foreach ($xreaggregator->cleanVars as $k => $v) {
			${$k} = $v;
		}
		if (empty($cid)) {
			$cid = $this->db->genId('XREAGGREGATOR_CATEGORIES_ID_SEQ');
			$sql = 'INSERT INTO '.$this->db->prefix('xreaggregator_categories').' (name, weight, display, domains) VALUES ('.$this->db->quoteString($name).', '.$weight.','.$display.', \''.addslashes($domains).'\')';
		} else {
			$sql = 'UPDATE '.$this->db->prefix('xreaggregator_categories').' SET name='.$this->db->quoteString($name).', weight='.$this->db->quoteString($weight).', display= '.$display.', domains = \''.addslashes($domains).'\' WHERE cid='.$cid;
		}

		if (!$this->db->queryF($sql)) {
			return false;
		}
		if (empty($cid)) {
			$cid = $this->db->getInsertId();
		}
		$xreaggregator->assignVar('cid', $cid);
		return $cid;
	}

	function delete(&$xreaggregator)
	{
		if (!is_a($xreaggregator, 'xreaggregatorCategories')) {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE cid = %u", $this->db->prefix('xreaggregator_categories'), $xreaggregator->getVar('cid'));
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}

	function &getObjects($criteria = null)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM '.$this->db->prefix('xreaggregator_categories');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$result = $this->db->query($sql, $limit, $start);
		if (!$result) {
			return $ret;
		}
		while ($myrow = $this->db->fetchArray($result)) {
			$xreaggregator = new xreaggregatorCategories();
			$xreaggregator->assignVars($myrow);
			$ret[] =& $xreaggregator;
			unset($xreaggregator);
		}
		return $ret;
	}

	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('xreaggregator_categories');
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

