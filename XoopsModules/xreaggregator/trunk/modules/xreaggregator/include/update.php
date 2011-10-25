<?php
// $Id: update.php 2 2005-11-02 18:23:29Z skalpa $
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
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

function xoops_module_update_xreaggregator(&$module) {
	$result = $GLOBALS['xoopsDB']->queryF("CREATE TABLE ".$GLOBALS['xoopsDB']->prefix('xreaggregator_mashables')."         
(
	`id` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,       
	`cid` SMALLINT(5) DEFAULT '0',                         
	`name` VARCHAR(128) DEFAULT NULL,                    
	`groups` MEDIUMTEXT,                                 
	`domains` MEDIUMTEXT,                                
	`keywords` MEDIUMTEXT,                                	
	`weight` INT(5) DEFAULT '1',                         
	`display` TINYINT(1) DEFAULT '1',                    
	`random` TINYINT(1) DEFAULT '0',                     
	PRIMARY KEY (`id`)                                   
) ENGINE=MyISAM");

	$result = $GLOBALS['xoopsDB']->queryF("ALTER TABLE ".$GLOBALS['xoopsDB']->prefix('xreaggregator_mashables')." ADD COLUMN (`keywords` MEDIUMTEXT)");
	$result = $GLOBALS['xoopsDB']->queryF("ALTER TABLE ".$GLOBALS['xoopsDB']->prefix('xreaggregator_mashables')." ADD COLUMN (`cid` smallint(5))");	
	
	$result = $GLOBALS['xoopsDB']->queryF("ALTER TABLE ".$GLOBALS['xoopsDB']->prefix('xreaggregator')." ADD COLUMN (`xreaggregator_cid` smallint(5))");	
	
	$result = $GLOBALS['xoopsDB']->queryF("CREATE TABLE ".$GLOBALS['xoopsDB']->prefix('xreaggregator_categories')." (  
	 `cid` SMALLINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,          
	 `name` VARCHAR(128) DEFAULT NULL,             
	 `weight` SMALLINT(5) UNSIGNED NOT NULL,          	 
	 PRIMARY KEY (`cid`)                           
) ENGINE=MYISAM");

	$result = $GLOBALS['xoopsDB']->queryF("ALTER TABLE ".$GLOBALS['xoopsDB']->prefix('xreaggregator_categories')." ADD COLUMN (`display` tinyint(1))");	
	if ($result)
		$result = $GLOBALS['xoopsDB']->queryF("UPDATE ".$GLOBALS['xoopsDB']->prefix('xreaggregator_categories')." SET `display` = 1");	
	$result = $GLOBALS['xoopsDB']->queryF("ALTER TABLE ".$GLOBALS['xoopsDB']->prefix('xreaggregator_categories')." ADD COLUMN (`domains` MEDIUMTEXT)");	
	if ($result)
		$result = $GLOBALS['xoopsDB']->queryF("UPDATE ".$GLOBALS['xoopsDB']->prefix('xreaggregator_categories')." SET `domains` = '".serialize(array(0=>'all',1=>urlencode(XOOPS_URL)))."'");	

	$result = $GLOBALS['xoopsDB']->queryF("SELECT count(*) FROM ".$GLOBALS['xoopsDB']->prefix('xreaggregator_categories'));
	list($rc) = $GLOBALS['xoopsDB']->fetchRow($result);
	if ($rs==0){
		$result = $GLOBALS['xoopsDB']->queryF("INSERT INTO ".$GLOBALS['xoopsDB']->prefix('xreaggregator_categories')." VALUES (1, 'Upgrade Cat', 1)");	
		$result = $GLOBALS['xoopsDB']->queryF("UPDATE ".$GLOBALS['xoopsDB']->prefix('xreaggregator_mashables')." set cid = 1");	
		$result = $GLOBALS['xoopsDB']->queryF("UPDATE ".$GLOBALS['xoopsDB']->prefix('xreaggregator')." set xreaggregator_cid = 1");			
	}

    return true;
}

?>