CREATE TABLE xreaggregator (
	xreaggregator_id smallint(3) unsigned NOT NULL auto_increment,
	xreaggregator_name varchar(255) NOT NULL default '',
	xreaggregator_url varchar(255) NOT NULL default '',
	xreaggregator_rssurl varchar(255) NOT NULL default '',
	xreaggregator_encoding varchar(15) NOT NULL default '',
	xreaggregator_cachetime mediumint(8) unsigned NOT NULL default '3600',
	xreaggregator_asblock tinyint(1) unsigned NOT NULL default '0',
	xreaggregator_display tinyint(1) unsigned NOT NULL default '0',
	xreaggregator_weight smallint(3) unsigned NOT NULL default '0',
	xreaggregator_mainfull tinyint(1) unsigned NOT NULL default '1',
	xreaggregator_mainimg tinyint(1) unsigned NOT NULL default '1',
	xreaggregator_mainmax tinyint(2) unsigned NOT NULL default '10',
	xreaggregator_blockimg tinyint(1) unsigned NOT NULL default '0',
	xreaggregator_blockmax tinyint(2) unsigned NOT NULL default '10',
	xreaggregator_xml text NOT NULL default '',
	xreaggregator_updated int(10) NOT NULL default'0',
	xreaggregator_domains MEDIUMTEXT,
	PRIMARY KEY  (xreaggregator_id)
) TYPE=MyISAM;


INSERT INTO xreaggregator VALUES (1, 'XOOPS Official Website', 'http://www.xoops.org/', 'http://www.xoops.org/backend.php', 'ISO-8859-1', 86400, 0, 1, 0, 1, 1, 10, 0, 10, '', 0, '|all');
INSERT INTO xreaggregator VALUES (2, 'Chronolabs International', 'http://www.chronolabs.org.au/', 'http://www.chronolabs.org.au/backend.php', 'ISO-8859-1', 86400, 0, 1, 0, 1, 1, 10, 0, 10, '', 0, '|all');

CREATE TABLE `xreaggregator_mashables` (          
	`id` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,       
	`name` VARCHAR(128) DEFAULT NULL,                    
	`groups` MEDIUMTEXT,                                 
	`domains` MEDIUMTEXT,                                
	`weight` INT(5) DEFAULT '1',                         
	`display` TINYINT(1) DEFAULT '1',                    
	`random` TINYINT(1) DEFAULT '0',                     
	PRIMARY KEY (`id`)                                   
) TYPE=MyISAM; 
