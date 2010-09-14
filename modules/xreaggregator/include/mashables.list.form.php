<?php

	echo "<h4>"._AM_XREAGGREGATORS_MASHABLES."</h4>";
	echo '<form name="mashables_form" action="index.php" method="post"><table><tr class="head"><td>'._AM_MASHNAME.'</td><td>'._AM_MASHGROUP.'</td><td>'._AM_DISPLAY.'</td><td>'._AM_RANDOM.'</td><td>'._AM_ORDER.'</td><td>&nbsp;</td></tr>';
	for ($i = 0; $i < count($mashables); $i++) {
		$class = ($class="even")?"odd":"even";
		echo '<tr class="'.$class.'"><td>'.$mashables[$i]->getVar('name').'</td>
		<td><select name="groups['.$mashables[$i]->getVar('id').'][]" multiple="1" size="4">';
			for ($ii = 0; $ii < count($xreaggregators); $ii++)
				$cachetime[$xreaggregators[$ii]->getVar('xreaggregator_id')] =  $xreaggregators[$ii]->getVar('xreaggregator_name');
		foreach ($cachetime as $value => $name) {
			echo '<option value="'.$value.'"';
			if (in_array($value, $mashables[$i]->getVar('groups'))) {
				echo ' selected="selected"';
			}
			echo '>'.$name.'</option>';
		}
		echo '</select></td>';
		echo '<td><input type="checkbox" value="1" name="display['.$mashables[$i]->getVar('id').']"';
		if (1 == $mashables[$i]->getVar('display')) {
			echo ' checked="checked"';
		}
		echo ' /></td>';
		echo '<td><input type="checkbox" value="1" name="random['.$mashables[$i]->getVar('id').']"';
		if (1 == $mashables[$i]->getVar('random')) {
			echo ' checked="checked"';
		}
		echo ' /></td>';		
		echo '<td><input type="text" maxlength="3" size="4" name="weight['.$mashables[$i]->getVar('id').']" value="'.$mashables[$i]->getVar('weight').'" /><td><a href="index.php?op=mashables&amp;fct=edit&amp;id='.$mashables[$i]->getVar('id').'">'._EDIT.'</a>&nbsp;<a href="index.php?op=mashables&amp;fct=delete&amp;id='.$mashables[$i]->getVar('id').'">'._DELETE.'</a><input type="hidden" name="id['.$mashables[$i]->getVar('id').']" value="'.$mashables[$i]->getVar('id').'" /></td></tr>';
	}
	echo '</table><div style="text-align:center"><input type="hidden" name="op" value="mashables" /><input type="hidden" name="fct" value="update" /><input type="submit" name="mashables_submit" value="'._SUBMIT.'" /></div></form>';

?>