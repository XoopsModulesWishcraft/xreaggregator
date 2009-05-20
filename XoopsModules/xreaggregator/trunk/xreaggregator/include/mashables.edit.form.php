<?php

	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	$form = new XoopsThemeForm(_AM_EDITHEADL, 'mashables_form', 'index.php', 'post', true);
	$form->addElement(new XoopsFormText(_AM_SITENAME, 'name', 50, 255, $ml->getVar('name')), true);
	$form->addElement(new XoopsFormText(_AM_ORDER, 'weight', 4, 3, $ml->getVar('weight')));
	$enc_sel = new XoopsFormCheckBox(_AM_MASHGROUP, 'groups[]','', '<br />');
	for ($i = 0; $i < count($xreaggregators); $i++)
		$enc_sel->addOption($xreaggregators[$i]->getVar('xreaggregator_id'), $xreaggregators[$i]->getVar('xreaggregator_name'));

	$enc_sel->setValue($ml->getVar('groups'));
	$form->addElement($enc_sel);

	if (defined('XOOPS_DOMAIN_ID')&&class_exists('XoopsFormCheckBoxDomains'))
	{
		$domains_sel = new XoopsFormCheckBoxDomains(_AM_DOMAINS, 'domains', $ml->getVar('domains'));
		$form->addElement($domains_sel);
	} else {
		foreach($ml->getVar('domains') as $key => $value)
			$form->addElement(new XoopsFormHidden('domains['.$key.']', $value));	
	}
		
	$form->addElement(new XoopsFormRadioYN(_AM_DISPLAY, 'display', $ml->getVar('display'), _YES, _NO));
	$form->addElement(new XoopsFormRadioYN(_AM_RANDOM, 'random', $ml->getVar('random'), _YES, _NO));

	$form->addElement($bmax_sel);
	$form->insertBreak();
	$form->addElement(new XoopsFormHidden('id', $ml->getVar('id')));
	$form->addElement(new XoopsFormHidden('fct', 'editgo'));
	$form->addElement(new XoopsFormHidden('op', 'mashables'));	
	$form->addElement(new XoopsFormButton('', 'xreaggregator_submit', _SUBMIT, 'submit'));

	echo "<h4>"._AM_XREAGGREGATORS_MASHABLES."</h4><br />";

	$form->display();
?>