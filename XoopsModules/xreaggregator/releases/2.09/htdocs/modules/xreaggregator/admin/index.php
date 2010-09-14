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
include '../../../include/cp_header.php';
include XOOPS_ROOT_PATH.'/modules/xreaggregator/include/functions.php';
$op = 'list';

if (isset($HTTP_GET_VARS['op']) && ($HTTP_GET_VARS['op'] == 'delete' || $HTTP_GET_VARS['op'] == 'edit')) {
	$op = $HTTP_GET_VARS['op'];
	$xreaggregator_id = intval($HTTP_GET_VARS['xreaggregator_id']);
}

if (isset($HTTP_POST_VARS)) {
	foreach ($HTTP_POST_VARS as $k => $v) {
		${$k} = $v;
	}
}

if ($op == 'list') {
	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	$hlman =& xoops_getmodulehandler('xreaggregator');
	$xreaggregators =& $hlman->getObjects();
	$count = count($xreaggregators);
	xoops_cp_header();
	echo "<h4>"._AM_xreaggregatorS."</h4>";
	echo '<form name="xreaggregator_form" action="index.php" method="post"><table><tr><td>'._AM_SITENAME.'</td><td>'._AM_CACHETIME.'</td><td>'._AM_ENCODING.'</td><td>'._AM_DISPLAY.'</td><td>'._AM_ASBLOCK.'</td><td>'._AM_ORDER.'</td><td>&nbsp;</td></tr>';
	for ($i = 0; $i < $count; $i++) {
		echo '<tr><td>'.$xreaggregators[$i]->getVar('xreaggregator_name').'</td>
		<td><select name="xreaggregator_cachetime[]">';
		$cachetime = array('3600' => sprintf(_HOUR, 1), '18000' => sprintf(_HOURS, 5), '86400' => sprintf(_DAY, 1), '259200' => sprintf(_DAYS, 3), '604800' => sprintf(_WEEK, 1), '2592000' => sprintf(_MONTH, 1));
		foreach ($cachetime as $value => $name) {
			echo '<option value="'.$value.'"';
			if ($value == $xreaggregators[$i]->getVar('xreaggregator_cachetime')) {
				echo ' selected="selecetd"';
			}
			echo '>'.$name.'</option>';
		}
		echo '</select></td>
		<td><select name="xreaggregator_encoding[]">';
		$encodings = array('utf-8' => 'UTF-8', 'iso-8859-1' => 'ISO-8859-1', 'us-ascii' => 'US-ASCII');
		foreach ($encodings as $value => $name) {
			echo '<option value="'.$value.'"';
			if ($value == $xreaggregators[$i]->getVar('xreaggregator_encoding')) {
				echo ' selected="selecetd"';
			}
			echo '>'.$name.'</option>';
		}
		echo '</select></td>';
		echo '<td><input type="checkbox" value="1" name="xreaggregator_display['.$xreaggregators[$i]->getVar('xreaggregator_id').']"';
		if (1 == $xreaggregators[$i]->getVar('xreaggregator_display')) {
			echo ' checked="checked"';
		}
		echo ' /></td>';
		echo '<td><input type="checkbox" value="1" name="xreaggregator_asblock['.$xreaggregators[$i]->getVar('xreaggregator_id').']"';
		if (1 == $xreaggregators[$i]->getVar('xreaggregator_asblock')) {
			echo ' checked="checked"';
		}
		echo ' /></td>';
		echo '<td><input type="text" maxlength="3" size="4" name="xreaggregator_weight[]" value="'.$xreaggregators[$i]->getVar('xreaggregator_weight').'" /><td><a href="index.php?op=edit&amp;xreaggregator_id='.$xreaggregators[$i]->getVar('xreaggregator_id').'">'._EDIT.'</a>&nbsp;<a href="index.php?op=delete&amp;xreaggregator_id='.$xreaggregators[$i]->getVar('xreaggregator_id').'">'._DELETE.'</a><input type="hidden" name="xreaggregator_id[]" value="'.$xreaggregators[$i]->getVar('xreaggregator_id').'" /></td></tr>';
	}
	echo '</table><div style="text-align:center"><input type="hidden" name="op" value="update" /><input type="submit" name="xreaggregator_submit" value="'._SUBMIT.'" /></div></form>';
	$form = new XoopsThemeForm(_AM_ADDHEADL, 'xreaggregator_form_new', 'index.php', 'post', true);
	$form->addElement(new XoopsFormText(_AM_SITENAME, 'xreaggregator_name', 50, 255), true);
	$form->addElement(new XoopsFormText(_AM_URL, 'xreaggregator_url', 50, 255, 'http://'), true);
	$form->addElement(new XoopsFormText(_AM_URLEDFXML, 'xreaggregator_rssurl', 50, 255, 'http://'), true);
	$form->addElement(new XoopsFormText(_AM_ORDER, 'xreaggregator_weight', 4, 3, 0));	$enc_sel = new XoopsFormSelect(_AM_ENCODING, 'xreaggregator_encoding', 'utf-8');
	$enc_sel->addOptionArray(array('utf-8' => 'UTF-8', 'iso-8859-1' => 'ISO-8859-1', 'us-ascii' => 'US-ASCII'));
	$form->addElement($enc_sel);
	$cache_sel = new XoopsFormSelect(_AM_CACHETIME, 'xreaggregator_cachetime', 86400);
	$cache_sel->addOptionArray(array('3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK, '2592000' => _MONTH));
	$form->addElement($cache_sel);

	$form->insertBreak(_AM_MAINSETT);
	$form->addElement(new XoopsFormRadioYN(_AM_DISPLAY, 'xreaggregator_display', 1, _YES, _NO));
	$form->addElement(new XoopsFormRadioYN(_AM_DISPIMG, 'xreaggregator_mainimg', 0, _YES, _NO));
	$form->addElement(new XoopsFormRadioYN(_AM_DISPFULL, 'xreaggregator_mainfull', 0, _YES, _NO));
	$mmax_sel = new XoopsFormSelect(_AM_DISPMAX, 'xreaggregator_mainmax', 10);
	$mmax_sel->addOptionArray(array('1' => 1, '5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30));
	$form->addElement($mmax_sel);

	$form->insertBreak(_AM_BLOCKSETT);
	$form->addElement(new XoopsFormRadioYN(_AM_ASBLOCK, 'xreaggregator_asblock', 1, _YES, _NO));
	$form->addElement(new XoopsFormRadioYN(_AM_DISPIMG, 'xreaggregator_blockimg', 0, _YES, _NO));
	$bmax_sel = new XoopsFormSelect(_AM_DISPMAX, 'xreaggregator_blockmax', 5);
	$bmax_sel->addOptionArray(array('1' => 1, '5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30));
	$form->addElement($bmax_sel);


	$form->insertBreak();
	$form->addElement(new XoopsFormHidden('op', 'addgo'));
	$form->addElement(new XoopsFormButton('', 'xreaggregator_submit2', _SUBMIT, 'submit'));
	$form->display();
	xoops_cp_footer();
	exit();
}

if ($op == 'update') {
	$hlman =& xoops_getmodulehandler('xreaggregator');;
	$i = 0;
	$msg = '';
	foreach ($xreaggregator_id as $id) {
		$hl =& $hlman->get($id);
		if (!is_object($hl)) {
			$i++;
			continue;
		}
		$xreaggregator_display[$id] = empty($xreaggregator_display[$id]) ? 0 : $xreaggregator_display[$id];
		$xreaggregator_asblock[$id] = empty($xreaggregator_asblock[$id]) ? 0 : $xreaggregator_asblock[$id];
		$old_cachetime = $hl->getVar('xreaggregator_cachetime');
		$hl->setVar('xreaggregator_cachetime', $xreaggregator_cachetime[$i]);
		$old_display = $hl->getVar('xreaggregator_display');
		$hl->setVar('xreaggregator_display', $xreaggregator_display[$id]);
		$hl->setVar('xreaggregator_weight', $xreaggregator_weight[$i]);
		$old_asblock = $hl->getVar('xreaggregator_asblock');
		$hl->setVar('xreaggregator_asblock', $xreaggregator_asblock[$id]);
		$old_encoding = $hl->getVar('xreaggregator_encoding');
		if (!$hlman->insert($hl)) {
			$msg .= '<br />'.sprintf(_AM_FAILUPDATE, $hl->getVar('xreaggregator_name'));
		} else {
			if ($hl->getVar('xreaggregator_xml') == '') {
				$renderer =& xreaggregator_getrenderer($hl);
				$renderer->updateCache();
			}
		}
		$i++;
	}
	if ($msg != '') {
		xoops_cp_header();
		echo "<h4>"._AM_xreaggregatorS."</h4>";
		xoops_error($msg);
		xoops_cp_footer();
		exit();
	}
	redirect_header('index.php', 2, _AM_DBUPDATED);
}

if ($op == 'addgo') {
    if ($GLOBALS['xoopsSecurity']->check()) {
        $hlman =& xoops_getmodulehandler('xreaggregator');;
        $hl =& $hlman->create();
        $hl->setVar('xreaggregator_name', $xreaggregator_name);
        $hl->setVar('xreaggregator_url', $xreaggregator_url);
        $hl->setVar('xreaggregator_rssurl', $xreaggregator_rssurl);
        $hl->setVar('xreaggregator_display', $xreaggregator_display);
        $hl->setVar('xreaggregator_weight', $xreaggregator_weight);
        $hl->setVar('xreaggregator_asblock', $xreaggregator_asblock);
        $hl->setVar('xreaggregator_encoding', $xreaggregator_encoding);
        $hl->setVar('xreaggregator_cachetime', $xreaggregator_cachetime);
        $hl->setVar('xreaggregator_mainfull', $xreaggregator_mainfull);
        $hl->setVar('xreaggregator_mainimg', $xreaggregator_mainimg);
        $hl->setVar('xreaggregator_mainmax', $xreaggregator_mainmax);
        $hl->setVar('xreaggregator_blockimg', $xreaggregator_blockimg);
        $hl->setVar('xreaggregator_blockmax', $xreaggregator_blockmax);
        if (!$hlman->insert($hl)) {
            $msg = sprintf(_AM_FAILUPDATE, $hl->getVar('xreaggregator_name'));
            $msg .= '<br />'.$hl->getErrors();
            xoops_cp_header();
            echo "<h4>"._AM_xreaggregatorS."</h4>";
            xoops_error($msg);
            xoops_cp_footer();
            exit();
        } else {
            if ($hl->getVar('xreaggregator_xml') == '') {
                $renderer =& xreaggregator_getrenderer($hl);
                $renderer->updateCache();
            }
        }
    }
    else {
        redirect_header('index.php', 2, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
    }
	redirect_header('index.php', 2, _AM_DBUPDATED);
}

if ($op == 'edit') {
	if ($xreaggregator_id <= 0) {
		xoops_cp_header();
		echo "<h4>"._AM_xreaggregatorS."</h4>";
		xoops_error(_AM_INVALIDID);
		xoops_cp_footer();
		exit();
	}
	$hlman =& xoops_getmodulehandler('xreaggregator');;
	$hl =& $hlman->get($xreaggregator_id);
	if (!is_object($hl)) {
		xoops_cp_header();
		echo "<h4>"._AM_xreaggregatorS."</h4>";
		xoops_error(_AM_OBJECTNG);
		xoops_cp_footer();
		exit();
	}
	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	$form = new XoopsThemeForm(_AM_EDITHEADL, 'xreaggregator_form', 'index.php', 'post', true);
	$form->addElement(new XoopsFormText(_AM_SITENAME, 'xreaggregator_name', 50, 255, $hl->getVar('xreaggregator_name')), true);
	$form->addElement(new XoopsFormText(_AM_URL, 'xreaggregator_url', 50, 255, $hl->getVar('xreaggregator_url')), true);
	$form->addElement(new XoopsFormText(_AM_URLEDFXML, 'xreaggregator_rssurl', 50, 255, $hl->getVar('xreaggregator_rssurl')), true);
	$form->addElement(new XoopsFormText(_AM_ORDER, 'xreaggregator_weight', 4, 3, $hl->getVar('xreaggregator_weight')));
	$enc_sel = new XoopsFormSelect(_AM_ENCODING, 'xreaggregator_encoding', $hl->getVar('xreaggregator_encoding'));
	$enc_sel->addOptionArray(array('utf-8' => 'UTF-8', 'iso-8859-1' => 'ISO-8859-1', 'us-ascii' => 'US-ASCII'));
	$form->addElement($enc_sel);
	$cache_sel = new XoopsFormSelect(_AM_CACHETIME, 'xreaggregator_cachetime', $hl->getVar('xreaggregator_cachetime'));
	$cache_sel->addOptionArray(array('3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK, '2592000' => _MONTH));
	$form->addElement($cache_sel);

	$form->insertBreak(_AM_MAINSETT);
	$form->addElement(new XoopsFormRadioYN(_AM_DISPLAY, 'xreaggregator_display', $hl->getVar('xreaggregator_display'), _YES, _NO));
	$form->addElement(new XoopsFormRadioYN(_AM_DISPIMG, 'xreaggregator_mainimg', $hl->getVar('xreaggregator_mainimg'), _YES, _NO));
	$form->addElement(new XoopsFormRadioYN(_AM_DISPFULL, 'xreaggregator_mainfull', $hl->getVar('xreaggregator_mainfull'), _YES, _NO));
	$mmax_sel = new XoopsFormSelect(_AM_DISPMAX, 'xreaggregator_mainmax', $hl->getVar('xreaggregator_mainmax'));
	$mmax_sel->addOptionArray(array('1' => 1, '5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30));
	$form->addElement($mmax_sel);

	$form->insertBreak(_AM_BLOCKSETT);
	$form->addElement(new XoopsFormRadioYN(_AM_ASBLOCK, 'xreaggregator_asblock', $hl->getVar('xreaggregator_asblock'), _YES, _NO));
	$form->addElement(new XoopsFormRadioYN(_AM_DISPIMG, 'xreaggregator_blockimg', $hl->getVar('xreaggregator_blockimg'), _YES, _NO));
	$bmax_sel = new XoopsFormSelect(_AM_DISPMAX, 'xreaggregator_blockmax', $hl->getVar('xreaggregator_blockmax'));
	$bmax_sel->addOptionArray(array('1' => 1, '5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30));
	$form->addElement($bmax_sel);
	$form->insertBreak();
	$form->addElement(new XoopsFormHidden('xreaggregator_id', $hl->getVar('xreaggregator_id')));
	$form->addElement(new XoopsFormHidden('op', 'editgo'));
	$form->addElement(new XoopsFormButton('', 'xreaggregator_submit', _SUBMIT, 'submit'));
	xoops_cp_header();
	echo "<h4>"._AM_xreaggregatorS."</h4><br />";
	//echo '<a href="index.php">'. _AM_HLMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$hl->getVar('xreaggregator_name').'<br /><br />';
	$form->display();
	xoops_cp_footer();
	exit();
}

if ($op == 'editgo') {
	$xreaggregator_id = intval($xreaggregator_id);
	if ($xreaggregator_id <= 0) {
		xoops_cp_header();
		echo "<h4>"._AM_xreaggregatorS."</h4>";
		xoops_error(_AM_INVALIDID);
		xoops_cp_footer();
		exit();
	}
	$hlman =& xoops_getmodulehandler('xreaggregator');;
	$hl =& $hlman->get($xreaggregator_id);
	if (!is_object($hl)) {
		xoops_cp_header();
		echo "<h4>"._AM_xreaggregatorS."</h4>";
		xoops_error(_AM_OBJECTNG);
		xoops_cp_footer();
		exit();
	}
	$hl->setVar('xreaggregator_name', $xreaggregator_name);
	$hl->setVar('xreaggregator_url', $xreaggregator_url);
	$hl->setVar('xreaggregator_encoding', $xreaggregator_encoding);
	$hl->setVar('xreaggregator_rssurl', $xreaggregator_rssurl);
	$hl->setVar('xreaggregator_display', $xreaggregator_display);
	$hl->setVar('xreaggregator_weight', $xreaggregator_weight);
	$hl->setVar('xreaggregator_asblock', $xreaggregator_asblock);
	$hl->setVar('xreaggregator_cachetime', $xreaggregator_cachetime);
	$hl->setVar('xreaggregator_mainfull', $xreaggregator_mainfull);
	$hl->setVar('xreaggregator_mainimg', $xreaggregator_mainimg);
	$hl->setVar('xreaggregator_mainmax', $xreaggregator_mainmax);
	$hl->setVar('xreaggregator_blockimg', $xreaggregator_blockimg);
	$hl->setVar('xreaggregator_blockmax', $xreaggregator_blockmax);
	
	if (!$GLOBALS['xoopsSecurity']->check() || !$hlman->insert($hl)) {
		$msg = sprintf(_AM_FAILUPDATE, $hl->getVar('xreaggregator_name'));
		$msg .= '<br />'.$hl->getErrors();
		$msg .= '<br />'.implode('<br />', $GLOBALS['xoopsSecurity']->getErrors());
		xoops_cp_header();
		echo "<h4>"._AM_xreaggregatorS."</h4>";
		xoops_error($msg);
		xoops_cp_footer();
		exit();
	} else {
		if ($hl->getVar('xreaggregator_xml') == '') {
			$renderer =& xreaggregator_getrenderer($hl);
			$renderer->updateCache();
		}
	}
	redirect_header('index.php', 2, _AM_DBUPDATED);
}

if ($op == 'delete') {
	if ($xreaggregator_id <= 0) {
		xoops_cp_header();
		echo "<h4>"._AM_xreaggregatorS."</h4>";
		xoops_error(_AM_INVALIDID);
		xoops_cp_footer();
		exit();
	}
	$hlman =& xoops_getmodulehandler('xreaggregator');;
	$hl =& $hlman->get($xreaggregator_id);
	if (!is_object($hl)) {
		xoops_cp_header();
		echo "<h4>"._AM_xreaggregatorS."</h4>";
		xoops_error(_AM_OBJECTNG);
		xoops_cp_footer();
		exit();
	}
	xoops_cp_header();
	$name = $hl->getVar('xreaggregator_name');
	echo "<h4>"._AM_xreaggregatorS."</h4>";
	//echo '<a href="index.php">'. _AM_HLMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$name.'<br /><br />';
	xoops_confirm(array('op' => 'deletego', 'xreaggregator_id' => $hl->getVar('xreaggregator_id')), 'index.php', sprintf(_AM_WANTDEL, $name));
	xoops_cp_footer();
	exit();
}

if ($op == 'deletego') {
	$xreaggregator_id = intval($xreaggregator_id);
	if ($xreaggregator_id <= 0) {
		xoops_cp_header();
		echo "<h4>"._AM_xreaggregatorS."</h4>";
		xoops_error(_AM_INVALIDID);
		xoops_cp_footer();
		exit();
	}
	$hlman =& xoops_getmodulehandler('xreaggregator');;
	$hl =& $hlman->get($xreaggregator_id);
	if (!is_object($hl)) {
		xoops_cp_header();
		echo "<h4>"._AM_xreaggregatorS."</h4>";
		xoops_error(_AM_OBJECTNG);
		xoops_cp_footer();
		exit();
	}
	if (!$GLOBALS['xoopsSecurity']->check() || !$hlman->delete($hl)) {
		xoops_cp_header();
		echo "<h4>"._AM_xreaggregatorS."</h4>";
		xoops_error(sprintf(_AM_FAILDELETE, $hl->getVar('xreaggregator_name'))."<br />".implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
		xoops_cp_footer();
		exit();
	}
	redirect_header('index.php', 2, _AM_DBUPDATED);
}

?>