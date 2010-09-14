<?php

if (!function_exists('xoops_sef')) {
	function xoops_sef($datab, $char ='-')
	{
		$datab = urldecode(strtolower($datab));
		$datab = urlencode($datab);
		$datab = str_replace(urlencode('æ'),'ae',$datab);
		$datab = str_replace(urlencode('ø'),'oe',$datab);
		$datab = str_replace(urlencode('å'),'aa',$datab);
		$replacement_chars = array(' ', '|', '=', '\\', '+', '-', '_', '{', '}', ']', '[', '/', '\'', '"', ';', ':', '?', '>', '<', '.', ',', ')', '(', '*', '&', '^', '%', '$', '#', '@', '!', '`', '~', ' ', '', '¡', '¦', '§', '¨', '©', 'ª', '«', '¬', '®', '­', '¯', '°', '±', '²', '³', '´', 'µ', '¶', '·', '¸', '¹', 'º', '»', '¼', '½', '¾', '¿');
		$return_data = str_replace($replacement_chars,$char,urldecode($datab));
		#print $return_data."<BR><BR>";
		switch ($char) {
		default:
			return urldecode($return_data);
			break;
		case "-";
			return urlencode($return_data);
			break;
		}
	}
}


if (!function_exists('sef')) {
	function sef($datab, $char ='-')
	{
		return xoops_sef($datab, $char);
	}
}

if (!function_exists("adminMenu")) {
  function adminMenu ($currentoption = 0)  {
	  /* Nice buttons styles */
		$module_handler =& xoops_gethandler('module');
		$xoModule = $module_handler->getByDirname("xreaggregator");
	    $dirname=$xoModule->getVar('dirname');
	    echo "
    	<style type='text/css'>
		#form {float:left; width:100%; background: #e7e7e7 url('" . XOOPS_URL . "/modules/$dirname/images/bg.gif') repeat-x left bottom; font-size:93%; line-height:normal; border-bottom: 1px solid black; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;}
		    	#buttontop { float:left; width:100%; background: #e7e7e7; font-size:93%; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }
    	#buttonbar { float:left; width:100%; background: #e7e7e7 url('" . XOOPS_URL . "/modules/$dirname/images/bg.gif') repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 0px; border-bottom: 1px solid black; }
    	#buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }
		  #buttonbar li { display:inline; margin:0; padding:0; }
		  #buttonbar a { float:left; background:url('" . XOOPS_URL . "/modules/$dirname/images/left_both.gif') no-repeat left top; margin:0; padding:0 0 0 9px;  text-decoration:none; }
		  #buttonbar a span { float:left; display:block; background:url('" . XOOPS_URL . "/modules/$dirname/images/right_both.gif') no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
		  /* Commented Backslash Hack hides rule from IE5-Mac \*/
		  #buttonbar a span {float:none;}
		  /* End IE5-Mac hack */
		  #buttonbar a:hover span { color:#333; }
		  #buttonbar #current a { background-position:0 -150px; border-width:0; }
		  #buttonbar #current a span { background-position:100% -150px; padding-bottom:5px; color:#333; }
		  #buttonbar a:hover { background-position:0% -150px; }
		  #buttonbar a:hover span { background-position:100% -150px; }
		  </style>";
	
	   // global $GLOBALS['xoopsDB'], $xoModule, $GLOBALS['xoopsConfig'], $GLOBALS['xoopsModuleConfig'];
	
	   $myts = &MyTextSanitizer::getInstance();
	
	   $tblColors = Array();
		// $adminmenu=array();
	   if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $xoModule->getVar('dirname') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/modinfo.php')) {
		   include_once XOOPS_ROOT_PATH . "/modules/$dirname/language/" . $GLOBALS['xoopsConfig']['language'] . '/modinfo.php';
	   } else {
		   include_once XOOPS_ROOT_PATH . "/modules/$dirname/language/english/modinfo.php";
	   }
       
	   echo "<table width=\"100%\" border='0'><tr><td>";
	   echo "<div id='buttontop'>";
	   echo "<table style=\"width: 100%; padding: 0; \" cellspacing=\"0\"><tr>";
	   echo "<td style=\"width: 45%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;\"><a class=\"nobutton\" href=\"".XOOPS_URL."/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $xoModule->getVar('mid') . "\">" . _PREFERENCES . "</a></td>";
	   echo "<td style='font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;'><b>" . $myts->displayTarea($xoModule->name()) ."</td>";
	   echo "</tr></table>";
	   echo "</div>";
	   echo "<div id='buttonbar'>";
	   echo "<ul>";
		 foreach ($xoModule->getAdminMenu() as $key => $value) {
		   $tblColors[$key] = '';
		   $tblColors[$currentoption] = 'current';
	     echo "<li id='" . $tblColors[$key] . "'><a href=\"" . XOOPS_URL . "/modules/".$xoModule->getVar('dirname')."/".$value['link']."\"><span>" . $value['title'] . "</span></a></li>";
		 }
		 
	   echo "</ul></div>";
	   echo "</td></tr>";
	   echo "<tr'><td><div id='form'>";
    
  }
  
  function footer_adminMenu()
  {
		echo "</div></td></tr>";
  		echo "</table>";
  }
}

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

function chronolabs_inline($flash = false)
{
	return _XA_CHRONOLABSINLINE;
}
?>