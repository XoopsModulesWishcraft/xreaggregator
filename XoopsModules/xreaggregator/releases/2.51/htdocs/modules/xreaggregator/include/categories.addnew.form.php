<?php



	$form = new XoopsThemeForm(_AM_ADDCATEGORY, 'xreaggregator_form_new', 'index.php', 'post', true);

	$form->addElement(new XoopsFormText(_AM_CATEGORYNAME, 'name', 50, 255), true);

	$form->addElement(new XoopsFormText(_AM_ORDER, 'weight', 4, 3, 0));	

	

	if ($GLOBALS['xoopsModuleConfig']['support_multisite']&&class_exists('XoopsFormSelectDomains'))

	{

		$domains_sel = new XoopsFormSelectDomains(_AM_DOMAINS, 'domains[]', 10, true);

		$form->addElement($domains_sel);

	} else {

		foreach(array('0' => 'all', '1' => urlencode(XOOPS_URL)) as $key => $value)

			$form->addElement(new XoopsFormHidden('domains['.$key.']', $value));	

	}

	

	$form->insertBreak();

	$form->addElement(new XoopsFormRadioYN(_AM_DISPLAY, 'display', 1, _YES, _NO));	

	$form->addElement(new XoopsFormHidden('fct', 'addgo'));

	$form->addElement(new XoopsFormHidden('op', 'categories'));	

	$form->addElement(new XoopsFormButton('', 'xreaggregator_submit2', _SUBMIT, 'submit'));

	$form->display();



?>

