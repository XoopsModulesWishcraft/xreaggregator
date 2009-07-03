<?php

	echo "<h4>"._AM_XREAGGREGATORS_CATEGORIES."</h4>";
	echo '<form name="categories_form" action="index.php" method="post"><table><tr class="head"><td>'._AM_CATEGORYNAME.'</td><td>'._AM_ORDER.'</td><td>&nbsp;</td></tr>';
	for ($i = 0; $i < count($categories); $i++) {
		$class = ($class="even")?"odd":"even";
		echo '<tr class="'.$class.'"><td>'.$categories[$i]->getVar('name').'</td>';
		echo '<td><input type="text" maxlength="3" size="4" name="weight['.$categories[$i]->getVar('cid').']" value="'.$categories[$i]->getVar('weight').'" /><td><a href="index.php?op=categories&amp;fct=edit&amp;id='.$categories[$i]->getVar('cid').'">'._EDIT.'</a>&nbsp;<a href="index.php?op=categories&amp;fct=delete&amp;id='.$categories[$i]->getVar('cid').'">'._DELETE.'</a><input type="hidden" name="id['.$categories[$i]->getVar('cid').']" value="'.$categories[$i]->getVar('cid').'" /></td></tr>';
	}
	echo '</table><div style="text-align:center"><input type="hidden" name="op" value="categories" /><input type="hidden" name="fct" value="update" /><input type="submit" name="categories_submit" value="'._SUBMIT.'" /></div></form>';

?>