<?php

	function xreaggregator_backend($num_items, $userid, $sort)
	{
		include('functions.php');
		$items = array();
		$hlman =& xoops_getmodulehandler('xreaggregator', 'xreaggregator');
		if (!$GLOBALS['xoopsModuleConfig']['support_multisite'])
		{
			$xreaggregators =& $hlman->getObjects(new Criteria('xreaggregator_display', 1));
		} else {
			$criteria= new CriteriaCompo(new Criteria('xreaggregator_domains', '%'.XOOPS_URL.'%', 'LIKE'), 'OR');
			$criteria->add(new Criteria('xreaggregator_domains', '%|all%', 'LIKE'), 'OR');
			$criteria_b = new CriteriaCompo(new Criteria('xreaggregator_display', 1), 'AND');
			$criteria->add($criteria_b);
			$xreaggregators =& $hlman->getObjects($criteria);
		}
		
		foreach($xreaggregators as $xreaggregator)
		{
			if (is_object($xreaggregator)) {
				$renderer =& xreaggregator_getrenderer($xreaggregator);
				$rss = $renderer->renderRSS();
				if (sizeof($rss['items'])>$num_items)
				{
					$max = $num_items;
				} else {
					$max = sizeof($rss['items']);
				}				
				for ($i=0;$i<$max;$i++)
				{
					$rss['items'][$i]['description'] = htmlspecialchars_decode($rss['items'][$i]['description']);
					$items[$j] = $rss['items'][$i];
					$j++;
				}
			}	
		}
		return $items;			
	}
	
	if (!function_exists('xoops_sef'))
	{
		function xoops_sef($datab, $char ='-')
		{
			$replacement_chars = array();
			$accepted = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","m","o","p","q",
					 "r","s","t","u","v","w","x","y","z","0","9","8","7","6","5","4","3","2","1");
			for($i=0;$i<256;$i++){
				if (!in_array(strtolower(chr($i)),$accepted))
					$replacement_chars[] = chr($i);
			}
			$return_data = (str_replace($replacement_chars,$char,$datab));
			#print $return_data . "<BR><BR>";
			return($return_data);
		
		}
	}

	function xreaggregator_sitemap($num_items, $userid, $sort, $agent)
	{
		$items = array();
		$hlmod =& xoops_gethandler('module');
		$hlcfg =& xoops_gethandler('config');		
		$GLOBALS['xoopsModule'] = $hlmod->getByDirName('xreaggregator');
		$GLOBALS['xoopsModuleConfig'] = $hlcfg->getConfigList($GLOBALS['xoopsModule']->getVar('mid'));
		
		$hlman =& xoops_getmodulehandler('xreaggregator', 'xreaggregator');
		if (!$GLOBALS['xoopsModuleConfig']['support_multisite'])
		{
			$xreaggregators =& $hlman->getObjects(new Criteria('xreaggregator_display', 1));
		} else {
			$criteria= new CriteriaCompo(new Criteria('xreaggregator_domains', '%'.XOOPS_URL.'%', 'LIKE'), 'OR');
			$criteria->add(new Criteria('xreaggregator_domains', '%|all%', 'LIKE'), 'OR');
			$criteria_b = new CriteriaCompo(new Criteria('xreaggregator_display', 1), 'AND');
			$criteria->add($criteria_b);
			$xreaggregators =& $hlman->getObjects($criteria);
		}
		
		foreach($xreaggregators as $aggregation)
		{
			if ($GLOBALS['xoopsModuleConfig']['htaccess'])
			{
				$loc = XOOPS_URL."/" . $GLOBALS['xoopsModuleConfig']['baseurl'] . "/".xoops_sef($aggregation->getVar('xreaggregator_name'))."/".$aggregation->getVar('xreaggregator_id');
			} else {
				$loc = XOOPS_URL."/modules/xreaggregator/index.php?id=".$aggregation->getVar('xreaggregator_id');
			}
			$lastmod = $aggregation->getVar('xreaggregator_updated');
			$priority = '0.'.rand(1,9);
			switch ($aggregation->getVar('xreaggregator_cachetime'))
			{
				case '3600':
				case '18000':
				case '86400':
					$changefreq = "daily";
					break;
				case '259200':
				case '604800':
					$changefreq = "weekly";
					break;
				case '2592000':
					$changefreq = "monthly";
			}
			
			$items[] = array('loc' => $loc,
							 'lastmod' => $lastmod,
							 'priority' => $priority,
							 'changefreq' => $changefreq);
							 
		}

		if (count($items)<$num_items)
			return $items;
		else
			for($i=0;$i<$num_items;$i++)
			{
				$items_b[$i] = $items[$i];
			}
			
		return $items_b;		
	}


?>