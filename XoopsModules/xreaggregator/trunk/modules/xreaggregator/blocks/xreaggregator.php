<?php
// $Id: xreaggregator.php,v 2.09 2004/12/26 19:12:09 wishcraft Exp $
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
include_once XOOPS_ROOT_PATH.'/modules/xreaggregator/include/functions.php';

function B_XREAGGREGATOR_SHOW($options)
{
	$block = array();
	$hlman =& xoops_getmodulehandler('xreaggregator', 'xreaggregator');
	$criteria = new CriteriaCompo(new Criteria('xreaggregator_asblock', 1));
	if (!empty($options[0])&&substr($options[0],0,1)!='0')
		$criteria->add(new Criteria('xreaggregator_id', "(".$options[0].")", "IN"));
	$xreaggregators =& $hlman->getObjects($criteria);
	$count = count($xreaggregators);
	for ($i = 0; $i < $count; $i++) {
		$renderer =& xreaggregator_getrenderer($xreaggregators[$i]);
		if (!$renderer->renderBlock($options)) {
			if ($GLOBALS['xoopsConfig']['debug_mode'] == 2) {
				$block['feeds'][] = sprintf(_XAL_FAILGET, $xreaggregators[$i]->getVar('xreaggregator_name')).'<br />'.$renderer->getErrors();
			}
			continue;
		}
		$block['feeds'][] = $renderer->getBlock($options);
	}
	return $block;
}

function B_XREAGGREGATOR_EDIT($options) {
	
	$hlman =& xoops_getmodulehandler('xreaggregator','xreaggregator');
	$xreaggregators =& $hlman->getObjects(new Criteria('xreaggregator_asblock', 1));
	$xreagcount = count($xreaggregators);
	
	$fhtml .= "<table width='100%' style='vertical-align:top;'><tr>";
	$fhtml .= "<td><label name='options[0][]'>Display Sources:</label>&nbsp;</td>";
	$fhtml .= "<td><select name='options[0][]' multiple='multiple'>";
	$fhtml .= "<option".((in_array('0', explode(',',$options[0])))?' selected=\'selected\'':'')." value='0'>All Feeds</option>";
	foreach($xreaggregators as $key => $feed)
		$fhtml .= "<option".((in_array("".$feed->getVar('xreaggregator_id'), explode(',',$options[0])))?' selected=\'selected\'':'')." value='".$feed->getVar('xreaggregator_id')."'>".ucfirst($feed->getVar('xreaggregator_name'))."</option>";
	$fhtml .= "</select></td>";
	$fhtml .= "</tr><tr>";
	$fhtml .= "<td><label name='options[1]'>Maximum Title Length:</label>&nbsp;</td>";	
	$fhtml .= "<td><input name='options[1]' type='text' value='".$options[1]."' maxlength='10' size='5' /></td>";	
	$fhtml .= "</tr></table>";
	return $fhtml;
}
?>

