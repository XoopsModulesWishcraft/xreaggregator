<?php

	$form = new XoopsThemeForm(_AM_ADDCATEGORY, 'xreaggregator_form_new', 'index.php', 'post', true);
	$form->addElement(new XoopsFormText(_AM_CATEGORYNAME, 'name', 50, 255), true);
	$form->addElement(new XoopsFormText(_AM_ORDER, 'weight', 4, 3, 0));	
	
	$form->insertBreak();
	$form->addElement(new XoopsFormHidden('fct', 'addgo'));
	$form->addElement(new XoopsFormHidden('op', 'categories'));	
	$form->addElement(new XoopsFormButton('', 'xreaggregator_submit2', _SUBMIT, 'submit'));
	$form->display();

?>
