<?php

	$form = new XoopsThemeForm(_AM_ADDHEADL, 'xreaggregator_form_new', 'index.php', 'post', true);
	$form->addElement(new XoopsFormText(_AM_SITENAME, 'xreaggregator_name', 50, 255), true);
	$form->addElement(new XoopsFormText(_AM_URL, 'xreaggregator_url', 50, 255, 'http://'), true);
	$form->addElement(new XoopsFormText(_AM_URLEDFXML, 'xreaggregator_rssurl', 50, 255, 'http://'), true);
	$categories_handler = xoops_getmodulehandler('categories', 'xreaggregator');
	$categories = $categories_handler->getObjects(NULL);
	$cat_form = new XoopsFormSelect(_AM_CATEGORY, 'xreaggregator_cid');
	foreach($categories as $key => $category)
		$cat_form->addOption($category->getVar('cid'), $category->getVar('name'));
	$form->addElement($cat_form);	
	$form->addElement(new XoopsFormText(_AM_ORDER, 'xreaggregator_weight', 4, 3, 0));	$enc_sel = new XoopsFormSelect(_AM_ENCODING, 'xreaggregator_encoding', 'utf-8');
	$enc_sel->addOptionArray(array('utf-8' => 'UTF-8', 'iso-8859-1' => 'ISO-8859-1', 'us-ascii' => 'US-ASCII'));
	$form->addElement($enc_sel);
	$cache_sel = new XoopsFormSelect(_AM_CACHETIME, 'xreaggregator_cachetime', 86400);
	$cache_sel->addOptionArray(array('3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK, '2592000' => _MONTH));
	$form->addElement($cache_sel);
	
	if ($GLOBALS['xoopsModuleConfig']['support_multisite']&&class_exists('XoopsFormSelectDomains'))
	{
		$domains_sel = new XoopsFormSelectDomains(_AM_DOMAINS, 'xreaggregator_domains[]', 10, true);
		$form->addElement($domains_sel);
	} else {
		foreach(array('0' => 'all', '1' => urlencode(XOOPS_URL)) as $key => $value)
			$form->addElement(new XoopsFormHidden('xreaggregator_domains['.$key.']', $value));	
	}
			
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

?>