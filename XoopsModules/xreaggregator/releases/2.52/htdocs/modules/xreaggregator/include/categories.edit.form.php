<?php



	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';

	$form = new XoopsThemeForm(_AM_EDITCATEGORY, 'category_form', 'index.php', 'post', true);

	$form->addElement(new XoopsFormText(_AM_CATEGORYNAME, 'name', 50, 255, $cl->getVar('name')), true);

	$form->addElement(new XoopsFormText(_AM_ORDER, 'weight', 4, 3, $cl->getVar('weight')));

	

	if ($GLOBALS['xoopsModuleConfig']['support_multisite']&&class_exists('XoopsFormSelectDomains'))

	{

		$domains_sel = new XoopsFormSelectDomains(_AM_DOMAINS, 'domains', $cl->getVar('domains'), 10, true);

		$form->addElement($domains_sel);

	} else {

		foreach($cl->getVar('domains') as $key => $value)

			$form->addElement(new XoopsFormHidden('domains['.$key.']', $value));	

	}

		

	$form->insertBreak();

	$form->addElement(new XoopsFormRadioYN(_AM_DISPLAY, 'display', $cl->getVar('display'), _YES, _NO));

	$form->addElement(new XoopsFormHidden('id', $cl->getVar('cid')));

	$form->addElement(new XoopsFormHidden('fct', 'editgo'));

	$form->addElement(new XoopsFormHidden('op', 'categories'));	

	$form->addElement(new XoopsFormButton('', 'xreaggregator_submit', _SUBMIT, 'submit'));



	echo "<h4>"._AM_XREAGGREGATORS_CATEGORIES."</h4><br />";



	$form->display();

?>