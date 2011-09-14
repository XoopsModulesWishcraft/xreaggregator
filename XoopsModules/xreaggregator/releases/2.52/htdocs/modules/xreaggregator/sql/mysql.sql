CREATE TABLE xreaggregator (
	xreaggregator_id smallint(3) unsigned NOT NULL auto_increment,
	xreaggregator_cid smallint(3) unsigned NOT NULL,
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
	xreaggregator_xml text,
	xreaggregator_updated int(10) NOT NULL default'0',
	xreaggregator_domains MEDIUMTEXT,
	PRIMARY KEY  (xreaggregator_id)
) ENGINE=MyISAM;


INSERT INTO xreaggregator VALUES (1, 1, 'XOOPS Official Website', 'http://www.xoops.org/', 'http://www.xoops.org/backend.php', 'UTF-8', 86400, 0, 1, 0, 1, 1, 10, 0, 10, '', 0, '|all');
INSERT INTO xreaggregator VALUES (2, 1, 'Chronolabs Co-Operative', 'http://www.chronolabs.coop/', 'http://www.chronolabs.coop/backend.php', 'UTF-8', 86400, 0, 1, 0, 1, 1, 10, 0, 10, '', 0, '|all');

CREATE TABLE `xreaggregator_mashables` (          
	`id` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,       
	`cid` SMALLINT(5) DEFAULT '0',
	`name` VARCHAR(128) DEFAULT NULL,                    
	`groups` MEDIUMTEXT,                                 
	`domains` MEDIUMTEXT,                                
	`keywords` MEDIUMTEXT, 
	`weight` INT(5) DEFAULT '1',                         
	`display` TINYINT(1) DEFAULT '1',                    
	`random` TINYINT(1) DEFAULT '0',                     
	PRIMARY KEY (`id`)                                   
) ENGINE=MyISAM; 

CREATE TABLE `xreaggregator_categories` (  
	 `cid` SMALLINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
	 `name` VARCHAR(128) DEFAULT NULL,             
	 `weight` SMALLINT(5) UNSIGNED,          
	 `display` TINYINT(2) UNSIGNED DEFAULT '1',           
         `domains` MEDIUMTEXT,                                
         PRIMARY KEY  (`cid`)                                 
) ENGINE=MYISAM;

INSERT INTO xreaggregator_categories VALUES (1, 'XOOPS', 1, 1, '');