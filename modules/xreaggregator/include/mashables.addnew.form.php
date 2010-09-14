<?php

	error_reporting(E_ALL);
	
	$form = new XoopsThemeForm(_AM_ADDMASHABLE, 'xreaggregator_form_new', 'index.php', 'post', true);
	$form->addElement(new XoopsFormText(_AM_MASHNAME, 'name', 50, 255), true);
	$form->addElement(new XoopsFormText(_AM_ORDER, 'weight', 4, 3, 0));	
	
	$enc_sel = new XoopsFormCheckBox(_AM_MASHGROUP, 'groups[]', '', '<br />');
	for ($i = 0; $i < count($xreaggregators); $i++)
		$enc_sel->addOption($xreaggregators[$i]->getVar('xreaggregator_id'), $xreaggregators[$i]->getVar('xreaggregator_name'));
	$form->addElement($enc_sel);

	if ($GLOBALS['xoopsModuleConfig']['support_multisite']&&class_exists('XoopsFormSelectDomains'))
	{
		$domains_sel = new XoopsFormSelectDomains(_AM_DOMAINS, 'domains[]', 10, true);
		$form->addElement($domains_sel);
	} else {
		foreach(array('0' => 'all', '1' => urlencode(XOOPS_URL)) as $key => $value)
			$form->addElement(new XoopsFormHidden('domains['.$key.']', $value));	
	}

	$categories_handler = xoops_getmodulehandler('categories', 'xreaggregator');
	$categories = $categories_handler->getObjects(NULL);
	$cat_form = new XoopsFormSelect(_AM_CATEGORY, 'cid');
	foreach($categories as $key => $category)
		$cat_form->addOption($category->getVar('cid'), $category->getVar('name'));
	$form->addElement($cat_form);	
	$form->addElement(new XoopsFormTextArea(_AM_KEYWORDS, 'keywords'));
	$form->addElement(new XoopsFormRadioYN(_AM_DISPLAY, 'display', 1, _YES, _NO));
	$form->addElement(new XoopsFormRadioYN(_AM_RANDOM, 'random', 0, _YES, _NO));
	$form->addElement($bmax_sel);


	$form->insertBreak();
	$form->addElement(new XoopsFormHidden('fct', 'addgo'));
	$form->addElement(new XoopsFormHidden('op', 'mashables'));	
	$form->addElement(new XoopsFormButton('', 'xreaggregator_submit2', _SUBMIT, 'submit'));
	$form->display();

?>
