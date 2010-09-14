<?php

	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	
	$form = new XoopsThemeForm(_AM_EDITHEADL, 'xreaggregator_form', 'index.php', 'post', true);
	$form->addElement(new XoopsFormText(_AM_SITENAME, 'xreaggregator_name', 50, 255, $hl->getVar('xreaggregator_name')), true);
	$form->addElement(new XoopsFormText(_AM_URL, 'xreaggregator_url', 50, 255, $hl->getVar('xreaggregator_url')), true);
	$form->addElement(new XoopsFormText(_AM_URLEDFXML, 'xreaggregator_rssurl', 50, 255, $hl->getVar('xreaggregator_rssurl')), true);
	
	$categories_handler = xoops_getmodulehandler('categories', 'xreaggregator');
	$categories = $categories_handler->getObjects(NULL);
	$cat_form = new XoopsFormSelect(_AM_CATEGORY, 'xreaggregator_cid');
	foreach($categories as $key => $category)
		$cat_form->addOption($category->getVar('cid'), $category->getVar('name'));
	$cat_form->setValue($hl->getVar('xreaggregator_cid'));
	$form->addElement($cat_form);

	$form->addElement(new XoopsFormText(_AM_ORDER, 'xreaggregator_weight', 4, 3, $hl->getVar('xreaggregator_weight')));
	$enc_sel = new XoopsFormSelect(_AM_ENCODING, 'xreaggregator_encoding', $hl->getVar('xreaggregator_encoding'));
	$enc_sel->addOptionArray(array('utf-8' => 'UTF-8', 'iso-8859-1' => 'ISO-8859-1', 'us-ascii' => 'US-ASCII'));
	$form->addElement($enc_sel);
	$cache_sel = new XoopsFormSelect(_AM_CACHETIME, 'xreaggregator_cachetime', $hl->getVar('xreaggregator_cachetime'));
	$cache_sel->addOptionArray(array('3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK, '2592000' => _MONTH));
	$form->addElement($cache_sel);

	if ($GLOBALS['xoopsModuleConfig']['support_multisite']&&class_exists('XoopsFormSelectDomains'))
	{
		$domains_sel = new XoopsFormSelectDomains(_AM_DOMAINS, 'xreaggregator_domains',  explode("|",$hl->getVar('xreaggregator_domains')), 10, true);
		$form->addElement($domains_sel);
	} else {
		$form->addElement(new XoopsFormHidden('xreaggregator_domains', $hl->getVar('xreaggregator_domains')));	
	}
	
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

	echo "<h4>"._AM_XREAGGREGATORS."</h4><br />";
	//echo '<a href="index.php">'. _AM_HLMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$hl->getVar('xreaggregator_name').'<br /><br />';
	$form->display();
?>