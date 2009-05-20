<?php

	$form = new XoopsThemeForm(_AM_ADDMASHABLE, 'xreaggregator_form_new', 'index.php', 'post', true);
	$form->addElement(new XoopsFormText(_AM_MASHNAME, 'name', 50, 255), true);
	$form->addElement(new XoopsFormText(_AM_ORDER, 'weight', 4, 3, 0));	
	
	$enc_sel = new XoopsFormCheckBox(_AM_MASHGROUP, 'groups[]', '', '<br />');
	for ($i = 0; $i < count($xreaggregators); $i++)
		$enc_sel->addOption($xreaggregators[$i]->getVar('xreaggregator_id'), $xreaggregators[$i]->getVar('xreaggregator_name'));
	$form->addElement($enc_sel);

	if (defined('XOOPS_DOMAIN_ID')&&class_exists('XoopsFormCheckBoxDomains'))
	{
		$domains_sel = new XoopsFormCheckBoxDomains(_AM_DOMAINS, 'domains[]');
		$form->addElement($domains_sel);
	} else {
		foreach($ml->getVar('domains') as $key => $value)
			$form->addElement(new XoopsFormHidden('domains['.$key.']', $value));	
	}
		
	$form->addElement(new XoopsFormRadioYN(_AM_DISPLAY, 'display', 1, _YES, _NO));
	$form->addElement(new XoopsFormRadioYN(_AM_RANDOM, 'random', 0, _YES, _NO));
	$form->addElement($bmax_sel);


	$form->insertBreak();
	$form->addElement(new XoopsFormHidden('fct', 'addgo'));
	$form->addElement(new XoopsFormHidden('op', 'mashables'));	
	$form->addElement(new XoopsFormButton('', 'xreaggregator_submit2', _SUBMIT, 'submit'));
	$form->display();

?>
