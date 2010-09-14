<?php

function &xreaggregator_getrenderer(&$xreaggregator)
{
	include_once XOOPS_ROOT_PATH.'/modules/xreaggregator/class/xreaggregatorrenderer.php';
	if (file_exists(XOOPS_ROOT_PATH.'/modules/xreaggregator/language/'.$GLOBALS['xoopsConfig']['language'].'/xreaggregatorrenderer.php')) {
		include_once XOOPS_ROOT_PATH.'/modules/xreaggregator/language/'.$GLOBALS['xoopsConfig']['language'].'/xreaggregatorrenderer.php';
		if (class_exists('xreaggregatorRendererLocal')) {
			return new xreaggregatorRendererLocal($xreaggregator);
		}
	}
	return new xreaggregatorRenderer($xreaggregator);
}
?>