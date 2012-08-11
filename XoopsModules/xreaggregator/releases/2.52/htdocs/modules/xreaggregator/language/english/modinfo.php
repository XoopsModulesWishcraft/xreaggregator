<?php
// $Id: modinfo.php,v 2.09 2008/06/10 15:04:00 wishcraft Exp $
// Module Info

// The name of this module
define("_XA_REAGGREGATOR_NAME","X-Aggregator");

// A brief description of this module
define("_XA_REAGGREGATOR_DESC","Displayes RSS/XML Newsfeed from other sites Re-aggregates them");

// Names of blocks for this module (Not all module has blocks)
define("_XA_REAGGREGATOR_BNAME","Re-aggregator");

// Names of admin menu items
define("_XA_REAGGREGATOR_ADMENU1","List Re-aggregation");
define("_XA_REAGGREGATOR_ADMENU2","RSS Mashables");
define("_XA_REAGGREGATOR_ADMENU3","Categories");

define("_XA_RSS_UTF8","RSS UTF Code");
define("_XA_RSS_UTF8_DESC","Set your RSS UTF Code here");
define("_XA_RSS_CACHETIME","RSS Cache time");
define("_XA_RSS_CACHETIME_DESC","Set the number of minutes for your RSS cache");
define("_XA_SNOOP_QUANTITY","RSS Snoop Quantity");
define("_XA_SNOOP_QUANTITY_DESC","Set the number items in an overall RSS Snoop");
define("_XA_HTACCESS",".htaccess SEO");
define("_XA_HTACCESS_DESC","Set this if you have installed the htaccess SEO in your XOOPS Root Path");
define("_XA_CACHEME","RSS Cache");
define("_XA_CACHEME_DESC","Set this if you want the RSS Re-aggregation cached.");

define("_XA_GET_METHOD","Method to Get Feed");
define("_XA_GET_METHOD_DESC","This is the method for retrieving the feed.");

define("_XA_FOPEN_AGENT","User Agent for Retrieval (Require's Curl)");
define("_XA_FOPEN_AGENT_DESC","X-Reaggreagtor identifies as with when retrieving Feeds (See <a href='http://www.useragentstring.com/pages/useragentstring.php'>Here</a> for a list of these)");

define('_XA_SUPPORTMULTISITE','Support Multisite Module');
define('_XA_SUPPORTMULTISITE_DESC','This support multisite module filtering.');
define('_XA_HTACCESS','Enabled HTACCESS SEO');
define('_XA_HTACCESS_DESC','This enables SEO');
define('_XA_BASEURL','Base URL for SEO');
define('_XA_BASEURL_DESC','Base URL for SEO');
define('_XA_ENDOFURL','End of URL');
define('_XA_ENDOFURL_DESC','File Extension to HTML Files');
define('_XA_ENDOFURLRSS','End of URL');
define('_XA_ENDOFURLRSS_DESC','File Extension to RSS Pages');
?>