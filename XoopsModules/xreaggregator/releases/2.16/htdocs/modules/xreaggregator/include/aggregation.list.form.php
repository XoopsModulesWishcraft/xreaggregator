<?php

	echo "<h4>"._AM_XREAGGREGATORS."</h4>";
	echo '<form name="xreaggregator_form" action="index.php" method="post"><table><tr><td>'._AM_SITENAME.'</td><td>'._AM_CACHETIME.'</td><td>'._AM_ENCODING.'</td><td>'._AM_DISPLAY.'</td><td>'._AM_ASBLOCK.'</td><td>'._AM_ORDER.'</td><td>&nbsp;</td></tr>';
	for ($i = 0; $i < count($xreaggregators); $i++) {
		echo '<tr><td>'.$xreaggregators[$i]->getVar('xreaggregator_name').'</td>
		<td><select name="xreaggregator_cachetime[]">';
		$cachetime = array('3600' => sprintf(_HOUR, 1), '18000' => sprintf(_HOURS, 5), '86400' => sprintf(_DAY, 1), '259200' => sprintf(_DAYS, 3), '604800' => sprintf(_WEEK, 1), '2592000' => sprintf(_MONTH, 1));
		foreach ($cachetime as $value => $name) {
			echo '<option value="'.$value.'"';
			if ($value == $xreaggregators[$i]->getVar('xreaggregator_cachetime')) {
				echo ' selected="selecetd"';
			}
			echo '>'.$name.'</option>';
		}
		echo '</select></td>
		<td><select name="xreaggregator_encoding[]">';
		$encodings = array('utf-8' => 'UTF-8', 'iso-8859-1' => 'ISO-8859-1', 'us-ascii' => 'US-ASCII');
		foreach ($encodings as $value => $name) {
			echo '<option value="'.$value.'"';
			if ($value == $xreaggregators[$i]->getVar('xreaggregator_encoding')) {
				echo ' selected="selecetd"';
			}
			echo '>'.$name.'</option>';
		}
		echo '</select></td>';
		echo '<td><input type="checkbox" value="1" name="xreaggregator_display['.$xreaggregators[$i]->getVar('xreaggregator_id').']"';
		if (1 == $xreaggregators[$i]->getVar('xreaggregator_display')) {
			echo ' checked="checked"';
		}
		echo ' /></td>';
		echo '<td><input type="checkbox" value="1" name="xreaggregator_asblock['.$xreaggregators[$i]->getVar('xreaggregator_id').']"';
		if (1 == $xreaggregators[$i]->getVar('xreaggregator_asblock')) {
			echo ' checked="checked"';
		}
		echo ' /></td>';
		echo '<td><input type="text" maxlength="3" size="4" name="xreaggregator_weight['.$xreaggregators[$i]->getVar('xreaggregator_id').']" value="'.$xreaggregators[$i]->getVar('xreaggregator_weight').'" /><td><a href="index.php?op=edit&amp;xreaggregator_id='.$xreaggregators[$i]->getVar('xreaggregator_id').'">'._EDIT.'</a>&nbsp;<a href="index.php?op=delete&amp;xreaggregator_id='.$xreaggregators[$i]->getVar('xreaggregator_id').'">'._DELETE.'</a><input type="hidden" name="xreaggregator_id['.$xreaggregators[$i]->getVar('xreaggregator_id').']" value="'.$xreaggregators[$i]->getVar('xreaggregator_id').'" /></td></tr>';
	}
	echo '</table><div style="text-align:center"><input type="hidden" name="op" value="update" /><input type="submit" name="xreaggregator_submit" value="'._SUBMIT.'" /></div></form>';

?>