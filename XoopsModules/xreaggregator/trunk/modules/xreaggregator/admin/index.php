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


if (isset($_REQUEST)) {
	foreach ($_REQUEST as $k => $v) {
  		${$k} = $v;
	}
}



if (isset($_REQUEST['op'])) {

	$op = $_REQUEST['op'];

	$xreaggregator_id = intval($_REQUEST['xreaggregator_id']);

}

if ($op == 'mashables') {



	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';

	$hlman =& xoops_getmodulehandler('xreaggregator');

	$mlman =& xoops_getmodulehandler('mashables');			

	$xreaggregators =& $hlman->getObjects();

	$xreagcount = count($xreaggregators);

	$mashables =& $mlman->getObjects();

	$mashcount = count($mashables);

	

	$fct = $_REQUEST['fct'];

	

	switch ($fct) {

	

		case 'edit':

			if ($id <= 0) {

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_MASHABLES."</h4>";

				xoops_error(_AM_INVALIDID);

				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}

			$ml =& $mlman->get($id);

			if (!is_object($ml)) {

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_MASHABLES."</h4>";

				xoops_error(_AM_OBJECTNG);

				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}



			xoops_cp_header();

			adminMenu(2);

			include('../include/mashables.edit.form.php');

			footer_adminMenu();

			echo chronolabs_inline(false);
			
			xoops_cp_footer();

			exit();

		

			break;

					

		case 'editgo':

			$id = intval($id);

			if ($id <= 0) {

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS."</h4>";

				xoops_error(_AM_INVALIDID);
				
				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}

			$ml =& $mlman->get($id);

			if (!is_object($ml)) {

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_MASHABLES."</h4>";

				xoops_error(_AM_OBJECTNG);

				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}

			$ml->setVar('name', $name);

			$ml->setVar('cid', $cid);				

			$ml->setVar('keywords', $keywords);								

			$ml->setVar('display', $display);

			$ml->setVar('random', $random);				

			$ml->setVar('weight', $weight);

			$ml->setVar('groups', $groups);

			$ml->setVar('domains', $domains);	

			

			if (!$GLOBALS['xoopsSecurity']->check() || !$mlman->insert($ml)) {

				$msg = sprintf(_AM_FAILUPDATE, $ml->getVar('name'));

				$msg .= '<br />'.$ml->getErrors();

				$msg .= '<br />'.implode('<br />', $GLOBALS['xoopsSecurity']->getErrors());

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_MASHABLES."</h4>";

				xoops_error($msg);

				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}

			redirect_header('index.php?op=mashables', 2, _AM_DBUPDATED);



			break;

		case 'delete':

			if ($id <= 0) {

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_MASHABLES."</h4>";

				xoops_error(_AM_INVALIDID);
				
				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}

			$ml =& $mlman->get($id);

			if (!is_object($ml)) {

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_MASHABLES."</h4>";

				xoops_error(_AM_OBJECTNG);

				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}

			xoops_cp_header();

			$name = $ml->getVar('name');

			echo "<h4>"._AM_XREAGGREGATORS_MASHABLES."</h4>";

			//echo '<a href="index.php">'. _AM_HLMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$name.'<br /><br />';

			xoops_confirm(array('fct' => 'deletego', 'op' => 'mashables', 'id' => $ml->getVar('id')), 'index.php', sprintf(_AM_WANTDEL, $name));

			echo chronolabs_inline(false);
			
			xoops_cp_footer();

			exit();

			break;

		

		case 'deletego':

			$id = intval($id);

			if ($id <= 0) {

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_MASHABLES."</h4>";

				xoops_error(_AM_INVALIDID);

				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}

			$ml =& $mlman->get($id);

			if (!is_object($ml)) {

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_MASHABLES."</h4>";

				xoops_error(_AM_OBJECTNG);

				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}

			if (!$GLOBALS['xoopsSecurity']->check() || !$mlman->delete($ml)) {

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_MASHABLES."</h4>";

				xoops_error(sprintf(_AM_FAILDELETE, $ml->getVar('name'))."<br />".implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));

				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}

			redirect_header('index.php?op=mashables', 2, _AM_DBUPDATED);

			break;

		

		case 'addgo':

			if ($GLOBALS['xoopsSecurity']->check()) {

				$ml =& $mlman->create();

				$ml->setVar('name', $name);

				$ml->setVar('cid', $cid);				

				$ml->setVar('keywords', $keywords);								

				$ml->setVar('groups', $groups);

				$ml->setVar('domains', $domains);

				$ml->setVar('display', $display);

				$ml->setVar('weight', $weight);

				$ml->setVar('random', $random);

				@$mlman->insert($ml);

			} else {

				redirect_header('index.php?op=mashables', 2, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));

			}

	

			redirect_header('index.php?op=mashables', 2, _AM_DBUPDATED);

			break;

			

		case 'update':

			$i = 0;

			$msg = '';

			foreach ($_POST['id'] as $id => $value) {

				$ml =& $mlman->get($id);

				if (!is_object($ml)) {

					$i++;

					continue;

				}

				$display[$id] = empty($display[$id]) ? 0 : $display[$id];

				$random[$id] = empty($random[$id]) ? 0 : $random[$id];				

				$ml->setVar('name', $name[$id]);

				$ml->setVar('weight', $weight[$id]);

				$ml->setVar('groups', $group[$id]);

				$ml->setVar('domains', $domains[$id]);				

				$ml->setVar('random', $random[$id]);

				$ml->setVar('groups', $groups[$id]);



				if (!$mlman->insert($ml)) {

					$msg .= '<br />'.sprintf(_AM_FAILUPDATE, $ml->getVar('name'));

				}

				$i++;

			}

			if ($msg != '') {

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_MASHABLES."</h4>";

				xoops_error($msg);

				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}

			redirect_header('index.php?op=mashables', 2, _AM_DBUPDATED);



			break;

			

		case "list":

		default:

			xoops_cp_header();

			adminMenu(2);

			include('../include/mashables.list.form.php');

			

			include('../include/mashables.addnew.form.php');

			footer_adminMenu();

			echo chronolabs_inline(false);
			
			xoops_cp_footer();

			exit();

	}

} elseif ($op == 'categories') {



	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';

	$hlman =& xoops_getmodulehandler('xreaggregator');

	$clman =& xoops_getmodulehandler('categories');			

	$xreaggregators =& $hlman->getObjects();

	$xreagcount = count($xreaggregators);

	$categories =& $clman->getObjects();

	$catcount = count($categories);

	

	$fct = $_REQUEST['fct'];

	

	switch ($fct) {

	

		case 'edit':

			if ($id <= 0) {

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_CATEGORIES."</h4>";

				xoops_error(_AM_INVALIDID);

				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}

			$cl =& $clman->get($id);

			if (!is_object($cl)) {

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_CATEGORIES."</h4>";

				xoops_error(_AM_OBJECTNG);
				
				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}



			xoops_cp_header();

			adminMenu(3);

			include('../include/categories.edit.form.php');

			footer_adminMenu();

			echo chronolabs_inline(false);
			
			xoops_cp_footer();

			exit();

		

			break;

					

		case 'editgo':

			$id = intval($id);

			if ($id <= 0) {

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_CATEGORIES."</h4>";

				xoops_error(_AM_INVALIDID);

				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}

			$cl =& $clman->get($id);

			if (!is_object($cl)) {

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_CATEGORIES."</h4>";

				xoops_error(_AM_OBJECTNG);

				xoops_cp_footer();

				exit();

			}

			$cl->setVar('name', $name);

			$cl->setVar('weight', $weight);

			$cl->setVar('domains', $domains);

			$cl->setVar('display', $display);		

			if (!$GLOBALS['xoopsSecurity']->check() || !$clman->insert($cl)) {

				$msg = sprintf(_AM_FAILUPDATE, $cl->getVar('name'));

				$msg .= '<br />'.$cl->getErrors();

				$msg .= '<br />'.implode('<br />', $GLOBALS['xoopsSecurity']->getErrors());

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_CATEGORIES."</h4>";

				xoops_error($msg);

				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}

			redirect_header('index.php?op=categories', 2, _AM_DBUPDATED);



			break;

		case 'delete':

			if ($id <= 0) {

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_CATEGORIES."</h4>";

				xoops_error(_AM_INVALIDID);

				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}

			$cl =& $clman->get($id);

			if (!is_object($cl)) {

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_CATEGORIES."</h4>";

				xoops_error(_AM_OBJECTNG);

				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}

			xoops_cp_header();

			$name = $cl->getVar('name');

			echo "<h4>"._AM_XREAGGREGATORS_CATEGORIES."</h4>";

			xoops_confirm(array('fct' => 'deletego', 'op' => 'categories', 'id' => $id), 'index.php', sprintf(_AM_WANTDEL, $name));

			echo chronolabs_inline(false);
			
			xoops_cp_footer();

			exit();

			break;

		

		case 'deletego':

			$id = intval($id);

			if ($id <= 0) {

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_CATEGORIES."</h4>";

				xoops_error(_AM_INVALIDID);

				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}

			$cl =& $clman->get($id);

			if (!is_object($cl)) {

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_CATEGORIES."</h4>";

				xoops_error(_AM_OBJECTNG);

				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}

			if (!$GLOBALS['xoopsSecurity']->check() || !$clman->delete($cl)) {

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_CATEGORIES."</h4>";

				xoops_error(sprintf(_AM_FAILDELETE, $cl->getVar('name'))."<br />".implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));

				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}

			redirect_header('index.php?op=categories', 2, _AM_DBUPDATED);

			break;

		

		case 'addgo':

			if ($GLOBALS['xoopsSecurity']->check()) {

				$cl =& $clman->create();

				$cl->setVar('name', $name);

				$cl->setVar('weight', $weight);

				$cl->setVar('domains', $domains);

				$cl->setVar('display', $display);

				@$clman->insert($cl);

			} else {

				redirect_header('index.php?op=categories', 2, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));

			}

	

			redirect_header('index.php?op=categories', 2, _AM_DBUPDATED);

			break;

			

		case 'update':

			$i = 0;

			$msg = '';

			foreach ($_POST['id'] as $id => $value) {

				$cl =& $clman->get($id);

				if (!is_object($cl)) {

					$i++;

					continue;

				}

				$cl->setVar('name', $name[$id]);

				$cl->setVar('weight', $weight[$id]);



				if (!$clman->insert($cl)) {

					$msg .= '<br />'.sprintf(_AM_FAILUPDATE, $cl->getVar('name'));

				}

				$i++;

			}

			if ($msg != '') {

				xoops_cp_header();

				echo "<h4>"._AM_XREAGGREGATORS_CATEGORIES."</h4>";

				xoops_error($msg);

				echo chronolabs_inline(false);
				
				xoops_cp_footer();

				exit();

			}

			redirect_header('index.php?op=categories', 2, _AM_DBUPDATED);



			break;

			

		case "list":

		default:

			xoops_cp_header();

			adminMenu(3);

			include('../include/categories.list.form.php');

			

			include('../include/categories.addnew.form.php');

			footer_adminMenu();

			echo chronolabs_inline(false);
			
			xoops_cp_footer();

			exit();

	}

} elseif ($op == 'list') {



	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';

	$hlman =& xoops_getmodulehandler('xreaggregator');

	$xreaggregators =& $hlman->getObjects();

	$count = count($xreaggregators);

	

	xoops_cp_header();

	adminMenu(1);

	include('../include/aggregation.list.form.php');

	

	include('../include/aggregation.addnew.form.php');

	footer_adminMenu();

	echo chronolabs_inline(false);
	
	xoops_cp_footer();

	exit();

} elseif ($op == 'update') {

	$hlman =& xoops_getmodulehandler('xreaggregator');;

	$i = 0;

	$msg = '';

	foreach ($_POST['xreaggregator_id'] as $id => $value) {

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

		$hl->setVar('xreaggregator_weight', $xreaggregator_weight[$id]);

		$old_asblock = $hl->getVar('xreaggregator_asblock');

		$hl->setVar('xreaggregator_asblock', $xreaggregator_asblock[$id]);

		$old_encoding = $hl->getVar('xreaggregator_encoding');

		$hl->setVar('xreaggregator_encoding', $xreaggregator_encoding[$i]);

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

		echo "<h4>"._AM_XREAGGREGATORS."</h4>";

		xoops_error($msg);

		echo chronolabs_inline(false);
		
		xoops_cp_footer();

		exit();

	}

	redirect_header('index.php', 2, _AM_DBUPDATED);

} elseif ($op == 'addgo') {

    if ($GLOBALS['xoopsSecurity']->check()) {

        $hlman =& xoops_getmodulehandler('xreaggregator');;

        $hl =& $hlman->create();

		$hl->setVar('xreaggregator_cid', $xreaggregator_cid);

        $hl->setVar('xreaggregator_name', $xreaggregator_name);

        $hl->setVar('xreaggregator_url', $xreaggregator_url);

        $hl->setVar('xreaggregator_rssurl', $xreaggregator_rssurl);

        $hl->setVar('xreaggregator_display', $xreaggregator_display);

        $hl->setVar('xreaggregator_weight', $xreaggregator_weight);

        $hl->setVar('xreaggregator_asblock', $xreaggregator_asblock);

        $hl->setVar('xreaggregator_encoding', $xreaggregator_encoding);

        $hl->setVar('xreaggregator_cachetime', $xreaggregator_cachetime);

		$hl->setVar('xreaggregator_domains', '|'.implode('|',$xreaggregator_domains));	

        $hl->setVar('xreaggregator_mainfull', $xreaggregator_mainfull);

        $hl->setVar('xreaggregator_mainimg', $xreaggregator_mainimg);

        $hl->setVar('xreaggregator_mainmax', $xreaggregator_mainmax);

        $hl->setVar('xreaggregator_blockimg', $xreaggregator_blockimg);

        $hl->setVar('xreaggregator_blockmax', $xreaggregator_blockmax);

        if (!$hlman->insert($hl)) {

            $msg = sprintf(_AM_FAILUPDATE, $hl->getVar('xreaggregator_name'));

            $msg .= '<br />'.$hl->getErrors();

            xoops_cp_header();

            echo "<h4>"._AM_XREAGGREGATORS."</h4>";

            xoops_error($msg);

            echo chronolabs_inline(false);
			
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

} elseif ($op == 'edit') {

	if ($xreaggregator_id <= 0) {

		xoops_cp_header();

		echo "<h4>"._AM_XREAGGREGATORS."</h4>";

		xoops_error(_AM_INVALIDID);

		echo chronolabs_inline(false);
		
		xoops_cp_footer();

		exit();

	}

	$hlman =& xoops_getmodulehandler('xreaggregator');;

	$hl =& $hlman->get($xreaggregator_id);

	if (!is_object($hl)) {

		xoops_cp_header();

		echo "<h4>"._AM_XREAGGREGATORS."</h4>";

		xoops_error(_AM_OBJECTNG);

		echo chronolabs_inline(false);
		
		xoops_cp_footer();

		exit();

	}

	xoops_cp_header();

	adminMenu(1);

	include('../include/aggregation.edit.form.php');

	footer_adminMenu();

	xoops_cp_footer();

	exit();

} elseif ($op == 'editgo') {

	$xreaggregator_id = intval($xreaggregator_id);

	if ($xreaggregator_id <= 0) {

		xoops_cp_header();

		echo "<h4>"._AM_XREAGGREGATORS."</h4>";

		xoops_error(_AM_INVALIDID);

		echo chronolabs_inline(false);
		
		xoops_cp_footer();

		exit();

	}

	$hlman =& xoops_getmodulehandler('xreaggregator');;

	$hl =& $hlman->get($xreaggregator_id);

	if (!is_object($hl)) {

		xoops_cp_header();

		echo "<h4>"._AM_XREAGGREGATORS."</h4>";

		xoops_error(_AM_OBJECTNG);

		echo chronolabs_inline(false);
		
		xoops_cp_footer();

		exit();

	}

	$hl->setVar('xreaggregator_cid', $xreaggregator_cid);

	$hl->setVar('xreaggregator_name', $xreaggregator_name);

	$hl->setVar('xreaggregator_url', $xreaggregator_url);

	$hl->setVar('xreaggregator_encoding', $xreaggregator_encoding);

	$hl->setVar('xreaggregator_rssurl', $xreaggregator_rssurl);

	$hl->setVar('xreaggregator_display', $xreaggregator_display);

	$hl->setVar('xreaggregator_weight', $xreaggregator_weight);

	$hl->setVar('xreaggregator_asblock', $xreaggregator_asblock);

	$hl->setVar('xreaggregator_cachetime', $xreaggregator_cachetime);

	$hl->setVar('xreaggregator_domains', '|'.implode('|',$xreaggregator_domains));	

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

		echo "<h4>"._AM_XREAGGREGATORS."</h4>";

		xoops_error($msg);

		echo chronolabs_inline(false);
		
		xoops_cp_footer();

		exit();

	} else {

		if ($hl->getVar('xreaggregator_xml') == '') {

			$renderer =& xreaggregator_getrenderer($hl);

			$renderer->updateCache();

		}

	}

	redirect_header('index.php', 2, _AM_DBUPDATED);

} elseif ($op == 'delete') {

	if ($xreaggregator_id <= 0) {

		xoops_cp_header();

		echo "<h4>"._AM_XREAGGREGATORS."</h4>";

		xoops_error(_AM_INVALIDID);

		echo chronolabs_inline(false);
		
		xoops_cp_footer();

		exit();

	}

	$hlman =& xoops_getmodulehandler('xreaggregator');;

	$hl =& $hlman->get($xreaggregator_id);

	if (!is_object($hl)) {

		xoops_cp_header();

		echo "<h4>"._AM_XREAGGREGATORS."</h4>";

		xoops_error(_AM_OBJECTNG);

		echo chronolabs_inline(false);
		
		xoops_cp_footer();

		exit();

	}

	xoops_cp_header();

	$name = $hl->getVar('xreaggregator_name');

	echo "<h4>"._AM_XREAGGREGATORS."</h4>";

	//echo '<a href="index.php">'. _AM_HLMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$name.'<br /><br />';

	xoops_confirm(array('op' => 'deletego', 'xreaggregator_id' => $hl->getVar('xreaggregator_id')), 'index.php', sprintf(_AM_WANTDEL, $name));

	xoops_cp_footer();

	exit();

} elseif ($op == 'deletego') {

	$xreaggregator_id = intval($xreaggregator_id);

	if ($xreaggregator_id <= 0) {

		xoops_cp_header();

		echo "<h4>"._AM_XREAGGREGATORS."</h4>";

		xoops_error(_AM_INVALIDID);

		echo chronolabs_inline(false);
		
		xoops_cp_footer();

		exit();

	}

	$hlman =& xoops_getmodulehandler('xreaggregator');;

	$hl =& $hlman->get($xreaggregator_id);

	if (!is_object($hl)) {

		xoops_cp_header();

		echo "<h4>"._AM_XREAGGREGATORS."</h4>";

		xoops_error(_AM_OBJECTNG);

		echo chronolabs_inline(false);
		
		xoops_cp_footer();

		exit();

	}

	if (!$GLOBALS['xoopsSecurity']->check() || !$hlman->delete($hl)) {

		xoops_cp_header();

		echo "<h4>"._AM_XREAGGREGATORS."</h4>";

		xoops_error(sprintf(_AM_FAILDELETE, $hl->getVar('xreaggregator_name'))."<br />".implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));

		echo chronolabs_inline(false);
		
		xoops_cp_footer();

		exit();

	}

	redirect_header('index.php', 2, _AM_DBUPDATED);

}



?>