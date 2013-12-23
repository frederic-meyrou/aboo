CREATE DATABASE IF NOT EXISTS test;
USE test;
DROP TABLE IF EXISTS abonnement;CREATE TABLE `abonnement` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `exercice_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `periodicitee` smallint(6) NOT NULL DEFAULT '1',
  `montant` decimal(10,2) DEFAULT NULL,
  `mois` tinyint(4) DEFAULT NULL COMMENT '1..12',
  `commentaire` varchar(1024) DEFAULT NULL,
  `mois_1` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_3` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_4` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_5` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_6` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_7` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_8` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_9` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_10` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_11` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_12` decimal(10,2) NOT NULL DEFAULT '0.00',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_abonnement_user1_idx` (`user_id`),
  KEY `fk_abonnement_exercice1_idx` (`exercice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;
INSERT INTO abonnement VALUES("0000000042","6","15","0","1","1","100.00","1","ponctuel","100.00","0.00","0.00","0.00","0.00","0.00","0.00","0.00","0.00","0.00","0.00","0.00","2013-12-19 15:19:38");
INSERT INTO abonnement VALUES("0000000043","6","15","0","1","3","300.00","1","test trimestre","100.00","100.00","100.00","0.00","0.00","0.00","0.00","0.00","0.00","0.00","0.00","0.00","2013-12-19 15:19:45");
INSERT INTO abonnement VALUES("0000000044","6","15","0","1","2","200.00","1","test bimestre","100.00","100.00","0.00","0.00","0.00","0.00","0.00","0.00","0.00","0.00","0.00","0.00","2013-12-19 15:19:52");
INSERT INTO abonnement VALUES("0000000045","6","15","0","1","6","600.00","1","test semestre","100.00","100.00","100.00","100.00","100.00","100.00","0.00","0.00","0.00","0.00","0.00","0.00","2013-12-19 15:20:00");
INSERT INTO abonnement VALUES("0000000046","6","15","0","1","12","1200.00","1","test annuel","100.00","100.00","100.00","100.00","100.00","100.00","100.00","100.00","100.00","100.00","100.00","100.00","2013-12-19 15:20:08");
INSERT INTO abonnement VALUES("0000000047","6","15","0","1","6","600.00","5","semestre en Mai","0.00","0.00","0.00","0.00","100.00","100.00","100.00","100.00","100.00","100.00","0.00","0.00","2013-12-19 15:30:15");
INSERT INTO abonnement VALUES("0000000048","6","14","0","1","12","1000.00","1","","83.33","83.33","83.33","83.33","83.33","83.33","83.33","83.33","83.33","83.33","83.33","83.33","2013-12-19 16:21:27");
INSERT INTO abonnement VALUES("0000000064","6","16","0","1","12","1200.00","1","test annuel","100.00","100.00","100.00","100.00","100.00","100.00","100.00","100.00","100.00","100.00","100.00","100.00","2013-12-23 09:50:18");
DROP TABLE IF EXISTS ca;CREATE TABLE `ca` (
  `user_id` int(11) NOT NULL,
  `exercice_id` int(11) NOT NULL,
  `mois` tinyint(4) DEFAULT NULL COMMENT '1..12',
  `total_abonnements` decimal(10,2) DEFAULT NULL,
  `total_charges` decimal(10,2) DEFAULT NULL,
  `salaire` decimal(10,2) DEFAULT NULL,
  `treso` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`exercice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Chiffre d''affaire mensuel';
DROP TABLE IF EXISTS ca_mensuel;CREATE TABLE `ca_mensuel` (
  `user_id` int(11) NOT NULL,
  `exercice_id` int(11) NOT NULL,
  `mois` tinyint(4) DEFAULT NULL COMMENT '1..12',
  `total_abonnements` decimal(10,2) DEFAULT NULL,
  `total_charges` decimal(10,2) DEFAULT NULL,
  `salaire` decimal(10,2) DEFAULT NULL,
  `treso` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`exercice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Chiffre d''affaire mensuel';
DROP TABLE IF EXISTS depense;CREATE TABLE `depense` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `exercice_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `periodicitee` smallint(6) DEFAULT NULL,
  `mois` tinyint(4) DEFAULT NULL COMMENT '1..12',
  `commentaire` varchar(1024) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
INSERT INTO depense VALUES("00000000024","6","15","1","500.00","","1","depense","2013-12-19 15:22:53");
INSERT INTO depense VALUES("00000000025","6","14","1","1000.00","","1","","2013-12-19 16:20:58");
INSERT INTO depense VALUES("00000000027","6","15","3","500.00","","1","oulalala","2013-12-20 17:21:18");
DROP TABLE IF EXISTS encaissement;CREATE TABLE `encaissement` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `abonnement_id` int(10) DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `mois` tinyint(4) DEFAULT NULL COMMENT '(1..12)',
  `type` varchar(45) DEFAULT NULL,
  `compte` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS exercice;CREATE TABLE `exercice` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `mois_debut` smallint(6) DEFAULT NULL,
  `montant_treso_initial` decimal(10,2) DEFAULT NULL,
  `annee_debut` year(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
INSERT INTO exercice VALUES("00000000014","6","1","111.00","2010");
INSERT INTO exercice VALUES("00000000015","6","12","10000.00","2013");
INSERT INTO exercice VALUES("00000000016","6","9","0.00","2014");
DROP TABLE IF EXISTS paiement;CREATE TABLE `paiement` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `abonnement_id` int(11) unsigned zerofill DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `mois` tinyint(4) DEFAULT NULL COMMENT '(1..12)',
  `type` varchar(45) DEFAULT NULL,
  `encaissement` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS session;CREATE TABLE `session` (
  `user_id` int(11) NOT NULL,
  `annee` year(4) NOT NULL,
  `mois` smallint(6) NOT NULL,
  `treso` decimal(10,2) NOT NULL,
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS user;CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(45) DEFAULT NULL,
  `nom` varchar(45) DEFAULT NULL,
  `email` varchar(96) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `password` varchar(20) NOT NULL,
  `inscription` date DEFAULT NULL,
  `actif` tinyint(4) NOT NULL DEFAULT '0',
  `essai` tinyint(4) NOT NULL DEFAULT '0',
  `montant` decimal(10,2) DEFAULT NULL,
  `expiration` date DEFAULT NULL,
  `administrateur` tinyint(1) NOT NULL DEFAULT '0',
  `token` varchar(45) DEFAULT NULL,
  `mois_encours` tinyint(4) DEFAULT NULL COMMENT '1..12',
  `exerciceid_encours` int(11) DEFAULT NULL,
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `email_2` (`email`),
  KEY `email` (`email`),
  KEY `email_3` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
INSERT INTO user VALUES("4","fred","meyrou","frederic@meyrou.com","0612345678","derf44","2013-11-28","1","0","1234.00","0000-00-00","1","","","");
INSERT INTO user VALUES("6","elise","meyrou","elise@meyrou.com","0612456789","grenouille","2013-01-12","1","0","100.00","0000-00-00","0","","1","16");
INSERT INTO user VALUES("8","Frederic","MEYROU","frederic_meyrou@yahoo.fr","0672268111","derf44","2013-12-14","1","0","999.00","2013-12-12","0","","","");
INSERT INTO user VALUES("10","Fr&eacute;d&eacute;ric","MEYROU","frederic.meyrou@gmail.com","0672268111","h6S2Tlv7","2013-12-12","0","0","","","0","432b5f36651f5bab7e96984650487bb51417dea8","","");
DROP TABLE IF EXISTS wp__wsd_plugin_alerts;CREATE TABLE `wp__wsd_plugin_alerts` (
  `alertId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alertType` tinyint(4) NOT NULL DEFAULT '0',
  `alertSeverity` int(11) NOT NULL DEFAULT '0',
  `alertActionName` varchar(255) NOT NULL,
  `alertTitle` varchar(255) NOT NULL,
  `alertDescription` text NOT NULL,
  `alertSolution` text NOT NULL,
  `alertDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `alertFirstSeen` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`alertId`),
  UNIQUE KEY `alertId_UNIQUE` (`alertId`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
INSERT INTO wp__wsd_plugin_alerts VALUES("1","0","3","fix_wp_version_hidden","WordPress version is displayed for all users","<p>Displaying your WordPress version on frontend and in the backend\'s footer to all visitors
                        and users of your website is a security risk because if a hacker knows which version of WordPress a website is running, it can make it easier for him to target a known WordPress security issue.</p>","<p>This plugin can automatically hide your WordPress version from frontend, backend and rss feeds if the option <strong>\"Hide WordPress version for all users but administrators\"</strong> is checked on the plugin\'s settings page.</p>","2013-12-23 10:55:40","2013-12-23 10:55:16");
INSERT INTO wp__wsd_plugin_alerts VALUES("2","0","3","fix_wp_generators_frontend","WordPress meta tags are displayed on frontend to all users","<p>By default, WordPress creates a few meta tags, among which is the currently installed version, that give a hacker the knowledge about your WordPress installation. At the moment, these meta tags are available for anyone to see, which is a potentially security risk.</p>","<p>This plugin can automatically hide your WordPress\'s default meta tags if the option <strong>\"Remove various meta tags generators from the blog\'s head tag for non-administrators\"</strong> is checked on the plugin\'s settings page.</p>","2013-12-23 10:55:40","2013-12-23 10:55:16");
INSERT INTO wp__wsd_plugin_alerts VALUES("3","0","0","fix_wp_rsd_frontend","WordPress Really Simple Discovery tag is displayed on frontend to all users","<p>By default, WordPress creates the <strong>rsd meta tag</strong> to allow bloggers to consume services like Flickr using the <a href=\"http://en.wikipedia.org/wiki/XML-RPC\" target=\"_blank\">XML-RPC</a> protocol.
                            If you don\'t use such services it is recommended to hide this meta tag.</p>","<p>This plugin can automatically hide the rsd meta tag if the option <strong>\"Remove Really Simple Discovery meta tags from front-end\"</strong> is checked on the plugin\'s settings page.</p>","2013-12-23 10:55:40","2013-12-23 10:55:16");
INSERT INTO wp__wsd_plugin_alerts VALUES("4","0","2","fix_wp_wlw_frontend","WordPress Windows Live Writer tag is displayed on frontend for all users","<p>By default, WordPress creates the wlw meta tag to allow bloggers to publish their articles using the <strong>\"Windows Live Writer\"</strong> application.
                        It is recommended to hide this meta tag from all visitors. If the option <strong>\"Remove Windows Live Writer meta tags from front-end\"</strong> is checked on the plugin\'s settings page, this meta tag
                        will still be available for administrator users to use the <strong>\"Windows Live Writer\"</strong> application to publish their blog posts.</p>","<p>This plugin can automatically hide the wlw meta tag if the option <strong>\"Remove Windows Live Writer meta tags from front-end\"\"</strong> is checked on the plugin\'s settings page.</p>","2013-12-23 10:55:40","2013-12-23 10:55:16");
INSERT INTO wp__wsd_plugin_alerts VALUES("5","0","3","fix_wp_error_reporting","The check for PHP and database error reporting is disabled","<p>By default, WordPress hides database errors, but there are times when a plugin might enable them thus it is very important to have this type of errors turned off
                            so if there is an error during a connection to the database the user will not get access to the error message generated during that request.</p>
                            <p>As regarding the PHP errors, with the <strong>display_error</strong> PHP configuration directive enabled, untrusted sources can see detailed web application environment
                            error messages which include sensitive information that can be used to craft further attacks.</p>
                            <p>Attackers will do anything to collect information in order to design their attack in a more sophisticated way to eventually hack your website or web application, and causing
                            errors to display is a common starting point. Website errors can always occur, but they should be suppressed from being displayed back to the public.</p>
                            <p>Therefore we highly recommend you to have the <strong>\"Disable error reporting (php + db) for all but administrators\"</strong> option checked on the plugin\'s settings page to ensure PHP and
                            database errors will be hidden from all users. For more information, please check the following <a href=\"http://www.acunetix.com/blog/web-security-zone/articles/php-security-directive-your-website-is-showing-php-errors/\" target=\"_blank\">article</a>.</p>","<p>This plugin can do this automatically if the option <strong>\"Disable error reporting (php + db) for all but administrators\"</strong> is checked on the plugin\'s settings page.</p>","2013-12-23 10:55:40","2013-12-23 10:55:16");
INSERT INTO wp__wsd_plugin_alerts VALUES("6","0","3","fix_wp_core_update_notif","Core update notifications are displayed to all users","<p>These notifications are displayed at the top of the screen by the WordPress platform whenever the website was updated or needs an update.</p>
                    <p>These notifications should only be viewed by the website\'s administrators and not visible to any other users registered with that website.</p>","<p>This plugin can automatically hide these notifications if the option <strong>\"Remove core update notifications from back-end for all but administrators\"</strong> is checked on the plugin\'s settings page.</p>","2013-12-23 10:55:40","2013-12-23 10:55:16");
INSERT INTO wp__wsd_plugin_alerts VALUES("7","0","2","fix_wp_plugins_update_notif","Plugins update notifications are displayed to all users","<p>These notifications are displayed at the top of the screen by the WordPress platform whenever the blog administrator
                        needs to be informed about an available update for a plugin.</p>
                    <p>These notifications should only be viewed by the website\'s administrators and not visible to any other users registered with that website.</p>","<p>This plugin can automatically hide these notifications if the option <strong>\"Remove plug-ins update notifications from back-end\"</strong> is checked on the plugin\'s settings page.</p>","2013-12-23 10:55:40","2013-12-23 10:55:16");
INSERT INTO wp__wsd_plugin_alerts VALUES("8","0","0","fix_wp_themes_update_notif","Themes update notifications are displayed to all users.","<p>These notifications are displayed at the top of the screen by the WordPress platform whenever the blog administrator
                        needs to be informed about an available update for a theme.</p>
                    <p>These notifications should only be viewed by the website\'s administrators and not visible to any other users registered with that website.</p>","<p>This plugin can automatically hide these notifications if the option <strong>\"Remove themes update notifications from back-end\"</strong> is checked on the plugin\'s settings page.</p>","2013-12-23 10:55:40","2013-12-23 10:55:16");
INSERT INTO wp__wsd_plugin_alerts VALUES("9","0","2","fix_wp_login_errors","WordPress login errors are displayed.","<p>Every time a failed login is encountered, the WordPress platform generates an error message that is displayed to the user.
                        This is a potential security risk because it let\'s the user know of his mistake (be it a wrong user name or password) thus making your
                        WordPress website more vulnerable to attacks.</p>
                    <p>We strongly recommend you to hide these login error messages from all users to ensure a better security of your blog.</p>","<p>This plugin can automatically hide these notifications if the option <strong>\"Remove login error notifications from front-end\"</strong> is checked on the plugin\'s settings page.</p>","2013-12-23 10:55:40","2013-12-23 10:55:16");
INSERT INTO wp__wsd_plugin_alerts VALUES("10","0","2","fix_wp_admin_notices","WordPress admin notifications are displayed to all users.","<p>These notifications are displayed at the top of the screen by the WordPress platform whenever the blog administrator
                       needs to be informed about an event that has occurred inside WordPress, it could be about an available update for the
                       WordPress platform, a plugin or a theme that was updated or needs an update or to be configured, etc.</p>
                    <p>These notifications should only be viewed by the website\'s administrators and not visible to any other users registered with that website.</p>","<p>This plugin can automatically hide these notifications if the option <strong>\"Hide admin notifications for non admins\"</strong> is checked on the plugin\'s settings page.</p>","2013-12-23 10:55:40","2013-12-23 10:55:16");
INSERT INTO wp__wsd_plugin_alerts VALUES("11","0","2","fix_wp_dir_listing","Directory listing check is disabled. This option should be enabled.","<p>A directory listing provides an attacker with the complete index of all the resources located inside of the directory.
                    The specific risks and consequences vary depending on which files are listed and accessible.
                    Therefore, it is important to protect your directories by having an empty index.php or index.htm file inside them.</p>","<p>This plugin can automatically create an empty <strong>index.php</strong> file in the following directories: wp-content, wp-content/plugins, wp-content/themes and wp-content/uploads if
                    the option <strong>\"Try to create the index.php file in the wp-content, wp-content/plugins, wp-content/themes and wp-content/uploads directories to prevent directory listing\"</strong> is checked on the plugin\'s settings page.</p>","2013-12-23 10:55:40","2013-12-23 10:55:16");
INSERT INTO wp__wsd_plugin_alerts VALUES("12","0","3","fix_remove_wp_version_links","WordPress version is displayed in links for all users","<p>By default, WordPress will display the current version in links to javascript scripts or stylesheets.
                    Therefore, if anyone has access to this information it might be a security risk because if a hacker knows which version of WordPress a website is running,
                    it can make it easier for him to target a known WordPress security issue.</p>","<p>This plugin can automatically hide the WordPress version from links if the option <strong>\"Remove the version parameter from urls\"</strong> is checked on the plugin\'s settings page.</p>","2013-12-23 10:55:40","2013-12-23 10:55:16");
INSERT INTO wp__wsd_plugin_alerts VALUES("13","0","3","check_table_prefix","The default WordPress database prefix (<strong>wp_</strong>) is used","<p>The majority of reported WordPress database security attacks were performed by exploiting SQL Injection vulnerabilities.
                        By renaming the WordPress database table prefixes you are securing your WordPress blog and website from zero day SQL injections attacks.</p>
                    <p>Therefore by renaming the WordPress database table prefixes, you are automatically enforcing your WordPress database security against such dangerous attacks
                        because the attacker would not be able to guess the table names.</p>
                    <p>We recommend to use difficult to guess prefixes, like long random strings which include both letters and numbers.</p>","<p>This plugin can automatically <a href=\"admin.php?page=wsd_database\">do this</a> for you, but if you want to do it manually then please read this <a href=\"http://www.websitedefender.com/wordpress-security/change-wordpress-database-prefix/\" target=\"_blank\">article</a> first.</p>","2013-12-23 10:55:40","2013-12-23 10:55:16");
INSERT INTO wp__wsd_plugin_alerts VALUES("14","0","0","check_wp_current_version","You have the latest version of WordPress installed","<p>The latest WordPress version is usually more stable and secure, and is only released to include new features or fix technical and WordPress security bugs;
                            making it an important part of your website administration to keep up to date since some fixes might resolve security issues.<p>
                        <p>Running an older WordPress version could put your blog security at risk, allowing a hacker to exploit known vulnerabilities for your specific version and take full control over your web server.</p>","","2013-12-23 10:55:40","2013-12-23 10:55:16");
INSERT INTO wp__wsd_plugin_alerts VALUES("15","0","0","check_index_wp_content","The <strong>\"index.php\"</strong> file was found in the <strong>\"/wp-content\"</strong> directory","<p>A directory listing provides an attacker with the complete index of all the resources located inside of the directory. The specific risks and consequences vary depending on which files are listed and accessible.</p>
                    <p>Therefore, it is important to protect your directories by having an empty index.php or index.htm file inside them.</p>","","2013-12-23 10:55:40","2013-12-23 10:55:16");
INSERT INTO wp__wsd_plugin_alerts VALUES("16","0","0","check_index_wp_plugins","The <strong>\"index.php\"</strong> file was found in the <strong>\"/wp-content/plugins\"</strong> directory","<p>A directory listing provides an attacker with the complete index of all the resources located inside of the directory. The specific risks and consequences vary depending on which files are listed and accessible.</p>
                    <p>Therefore, it is important to protect your directories by having an empty index.php or index.htm file inside them.</p>","","2013-12-23 10:55:40","2013-12-23 10:55:16");
INSERT INTO wp__wsd_plugin_alerts VALUES("17","0","0","check_index_wp_themes","The <strong>\"index.php\"</strong> file was found in the <strong>\"/wp-content/themes\"</strong> directory","<p>A directory listing provides an attacker with the complete index of all the resources located inside of the directory. The specific risks and consequences vary depending on which files are listed and accessible.</p>
                    <p>Therefore, it is important to protect your directories by having an empty index.php or index.htm file inside them.</p>","","2013-12-23 10:55:40","2013-12-23 10:55:16");
INSERT INTO wp__wsd_plugin_alerts VALUES("18","0","2","check_index_wp_uploads","The <strong>\"index.php\"</strong> file was not found in the <strong>\"/wp-content/uploads\"</strong> directory","<p>A directory listing provides an attacker with the complete index of all the resources located inside of the directory. The specific risks and consequences vary depending on which files are listed and accessible.</p>
                        <p>Therefore, it is important to protect your directories by having an empty index.php or index.htm file inside them.</p>","<p>This plugin can automatically create an empty <strong>\"index.php\"</strong> file in the following directories: wp-content, wp-content/plugins, wp-content/themes and wp-content/uploads if the
                        option <strong>\"Try to create the index.php file in the wp-content, wp-content/plugins, wp-content/themes and wp-content/uploads directories to prevent directory listing\"</strong> is checked on the plugin\'s settings page.</p>","2013-12-23 10:55:40","2013-12-23 10:55:16");
INSERT INTO wp__wsd_plugin_alerts VALUES("19","0","2","check_htaccess_wp_admin","The <strong>\".htaccess\"</strong> file was not found in the <strong>\"wp-admin\"</strong> directory","<p>An .htaccess file is a configuration file which provides the ability to specify configuration settings for a specific directory in a website.
                    The .htaccess file can include one or more configuration settings which apply only for the directory in which the .htaccess file has been placed.
                    So while web servers have their own main configuration settings file, the .htaccess file can be used to override their main configuration settings.</p>","<p>Please refer to this <a href=\"http://www.acunetix.com/blog/web-security-zone/articles/what-is-an-htaccess-file/\" target=\"_blank\">article</a> for more information on how to create an .htaccess file.</p>","2013-12-23 10:55:40","2013-12-23 10:55:16");
INSERT INTO wp__wsd_plugin_alerts VALUES("20","0","0","check_readme_wp_root","The <strong>readme.html</strong> file was not found in the root directory","<p>A default WordPress installation contains a readme.html file.
                        This file is a simple html file that does not contain executable content that can be exploited by hackers or malicious users.
                        Still, this file can provide hackers the version of your WordPress installation, therefore it is important to either delete this file or make it inaccessible for your visitors.</p>","","2013-12-23 10:55:40","2013-12-23 10:55:16");
INSERT INTO wp__wsd_plugin_alerts VALUES("21","0","3","check_username_admin ","The default user <strong>\"admin\"</strong> was found","<p>One well known and dangerous WordPress security vulnerability is User Enumeration, in which a malicious user is able to enumerate
                            a valid WordPress user account to launch a brute force attack against it.</p>
                            <p>In order to help deter this type of attack, you should change your default <a href=\"http://www.acunetix.com/blog/web-security-zone/articles/default-wordpress-administrator-account/\" target=\"_blank\">WordPress administrator</a>
                            username to something more difficult to guess.</p>","<p>Do not make the following change unless you are comfortable working with PHPMyAdmin and MySQL. If not, ask someone who is familiar with WordPress and MySQL to assist you. </p>
                            <p>Also, it is of utmost importance to backup your whole blog - including the database - before making any of the changes described below.</p>
                            <p>To change your WordPress default admin username, navigate to your web host\'s MySQL administration tool (probably PHPMyAdmin) and browse to your WordPress database.
                            Locate the users table, in which you will find a user_login column. One of the rows will contain admin in the field.
                            Change this to a complex and hard-to-guess name, which ideally consists of alpha-numeric characters.</p>
                            <p><strong>IMPORTANT:</strong> Even if the username is hard to guess, you will still need a very strong password.</p>","2013-12-23 10:55:16","2013-12-23 10:55:16");
DROP TABLE IF EXISTS wp__wsd_plugin_live_traffic;CREATE TABLE `wp__wsd_plugin_live_traffic` (
  `entryId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `entryTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `entryIp` text,
  `entryReferrer` text,
  `entryUA` text,
  `entryRequestedUrl` text,
  PRIMARY KEY (`entryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS wp_commentmeta;CREATE TABLE `wp_commentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`meta_id`),
  KEY `comment_id` (`comment_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
INSERT INTO wp_commentmeta VALUES("1","1","_wp_trash_meta_status","1");
INSERT INTO wp_commentmeta VALUES("2","1","_wp_trash_meta_time","1387044949");
DROP TABLE IF EXISTS wp_comments;CREATE TABLE `wp_comments` (
  `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT '0',
  `comment_author` tinytext NOT NULL,
  `comment_author_email` varchar(100) NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) NOT NULL DEFAULT '',
  `comment_type` varchar(20) NOT NULL DEFAULT '',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
INSERT INTO wp_comments VALUES("1","1","Monsieur WordPress","","http://wordpress.org/","","2013-12-14 14:35:17","2013-12-14 14:35:17","Bonjour, ceci est un commentaire.
Pour supprimer un commentaire, connectez-vous et affichez les commentaires de cet article. Vous pourrez alors les modifier ou les supprimer.","0","trash","","","0","0");
DROP TABLE IF EXISTS wp_links;CREATE TABLE `wp_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) NOT NULL DEFAULT '',
  `link_name` varchar(255) NOT NULL DEFAULT '',
  `link_image` varchar(255) NOT NULL DEFAULT '',
  `link_target` varchar(25) NOT NULL DEFAULT '',
  `link_description` varchar(255) NOT NULL DEFAULT '',
  `link_visible` varchar(20) NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) unsigned NOT NULL DEFAULT '1',
  `link_rating` int(11) NOT NULL DEFAULT '0',
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) NOT NULL DEFAULT '',
  `link_notes` mediumtext NOT NULL,
  `link_rss` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS wp_options;CREATE TABLE `wp_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) NOT NULL DEFAULT '',
  `option_value` longtext NOT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=InnoDB AUTO_INCREMENT=541 DEFAULT CHARSET=utf8;
INSERT INTO wp_options VALUES("1","siteurl","http://localhost/gestabo","yes");
INSERT INTO wp_options VALUES("2","blogname","Aboo","yes");
INSERT INTO wp_options VALUES("3","blogdescription","","yes");
INSERT INTO wp_options VALUES("4","users_can_register","0","yes");
INSERT INTO wp_options VALUES("5","admin_email","frederic@meyrou.com","yes");
INSERT INTO wp_options VALUES("6","start_of_week","1","yes");
INSERT INTO wp_options VALUES("7","use_balanceTags","","yes");
INSERT INTO wp_options VALUES("8","use_smilies","1","yes");
INSERT INTO wp_options VALUES("9","require_name_email","1","yes");
INSERT INTO wp_options VALUES("10","comments_notify","","yes");
INSERT INTO wp_options VALUES("11","posts_per_rss","10","yes");
INSERT INTO wp_options VALUES("12","rss_use_excerpt","0","yes");
INSERT INTO wp_options VALUES("13","mailserver_url","smtp.free.fr","yes");
INSERT INTO wp_options VALUES("14","mailserver_login","","yes");
INSERT INTO wp_options VALUES("15","mailserver_pass","","yes");
INSERT INTO wp_options VALUES("16","mailserver_port","110","yes");
INSERT INTO wp_options VALUES("17","default_category","6","yes");
INSERT INTO wp_options VALUES("18","default_comment_status","closed","yes");
INSERT INTO wp_options VALUES("19","default_ping_status","open","yes");
INSERT INTO wp_options VALUES("20","default_pingback_flag","","yes");
INSERT INTO wp_options VALUES("21","posts_per_page","5","yes");
INSERT INTO wp_options VALUES("22","date_format","j F Y","yes");
INSERT INTO wp_options VALUES("23","time_format","G \\h i \\m\\i\\n","yes");
INSERT INTO wp_options VALUES("24","links_updated_date_format","j F Y G \\h i \\m\\i\\n","yes");
INSERT INTO wp_options VALUES("25","links_recently_updated_prepend","<em>","yes");
INSERT INTO wp_options VALUES("26","links_recently_updated_append","</em>","yes");
INSERT INTO wp_options VALUES("27","links_recently_updated_time","120","yes");
INSERT INTO wp_options VALUES("28","comment_moderation","1","yes");
INSERT INTO wp_options VALUES("29","moderation_notify","","yes");
INSERT INTO wp_options VALUES("30","permalink_structure","/%postname%/","yes");
INSERT INTO wp_options VALUES("31","gzipcompression","0","yes");
INSERT INTO wp_options VALUES("32","hack_file","0","yes");
INSERT INTO wp_options VALUES("33","blog_charset","UTF-8","yes");
INSERT INTO wp_options VALUES("34","moderation_keys","","no");
INSERT INTO wp_options VALUES("35","active_plugins","a:10:{i:0;s:36:\"contact-form-7/wp-contact-form-7.php\";i:1;s:37:\"disable-comments/disable-comments.php\";i:2;s:53:\"easy-bootstrap-shortcodes/osc_bootstrap_shortcode.php\";i:3;s:49:\"google-xml-sitemaps-v3-for-qtranslate/sitemap.php\";i:4;s:38:\"hide-title/dojo-digital-hide-title.php\";i:5;s:19:\"jetpack/jetpack.php\";i:6;s:47:\"quick-paypal-payments/quick-paypal-payments.php\";i:7;s:26:\"secure-wordpress/index.php\";i:8;s:37:\"tinymce-advanced/tinymce-advanced.php\";i:9;s:41:\"wordpress-importer/wordpress-importer.php\";}","yes");
INSERT INTO wp_options VALUES("36","home","http://localhost/gestabo","yes");
INSERT INTO wp_options VALUES("37","category_base","","yes");
INSERT INTO wp_options VALUES("38","ping_sites","http://rpc.pingomatic.com/","yes");
INSERT INTO wp_options VALUES("39","advanced_edit","0","yes");
INSERT INTO wp_options VALUES("40","comment_max_links","2","yes");
INSERT INTO wp_options VALUES("41","gmt_offset","1","yes");
INSERT INTO wp_options VALUES("42","default_email_category","1","yes");
INSERT INTO wp_options VALUES("43","recently_edited","a:5:{i:0;s:62:\"D:\\DEV\\xampp\\htdocs\\gestabo/wp-content/themes/tonic/header.php\";i:2;s:61:\"D:\\DEV\\xampp\\htdocs\\gestabo/wp-content/themes/tonic/index.php\";i:3;s:77:\"D:\\DEV\\xampp\\htdocs\\gestabo/wp-content/themes/tonic/library/theme-options.php\";i:4;s:61:\"D:\\DEV\\xampp\\htdocs\\gestabo/wp-content/themes/tonic/style.css\";i:5;s:67:\"D:\\xampp\\htdocs\\gestabo\\wordpress/wp-content/themes/tonic/style.css\";}","no");
INSERT INTO wp_options VALUES("44","template","tonic","yes");
INSERT INTO wp_options VALUES("45","stylesheet","tonic","yes");
INSERT INTO wp_options VALUES("46","comment_whitelist","1","yes");
INSERT INTO wp_options VALUES("47","blacklist_keys","","no");
INSERT INTO wp_options VALUES("48","comment_registration","1","yes");
INSERT INTO wp_options VALUES("49","html_type","text/html","yes");
INSERT INTO wp_options VALUES("50","use_trackback","0","yes");
INSERT INTO wp_options VALUES("51","default_role","subscriber","yes");
INSERT INTO wp_options VALUES("52","db_version","26691","yes");
INSERT INTO wp_options VALUES("53","uploads_use_yearmonth_folders","1","yes");
INSERT INTO wp_options VALUES("54","upload_path","","yes");
INSERT INTO wp_options VALUES("55","blog_public","0","yes");
INSERT INTO wp_options VALUES("56","default_link_category","0","yes");
INSERT INTO wp_options VALUES("57","show_on_front","page","yes");
INSERT INTO wp_options VALUES("58","tag_base","","yes");
INSERT INTO wp_options VALUES("59","show_avatars","","yes");
INSERT INTO wp_options VALUES("60","avatar_rating","G","yes");
INSERT INTO wp_options VALUES("61","upload_url_path","","yes");
INSERT INTO wp_options VALUES("62","thumbnail_size_w","150","yes");
INSERT INTO wp_options VALUES("63","thumbnail_size_h","150","yes");
INSERT INTO wp_options VALUES("64","thumbnail_crop","1","yes");
INSERT INTO wp_options VALUES("65","medium_size_w","300","yes");
INSERT INTO wp_options VALUES("66","medium_size_h","300","yes");
INSERT INTO wp_options VALUES("67","avatar_default","mystery","yes");
INSERT INTO wp_options VALUES("68","large_size_w","1024","yes");
INSERT INTO wp_options VALUES("69","large_size_h","1024","yes");
INSERT INTO wp_options VALUES("70","image_default_link_type","file","yes");
INSERT INTO wp_options VALUES("71","image_default_size","","yes");
INSERT INTO wp_options VALUES("72","image_default_align","","yes");
INSERT INTO wp_options VALUES("73","close_comments_for_old_posts","","yes");
INSERT INTO wp_options VALUES("74","close_comments_days_old","14","yes");
INSERT INTO wp_options VALUES("75","thread_comments","","yes");
INSERT INTO wp_options VALUES("76","thread_comments_depth","5","yes");
INSERT INTO wp_options VALUES("77","page_comments","","yes");
INSERT INTO wp_options VALUES("78","comments_per_page","50","yes");
INSERT INTO wp_options VALUES("79","default_comments_page","newest","yes");
INSERT INTO wp_options VALUES("80","comment_order","asc","yes");
INSERT INTO wp_options VALUES("81","sticky_posts","a:0:{}","yes");
INSERT INTO wp_options VALUES("82","widget_categories","a:2:{i:2;a:4:{s:5:\"title\";s:0:\"\";s:5:\"count\";i:1;s:12:\"hierarchical\";i:0;s:8:\"dropdown\";i:0;}s:12:\"_multiwidget\";i:1;}","yes");
INSERT INTO wp_options VALUES("83","widget_text","a:1:{s:12:\"_multiwidget\";i:1;}","yes");
INSERT INTO wp_options VALUES("84","widget_rss","a:0:{}","yes");
INSERT INTO wp_options VALUES("85","uninstall_plugins","a:2:{s:34:\"quick-paypal-payments/settings.php\";s:17:\"delete_everything\";s:26:\"secure-wordpress/index.php\";a:2:{i:0;s:10:\"SwpaPlugin\";i:1;s:9:\"uninstall\";}}","no");
INSERT INTO wp_options VALUES("86","timezone_string","","yes");
INSERT INTO wp_options VALUES("88","page_on_front","2","yes");
INSERT INTO wp_options VALUES("89","default_post_format","0","yes");
INSERT INTO wp_options VALUES("90","link_manager_enabled","0","yes");
INSERT INTO wp_options VALUES("91","initial_db_version","26691","yes");
INSERT INTO wp_options VALUES("92","wp_user_roles","a:5:{s:13:\"administrator\";a:2:{s:4:\"name\";s:13:\"Administrator\";s:12:\"capabilities\";a:62:{s:13:\"switch_themes\";b:1;s:11:\"edit_themes\";b:1;s:16:\"activate_plugins\";b:1;s:12:\"edit_plugins\";b:1;s:10:\"edit_users\";b:1;s:10:\"edit_files\";b:1;s:14:\"manage_options\";b:1;s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:6:\"import\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:8:\"level_10\";b:1;s:7:\"level_9\";b:1;s:7:\"level_8\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:12:\"delete_users\";b:1;s:12:\"create_users\";b:1;s:17:\"unfiltered_upload\";b:1;s:14:\"edit_dashboard\";b:1;s:14:\"update_plugins\";b:1;s:14:\"delete_plugins\";b:1;s:15:\"install_plugins\";b:1;s:13:\"update_themes\";b:1;s:14:\"install_themes\";b:1;s:11:\"update_core\";b:1;s:10:\"list_users\";b:1;s:12:\"remove_users\";b:1;s:9:\"add_users\";b:1;s:13:\"promote_users\";b:1;s:18:\"edit_theme_options\";b:1;s:13:\"delete_themes\";b:1;s:6:\"export\";b:1;}}s:6:\"editor\";a:2:{s:4:\"name\";s:6:\"Editor\";s:12:\"capabilities\";a:34:{s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;}}s:6:\"author\";a:2:{s:4:\"name\";s:6:\"Author\";s:12:\"capabilities\";a:10:{s:12:\"upload_files\";b:1;s:10:\"edit_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:22:\"delete_published_posts\";b:1;}}s:11:\"contributor\";a:2:{s:4:\"name\";s:11:\"Contributor\";s:12:\"capabilities\";a:5:{s:10:\"edit_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;}}s:10:\"subscriber\";a:2:{s:4:\"name\";s:10:\"Subscriber\";s:12:\"capabilities\";a:2:{s:4:\"read\";b:1;s:7:\"level_0\";b:1;}}}","yes");
INSERT INTO wp_options VALUES("93","widget_search","a:2:{i:2;a:1:{s:5:\"title\";s:0:\"\";}s:12:\"_multiwidget\";i:1;}","yes");
INSERT INTO wp_options VALUES("94","widget_recent-posts","a:2:{i:2;a:3:{s:5:\"title\";s:0:\"\";s:6:\"number\";i:5;s:9:\"show_date\";b:0;}s:12:\"_multiwidget\";i:1;}","yes");
INSERT INTO wp_options VALUES("95","widget_recent-comments","a:1:{s:12:\"_multiwidget\";i:1;}","yes");
INSERT INTO wp_options VALUES("96","widget_archives","a:1:{s:12:\"_multiwidget\";i:1;}","yes");
INSERT INTO wp_options VALUES("97","widget_meta","a:1:{s:12:\"_multiwidget\";i:1;}","yes");
INSERT INTO wp_options VALUES("98","sidebars_widgets","a:4:{s:19:\"wp_inactive_widgets\";a:0:{}s:7:\"sidebar\";a:4:{i:0;s:8:\"search-2\";i:1;s:14:\"recent-posts-2\";i:2;s:12:\"categories-2\";i:3;s:11:\"rss_links-2\";}s:18:\"home-page-top-area\";a:4:{i:0;s:30:\"bavotasan_custom_text_widget-2\";i:1;s:30:\"bavotasan_custom_text_widget-3\";i:2;s:30:\"bavotasan_custom_text_widget-4\";i:3;s:30:\"bavotasan_custom_text_widget-5\";}s:13:\"array_version\";i:3;}","yes");
INSERT INTO wp_options VALUES("99","cron","a:8:{i:1387795910;a:1:{s:20:\"jetpack_clean_nonces\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}}i:1387796116;a:1:{s:25:\"swpa_cleanup_live_traffic\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}}i:1387809320;a:3:{s:16:\"wp_version_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:17:\"wp_update_plugins\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:16:\"wp_update_themes\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1387809337;a:1:{s:19:\"wp_scheduled_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1387821316;a:1:{s:21:\"swpa_check_user_admin\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:2:\"8h\";s:4:\"args\";a:0:{}s:8:\"interval\";i:28800;}}}i:1387822134;a:1:{s:30:\"wp_scheduled_auto_draft_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1387825020;a:1:{s:20:\"wp_maybe_auto_update\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}s:7:\"version\";i:2;}","yes");
INSERT INTO wp_options VALUES("106","_site_transient_timeout_browser_2a45585bf6fbca9e24972054edb9a714","1387636531","yes");
INSERT INTO wp_options VALUES("107","_site_transient_browser_2a45585bf6fbca9e24972054edb9a714","a:9:{s:8:\"platform\";s:7:\"Windows\";s:4:\"name\";s:6:\"Chrome\";s:7:\"version\";s:12:\"31.0.1650.63\";s:10:\"update_url\";s:28:\"http://www.google.com/chrome\";s:7:\"img_src\";s:49:\"http://s.wordpress.org/images/browsers/chrome.png\";s:11:\"img_src_ssl\";s:48:\"https://wordpress.org/images/browsers/chrome.png\";s:15:\"current_version\";s:2:\"18\";s:7:\"upgrade\";b:0;s:8:\"insecure\";b:0;}","yes");
INSERT INTO wp_options VALUES("112","can_compress_scripts","1","yes");
INSERT INTO wp_options VALUES("121","_transient_timeout_plugin_slugs","1387878917","no");
INSERT INTO wp_options VALUES("122","_transient_plugin_slugs","a:15:{i:0;s:26:\"secure-wordpress/index.php\";i:1;s:43:\"all-in-one-seo-pack/all_in_one_seo_pack.php\";i:2;s:95:\"clean-and-simple-contact-form-by-meg-nicholas/clean-and-simple-contact-form-by-meg-nicholas.php\";i:3;s:36:\"contact-form-7/wp-contact-form-7.php\";i:4;s:37:\"disable-comments/disable-comments.php\";i:5;s:53:\"easy-bootstrap-shortcodes/osc_bootstrap_shortcode.php\";i:6;s:50:\"google-analytics-for-wordpress/googleanalytics.php\";i:7;s:49:\"google-xml-sitemaps-v3-for-qtranslate/sitemap.php\";i:8;s:38:\"hide-title/dojo-digital-hide-title.php\";i:9;s:19:\"jetpack/jetpack.php\";i:10;s:47:\"quick-paypal-payments/quick-paypal-payments.php\";i:11;s:37:\"tinymce-advanced/tinymce-advanced.php\";i:12;s:33:\"w3-total-cache/w3-total-cache.php\";i:13;s:41:\"wordpress-importer/wordpress-importer.php\";i:14;s:29:\"wp-mail-smtp/wp_mail_smtp.php\";}","no");
INSERT INTO wp_options VALUES("129","EBS_BOOTSTRAP_JS_LOCATION","1","yes");
INSERT INTO wp_options VALUES("130","EBS_BOOTSTRAP_JS_CDN_PATH","http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js","yes");
INSERT INTO wp_options VALUES("131","EBS_BOOTSTRAP_RESPOND_CDN_PATH","http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js","yes");
INSERT INTO wp_options VALUES("132","EBS_BOOTSTRAP_RESPOND_LOCATION","1","yes");
INSERT INTO wp_options VALUES("133","EBS_BOOTSTRAP_CSS_LOCATION","1","yes");
INSERT INTO wp_options VALUES("134","recently_activated","a:0:{}","yes");
INSERT INTO wp_options VALUES("145","current_theme","Tonic","yes");
INSERT INTO wp_options VALUES("146","theme_mods_the-bootstrap","a:9:{i:0;b:0;s:16:\"header_textcolor\";s:6:\"7f7f7f\";s:16:\"background_color\";s:6:\"cccccc\";s:16:\"background_image\";s:0:\"\";s:17:\"background_repeat\";s:6:\"repeat\";s:21:\"background_position_x\";s:4:\"left\";s:21:\"background_attachment\";s:5:\"fixed\";s:18:\"nav_menu_locations\";a:3:{s:7:\"primary\";i:2;s:11:\"header-menu\";i:0;s:11:\"footer-menu\";i:3;}s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1387186492;s:4:\"data\";a:3:{s:19:\"wp_inactive_widgets\";a:5:{i:0;s:8:\"search-2\";i:1;s:6:\"text-3\";i:2;s:6:\"text-4\";i:3;s:12:\"categories-2\";i:4;s:14:\"recent-posts-2\";}s:4:\"main\";a:0:{}s:5:\"image\";a:0:{}}}}","yes");
INSERT INTO wp_options VALUES("147","theme_switched","","yes");
INSERT INTO wp_options VALUES("148","theme_mods_tonic","a:8:{i:0;b:0;s:16:\"header_textcolor\";s:6:\"9abc00\";s:16:\"background_color\";s:6:\"cccccc\";s:16:\"background_image\";s:0:\"\";s:17:\"background_repeat\";s:6:\"repeat\";s:21:\"background_position_x\";s:4:\"left\";s:21:\"background_attachment\";s:5:\"fixed\";s:18:\"nav_menu_locations\";a:1:{s:7:\"primary\";i:2;}}","yes");
INSERT INTO wp_options VALUES("149","tonic_theme_options","a:15:{s:7:\"tagline\";b:0;s:5:\"width\";s:0:\"\";s:6:\"layout\";s:1:\"6\";s:7:\"primary\";s:3:\"c10\";s:15:\"excerpt_content\";s:7:\"content\";s:11:\"home_widget\";b:1;s:10:\"home_posts\";b:0;s:20:\"jumbo_headline_title\";s:32:\"Gérez vos abonnements en ligne!\";s:19:\"jumbo_headline_text\";s:211:\"Vous vendez des prestations périodiques? <br> Calculer votre revenu mensuel disponible est un casse tête? <br> Gérer votre fichier client et vos relances est fastidieux? <br> Aboo est l\'outil qu\'il vous faut!\";s:26:\"jumbo_headline_button_text\";s:14:\"Connectez-vous\";s:26:\"jumbo_headline_button_link\";s:13:\"connexion.php\";s:18:\"display_categories\";b:1;s:14:\"display_author\";s:2:\"on\";s:12:\"display_date\";s:2:\"on\";s:21:\"display_comment_count\";b:0;}","yes");
INSERT INTO wp_options VALUES("151","wpcf7","a:1:{s:7:\"version\";s:3:\"3.6\";}","yes");
INSERT INTO wp_options VALUES("155","cscf_version","4.3.0","yes");
INSERT INTO wp_options VALUES("156","cscf_options","a:1:{s:13:\"confirm-email\";b:1;}","yes");
INSERT INTO wp_options VALUES("168","_site_transient_update_core","O:8:\"stdClass\":4:{s:7:\"updates\";a:2:{i:0;O:8:\"stdClass\":10:{s:8:\"response\";s:6:\"latest\";s:8:\"download\";s:47:\"http://fr.wordpress.org/wordpress-3.8-fr_FR.zip\";s:6:\"locale\";s:5:\"fr_FR\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:47:\"http://fr.wordpress.org/wordpress-3.8-fr_FR.zip\";s:10:\"no_content\";b:0;s:11:\"new_bundled\";b:0;s:7:\"partial\";b:0;s:8:\"rollback\";b:0;}s:7:\"current\";s:3:\"3.8\";s:7:\"version\";s:3:\"3.8\";s:11:\"php_version\";s:5:\"5.2.4\";s:13:\"mysql_version\";s:3:\"5.0\";s:11:\"new_bundled\";s:3:\"3.8\";s:15:\"partial_version\";s:0:\"\";}i:1;O:8:\"stdClass\":10:{s:8:\"response\";s:6:\"latest\";s:8:\"download\";s:39:\"https://wordpress.org/wordpress-3.8.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:39:\"https://wordpress.org/wordpress-3.8.zip\";s:10:\"no_content\";s:50:\"https://wordpress.org/wordpress-3.8-no-content.zip\";s:11:\"new_bundled\";s:51:\"https://wordpress.org/wordpress-3.8-new-bundled.zip\";s:7:\"partial\";b:0;s:8:\"rollback\";b:0;}s:7:\"current\";s:3:\"3.8\";s:7:\"version\";s:3:\"3.8\";s:11:\"php_version\";s:5:\"5.2.4\";s:13:\"mysql_version\";s:3:\"5.0\";s:11:\"new_bundled\";s:3:\"3.8\";s:15:\"partial_version\";s:0:\"\";}}s:12:\"last_checked\";i:1387792451;s:15:\"version_checked\";s:3:\"3.8\";s:12:\"translations\";a:0:{}}","yes");
INSERT INTO wp_options VALUES("171","_transient_random_seed","a898bdf59b04b19db81f594efb64929b","yes");
INSERT INTO wp_options VALUES("175","sm_options","a:56:{s:18:\"sm_b_prio_provider\";s:41:\"GoogleSitemapGeneratorPrioByCountProvider\";s:13:\"sm_b_filename\";s:11:\"sitemap.xml\";s:10:\"sm_b_debug\";b:1;s:8:\"sm_b_xml\";b:1;s:9:\"sm_b_gzip\";b:1;s:9:\"sm_b_ping\";b:1;s:12:\"sm_b_pingmsn\";b:1;s:19:\"sm_b_manual_enabled\";b:0;s:17:\"sm_b_auto_enabled\";b:1;s:15:\"sm_b_auto_delay\";b:1;s:15:\"sm_b_manual_key\";s:32:\"7da44283f16ff6ed283da53a1001e9ec\";s:11:\"sm_b_memory\";s:0:\"\";s:9:\"sm_b_time\";i:-1;s:14:\"sm_b_max_posts\";i:-1;s:13:\"sm_b_safemode\";b:0;s:18:\"sm_b_style_default\";b:1;s:10:\"sm_b_style\";s:0:\"\";s:11:\"sm_b_robots\";b:1;s:12:\"sm_b_exclude\";a:0:{}s:17:\"sm_b_exclude_cats\";a:0:{}s:18:\"sm_b_location_mode\";s:4:\"auto\";s:20:\"sm_b_filename_manual\";s:0:\"\";s:19:\"sm_b_fileurl_manual\";s:0:\"\";s:10:\"sm_in_home\";b:1;s:11:\"sm_in_posts\";b:1;s:15:\"sm_in_posts_sub\";b:0;s:11:\"sm_in_pages\";b:1;s:10:\"sm_in_cats\";b:0;s:10:\"sm_in_arch\";b:0;s:10:\"sm_in_auth\";b:0;s:10:\"sm_in_tags\";b:0;s:9:\"sm_in_tax\";a:0:{}s:17:\"sm_in_customtypes\";a:0:{}s:13:\"sm_in_lastmod\";b:1;s:10:\"sm_cf_home\";s:5:\"daily\";s:11:\"sm_cf_posts\";s:7:\"monthly\";s:11:\"sm_cf_pages\";s:6:\"weekly\";s:10:\"sm_cf_cats\";s:6:\"weekly\";s:10:\"sm_cf_auth\";s:6:\"weekly\";s:15:\"sm_cf_arch_curr\";s:5:\"daily\";s:14:\"sm_cf_arch_old\";s:6:\"yearly\";s:10:\"sm_cf_tags\";s:6:\"weekly\";s:10:\"sm_pr_home\";d:1;s:11:\"sm_pr_posts\";d:0.59999999999999997779553950749686919152736663818359375;s:15:\"sm_pr_posts_min\";d:0.200000000000000011102230246251565404236316680908203125;s:11:\"sm_pr_pages\";d:0.59999999999999997779553950749686919152736663818359375;s:10:\"sm_pr_cats\";d:0.299999999999999988897769753748434595763683319091796875;s:10:\"sm_pr_arch\";d:0.299999999999999988897769753748434595763683319091796875;s:10:\"sm_pr_auth\";d:0.299999999999999988897769753748434595763683319091796875;s:10:\"sm_pr_tags\";d:0.299999999999999988897769753748434595763683319091796875;s:12:\"sm_i_donated\";b:0;s:17:\"sm_i_hide_donated\";b:0;s:17:\"sm_i_install_date\";i:1387040772;s:14:\"sm_i_hide_note\";b:0;s:15:\"sm_i_hide_works\";b:0;s:16:\"sm_i_hide_donors\";b:0;}","yes");
INSERT INTO wp_options VALUES("176","nav_menu_options","a:2:{i:0;b:0;s:8:\"auto_add\";a:1:{i:0;i:2;}}","yes");
INSERT INTO wp_options VALUES("179","widget_calendar","a:1:{s:12:\"_multiwidget\";i:1;}","yes");
INSERT INTO wp_options VALUES("180","theme_mods_customizr","a:3:{i:0;b:0;s:18:\"nav_menu_locations\";a:1:{s:4:\"main\";i:2;}s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1387192066;s:4:\"data\";a:6:{s:19:\"wp_inactive_widgets\";a:2:{i:0;s:6:\"text-3\";i:1;s:6:\"text-4\";}s:5:\"right\";a:3:{i:0;s:8:\"search-2\";i:1;s:14:\"recent-posts-2\";i:2;s:12:\"categories-2\";}s:4:\"left\";a:0:{}s:10:\"footer_one\";N;s:10:\"footer_two\";N;s:12:\"footer_three\";N;}}}","yes");
INSERT INTO wp_options VALUES("182","menu_button","","yes");
INSERT INTO wp_options VALUES("183","tc_theme_options","a:49:{s:7:\"tc_skin\";s:9:\"green.css\";s:13:\"tc_top_border\";b:1;s:14:\"tc_logo_upload\";s:0:\"\";s:14:\"tc_logo_resize\";i:1;s:13:\"tc_fav_upload\";s:0:\"\";s:15:\"tc_front_layout\";s:1:\"f\";s:15:\"tc_front_slider\";s:1:\"0\";s:15:\"tc_slider_width\";b:1;s:15:\"tc_slider_delay\";i:5000;s:22:\"tc_show_featured_pages\";s:1:\"1\";s:26:\"tc_show_featured_pages_img\";b:0;s:28:\"tc_featured_page_button_text\";s:16:\"Lire la suite »\";s:20:\"tc_featured_page_one\";s:2:\"55\";s:20:\"tc_featured_page_two\";s:2:\"66\";s:22:\"tc_featured_page_three\";s:2:\"41\";s:20:\"tc_featured_text_one\";s:0:\"\";s:20:\"tc_featured_text_two\";s:0:\"\";s:22:\"tc_featured_text_three\";s:0:\"\";s:13:\"tc_breadcrumb\";b:0;s:24:\"tc_sidebar_global_layout\";s:1:\"f\";s:23:\"tc_sidebar_force_layout\";b:0;s:22:\"tc_sidebar_post_layout\";s:1:\"f\";s:19:\"tc_post_list_length\";s:4:\"full\";s:22:\"tc_sidebar_page_layout\";s:1:\"f\";s:16:\"tc_page_comments\";i:0;s:19:\"tc_social_in_header\";b:0;s:25:\"tc_social_in_left-sidebar\";i:0;s:26:\"tc_social_in_right-sidebar\";i:0;s:19:\"tc_social_in_footer\";i:1;s:6:\"tc_rss\";s:44:\"http://localhost/gestabo/wordpress/feed/rss/\";s:10:\"tc_twitter\";s:0:\"\";s:11:\"tc_facebook\";s:0:\"\";s:9:\"tc_google\";s:0:\"\";s:12:\"tc_instagram\";s:0:\"\";s:12:\"tc_wordpress\";s:0:\"\";s:10:\"tc_youtube\";s:0:\"\";s:12:\"tc_pinterest\";s:0:\"\";s:9:\"tc_github\";s:0:\"\";s:11:\"tc_dribbble\";s:0:\"\";s:11:\"tc_linkedin\";s:0:\"\";s:11:\"tc_fancybox\";i:1;s:21:\"tc_fancybox_autoscale\";i:1;s:13:\"tc_custom_css\";s:0:\"\";s:12:\"tc_debug_box\";b:0;s:13:\"tc_debug_tips\";b:0;s:19:\"tc_debug_tips_color\";s:4:\"#F00\";s:12:\"tc_menu_type\";s:5:\"hover\";s:17:\"tc_retina_support\";i:1;s:14:\"tc_link_scroll\";b:1;}","yes");
INSERT INTO wp_options VALUES("184","hr_logo","","yes");
INSERT INTO wp_options VALUES("185","homecontent_title","","yes");
INSERT INTO wp_options VALUES("186","slider_check","","yes");
INSERT INTO wp_options VALUES("187","dev_box_title","","yes");
INSERT INTO wp_options VALUES("188","dev_tooltip_title","","yes");
INSERT INTO wp_options VALUES("189","the_bootstrap_theme_options","a:5:{s:12:\"theme_layout\";s:15:\"content-sidebar\";s:16:\"navbar_site_name\";b:1;s:17:\"navbar_searchform\";b:0;s:14:\"navbar_inverse\";b:1;s:15:\"navbar_position\";s:6:\"static\";}","yes");
INSERT INTO wp_options VALUES("191","theme_mods_twentyfourteen","a:9:{s:16:\"header_textcolor\";s:3:\"fff\";s:16:\"background_color\";s:6:\"f5f5f5\";s:16:\"background_image\";s:0:\"\";s:17:\"background_repeat\";s:6:\"repeat\";s:21:\"background_position_x\";s:4:\"left\";s:21:\"background_attachment\";s:5:\"fixed\";s:18:\"nav_menu_locations\";a:2:{s:7:\"primary\";i:3;s:9:\"secondary\";i:2;}s:23:\"featured_content_layout\";s:4:\"grid\";s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1387186604;s:4:\"data\";a:4:{s:19:\"wp_inactive_widgets\";a:5:{i:0;s:8:\"search-2\";i:1;s:6:\"text-3\";i:2;s:6:\"text-4\";i:3;s:12:\"categories-2\";i:4;s:14:\"recent-posts-2\";}s:9:\"sidebar-1\";a:0:{}s:9:\"sidebar-2\";a:1:{i:0;s:32:\"widget_twentyfourteen_ephemera-2\";}s:9:\"sidebar-3\";a:0:{}}}}","yes");
INSERT INTO wp_options VALUES("197","sm_status","O:28:\"GoogleSitemapGeneratorStatus\":24:{s:10:\"_startTime\";d:1387661106.2678329944610595703125;s:8:\"_endTime\";d:1387661107.0818789005279541015625;s:11:\"_hasChanged\";b:1;s:12:\"_memoryUsage\";i:17563648;s:9:\"_lastPost\";i:8;s:9:\"_lastTime\";d:1387661106.5288479328155517578125;s:8:\"_usedXml\";b:1;s:11:\"_xmlSuccess\";b:1;s:8:\"_xmlPath\";s:35:\"D:/xampp/htdocs/gestabo/sitemap.xml\";s:7:\"_xmlUrl\";s:36:\"http://localhost/gestabo/sitemap.xml\";s:8:\"_usedZip\";b:1;s:11:\"_zipSuccess\";b:1;s:8:\"_zipPath\";s:38:\"D:/xampp/htdocs/gestabo/sitemap.xml.gz\";s:7:\"_zipUrl\";s:39:\"http://localhost/gestabo/sitemap.xml.gz\";s:11:\"_usedGoogle\";b:1;s:10:\"_googleUrl\";s:104:\"http://www.google.com/webmasters/sitemaps/ping?sitemap=http%3A%2F%2Flocalhost%2Fgestabo%2Fsitemap.xml.gz\";s:15:\"_gooogleSuccess\";b:1;s:16:\"_googleStartTime\";d:1387661106.6218531131744384765625;s:14:\"_googleEndTime\";d:1387661106.7578608989715576171875;s:8:\"_usedMsn\";b:1;s:7:\"_msnUrl\";s:97:\"http://www.bing.com/webmaster/ping.aspx?siteMap=http%3A%2F%2Flocalhost%2Fgestabo%2Fsitemap.xml.gz\";s:11:\"_msnSuccess\";b:1;s:13:\"_msnStartTime\";d:1387661106.758861064910888671875;s:11:\"_msnEndTime\";d:1387661107.0798790454864501953125;}","no");
INSERT INTO wp_options VALUES("226","theme_mods_twentythirteen","a:6:{i:0;b:0;s:16:\"header_textcolor\";s:6:\"878787\";s:12:\"header_image\";s:93:\"http://localhost/gestabo/wordpress/wp-content/themes/twentythirteen/images/headers/circle.png\";s:18:\"nav_menu_locations\";a:1:{s:7:\"primary\";i:2;}s:17:\"header_image_data\";a:3:{s:3:\"url\";s:93:\"http://localhost/gestabo/wordpress/wp-content/themes/twentythirteen/images/headers/circle.png\";s:13:\"thumbnail_url\";s:103:\"http://localhost/gestabo/wordpress/wp-content/themes/twentythirteen/images/headers/circle-thumbnail.png\";s:11:\"description\";s:6:\"Cercle\";}s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1387186557;s:4:\"data\";a:3:{s:19:\"wp_inactive_widgets\";a:5:{i:0;s:8:\"search-2\";i:1;s:6:\"text-3\";i:2;s:6:\"text-4\";i:3;s:12:\"categories-2\";i:4;s:14:\"recent-posts-2\";}s:9:\"sidebar-1\";a:0:{}s:9:\"sidebar-2\";a:0:{}}}}","yes");
INSERT INTO wp_options VALUES("229","page_for_posts","17","yes");
INSERT INTO wp_options VALUES("230","featured-content","a:2:{s:6:\"tag-id\";i:0;s:8:\"hide-tag\";i:1;}","yes");
INSERT INTO wp_options VALUES("237","widget_widget_twentyfourteen_ephemera","a:2:{i:2;a:3:{s:5:\"title\";s:0:\"\";s:6:\"number\";i:1;s:6:\"format\";s:4:\"link\";}s:12:\"_multiwidget\";i:1;}","yes");
INSERT INTO wp_options VALUES("250","theme_mods_wordpress-bootstrap","a:8:{i:0;b:0;s:16:\"background_color\";s:0:\"\";s:16:\"background_image\";s:0:\"\";s:17:\"background_repeat\";s:6:\"repeat\";s:21:\"background_position_x\";s:4:\"left\";s:21:\"background_attachment\";s:5:\"fixed\";s:18:\"nav_menu_locations\";a:2:{s:8:\"main_nav\";i:2;s:12:\"footer_links\";i:0;}s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1387198719;s:4:\"data\";a:6:{s:19:\"wp_inactive_widgets\";a:2:{i:0;s:6:\"text-3\";i:1;s:6:\"text-4\";}s:8:\"sidebar1\";a:3:{i:0;s:8:\"search-2\";i:1;s:12:\"categories-2\";i:2;s:14:\"recent-posts-2\";}s:8:\"sidebar2\";a:0:{}s:7:\"footer1\";a:0:{}s:7:\"footer2\";a:0:{}s:7:\"footer3\";a:0:{}}}}","yes");
INSERT INTO wp_options VALUES("251","optionsframework","a:2:{s:2:\"id\";s:11:\"wpbootstrap\";s:12:\"knownoptions\";a:1:{i:0;s:11:\"wpbootstrap\";}}","yes");
INSERT INTO wp_options VALUES("252","wpbootstrap","a:22:{s:18:\"heading_typography\";a:3:{s:4:\"face\";s:43:\"\"Helvetica Neue\",Helvetica,Arial,sans-serif\";s:5:\"style\";s:6:\"normal\";s:5:\"color\";s:0:\"\";}s:20:\"main_body_typography\";a:3:{s:4:\"face\";s:6:\"tahoma\";s:5:\"style\";s:6:\"italic\";s:5:\"color\";s:0:\"\";}s:10:\"link_color\";s:0:\"\";s:16:\"link_hover_color\";s:0:\"\";s:17:\"link_active_color\";s:0:\"\";s:12:\"nav_position\";s:5:\"fixed\";s:16:\"top_nav_bg_color\";s:7:\"#000000\";s:19:\"showhidden_gradient\";s:1:\"1\";s:29:\"top_nav_bottom_gradient_color\";s:0:\"\";s:18:\"top_nav_link_color\";s:0:\"\";s:24:\"top_nav_link_hover_color\";s:0:\"\";s:21:\"top_nav_dropdown_item\";s:0:\"\";s:25:\"top_nav_dropdown_hover_bg\";s:0:\"\";s:10:\"search_bar\";s:1:\"0\";s:17:\"showhidden_themes\";s:1:\"1\";s:10:\"wpbs_theme\";s:7:\"simplex\";s:24:\"showhidden_slideroptions\";s:1:\"0\";s:14:\"slider_options\";s:1:\"5\";s:18:\"hero_unit_bg_color\";s:0:\"\";s:25:\"suppress_comments_message\";s:1:\"1\";s:9:\"blog_hero\";s:1:\"0\";s:8:\"wpbs_css\";s:0:\"\";}","yes");
INSERT INTO wp_options VALUES("302","_transient_timeout_feed_66a70e9599b658d5cc038e8074597e7c","1387187707","no");
INSERT INTO wp_options VALUES("303","_transient_feed_66a70e9599b658d5cc038e8074597e7c","a:4:{s:5:\"child\";a:1:{s:0:\"\";a:1:{s:3:\"rss\";a:1:{i:0;a:6:{s:4:\"data\";s:3:\"
\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:7:\"version\";s:3:\"2.0\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:0:\"\";a:1:{s:7:\"channel\";a:1:{i:0;a:6:{s:4:\"data\";s:51:\"
	\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:5:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:21:\"WordPress Francophone\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:27:\"http://www.wordpress-fr.net\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:70:\"La communauté francophone autour du CMS WordPress et son écosystème\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:13:\"lastBuildDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Fri, 13 Dec 2013 12:43:28 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"language\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"fr-FR\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"generator\";a:1:{i:0;a:5:{s:4:\"data\";s:27:\"http://wordpress.org/?v=3.8\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"item\";a:10:{i:0;a:6:{s:4:\"data\";s:45:\"
		\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:6:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:38:\"Sortie de WordPress 3.8 « Parker »\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:67:\"http://feedproxy.google.com/~r/WordpressFrancophone/~3/ag9OPg37Fmk/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"comments\";a:1:{i:0;a:5:{s:4:\"data\";s:79:\"http://www.wordpress-fr.net/2013/12/13/sortie-de-wordpress-3-8-parker/#comments\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Fri, 13 Dec 2013 12:43:28 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:2:{i:0;a:5:{s:4:\"data\";s:4:\"Blog\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:9:\"WordPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"http://www.wordpress-fr.net/?p=6547\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:427:\"La version 3.8 de WordPress, baptisée &#171;&#160;Parker&#160;&#187; en hommage à Charlie Parker, l&#8217;innovateur du be-bop, est disponible en téléchargement ou en mise à jour automatique depuis le tableau de bord de votre WordPress. Nous pensons que c&#8217;est le WordPress le plus beau à ce jour. &#160; Un nouveau design moderne WordPress a fait peau neuve. La version 3.8 dispose d&#8217;une toute [&#8230;]\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:6:\"Xavier\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:6645:\"<p><span style=\"line-height: 1.5em;\">La version 3.8 de WordPress, baptisée &laquo;&nbsp;Parker&nbsp;&raquo; en hommage à </span><a style=\"line-height: 1.5em;\" href=\"https://fr.wikipedia.org/wiki/Charlie_Parker\">Charlie Parker</a><span style=\"line-height: 1.5em;\">, l&rsquo;innovateur du be-bop, est <a href=\"http://fr.wordpress.org/\">disponible en téléchargement</a> ou en mise à jour automatique depuis le tableau de bord de votre WordPress. Nous pensons que c&rsquo;est le WordPress le plus beau à ce jour.</span></p>
<p><object width=\"400\" height=\"224\" classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0\"><param name=\"src\" value=\"http://s0.videopress.com/player.swf?v=1.03\" /><param name=\"wmode\" value=\"direct\" /><param name=\"seamlesstabbing\" value=\"true\" /><param name=\"allowfullscreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /><param name=\"overstretch\" value=\"true\" /><param name=\"flashvars\" value=\"guid=6wORgoGb&amp;isDynamicSeeking=true\" /><embed width=\"400\" height=\"224\" type=\"application/x-shockwave-flash\" src=\"http://s0.videopress.com/player.swf?v=1.03\" wmode=\"direct\" seamlesstabbing=\"true\" allowfullscreen=\"true\" allowscriptaccess=\"always\" overstretch=\"true\" flashvars=\"guid=6wORgoGb&amp;isDynamicSeeking=true\" /></object></p>
<p>&nbsp;</p>
<h2>Un nouveau design moderne</h2>
<p><span style=\"line-height: 1.5em;\">WordPress a fait peau neuve. La version 3.8 dispose d&rsquo;une toute nouvelle apparence pour son administration. Terminés les dégradés imposants et les douzines de nuances de gris &#8212; faites place à un design plus grand, plus audacieux, plus coloré </span></p>
<p><img class=\"alignnone\" alt=\"\" src=\"http://i2.wp.com/wpdotorg.files.wordpress.com/2013/12/design.png?resize=623%2C151\" width=\"623\" height=\"151\" /></p>
<h3>Une esthétique moderne</h3>
<p>Le nouveau tableau de bord de WordPress propose une mise en page agréable et épurée qui fait la part belle à la clarté et la simplicité.</p>
<h3>Une typographie nette</h3>
<p>La police de caractères Open Sans présente votre texte de manière simple et conviviale. Elle est optimisée tant pour les ordinateurs de bureau que pour l’affichage mobile, et elle est open-source, tout comme WordPress.</p>
<h3>Des contrastes affinés</h3>
<p>Nous pensons qu’un beau design ne devrait jamais sacrifier à la lisibilité. Avec des contrastes larges et supérieurs et une police agréable, le nouveau design est facile à lire et un plaisir à parcourir.</p>
<h2>WordPress sur tous les terminaux</h2>
<p>Nous accédons tous à Internet de différentes manières. Téléphones mobiles, tablettes, ordinateurs portables ou de bureau — quel que soit votre usage, WordPress s’y adaptera et vous vous sentirez chez vous.</p>
<h3>Haute définition à haute vitesse</h3>
<p>WordPress est plus classe que jamais avec ses nouvelles icônes vectorielles qui s’adaptent à votre écran. En éliminant les pixels, nous avons également amélioré le temps de chargement de la page.</p>
<h2>Des jeux de couleurs adaptés à votre personnalité</h2>
<p>WordPress a reçu une mise à jour haute en couleurs. Nous avons ajouté 8 jeux de couleurs afin que vous trouviez votre préféré.</p>
<p>Vous pouvez prévisualiser et changer le jeu de couleurs à n’importe quel moment en vous rendant sur votre profil.</p>
<h2>Une gestion des thèmes affinée</h2>
<p>Le nouvel écran des thèmes vous permet d’inspecter tous vos thèmes d’un coup d’œil. Mais peut-être voulez-vous plus d’information ? Cliquez pour en apprendre plus, puis naviguez tranquillement d’un thème à l’autre à l’aide de votre clavier.</p>
<h3>Une utilisation plus souple des widgets</h3>
<p>Glisser-déposer, glisser-déposer, glisser-déposer. Faire défiler, faire défiler, faire défiler. La gestion des widgets peut s’avérer laborieuse. Avec le nouveau design, nous avons rationalisé l’écran des widgets.</p>
<p>Vous avez un grand moniteur ? Les nombreuses zones de widgets se placent les unes à côté des autres pour utiliser tout l’espace disponible. Vous utilisez une tablette ? Touchez simplement un widget pour l’ajouter.</p>
<h2>Twenty Fourteen, un superbe nouveau thème &nbsp;&raquo;magazine&nbsp;&raquo;</h2>
<h3>Transformez votre blog en un magazine</h3>
<p>Créez un magnifique site de style magazine avec WordPress et Twenty Fourteen. Choisissez d’afficher votre contenu mis en avant dans une grille ou un diaporama sur votre page d’accueil. Personnalisez votre site avec trois zones de widgets, ou modifiez votre mise en page grâce à deux modèles de pages.</p>
<p>Avec une nouvelle conception remarquable qui ne remet pas en question notre simplicité légendaire, Twenty Fourteen est notre thème par défaut le plus intrépide à ce jour.</p>
<h2>Le début d&rsquo;une nouvelle ère</h2>
<p>Cette version a été menée par Matt Mullenweg. C&rsquo;est notre deuxième version à utiliser le nouveau processus de développement passant par des prototypes sous forme d&rsquo;extensions, avec un délai beaucoup plus court que par le passé. Nous pensons que ce processus s&rsquo;est très bien déroulé. Vous pouvez découvrir les fonctionnalités en cours de mise en place le blog <a title=\"Make WordPress Core\" href=\"http://make.wordpress.org/core/\" target=\"_blank\">make/core</a>.</p>
<p>Merci à vous d&rsquo;avoir choisi WordPress, et à bientôt pour WordPress 3.9 !</p>
<p><em>(cet article est une adaptation de <a href=\"http://wordpress.org/news/2013/12/parker/\">l&rsquo;annonce originale</a>)</em></p>
<div class=\"feedflare\">
<a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=ag9OPg37Fmk:4CYF5WimPC8:yIl2AUoC8zA\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=yIl2AUoC8zA\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=ag9OPg37Fmk:4CYF5WimPC8:V_sGLiPBpWU\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=ag9OPg37Fmk:4CYF5WimPC8:V_sGLiPBpWU\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=ag9OPg37Fmk:4CYF5WimPC8:qj6IDK7rITs\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=qj6IDK7rITs\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=ag9OPg37Fmk:4CYF5WimPC8:gIN9vFwOqvQ\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=ag9OPg37Fmk:4CYF5WimPC8:gIN9vFwOqvQ\" border=\"0\"></img></a>
</div><img src=\"http://feeds.feedburner.com/~r/WordpressFrancophone/~4/ag9OPg37Fmk\" height=\"1\" width=\"1\"/>\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:36:\"http://wellformedweb.org/CommentAPI/\";a:1:{s:10:\"commentRss\";a:1:{i:0;a:5:{s:4:\"data\";s:75:\"http://www.wordpress-fr.net/2013/12/13/sortie-de-wordpress-3-8-parker/feed/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:38:\"http://purl.org/rss/1.0/modules/slash/\";a:1:{s:8:\"comments\";a:1:{i:0;a:5:{s:4:\"data\";s:1:\"4\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:42:\"http://rssnamespace.org/feedburner/ext/1.0\";a:1:{s:8:\"origLink\";a:1:{i:0;a:5:{s:4:\"data\";s:70:\"http://www.wordpress-fr.net/2013/12/13/sortie-de-wordpress-3-8-parker/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:1;a:6:{s:4:\"data\";s:54:\"
		\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:6:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:73:\"L’Hebdo WordPress n°211 : WordPress 3.8 – BuddyPress – WooCommerce\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:67:\"http://feedproxy.google.com/~r/WordpressFrancophone/~3/_XliP6voE44/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"comments\";a:1:{i:0;a:5:{s:4:\"data\";s:107:\"http://www.wordpress-fr.net/2013/12/10/lhebdo-wordpress-n211-wordpress-3-8-buddypress-woocommerce/#comments\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Tue, 10 Dec 2013 06:22:11 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:5:{i:0;a:5:{s:4:\"data\";s:14:\"Développement\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:9:\"WordPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:10:\"BuddyPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:3;a:5:{s:4:\"data\";s:5:\"Hebdo\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:4;a:5:{s:4:\"data\";s:13:\"WordPress 3.8\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"http://www.wordpress-fr.net/?p=6537\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:344:\"Des nouvelles de WordPress 3.8 Matt fait un dernier point des avancées de WordPress 3.8 (en). La RC2 sera d’ailleurs peut-être disponible au moment où vous lirez ces lignes. Le futur personnaliseur de thèmes (en) est à découvrir. Le futur de l&#8217;aide Un projet est lancé depuis la fin de l&#8217;été pour faire subir un [&#8230;]\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:7:\"Benoît\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:5070:\"<h3>Des nouvelles de WordPress 3.8</h3>
<p><a href=\"http://make.wordpress.org/core/2013/12/06/state-of-3-8-and-thoughts-on-the-next-week/\">Matt fait un dernier point des avancées de WordPress 3.8</a> (en). La RC2 sera d’ailleurs peut-être disponible au moment où vous lirez ces lignes.</p>
<p>Le <a href=\"http://make.wordpress.org/core/2013/12/07/widgets-and-the-customizer/\">futur personnaliseur de thèmes </a>(en) est à découvrir.</p>
<h3>Le futur de l&rsquo;aide</h3>
<p>Un projet est lancé depuis la fin de l&rsquo;été pour faire subir <a href=\"http://make.wordpress.org/core/2013/12/09/ah-o-breathing-new-life-into-admin-help/\">un lifting à l&rsquo;aide en ligne de WordPress </a>(en).</p>
<h3>Buddy Coffee</h3>
<p>Le 5 février sera le prochain Nutella Day&#8230; mais aussi le le premier BuddyCoffee. <a href=\"http://bp-fr.net/events/buddy-coffee/\">Pour vous inscrire, c&rsquo;est ici</a>.</p>
<h3>BuddyPress 1.9 Beta 2</h3>
<p><a href=\"http://buddypress.org/2013/12/buddypress-1-9-beta-2/\">BuddyPress 1.9 arrive avec une version Beta 2 (en)</a> et se pare d&rsquo;un <a href=\"http://bpdevel.wordpress.com/2013/12/03/a-couple-of-weeks-ago-wordcamp-london-happened/\">nouveau Codex </a>(en).</p>
<h3>WordSesh 2</h3>
<p><a href=\"http://www.wptavern.com/wordsesh-recap-global-wordpress-event-pulls-3000-unique-viewers-from-85-countries\">Ce week end avait lieu WordSesh 2 (en)</a>, la 2e conférence WordPress live sur Internet <a href=\"http://www.wptavern.com/this-week-on-wpweekly-scott-basgaard\">pendant 24 H non stop</a> (en).</p>
<h3>WooCommerce et MailPoet</h3>
<p><a href=\"http://wpchannel.com/newsletter-commande-woocommerce/\">Comment utiliser l&rsquo;extension MailPoet avec WooCommerce</a> ? Réponse sur WPChannel.</p>
<h3>Un thème multilingues avec WPML</h3>
<p><a href=\"http://www.seomix.fr/theme-multilingue-wpml/\">SEOMix présente un thème WordPress avec WPML</a>, l&rsquo;extension multilingues.</p>
<h3>Rocketeer et Jetpack, les 2 font la paire !</h3>
<p><a href=\"http://www.wptavern.com/easier-way-to-enable-or-disable-jetpack-modules-with-rocketeer\">Rocketeer est une nouvelle extension</a> (en) qui va permettre de mieux gérer les modules de Jetpack.</p>
<h3>Automattic doit-il créer une certification WordPress</h3>
<p>la question d&rsquo;une certification WordPress officielle revient régulièrement. <a href=\"http://www.wptavern.com/should-automattic-create-and-manage-a-wordpress-certification-program\">Cette fois c&rsquo;est Automattic qui la pose</a> (en).</p>
<h3>AppPresser : le framework de développement mobile</h3>
<p><a href=\"http://www.wptavern.com/coming-soon-apppresser-mobile-app-framework-for-wordpress\">AppPresser est une application</a> (en) pour mobile qui cache un framework de développement mobile.</p>
<h3>Les icônes de WordPress 3.8</h3>
<p>Si vous voulez réutiliser les icônes de WordPress 3.8, <a href=\"http://jameskoster.co.uk/work/using-wordpress-3-8s-dashicons-theme-plugin\">elles sont ici</a> (en).</p>
<h3>Faire une thème parfait</h3>
<p><a href=\"http://wp.tutsplus.com/tutorials/theme-development/making-the-perfect-wordpress-theme-maintenance-compatibility-and-customer-care/\">C&rsquo;est la promesse de cette présentation sur wptuts.com </a>(en).</p>
<h3>Comparaison entre WordPress, Drupal et Joomla!</h3>
<p>Voici un comparatif assez complet entre les 3 CMS du moment : <a href=\"http://websitesetup.org/cms-comparison-wordpress-vs-joomla-drupal/\">WordPress, Drupal et Joomla!</a> (en).</p>
<h3>Un lien sur chaque galerie d&rsquo;images</h3>
<p>Si vous souhaitez<a href=\"http://wordpressapi.com/add-link-gallery-image-wordpress/\"> ajouter des liens sur vos galeries d&rsquo;images </a>(en).</p>
<h3>Exiger un montant minimum dans WooCommerce.</h3>
<p>Si vous voulez mettre un montant de <a href=\"http://wpchannel.com/montant-minimum-woocommerce-commande/\">commandes minimum dans WooCommerce</a>, essayez cette astuce.</p>
<h3>Interview de Matt par TechCocktail</h3>
<p><a href=\"http://tech.co/matt-mullenweg-dont-let-people-tell-already-done-2013-12\">Le magazine Tech CockTail interviewe Matt. (en)</a></p>
<div class=\"feedflare\">
<a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=_XliP6voE44:atxK-7ND67k:yIl2AUoC8zA\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=yIl2AUoC8zA\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=_XliP6voE44:atxK-7ND67k:V_sGLiPBpWU\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=_XliP6voE44:atxK-7ND67k:V_sGLiPBpWU\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=_XliP6voE44:atxK-7ND67k:qj6IDK7rITs\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=qj6IDK7rITs\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=_XliP6voE44:atxK-7ND67k:gIN9vFwOqvQ\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=_XliP6voE44:atxK-7ND67k:gIN9vFwOqvQ\" border=\"0\"></img></a>
</div><img src=\"http://feeds.feedburner.com/~r/WordpressFrancophone/~4/_XliP6voE44\" height=\"1\" width=\"1\"/>\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:36:\"http://wellformedweb.org/CommentAPI/\";a:1:{s:10:\"commentRss\";a:1:{i:0;a:5:{s:4:\"data\";s:103:\"http://www.wordpress-fr.net/2013/12/10/lhebdo-wordpress-n211-wordpress-3-8-buddypress-woocommerce/feed/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:38:\"http://purl.org/rss/1.0/modules/slash/\";a:1:{s:8:\"comments\";a:1:{i:0;a:5:{s:4:\"data\";s:2:\"13\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:42:\"http://rssnamespace.org/feedburner/ext/1.0\";a:1:{s:8:\"origLink\";a:1:{i:0;a:5:{s:4:\"data\";s:98:\"http://www.wordpress-fr.net/2013/12/10/lhebdo-wordpress-n211-wordpress-3-8-buddypress-woocommerce/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:2;a:6:{s:4:\"data\";s:45:\"
		\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:6:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:17:\"WordPress 3.8 RC1\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:67:\"http://feedproxy.google.com/~r/WordpressFrancophone/~3/s3eZeDrjimM/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"comments\";a:1:{i:0;a:5:{s:4:\"data\";s:66:\"http://www.wordpress-fr.net/2013/12/06/wordpress-3-8-rc1/#comments\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Fri, 06 Dec 2013 14:08:18 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:2:{i:0;a:5:{s:4:\"data\";s:14:\"Développement\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:13:\"WordPress 3.8\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"http://www.wordpress-fr.net/?p=6531\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:354:\"Nous arrivons dans la partie la plus calme mais aussi la plus occupée d&#8217;une version, les problèmes diminuent petit à petit pour vous apporter toutes les nouvelles fonctionnalités tant espérées avec toute la stabilité que vous pouvez attendre de WordPress. Il y a seulement quelques jours qui se sont écoulés depuis le gel du code [&#8230;]\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:7:\"Benoît\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:3272:\"<p>Nous arrivons dans la partie la plus calme mais aussi la plus occupée d&rsquo;une version, les problèmes diminuent petit à petit pour vous apporter toutes les nouvelles fonctionnalités tant espérées avec toute la stabilité que vous pouvez attendre de WordPress. Il y a seulement quelques jours qui se sont écoulés depuis le gel du code de 3.8 qui inclue un <a href=\"http://wordpress.org/news/2013/11/wordpress-3-8-beta-1/\">grand nombre d&rsquo;améliorations importantes</a> (en), donc l&rsquo;objectif est d&rsquo;identifier tous problème majeur et de les résoudre le plus vite possible.</p>
<p>Si vous vous êtes déjà demandé comment contribuer à WordPress, il est temps de commencer : Téléchargez cette version candidate et utilisez la de toutes les manières que vous pourrez imaginez. Essayez de la casser et si vous y parvenez, dites nous comment vous avez fait et soyez certain que cela ne se reproduira plus. Si vous travaillez pour un hébergeur web, c&rsquo;est la version que vous devez tester autant que possible et démarrer votre système de mise à jour automatique et d&rsquo;installation en 1 clic.</p>
<p><a href=\"http://wordpress.org/wordpress-3.8-RC1.zip\">Téléchargez WordPress 3.8 RC1</a> (zip) ou utilisez l&rsquo;extension <a href=\"http://wordpress.org/plugins/wordpress-beta-tester/\">WordPress Beta Tester</a> (avec “bleeding edge nightlies”).</p>
<p>Si vous pensez avoir découvert un bug, vous pouvez laisser un message dans la zone du forum officiel <a href=\"http://wordpress.org/support/forum/alphabeta\">Alpha/Beta (en)</a>. Ou bien, si vous pouvez rédigier un rapport d&rsquo;erreur dirigez-vous vers le <a href=\"http://core.trac.wordpress.org/\">WordPress Trac (en)</a>. Vous trouverez aussi une<a href=\"http://core.trac.wordpress.org/report/5\"> liste des bugs connus (en)</a> et <a href=\"http://core.trac.wordpress.org/query?status=closed&amp;group=component&amp;milestone=3.8\">tout ce qui a déjà été corrigé (en)</a>.</p>
<p><em>Nous sommes si proche de</em><br />
la ligne d&rsquo;arrivée, activez-vous et aidez-nous<em></em><br />
Vous avez un bon karma<em></em></p>
<p>&nbsp;</p>
<p>NB : Ceci est une traduction adaptée de l&rsquo;<a href=\"http://wordpress.org/news/2013/12/3-8-almost/\">article original de Matt</a>.</p>
<div class=\"feedflare\">
<a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=s3eZeDrjimM:ZE61ZTyeHxg:yIl2AUoC8zA\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=yIl2AUoC8zA\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=s3eZeDrjimM:ZE61ZTyeHxg:V_sGLiPBpWU\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=s3eZeDrjimM:ZE61ZTyeHxg:V_sGLiPBpWU\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=s3eZeDrjimM:ZE61ZTyeHxg:qj6IDK7rITs\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=qj6IDK7rITs\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=s3eZeDrjimM:ZE61ZTyeHxg:gIN9vFwOqvQ\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=s3eZeDrjimM:ZE61ZTyeHxg:gIN9vFwOqvQ\" border=\"0\"></img></a>
</div><img src=\"http://feeds.feedburner.com/~r/WordpressFrancophone/~4/s3eZeDrjimM\" height=\"1\" width=\"1\"/>\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:36:\"http://wellformedweb.org/CommentAPI/\";a:1:{s:10:\"commentRss\";a:1:{i:0;a:5:{s:4:\"data\";s:62:\"http://www.wordpress-fr.net/2013/12/06/wordpress-3-8-rc1/feed/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:38:\"http://purl.org/rss/1.0/modules/slash/\";a:1:{s:8:\"comments\";a:1:{i:0;a:5:{s:4:\"data\";s:1:\"0\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:42:\"http://rssnamespace.org/feedburner/ext/1.0\";a:1:{s:8:\"origLink\";a:1:{i:0;a:5:{s:4:\"data\";s:57:\"http://www.wordpress-fr.net/2013/12/06/wordpress-3-8-rc1/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:3;a:6:{s:4:\"data\";s:60:\"
		\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:6:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:75:\"L’Hebdo WordPress n°210 : WordCamp Paris 2014 – bbPress – WordSesh 2\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:67:\"http://feedproxy.google.com/~r/WordpressFrancophone/~3/E3WutvVVZ0A/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"comments\";a:1:{i:0;a:5:{s:4:\"data\";s:72:\"http://www.wordpress-fr.net/2013/12/03/lhebdo-wordpress-n210-2/#comments\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Tue, 03 Dec 2013 06:04:24 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:7:{i:0;a:5:{s:4:\"data\";s:7:\"Astuces\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:14:\"Développement\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:8:\"WordCamp\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:3;a:5:{s:4:\"data\";s:7:\"bbPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:4;a:5:{s:4:\"data\";s:5:\"Hebdo\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:5;a:5:{s:4:\"data\";s:7:\"Jetpack\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:6;a:5:{s:4:\"data\";s:8:\"wordcamp\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"http://www.wordpress-fr.net/?p=6517\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:399:\"WordCamp Paris 2014 Les propositions de conférence sont closes depuis minuit. Nous vous remercions pour vos nombreuses propositions. À l&#8217;heure où j&#8217;écris ces lignes, ce sont presque 55 demandes qui nous sont parvenues. L&#8217;équipe d&#8217;organisation va se réunir sous peu pour déterminer qui aura l&#8217;opportunité de présenter la sienne sur la scène de la MAS [&#8230;]\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:7:\"Benoît\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:5382:\"<h3><a href=\"http://2014.paris.wordcamp.org/\">WordCamp Paris 2014</a></h3>
<p>Les propositions de conférence sont closes depuis minuit. Nous vous remercions pour vos nombreuses propositions. À l&rsquo;heure où j&rsquo;écris ces lignes, ce sont presque 55 demandes qui nous sont parvenues. L&rsquo;équipe d&rsquo;organisation va se réunir sous peu pour déterminer qui aura l&rsquo;opportunité de présenter la sienne <a href=\"http://2014.paris.wordcamp.org/informations-pratiques/\">sur la scène de la MAS et/ou de Sup&rsquo;Internet les 17 et 18 janvier prochains</a>.</p>
<h3>bbPress 2.5</h3>
<p><a href=\"http://bbpress.org/blog/2013/11/bbpress-2-5-released/\">bbPress 2.5 est disponible</a> (en) au téléchargement. Quelques informations supplémentaires <a href=\"http://www.wptavern.com/bbpress-2-5-released-forum-subscriptions-importers-and-theme-compatibility\">à ce propos sur WPTavern</a> (en).</p>
<h3>WordSesh 2</h3>
<p>La 2e édition du <a href=\"http://wordsesh.org/\">WordSesh</a> (en) est le 7 décembre 2013. Le principe est simple, 24 heures de conférences WordPress non stop.</p>
<h3>Jetpack 2.6</h3>
<p><a href=\"http://jetpack.me/2013/11/26/new-release-jetpack-2-6/\">Jetpack 2.6 est disponible</a> (en). Il apporte la fonction de Monitoring pour votre site : un système de surveillance pour vous prévenir en cas de problème&#8230; le temps que <a href=\"http://richardmtl.ca/2013/12/01/jetpack-at-the-beach/\">les développeurs prennent des vacances</a> (en) ^^</p>
<h3>Créer et gérer son identité sur le web</h3>
<p>Gérer son identité sur le web est aujourd&rsquo;hui  indispensable. <a href=\"http://boiteaweb.fr/creer-identite-web-7787.html\">Voici des clés pour s&rsquo;en sortir le mieux possible</a>&#8230; avec WordPress.</p>
<h3>Mettre à jour les commentaires avec l&rsquo;Heartbeat API</h3>
<p>Vous souhaitez que les commentaires se mettent à jour automatiquement lorsqu&rsquo;on lit vos articles ? <a href=\"http://wabeo.fr/commentaires-heartbeat-api/\">Willy propose la solution</a>.</p>
<h3>Créer un thème correctement</h3>
<p>Les bonnes manières en matières de <a href=\"http://dev.tutsplus.com/tutorials/making-the-perfect-wordpress-theme-how-to-code-well--wp-33397\">réalisation de thèmes (en)</a>.</p>
<h3>Un thème premium en jeu</h3>
<p>Pour la mise en place de sa V2, <a href=\"http://www.cree1site.com/theme-storyline-board/\">Rodrigue met en jeu un thème Premium &laquo;&nbsp;Storyline Board&nbsp;&raquo;</a>.</p>
<h3>La communauté WordPress au Japon</h3>
<p>Lors d&rsquo;un voyage au Japon, l&rsquo;auteur de Dysign.fr a fait connaissance <a href=\"http://www.dysign.fr/rencontre-communaute-wordpress-japon/\">avec la communauté WordPress japonaise</a>.</p>
<h3>WordPress et les Google Fonts</h3>
<p>Quelques règles à suivre pour <a href=\"http://wpmu.org/how-to-make-your-wordpress-site-good-lookin-with-google-fonts/\">utiliser les Google Fonts</a> (en) sur votre site web.</p>
<h3>Justin Tadlock : nouveau membre de l&rsquo;équipe de vérificateur de thèmes</h3>
<p><a href=\"http://www.wptavern.com/justin-tadlock-joins-the-wordpress-theme-review-team\">Justin Tadlock vient de rejoindre l&rsquo;équipe de vérificateurs des thèmes</a> soumis dans le répertoire officiel de wordpress.org.</p>
<h3>Ils font la communauté WordPress française</h3>
<p><a href=\"http://www.remicorson.com/the-french-community-in-the-wordpress-ecosystem/\">Rémi propose une liste des membres</a> qu&rsquo;il estime les plus impliqués dans la communauté française de WordPress&#8230; en anglais.</p>
<h3>Interview de Matt dans SOMA</h3>
<p><a href=\"http://www.somamagazine.com/matt-mullenweg-an-open-source/\">Le magazine SOMA interviewe Matt Mullenweg (en)</a>.</p>
<h3>La navigation avec AJAX</h3>
<p><a href=\"http://boiteaweb.fr/la-navigation-avec-ajax-7743.html\">Utiliser Ajax dans la navigation</a>, c&rsquo;est le sujet de cet article de <del>Julio </del>Willy.</p>
<h3>Pourquoi choisir WordPress ?</h3>
<p><a href=\"http://www.frederiquegame.fr/pourquoi-choisir-wordpress-migration-nouveau-site-plate-forme-incontournable/\">Frédérique nous donne les réponses</a> à cette question qu&rsquo;elle pense les plus importantes.</p>
<h3>Repérer et réparer des erreurs de WordPress</h3>
<p><a href=\"http://www.wpjedi.com/fix-wordpress-errors/\">Une liste d&rsquo;extensions</a> (en) à retenir pour repérer et réparer des erreurs de WordPress.</p>
<div class=\"feedflare\">
<a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=E3WutvVVZ0A:9Yb2SZVJjKg:yIl2AUoC8zA\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=yIl2AUoC8zA\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=E3WutvVVZ0A:9Yb2SZVJjKg:V_sGLiPBpWU\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=E3WutvVVZ0A:9Yb2SZVJjKg:V_sGLiPBpWU\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=E3WutvVVZ0A:9Yb2SZVJjKg:qj6IDK7rITs\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=qj6IDK7rITs\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=E3WutvVVZ0A:9Yb2SZVJjKg:gIN9vFwOqvQ\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=E3WutvVVZ0A:9Yb2SZVJjKg:gIN9vFwOqvQ\" border=\"0\"></img></a>
</div><img src=\"http://feeds.feedburner.com/~r/WordpressFrancophone/~4/E3WutvVVZ0A\" height=\"1\" width=\"1\"/>\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:36:\"http://wellformedweb.org/CommentAPI/\";a:1:{s:10:\"commentRss\";a:1:{i:0;a:5:{s:4:\"data\";s:68:\"http://www.wordpress-fr.net/2013/12/03/lhebdo-wordpress-n210-2/feed/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:38:\"http://purl.org/rss/1.0/modules/slash/\";a:1:{s:8:\"comments\";a:1:{i:0;a:5:{s:4:\"data\";s:1:\"4\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:42:\"http://rssnamespace.org/feedburner/ext/1.0\";a:1:{s:8:\"origLink\";a:1:{i:0;a:5:{s:4:\"data\";s:63:\"http://www.wordpress-fr.net/2013/12/03/lhebdo-wordpress-n210-2/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:4;a:6:{s:4:\"data\";s:60:\"
		\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:6:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:67:\"L’Hebdo WordPress n°209 : BuddyPress – Veille – Contribution\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:67:\"http://feedproxy.google.com/~r/WordpressFrancophone/~3/YqF0QRIza80/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"comments\";a:1:{i:0;a:5:{s:4:\"data\";s:101:\"http://www.wordpress-fr.net/2013/11/26/lhebdo-wordpress-n209-buddypress-veille-contribution/#comments\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Tue, 26 Nov 2013 06:16:17 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:7:{i:0;a:5:{s:4:\"data\";s:7:\"Astuces\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:10:\"Extensions\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:7:\"Thèmes\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:3;a:5:{s:4:\"data\";s:9:\"WordPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:4;a:5:{s:4:\"data\";s:10:\"BuddyPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:5;a:5:{s:4:\"data\";s:5:\"Hebdo\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:6;a:5:{s:4:\"data\";s:10:\"Sécurité\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"http://www.wordpress-fr.net/?p=6500\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:369:\"BuddyPress : Codex et traduction Le Codex de BuddyPress a été mis à jour (en), notamment avec des articles de nos amis français iMath et Chouf1. En outre, des informations concernant la traduction de l&#8217;extension sociale sont également fournies, ainsi qu&#8217;une liste de tâche pour améliorer la situation. BP Show Friends 2.0 iMath met à jour [&#8230;]\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:7:\"Benoît\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:3628:\"<h3>BuddyPress : Codex et traduction</h3>
<p><a href=\"http://bpdevel.wordpress.com/2013/11/18/bp-codex-update-and-buddypress-translations/\">Le Codex de BuddyPress a été mis à jour (en)</a>, notamment avec des articles de nos amis français iMath et Chouf1. En outre, des informations concernant la traduction de l&rsquo;extension sociale sont également fournies, ainsi qu&rsquo;une liste de tâche pour améliorer la situation.</p>
<h3>BP Show Friends 2.0</h3>
<p>iMath met à jour <a href=\"http://imathi.eu/2013/11/24/bp-show-friends-2-0/\">sa première extension BuddyPress</a>. C&rsquo;est forcément un mini événement !</p>
<h3>Flux RSS, Panda et Penguin</h3>
<p><a href=\"http://yoast.com/rss-feeds-panda-penguin/\">L&rsquo;analyse de Yoast sur ce sujet fort intéressant (en)</a> qu&rsquo;est le déclin du flux RSS et l&rsquo;arrivée de Panda et Penguin, les mises à jour des bases de Google.</p>
<h3>Simple Theme</h3>
<p>ThemeSmarts propose un thème gratuit : <a href=\"http://themesmarts.com/themes/simple-dark-wordpress-theme/\">Simple Theme</a> (en).</p>
<h3>Comment organiser son blog avec WordPress ?</h3>
<p><a href=\"http://www.lipaonline.com/creer-blog/1149\">Quelques idées</a> pour mieux bloguer avec WordPress.</p>
<h3>La sécurité de WordPress</h3>
<p><a href=\"http://www.wpexplorer.com/wordpress-security-tips/\">La sécurité de WordPress (en) </a>est toujours au cœur des préoccupations.</p>
<h3>Contribuer à WordPress en étant un pro</h3>
<p>Quel intérêt pour un professionnel de contribuer à la communauté WordPress ? <a href=\"http://10up.com/blog/giving-back-to-wordpress/\">Voici une réponse</a> (en).</p>
<h3>La veille WordPress</h3>
<p>Un sujet qui m&rsquo;intéresse forcément. <a href=\"http://wpformation.com/ma-veille-wordpress/\">Fabrice évoque l&rsquo;art de la veille</a> en prenant exemple sur ce qu&rsquo;il fait pour WordPress.</p>
<h3>WooCommerce 2.1 Beta 1</h3>
<p>L&rsquo;extension e-commerce bien connue pour WordPress, <a href=\"http://develop.woothemes.com/woocommerce/2013/11/woocommerce-2-1-beta-1-is-ready/\">WooCommerce débarque en bersion 2.1 beta 1</a> (en). A vos tests !</p>
<h3>Joomla! vs WordPress</h3>
<p>La bataille commence ! <a href=\"http://www.cree1site.com/wordpress-vs-joomla-seo/\">Qui de WordPress ou de Joomla!</a> l&rsquo;emportera en combat singulier ?</p>
<h3>Rendre visible ses activités à travers un blog WordPress</h3>
<p>Stephanie Booth a fait un atelier présentant les intérêts de bloguer et de WordPress. <a href=\"http://www.pressmitic.ch/atelier-du-15-novembre-rendre-visible-ses-activites-a-travers-un-blog-wordpress/\">La vidéo est en ligne</a> !</p>
<div class=\"feedflare\">
<a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=YqF0QRIza80:Uyh-h2BrDoI:yIl2AUoC8zA\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=yIl2AUoC8zA\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=YqF0QRIza80:Uyh-h2BrDoI:V_sGLiPBpWU\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=YqF0QRIza80:Uyh-h2BrDoI:V_sGLiPBpWU\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=YqF0QRIza80:Uyh-h2BrDoI:qj6IDK7rITs\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=qj6IDK7rITs\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=YqF0QRIza80:Uyh-h2BrDoI:gIN9vFwOqvQ\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=YqF0QRIza80:Uyh-h2BrDoI:gIN9vFwOqvQ\" border=\"0\"></img></a>
</div><img src=\"http://feeds.feedburner.com/~r/WordpressFrancophone/~4/YqF0QRIza80\" height=\"1\" width=\"1\"/>\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:36:\"http://wellformedweb.org/CommentAPI/\";a:1:{s:10:\"commentRss\";a:1:{i:0;a:5:{s:4:\"data\";s:97:\"http://www.wordpress-fr.net/2013/11/26/lhebdo-wordpress-n209-buddypress-veille-contribution/feed/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:38:\"http://purl.org/rss/1.0/modules/slash/\";a:1:{s:8:\"comments\";a:1:{i:0;a:5:{s:4:\"data\";s:1:\"6\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:42:\"http://rssnamespace.org/feedburner/ext/1.0\";a:1:{s:8:\"origLink\";a:1:{i:0;a:5:{s:4:\"data\";s:92:\"http://www.wordpress-fr.net/2013/11/26/lhebdo-wordpress-n209-buddypress-veille-contribution/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:5;a:6:{s:4:\"data\";s:45:\"
		\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:6:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:20:\"WordPress 3.8 beta 1\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:67:\"http://feedproxy.google.com/~r/WordpressFrancophone/~3/DfxKjx6zZzs/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"comments\";a:1:{i:0;a:5:{s:4:\"data\";s:69:\"http://www.wordpress-fr.net/2013/11/23/wordpress-3-8-beta-1/#comments\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Sat, 23 Nov 2013 17:23:33 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:2:{i:0;a:5:{s:4:\"data\";s:14:\"Développement\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:13:\"WordPress 3.8\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"http://www.wordpress-fr.net/?p=6492\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:339:\"La première beta de 3.8 est disponible au téléchargement. Les prochaines dates à retenir sont pour le gel du code le 5 décembre et la sortie de la version finale le 12 décembre. 3.8 rassemble plusieurs des fonctions développées comme des projets d&#8217;extensions (en) et bien que cela ne soit pas notre premier essai du [&#8230;]\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:7:\"Benoît\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:3764:\"<p>La première beta de 3.8 est disponible au téléchargement. Les prochaines dates à retenir sont pour le gel du code le 5 décembre et la sortie de la version finale le 12 décembre.</p>
<p>3.8 rassemble plusieurs des fonctions développées comme <a href=\"http://make.wordpress.org/core/features-as-plugins/\">des projets d&rsquo;extensions (en)</a> et bien que cela ne soit pas notre premier essai du genre, considéré tout ceci encore plus beta que d&rsquo;habitude. Les éléments à tester en premier sont :</p>
<ul>
<li>La nouvelle interface d&rsquo;admin et son nouvel aspect responsive. Essayez-la sur différents supports et navigateurs, regardez comment ça se passe, notamment les pages les plus complexes telles que l&rsquo;interface des widgets ou celles les moins utilisées comme &laquo;&nbsp;Press-Minute&nbsp;&raquo;. Le choix des couleurs, que vous pouvez changer dans votre profil a également été refait.</li>
<li>La page d&rsquo;accueil du tableau de bord a été rafraichie.</li>
<li>Choisir un thème dans Apparence est complétement différent, essayez de jouer avec autant que possible.</li>
<li>Il y a un nouveau thème par défaut : Twenty Fourteen.</li>
<li>Plus de 250 problèmes ont déjà été résolus.</li>
</ul>
<p>Étant donné le nombre de choses dans l&rsquo;admin qui ont changé c&rsquo;est super important de tester autant d&rsquo;extensions et de thèmes que possible avec les pages d&rsquo;administration contre tous les nouveaux trucs. Aussi, si vous êtes développeurs, voyez comment vous pouvez adapter votre administration à la nouvelle interface MP6.</p>
<p>Comme toujours, si vous pensez avoir trouvé un bug, vous pouvez le dire dans le forum officiel : <a href=\"http://wordpress.org/support/forum/alphabeta\">Alpha/Beta (en)</a>. Ou, si vous êtes en mesure de rédiger un rapport de bug, <a href=\"http://core.trac.wordpress.org/\">rapportez-le sur le WordPress Trac (en)</a>. Vous trouverez une <a href=\"http://core.trac.wordpress.org/report/5\">liste des bugs connus (en)</a> et <a href=\"http://core.trac.wordpress.org/query?status=closed&amp;group=component&amp;milestone=3.8\">tout ce qui a été corrigé (en).</a></p>
<p><a href=\"http://wordpress.org/wordpress-3.8-beta-1.zip\">Téléchargez WordPress 3.8 Beta 1</a> (zip) ou utilisez l&rsquo;extension <a href=\"http://wordpress.org/plugins/wordpress-beta-tester/\">WordPress Beta Tester</a> (vous voudrez “bleeding edge nightlies”).</p>
<p><em>Soupe à l&rsquo;Alphabet</em><br />
<em>Extensions comme fonction à gogo</em><br />
<em>Le futur c&rsquo;est maintenant<br />
</em></p>
<p><em><strong>NB</strong> : Cet article est une traduction adaptée de l&rsquo;<a href=\"http://wordpress.org/news/2013/11/wordpress-3-8-beta-1/\">article original de Matt</a>.</em></p>
<div class=\"feedflare\">
<a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=DfxKjx6zZzs:IDOjiIf0W2o:yIl2AUoC8zA\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=yIl2AUoC8zA\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=DfxKjx6zZzs:IDOjiIf0W2o:V_sGLiPBpWU\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=DfxKjx6zZzs:IDOjiIf0W2o:V_sGLiPBpWU\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=DfxKjx6zZzs:IDOjiIf0W2o:qj6IDK7rITs\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=qj6IDK7rITs\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=DfxKjx6zZzs:IDOjiIf0W2o:gIN9vFwOqvQ\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=DfxKjx6zZzs:IDOjiIf0W2o:gIN9vFwOqvQ\" border=\"0\"></img></a>
</div><img src=\"http://feeds.feedburner.com/~r/WordpressFrancophone/~4/DfxKjx6zZzs\" height=\"1\" width=\"1\"/>\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:36:\"http://wellformedweb.org/CommentAPI/\";a:1:{s:10:\"commentRss\";a:1:{i:0;a:5:{s:4:\"data\";s:65:\"http://www.wordpress-fr.net/2013/11/23/wordpress-3-8-beta-1/feed/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:38:\"http://purl.org/rss/1.0/modules/slash/\";a:1:{s:8:\"comments\";a:1:{i:0;a:5:{s:4:\"data\";s:1:\"1\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:42:\"http://rssnamespace.org/feedburner/ext/1.0\";a:1:{s:8:\"origLink\";a:1:{i:0;a:5:{s:4:\"data\";s:60:\"http://www.wordpress-fr.net/2013/11/23/wordpress-3-8-beta-1/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:6;a:6:{s:4:\"data\";s:51:\"
		\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:6:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:79:\"L’Hebdo WordPress n°208 : WordCamp Paris 2014 – BuddyPress 1.9 – Brèves\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:67:\"http://feedproxy.google.com/~r/WordpressFrancophone/~3/eWMHFBm5mpY/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"comments\";a:1:{i:0;a:5:{s:4:\"data\";s:112:\"http://www.wordpress-fr.net/2013/11/19/lhebdo-wordpress-n208-wordcamp-paris-2014-buddypress-1-9-breves/#comments\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Tue, 19 Nov 2013 06:39:25 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:4:{i:0;a:5:{s:4:\"data\";s:4:\"Blog\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:8:\"WordCamp\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:5:\"Hebdo\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:3;a:5:{s:4:\"data\";s:8:\"wordcamp\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"http://www.wordpress-fr.net/?p=6479\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:359:\"WordCamp Paris 2014 On sait donc que le WordCamp Paris 2014 se déroulera les 17 et 18 janvier prochain. L&#8217;appel à sponsors est lancé. Si vous voulez nous aider dans l&#8217;organisation de cet événement alors devenez partenaire ! Si vous voulez être sur scène et partager votre passion pour WordPress, alors l&#8217;appel à orateur est [&#8230;]\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:7:\"Benoît\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:4667:\"<h3>WordCamp Paris 2014</h3>
<p>On sait donc que le WordCamp Paris 2014 se déroulera les 17 et 18 janvier prochain. L&rsquo;<a href=\"http://2014.paris.wordcamp.org/2013/11/13/appel-a-sponsors/\">appel à sponsors est lancé</a>. Si vous voulez nous aider dans l&rsquo;organisation de cet événement alors devenez partenaire ! Si vous voulez être sur scène et partager votre passion pour WordPress, alors <a href=\"http://2014.paris.wordcamp.org/2013/10/29/appel-a-orateurs/\">l&rsquo;appel à orateur est ici</a>.</p>
<h3>WordCamp Cape Town</h3>
<p><a href=\"http://www.youtube.com/watch?v=YOVqmjgdEcg&amp;feature=youtu.be\">Le WordCamp de Cap Town fait son résumé en vidéo (en)</a>. Classe !</p>
<h3>Un forum pour les WordCamp</h3>
<p>WordCamp Central met en place un <a href=\"http://plan.wordcamp.org/forums/\">forum pour les organisateur (en)</a>.</p>
<h3>Langage et vocabulaire</h3>
<p>Diane fait le point sur<a href=\"http://dianebourque.com/2013/11/11/le-langage-et-vocabulaire-de-wordpress/\"> les langages et vocabulaires utilisées dans WordPress</a>.</p>
<h3>BuddyPress et le futur de son thème par défaut</h3>
<p>La version 1.9 de BuddyPress va redéfinir <a href=\"http://bpdevel.wordpress.com/2013/11/13/the-future-of-the-bp-default-theme/\">la stratégie du thème par défaut (en)</a> pour <a href=\"http://bp-fr.net/la-fin-du-theme-bp-default/\">préparer son abandon</a>.</p>
<h3>BuddyPress 1.9 Beta 1</h3>
<p><a href=\"http://buddypress.org/2013/11/buddypress-1-9-beta-1-is-now-available/\">BuddyPress 1.9 beta 1 est disponible (en)</a>.</p>
<h3>Pippin en interview</h3>
<p>Pippin Williamson bien connu pour son site pippinsplugins.com a été <a href=\"http://build.codepoet.com/2013/11/13/pippin-williamson-interview/\">interviewé par Code Poet</a>.</p>
<h3>Améliorer vos commentaires</h3>
<p>Willy propose son extension qui va permettre d&rsquo;améliorer la gestion des commentaires de votre site : <a href=\"http://www.geekpress.fr/wordpress/extension/mention-comments-authors-1961/\">Mention Comment&rsquo;s Author</a>. Cette extension permet de répondre aux commentaires précédents de manière intuitive.</p>
<h3>Mettre à jour WordPress automatiquement ?</h3>
<p>C&rsquo;est la question que se pose Rodrigue. <a href=\"http://www.cree1site.com/plugin-mise-a-jour-de-plugin-automatique/\">Voici les éléments de réponses qu&rsquo;il apporte</a>.</p>
<h3>Une vulnérabilité dans le framework Themify</h3>
<p>Une vulnérabilité a été découverte dans le framework Themify. <a href=\"http://themify.me/blog/urgent-vulnerability-found-in-themify-framework-please-read\">Toutes les informations ici</a> (en).</p>
<h3>Mystique et les mises à jour automatiques</h3>
<p><a href=\"http://www.lumieredelune.com/encrelune/utilisateurs-mystique-attention,2013,11\">Lumière de Lune met en garde les utilisateurs du thème Mystique</a> avec les mises à jour automatique.</p>
<h3>Pressnomics, le bon, le mauvais et le génial</h3>
<p>Chris Lema fait <a href=\"http://chrislema.com/pressnomics-good-bad-awesome/\">son résumé du PressNomics</a>. (en)</p>
<h3>WordPress 3.8 : A quoi s&rsquo;attendre ?</h3>
<p>Quelles sont les <a href=\"http://slocumstudio.com/2013/11/wordpress-3-8-proposals/\">nouveautés à attendre de WordPress 3.8</a> ?</p>
<h3>Installer une extension depuis Github</h3>
<p><a href=\"http://www.wptavern.com/how-to-install-wordpress-plugins-directly-from-github\">C&rsquo;est ce que nous propose de découvrir WPTavern</a> (en).</p>
<h3>3 extensions pour gérer LinkedIn</h3>
<p>Si vous voulez intégrer <a href=\"http://www.wpjedi.com/linkedin-import-wordpress-plugins/\">votre compte LinkedIn avec votre site web</a> (en), voici des extensions utiles.</p>
<div class=\"feedflare\">
<a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=eWMHFBm5mpY:WWGPBaP3lrg:yIl2AUoC8zA\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=yIl2AUoC8zA\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=eWMHFBm5mpY:WWGPBaP3lrg:V_sGLiPBpWU\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=eWMHFBm5mpY:WWGPBaP3lrg:V_sGLiPBpWU\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=eWMHFBm5mpY:WWGPBaP3lrg:qj6IDK7rITs\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=qj6IDK7rITs\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=eWMHFBm5mpY:WWGPBaP3lrg:gIN9vFwOqvQ\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=eWMHFBm5mpY:WWGPBaP3lrg:gIN9vFwOqvQ\" border=\"0\"></img></a>
</div><img src=\"http://feeds.feedburner.com/~r/WordpressFrancophone/~4/eWMHFBm5mpY\" height=\"1\" width=\"1\"/>\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:36:\"http://wellformedweb.org/CommentAPI/\";a:1:{s:10:\"commentRss\";a:1:{i:0;a:5:{s:4:\"data\";s:108:\"http://www.wordpress-fr.net/2013/11/19/lhebdo-wordpress-n208-wordcamp-paris-2014-buddypress-1-9-breves/feed/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:38:\"http://purl.org/rss/1.0/modules/slash/\";a:1:{s:8:\"comments\";a:1:{i:0;a:5:{s:4:\"data\";s:1:\"5\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:42:\"http://rssnamespace.org/feedburner/ext/1.0\";a:1:{s:8:\"origLink\";a:1:{i:0;a:5:{s:4:\"data\";s:103:\"http://www.wordpress-fr.net/2013/11/19/lhebdo-wordpress-n208-wordcamp-paris-2014-buddypress-1-9-breves/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:7;a:6:{s:4:\"data\";s:54:\"
		\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:6:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:74:\"L’Hebdo WordPress n°207 : WordPress 3.8 – Communautés – Automattic\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:67:\"http://feedproxy.google.com/~r/WordpressFrancophone/~3/-r3940AtkOw/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"comments\";a:1:{i:0;a:5:{s:4:\"data\";s:107:\"http://www.wordpress-fr.net/2013/11/13/lhebdo-wordpress-n207-wordpress-3-8-communautes-automattic/#comments\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Wed, 13 Nov 2013 14:16:35 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:5:{i:0;a:5:{s:4:\"data\";s:7:\"Astuces\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:7:\"Brèves\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:9:\"WordPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:3;a:5:{s:4:\"data\";s:11:\"communauté\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:4;a:5:{s:4:\"data\";s:13:\"WordPress 3.8\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"http://www.wordpress-fr.net/?p=6461\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:319:\"Avec un jour de retard, voici mon hebdo ! Merci pour votre patience ! WordPress 3.8 Une date de sortie pour WordPress 3.8 (en) : le 12 décembre 2013 (en). Les fonctions qui seront intégrées au core de WordPress 3.8 (en). WordPress 3.8 aura-t-il un générateur de mot de passe (en) ? Dernière ligne droite [&#8230;]\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:7:\"Benoît\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:10355:\"<p>Avec un jour de retard, voici mon hebdo ! Merci pour votre patience !</p>
<h3>WordPress 3.8</h3>
<ul>
<li>Une <a href=\"http://www.bobwp.com/wordpress-3-8-schedule/\">date de sortie pour WordPress 3.8 (en)</a> : <a href=\"http://make.wordpress.org/core/version-3-8-project-schedule/\">le 12 décembre 2013</a> (en).</li>
<li><a href=\"http://www.wptavern.com/breaking-new-features-selected-to-merge-into-wordpress-3-8-core\">Les fonctions qui seront intégrées au core de WordPress 3.8 (en).</a></li>
<li><a href=\"http://www.wptavern.com/should-wordpress-include-a-password-generator\">WordPress 3.8 aura-t-il un générateur de mot de passe (en)</a> ?</li>
<li><a href=\"http://www.wptavern.com/wordpress-twenty-fourteen-project-enters-crunch-time-for-3-8-release\">Dernière ligne droite pour le thème 2014 (en)</a>.</li>
<li>La <a href=\"http://www.wptavern.com/the-future-of-wordpress-widgets-a-better-ui-with-real-time-previews\">nouvelle interface de gestion des widgets (en)</a>.</li>
</ul>
<h3>Session WordPress MX</h3>
<p>La prochaine session WordPress MX (la communauté WordPress du Sud Ouest) <a href=\"https://www.facebook.com/events/395437210559441/?ref=3&amp;ref_newsfeed_story_type=regular\">aura lieu le samedi 23 novembre 2013</a>.</p>
<h3>Résumé du meetup de Nantes par iMath</h3>
<p>iMath intervenait au meetup de Nantes la semaine dernière consacré à BuddyPress. <a href=\"http://imathi.eu/2013/11/08/buddypress-meetup/\">Voici son résumé</a>. CMS.fr, présent à ce meetup en profite pour faire <a href=\"http://www.cms.fr/cms/wordpress/articles-wordpress/375-creer-un-reseau-social-avec-wordpress-et-buddypress.html\">une présentation de BuddyPress</a>.</p>
<h3>Profiter des options de BuddyPress</h3>
<p>Il est possible de profiter des options de BuddyPress <a href=\"http://codex.buddypress.org/plugindev/taking-benefits-from-buddypress-settings-to-add-your-plugins-options/\">pour ajouter des options à ses extensions </a>(en).</p>
<h3>WordPress pour Android</h3>
<p><a href=\"http://android.wordpress.org/2013/11/11/2-5-release/\">La dernière version de WordPress pour Android</a> (en) est disponible au téléchargement.</p>
<h3>Un bouton &laquo;&nbsp;suivre&nbsp;&raquo; pour wordpress.com</h3>
<p>WordPress.com, la plateforme commerciale d&rsquo;Automattic vient de <a href=\"http://www.fredzone.org/bouton-suivre-wordpress-334\">mettre à disposition un bouton &laquo;&nbsp;suivre&nbsp;&raquo;</a> installable n&rsquo;importe où.</p>
<h3>La réussite d&rsquo;Automattic par Matt</h3>
<p>Matt est interviewé <a href=\"http://www.businessinsider.com/automattic-no-email-no-office-workers-2013-11\">au sujet de la réussite d&rsquo;Automattic</a> (en).</p>
<h3>Comment participer au développement de WordPress</h3>
<p>Une vidéo présente<a href=\"http://speakinginbytes.com/2013/11/contribute-to-wordpress/\"> comment participer au développement de WordPress</a> (en).</p>
<h3>Le futur du développement d&rsquo;extension et la théorie de la ruée vers l&rsquo;or</h3>
<p>Chris Lema explique <a href=\"http://chrislema.com/future-wordpress-plugin-development/\">sa vision de l&rsquo;avenir du développement d’extensions</a> sur WordPress (en) au travers de la théorie de la ruée vers l&rsquo;or&#8230; <a href=\"http://wplift.com/wordpress-gold-rush\">reprise aussi par WPLift</a> (en).</p>
<h3>Les notifications dans BuddyPress</h3>
<p>John James Jacoby présente les <a href=\"http://jaco.by/2013/11/07/buddypress-notifications/\">notifications dans BuddyPress (en)</a>.</p>
<h3>Présentation de WP-Rocket</h3>
<p>Une nouvelle présentation de l&rsquo;<a href=\"http://www.web-geek.fr/wprocket-plugin-cache-wordpress/\">extension premium WP-Rocket</a>&#8230; avec une vidéo en prime.</p>
<h3>La cuvée WordPress</h3>
<p>Pour les amateurs de bons crus, <a href=\"https://plus.google.com/u/0/photos/+RodrigueFenard/albums/5943558310403607409/5943558307186656754?sqi=104465035194704257922&amp;sqsi=acc44b17-a607-4d89-99ff-509dcce8ae02&amp;pid=5943558307186656754&amp;oid=104152106088973822260\">le millésime WordPress est un choix inévitable</a> !</p>
<h3>35 % de part de marché en France</h3>
<p>Selon le site CMSCrawler, WordPress possède <a href=\"http://www.cmscrawler.com/country/FR\">près de 35 % de part de marché en France</a>.</p>
<h3>Introduction à WP-CLI</h3>
<p>WP-CLI est très à la mode, c&rsquo;est une interface en ligne de commande pour gérer WordPress. Voici une <a href=\"http://www.wpmayor.com/code/introduction-wp-cli/\">présentation réalisée par WP Mayor</a> (en).</p>
<h3>ThemeForest atteint 3 millions de dollars de vente</h3>
<p>ThemeForest vient d&rsquo;atteindre le chiffre impressionnant de <a href=\"http://www.wpmayor.com/news/themeforest-author-hits-3000000-sales/\">3 millions de dollars de ventes totales de thèmes</a> (en).</p>
<h3>Gravity Form 1.8 Beta 1</h3>
<p>L&rsquo;extension de formulaires bien connue arrive en <a href=\"http://www.wptavern.com/gravityforms-1-8-beta-released-introduces-api\">version 1.8 et offre plusieurs nouveautés</a> (en).</p>
<h3>WordPress logo et typographie</h3>
<p><a href=\"http://www.frederiquegame.fr/wordpress-logo-et-typographie/\">Frédérique Game propose une petite analyse du logo et de la typographie</a> de WordPress.</p>
<h3>Comment créer un thème enfant</h3>
<p>Je remets souvent des articles présentant les thèmes enfants car c&rsquo;est vraiment important de connaitre ce fonctionnement des thèmes. <a href=\"http://wpmu.org/create-wordpress-child-theme/\">WPMU.org nous propose donc une nouvelle présentation</a> (en).</p>
<h3>7 raisons pourquoi les débutants ne devraient pas auto-héberger WordPress</h3>
<p><a href=\"http://wpmu.org/7-reasons-why-novices-should-not-self-host-wordpress/\">C&rsquo;est provocateur et c&rsquo;est un avis à mon sens très personnel de l&rsquo;auteur</a> (en). Il n&rsquo;a pas tort sur toute la ligne, mais tout le monde ne partagera peut-être pas son avis.</p>
<h3>Déménager vers un serveur local</h3>
<p>Il est souvent utile de travailler en local, et déménager son site est parfois complexe. <a href=\"http://www.wpbeginner.com/wp-tutorials/how-to-move-live-wordpress-site-to-local-server/\">Voici une solution en anglais</a> sans WAMP&#8230; <a href=\"http://www.crazyws.fr/tutos/utiliser-wordpress-en-local-avec-wamp-6YAX8.html\">et une autre en français avec WAMP</a>.</p>
<h3>Programmer une connexion d&rsquo;utilisateur</h3>
<p>Si vous avez besoin de programmer une connexion d&rsquo;un utilisateur <a href=\"http://www.wprecipes.com/log-in-a-wordpress-user-programmatically\">cette démarche peut vous intéresser</a> (en).</p>
<h3>Optimiser son référencement</h3>
<p><a href=\"http://wpformation.com/optimiser-referencement-wordpress/\">Fabrice présente le nouveau livre de Daniel Roch </a>sur le référencement.</p>
<h3>Comment faire une recherche sous forme de bouton rétractable</h3>
<p>Un tutoriel vous apprendra comment réaliser un<a href=\"http://www.wpbeginner.com/wp-themes/how-to-add-a-search-toggle-effect-in-wordpress/\"> champ de recherche qui s&rsquo;ouvre à la demande</a> (en).</p>
<h3>WordPress et le SEO</h3>
<p>Le <a href=\"http://fr.slideshare.net/SeoMix/wordpress-et-seo-seocampus-2013\">diaporama de Daniel Roch</a> utilisé lors du SEOCamp 2013 est en ligne.</p>
<h3>Créer des règles pour vos auteurs</h3>
<p><a href=\"http://www.cree1site.com/regles-auteurs-wordpress/\">Rodrigue explique comment créer des règles</a> pour les auteurs.</p>
<h3>Posez-moi une question</h3>
<p><a href=\"http://boiteaweb.fr/posez-question-1-action-page-7705.html\">Julio se lance dans la réponse aux questions des utilisateurs</a>. La règle du jeu est simple. Il prend une question parmi celle qu&rsquo;on lui pose via un formulaire et y répond.</p>
<h3>Une réflexion sur les défauts de WordPress</h3>
<p>WordPress a aussi des défauts, <a href=\"http://eamann.com/biz/wordpress-falling-behind-still-leading/\">voici quelques critiques</a>, qui se veulent constructives (en).</p>
<h3>Le WordPress des blogueurs</h3>
<p><a href=\"http://wpformation.com/blogueurs-wordpress/\">Fabrice a demandé à des blogueurs</a> de décrire leur utilisation de WordPress, ainsi que les thèmes et extensions utilisés.</p>
<h3>Pourquoi WordPress est le meilleur outil pour votre site web ?</h3>
<p>TeslaThemes démontre pourquoi <a href=\"http://teslathemes.com/blog/why-wordpress-is-the-best-solution-for-your-website/\">WordPress est le meilleur outil pour un site web (en).</a>.. et donc pourquoi ils l&rsquo;utilisent.</p>
<h3>Exclure les formats d&rsquo;articles de la boucle</h3>
<p><a href=\"http://wpchannel.com/exclure-post_formats-formats-articles-boucle-wordpress/\">WP Channel explique comment exclure les formats d&rsquo;article de la la boucle</a> WordPress.</p>
<h3>2 tutos pour personnaliser son WordPress</h3>
<ul>
<li><a href=\"http://wp.tutsplus.com/tutorials/creative-coding/customizing-the-wordpress-admin-adding-styling/\">Personnaliser l&rsquo;admin</a> (en).</li>
<li><a href=\"http://wp.tutsplus.com/tutorials/theme-development/a-guide-to-the-wordpress-theme-customizer-review-and-resources/\">Personnaliser son thème.</a> (en)</li>
</ul>
<h3>Matt en conférence chez Joomla !</h3>
<p>Lors du rendez-vous annuel de Joomla! Matt Mullenweg était invité. <a href=\"http://www.wptavern.com/wordpress-co-founder-matt-mullenweg-keynotes-joomla-world-conference\">Il a parlé de WordPress, évidemment</a> (en) !</p>
<div class=\"feedflare\">
<a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=-r3940AtkOw:UGJrjenxuEc:yIl2AUoC8zA\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=yIl2AUoC8zA\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=-r3940AtkOw:UGJrjenxuEc:V_sGLiPBpWU\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=-r3940AtkOw:UGJrjenxuEc:V_sGLiPBpWU\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=-r3940AtkOw:UGJrjenxuEc:qj6IDK7rITs\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=qj6IDK7rITs\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=-r3940AtkOw:UGJrjenxuEc:gIN9vFwOqvQ\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=-r3940AtkOw:UGJrjenxuEc:gIN9vFwOqvQ\" border=\"0\"></img></a>
</div><img src=\"http://feeds.feedburner.com/~r/WordpressFrancophone/~4/-r3940AtkOw\" height=\"1\" width=\"1\"/>\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:36:\"http://wellformedweb.org/CommentAPI/\";a:1:{s:10:\"commentRss\";a:1:{i:0;a:5:{s:4:\"data\";s:103:\"http://www.wordpress-fr.net/2013/11/13/lhebdo-wordpress-n207-wordpress-3-8-communautes-automattic/feed/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:38:\"http://purl.org/rss/1.0/modules/slash/\";a:1:{s:8:\"comments\";a:1:{i:0;a:5:{s:4:\"data\";s:1:\"9\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:42:\"http://rssnamespace.org/feedburner/ext/1.0\";a:1:{s:8:\"origLink\";a:1:{i:0;a:5:{s:4:\"data\";s:98:\"http://www.wordpress-fr.net/2013/11/13/lhebdo-wordpress-n207-wordpress-3-8-communautes-automattic/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:8;a:6:{s:4:\"data\";s:48:\"
		\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:6:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:40:\"WordPress Francophone et l’affiliation\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:67:\"http://feedproxy.google.com/~r/WordpressFrancophone/~3/mKzBNZZi8IM/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"comments\";a:1:{i:0;a:5:{s:4:\"data\";s:83:\"http://www.wordpress-fr.net/2013/11/13/wordpress-francophone-laffiliation/#comments\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Wed, 13 Nov 2013 06:18:55 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:3:{i:0;a:5:{s:4:\"data\";s:21:\"WordPress Francophone\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:11:\"affiliation\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:11:\"communauté\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"http://www.wordpress-fr.net/?p=6455\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:409:\"En raison d&#8217;une recrudescence de liens affiliés dans les contenus que nous souhaitons publier, nous avons souhaité préciser notre position éditoriale à ce sujet. Nous voulions vraiment davantage de transparence à propos de l&#8217;affiliation. Quels contenus ont leur place dans les publications (Hebdo, Planet, etc.) de WordPress Francophone (WPFR) ? Avec l&#8217;utilisation de plus en [&#8230;]\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:7:\"Benoît\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:7111:\"<p>En raison d&rsquo;une recrudescence de liens affiliés dans les contenus que nous souhaitons publier, nous avons souhaité préciser notre position éditoriale à ce sujet. Nous voulions vraiment davantage de transparence à propos de l&rsquo;affiliation.</p>
<p>Quels contenus ont leur place dans les publications (Hebdo, <a href=\"En raison d\'une recrudescence de liens affiliés dans les contenus que nous souhaitons publier, nous avons souhaité préciser notre position éditoriale à ce sujet. Nous voulions vraiment davantage de transparence à propos de l\'affiliation.  Quels contenus ont leur place dans les publications (Hebdo, Planet, etc.) de WordPress Francophone (WPFR) ?  Avec l\'utilisation de plus en plus fréquente de liens affiliés dans les articles diffusés, nous devions prendre une décision.  Jusqu\'à maintenant, notre ligne de conduite était stricte. Les contenus avec liens affiliés étaient proscrits des publications de WPFR. Ainsi les liens publiés dans l\'hebdo se révélant contenir de l\'affiliation étaient supprimés, souvent a posteriori lorsqu\'ils n\'avaient pas été décelés avant. De même, les sites utilisant l\'affiliation en masse n\'ont pas leur place dans le Planet (cela ne changera pas).  La réalité est que de plus en plus de sites, dont le contenu est pourtant intéressant, utilisent des liens affiliés. En les supprimant ou en ne les diffusant pas, nous nous privons donc de contenus pertinents. C\'est pourquoi nous avons décidé d\'assouplir quelque peu nos règles, non pas pour encourager l\'affiliation, mais pour l\'encadrer, et ce de la manière suivante :  si un contenu est juste une collection de liens d\'affiliation, sans réelle valeur ajoutée, il ne sera pas diffusé sur WPFR. si un contenu réalise une vraie recherche / explication et est pertinent, qu\'il propose aussi des liens non affiliés et diffuse de temps en temps des liens affiliés, il pourra être diffusé dans l\'hebdo. si un contenu avec lien d’affiliation est déjà passé dans le Planet (parce qu\'un site déjà validé pour le Planet diffuse un lien affilié), il ne repassera pas dans l\'hebdo, à moins que ce contenu soit vraiment exceptionnel. si un site présent dans le Planet se met à user largement de l\'affiliation au détriment de son contenu, il pourra être supprimé du Planet. un site non présent dans le Planet usant en masse de l\'affiliation perdra toute chance d\'y figurer s\'il en fait la demande. Nous pensons que ces quelques règles permettent de maintenir un certain équilibre afin que la communauté ne passe pas au travers de contenus vraiment intéressants. Simplement, WPFR n\'a pas vocation à devenir une vitrine commerciale pour les sites qui chercheraient uniquement à profiter du trafic de notre portail.  Et parce que nous ne sommes que des êtres humains, n\'hésitez pas à nous signaler les liens où notre vigilance à failli !  Les commentaires sont ouverts... et le débat aussi. Vous pouvez donner votre avis sur cette question Ô combien polémique qu\'est l\'affiliation.  P.S. : Étant en congé, j\'ai pris quelques largesse sur le jour de la publication de l\'hebdo... mais comme on dit, mieux vaut tard que jamais ! Il paraîtra dans la journée.\">Planet</a>, etc.) de WordPress Francophone (WPFR) ?</p>
<p>Avec l&rsquo;utilisation de plus en plus fréquente de liens affiliés dans les articles diffusés, nous devions prendre une décision.</p>
<p>Jusqu&rsquo;à maintenant, notre ligne de conduite était stricte. Les contenus avec liens affiliés étaient proscrits des publications de WPFR. Ainsi les liens publiés dans l&rsquo;hebdo se révélant contenir de l&rsquo;affiliation étaient supprimés, souvent a posteriori lorsqu&rsquo;ils n&rsquo;avaient pas été décelés avant. De même, les sites utilisant l&rsquo;affiliation en masse n&rsquo;ont pas leur place dans le Planet (cela ne changera pas).</p>
<p>La réalité est que de plus en plus de sites, dont le contenu est pourtant intéressant, utilisent des liens affiliés. En les supprimant ou en ne les diffusant pas, nous nous privons donc de contenus pertinents. C&rsquo;est pourquoi nous avons décidé d&rsquo;assouplir quelque peu nos règles, non pas pour encourager l&rsquo;affiliation, mais pour l&rsquo;encadrer, et ce de la manière suivante :</p>
<ul>
<li>si un contenu est juste une collection de liens d&rsquo;affiliation, sans réelle valeur ajoutée, il ne sera pas diffusé sur WPFR.</li>
<li>si un contenu réalise une vraie recherche / explication et est pertinent, qu&rsquo;il propose aussi des liens non affiliés et diffuse de temps en temps des liens affiliés, il pourra être diffusé dans l&rsquo;hebdo.</li>
<li>si un contenu avec lien d’affiliation est déjà passé dans le Planet (parce qu&rsquo;un site déjà validé pour le Planet diffuse un lien affilié), il ne repassera pas dans l&rsquo;hebdo, à moins que ce contenu soit vraiment exceptionnel.</li>
<li>si un site présent dans le Planet se met à user largement de l&rsquo;affiliation au détriment de son contenu, il pourra être supprimé du Planet.</li>
<li>un site non présent dans le Planet usant en masse de l&rsquo;affiliation perdra toute chance d&rsquo;y figurer s&rsquo;il en fait la demande.</li>
</ul>
<p>Nous pensons que ces quelques règles permettent de maintenir un certain équilibre afin que la communauté ne passe pas au travers de contenus vraiment intéressants. Simplement, WPFR n&rsquo;a pas vocation à devenir une vitrine commerciale pour les sites qui chercheraient uniquement à profiter du trafic de notre portail.</p>
<p>Et parce que nous ne sommes que des êtres humains, n&rsquo;hésitez pas à nous signaler les liens où notre vigilance à failli !</p>
<p>Les commentaires sont ouverts&#8230; et le débat aussi. Vous pouvez donner votre avis sur cette question Ô combien polémique qu&rsquo;est l&rsquo;affiliation.</p>
<p><em><strong>P.S.</strong> : Étant en congé, j&rsquo;ai pris quelques largesse sur le jour de la publication de l&rsquo;hebdo&#8230; mais comme on dit, mieux vaut tard que jamais ! Il paraîtra dans la journée.</em></p>
<div class=\"feedflare\">
<a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=mKzBNZZi8IM:0tm1X7f_edU:yIl2AUoC8zA\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=yIl2AUoC8zA\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=mKzBNZZi8IM:0tm1X7f_edU:V_sGLiPBpWU\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=mKzBNZZi8IM:0tm1X7f_edU:V_sGLiPBpWU\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=mKzBNZZi8IM:0tm1X7f_edU:qj6IDK7rITs\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=qj6IDK7rITs\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=mKzBNZZi8IM:0tm1X7f_edU:gIN9vFwOqvQ\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=mKzBNZZi8IM:0tm1X7f_edU:gIN9vFwOqvQ\" border=\"0\"></img></a>
</div><img src=\"http://feeds.feedburner.com/~r/WordpressFrancophone/~4/mKzBNZZi8IM\" height=\"1\" width=\"1\"/>\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:36:\"http://wellformedweb.org/CommentAPI/\";a:1:{s:10:\"commentRss\";a:1:{i:0;a:5:{s:4:\"data\";s:79:\"http://www.wordpress-fr.net/2013/11/13/wordpress-francophone-laffiliation/feed/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:38:\"http://purl.org/rss/1.0/modules/slash/\";a:1:{s:8:\"comments\";a:1:{i:0;a:5:{s:4:\"data\";s:1:\"2\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:42:\"http://rssnamespace.org/feedburner/ext/1.0\";a:1:{s:8:\"origLink\";a:1:{i:0;a:5:{s:4:\"data\";s:74:\"http://www.wordpress-fr.net/2013/11/13/wordpress-francophone-laffiliation/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:9;a:6:{s:4:\"data\";s:51:\"
		\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:6:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:49:\"WordCamp Paris 2014 : et si vous étiez orateur ?\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:67:\"http://feedproxy.google.com/~r/WordpressFrancophone/~3/g_h34lO6c00/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"comments\";a:1:{i:0;a:5:{s:4:\"data\";s:90:\"http://www.wordpress-fr.net/2013/11/05/wordcamp-paris-2014-si-vous-etiez-orateur/#comments\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Tue, 05 Nov 2013 11:31:14 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"category\";a:4:{i:0;a:5:{s:4:\"data\";s:16:\"Association WPFR\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:11:\"Evènements\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:8:\"WordCamp\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:3;a:5:{s:4:\"data\";s:9:\"WordPress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"http://www.wordpress-fr.net/?p=6434\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:367:\"Le 17 janvier prochain (2014) se tiendra le troisième WordCamp Paris. On espère que ça sera pour autant de gens que possible l’occasion de se retrouver pour échanger, apprendre et… s’amuser autour de WordPress. Un WordCamp, c’est avant tout des conférences sur  WordPress. Mais de quoi y parle-t-on exactement ? Vous voulez quelques exemples ? [&#8230;]\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:13:\"Benjamin Lupu\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:40:\"http://purl.org/rss/1.0/modules/content/\";a:1:{s:7:\"encoded\";a:1:{i:0;a:5:{s:4:\"data\";s:5749:\"<p><strong>Le 17 janvier prochain (2014) se tiendra le troisième WordCamp Paris.</strong> On espère que ça sera pour autant de gens que possible l’occasion de se retrouver pour échanger, apprendre et… s’amuser autour de WordPress.</p>
<p>Un WordCamp, c’est avant tout <strong>des conférences sur  WordPress</strong>. Mais de quoi y parle-t-on exactement ? Vous voulez quelques exemples ? C’est parti !</p>
<h2>De la technique</h2>
<p>Les conférences d’un WordCamp sont-elles forcément techniques ? Non bien sûr. Mais c’est quand même l’occasion de bénéficier de conférences sur les bonnes pratiques techniques pour WordPress (développements de plugins et thèmes, performance, sécurité, déploiements…).</p>
<h2>Des études de cas</h2>
<p>Vous êtes décisionnaire en entreprise ? Entrepreneur ? Agence ? Freelance ? Responsable associatif ? Alors qu’est-ce qu’on peut faire avec WordPress ? Des orateurs viennent présenter leurs réalisations WordPress avec les bons et les mauvais côtés (pas de blah blah). On peut aussi parler de comment on monte un projet WordPress.</p>
<h2>Du marketing</h2>
<p>WordPress peut être un très bon outil marketing. SEO, Analytics, social, quelles sont les bonnes pratiques pour tirer le maximum de WordPress et soigner son marketing digital ?</p>
<h2>L’écosystème WordPress</h2>
<p>WordPress, c’est un logiciel mais c’est aussi un écosystème : thèmes, plugins, hébergeurs, services SaaS, gratuit ou premium ? Comment on s’y retrouve ? Comment choisir ? Comment profiter au maximum de cet écosystème ?</p>
<h2>Au-delà de WordPress et vers l’infini</h2>
<p>WordPress est peut-être un peu plus qu’un CMS. Ajoutez-y une pincée de BuddyPress et le voilà Réseau social, installez BBPress et vous aurez un forum de support. Deux exemples parmi beaucoup d’autres sur les 1001 visages de WordPress.</p>
<h2>La communauté</h2>
<p>Essentielle ! Comment organise-t-on un meetup, un WordCamp, comment peut-on participer et s’impliquer dans le développement de WordPress (qu&rsquo;on soit technicien ou pas) ?</p>
<h2>Alors prêt(e) pour être orateur ou oratrice au prochain WordCamp ?</h2>
<p>Le fameux « <a title=\"Lien vers l\'appel à orateurs du WordCamp Paris 2014\" href=\"http://2014.paris.wordcamp.org/2013/10/29/appel-a-orateurs/\">appel à orateurs</a> »a été lancé. Alors pourquoi pas vous ? Il n’y a pas besoin d’être un orateur ou une oratrice professionnel(le). La communauté est plutôt bienveillante. Elle est même avide d’apprendre et d’échanger avec vous en fait. Si vous pensez pouvoir développer un sujet dont vous êtes fier(e) et de le partager, n’hésitez pas une seconde ! L’expérience en vaut le coup.</p>
<h2>Petite mention finale (mais qui a son importance)</h2>
<p>Les WordCamps ne sont pas des tribunes commerciales, politiques ou religieuses. Ils ont vocation à faire la promotion de la démarche open source (et de WordPress bien sûr). Quoi, je ne peux pas parler business ?! Si, si (et de bien d’autres choses) mais en respectant certaines règles simples.</p>
<ul>
<li>La première et la plus simple : votre sujet doit avoir un rapport avec… WordPress <img src=\"http://www.wordpress-fr.net/wp-includes/images/smilies/icon_wink.gif\" alt=\";-)\" class=\"wp-smiley\" /> </li>
<li>Vous pouvez vous présenter brièvement, ainsi que votre société, activité, association… Cette présentation doit avant tout servir au public à savoir à qui il a affaire (développeur, designer, entrepreneur, amateur…)</li>
<li>La mention des activités commerciales doit avant tout servir d’exemple et de retour d’expérience et non pas à faire une promotion brute d’un service payant</li>
</ul>
<p>Donc les agences, les entreprises, les services premiums, c’est n’est pas le mal mais le WordCamp n’est pas un terrain de chasse pour les commerciaux avant-vente. C’est un espace de partage ouvert et attentif aux besoins des utilisateurs. Vos réponses doivent avoir une valeur ajoutée (et j’ai tendance à croire que c’est aussi  une des meilleures formes d’avant-vente mais ça n’engage que moi).</p>
<p>Attendez-vous aussi à des questions du public sur la démarche open source de votre activité ou votre entreprise. Les gens chercheront à savoir ce que vous apportez à la communauté (et je vous encourage à apporter quelque chose à la communauté).</p>
<h3 style=\"text-align: center;\"><a title=\"Lien vers l\'appel à orateurs pour le WordCamp Paris 2014\" href=\"http://2014.paris.wordcamp.org/2013/10/29/appel-a-orateurs/\">Je propose une ou plusieurs conférences</a></h3>
<p>&nbsp;</p>
<p><strong>Voilà, on espère vous voir nombreux et en forme le 17 janvier prochain ! A bientôt.</strong></p>
<div class=\"feedflare\">
<a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=g_h34lO6c00:RVTsZHl76rU:yIl2AUoC8zA\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=yIl2AUoC8zA\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=g_h34lO6c00:RVTsZHl76rU:V_sGLiPBpWU\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=g_h34lO6c00:RVTsZHl76rU:V_sGLiPBpWU\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=g_h34lO6c00:RVTsZHl76rU:qj6IDK7rITs\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=qj6IDK7rITs\" border=\"0\"></img></a> <a href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=g_h34lO6c00:RVTsZHl76rU:gIN9vFwOqvQ\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=g_h34lO6c00:RVTsZHl76rU:gIN9vFwOqvQ\" border=\"0\"></img></a>
</div><img src=\"http://feeds.feedburner.com/~r/WordpressFrancophone/~4/g_h34lO6c00\" height=\"1\" width=\"1\"/>\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:36:\"http://wellformedweb.org/CommentAPI/\";a:1:{s:10:\"commentRss\";a:1:{i:0;a:5:{s:4:\"data\";s:86:\"http://www.wordpress-fr.net/2013/11/05/wordcamp-paris-2014-si-vous-etiez-orateur/feed/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:38:\"http://purl.org/rss/1.0/modules/slash/\";a:1:{s:8:\"comments\";a:1:{i:0;a:5:{s:4:\"data\";s:2:\"20\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:42:\"http://rssnamespace.org/feedburner/ext/1.0\";a:1:{s:8:\"origLink\";a:1:{i:0;a:5:{s:4:\"data\";s:81:\"http://www.wordpress-fr.net/2013/11/05/wordcamp-paris-2014-si-vous-etiez-orateur/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}}s:44:\"http://purl.org/rss/1.0/modules/syndication/\";a:2:{s:12:\"updatePeriod\";a:1:{i:0;a:5:{s:4:\"data\";s:6:\"hourly\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:15:\"updateFrequency\";a:1:{i:0;a:5:{s:4:\"data\";s:1:\"1\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"link\";a:2:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:3:{s:3:\"rel\";s:4:\"self\";s:4:\"type\";s:19:\"application/rss+xml\";s:4:\"href\";s:48:\"http://feeds.feedburner.com/WordpressFrancophone\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:3:\"rel\";s:3:\"hub\";s:4:\"href\";s:32:\"http://pubsubhubbub.appspot.com/\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:42:\"http://rssnamespace.org/feedburner/ext/1.0\";a:4:{s:4:\"info\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:3:\"uri\";s:20:\"wordpressfrancophone\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:14:\"emailServiceId\";a:1:{i:0;a:5:{s:4:\"data\";s:20:\"WordpressFrancophone\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:18:\"feedburnerHostname\";a:1:{i:0;a:5:{s:4:\"data\";s:28:\"http://feedburner.google.com\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"feedFlare\";a:9:{i:0;a:5:{s:4:\"data\";s:24:\"Subscribe with NewsGator\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:4:\"href\";s:112:\"http://www.newsgator.com/ngs/subscriber/subext.aspx?url=http%3A%2F%2Ffeeds.feedburner.com%2FWordpressFrancophone\";s:3:\"src\";s:42:\"http://www.newsgator.com/images/ngsub1.gif\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:24:\"Subscribe with Bloglines\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:4:\"href\";s:77:\"http://www.bloglines.com/sub/http://feeds.feedburner.com/WordpressFrancophone\";s:3:\"src\";s:48:\"http://www.bloglines.com/images/sub_modern11.gif\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:23:\"Subscribe with Netvibes\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:4:\"href\";s:98:\"http://www.netvibes.com/subscribe.php?url=http%3A%2F%2Ffeeds.feedburner.com%2FWordpressFrancophone\";s:3:\"src\";s:44:\"http://www.netvibes.com/img/add2netvibes.gif\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:3;a:5:{s:4:\"data\";s:21:\"Subscribe with Google\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:4:\"href\";s:93:\"http://fusion.google.com/add?feedurl=http%3A%2F%2Ffeeds.feedburner.com%2FWordpressFrancophone\";s:3:\"src\";s:51:\"http://buttons.googlesyndication.com/fusion/add.gif\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:4;a:5:{s:4:\"data\";s:25:\"Subscribe with Pageflakes\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:4:\"href\";s:101:\"http://www.pageflakes.com/subscribe.aspx?url=http%3A%2F%2Ffeeds.feedburner.com%2FWordpressFrancophone\";s:3:\"src\";s:87:\"http://www.pageflakes.com/ImageFile.ashx?instanceId=Static_4&fileName=ATP_blu_91x17.gif\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:5;a:5:{s:4:\"data\";s:21:\"Subscribe with Plusmo\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:4:\"href\";s:86:\"http://www.plusmo.com/add?url=http%3A%2F%2Ffeeds.feedburner.com%2FWordpressFrancophone\";s:3:\"src\";s:43:\"http://plusmo.com/res/graphics/fbplusmo.gif\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:6;a:5:{s:4:\"data\";s:23:\"Subscribe with Live.com\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:4:\"href\";s:81:\"http://www.live.com/?add=http%3A%2F%2Ffeeds.feedburner.com%2FWordpressFrancophone\";s:3:\"src\";s:141:\"http://tkfiles.storage.msn.com/x1piYkpqHC_35nIp1gLE68-wvzLZO8iXl_JMledmJQXP-XTBOLfmQv4zhj4MhcWEJh_GtoBIiAl1Mjh-ndp9k47If7hTaFno0mxW9_i3p_5qQw\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:7;a:5:{s:4:\"data\";s:25:\"Subscribe with Mon Yahoo!\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:4:\"href\";s:98:\"http://add.my.yahoo.com/content?lg=fr&url=http%3A%2F%2Ffeeds.feedburner.com%2FWordpressFrancophone\";s:3:\"src\";s:60:\"http://us.i1.yimg.com/us.yimg.com/i/us/my/bn/intatm_fr_1.gif\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:8;a:5:{s:4:\"data\";s:25:\"Subscribe with Excite MIX\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:4:\"href\";s:89:\"http://mix.excite.eu/add?feedurl=http%3A%2F%2Ffeeds.feedburner.com%2FWordpressFrancophone\";s:3:\"src\";s:42:\"http://image.excite.co.uk/mix/addtomix.gif\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:52:\"http://backend.userland.com/creativeCommonsRssModule\";a:1:{s:7:\"license\";a:1:{i:0;a:5:{s:4:\"data\";s:49:\"http://creativecommons.org/licenses/by-nc-sa/3.0/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}}}}}}}s:4:\"type\";i:128;s:7:\"headers\";a:10:{s:12:\"content-type\";s:23:\"text/xml; charset=UTF-8\";s:4:\"etag\";s:27:\"rbUoBgUdMPYZ1zE0/C9YQmcHdpw\";s:13:\"last-modified\";s:29:\"Sun, 15 Dec 2013 21:40:19 GMT\";s:4:\"date\";s:29:\"Sun, 15 Dec 2013 21:55:21 GMT\";s:7:\"expires\";s:29:\"Sun, 15 Dec 2013 21:55:21 GMT\";s:13:\"cache-control\";s:18:\"private, max-age=0\";s:22:\"x-content-type-options\";s:7:\"nosniff\";s:16:\"x-xss-protection\";s:13:\"1; mode=block\";s:6:\"server\";s:3:\"GSE\";s:18:\"alternate-protocol\";s:7:\"80:quic\";}s:5:\"build\";s:14:\"20130911030210\";}","no");
INSERT INTO wp_options VALUES("304","_transient_timeout_feed_mod_66a70e9599b658d5cc038e8074597e7c","1387187707","no");
INSERT INTO wp_options VALUES("305","_transient_feed_mod_66a70e9599b658d5cc038e8074597e7c","1387144507","no");
INSERT INTO wp_options VALUES("306","_transient_timeout_feed_2fb9572e3d6a42f680e36370936a57ae","1387187708","no");
INSERT INTO wp_options VALUES("307","_transient_feed_2fb9572e3d6a42f680e36370936a57ae","a:4:{s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"feed\";a:1:{i:0;a:6:{s:4:\"data\";s:268:\"
        \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:2:{s:27:\"http://www.w3.org/2005/Atom\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:30:\"WordPress Francophone : Planet\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"subtitle\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:2:\"id\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"http://www.wordpress-fr.net/planet/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:3:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:3:{s:3:\"rel\";s:9:\"alternate\";s:4:\"type\";s:9:\"text/html\";s:4:\"href\";s:35:\"http://www.wordpress-fr.net/planet/\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:1;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:3:{s:3:\"rel\";s:4:\"self\";s:4:\"type\";s:20:\"application/atom+xml\";s:4:\"href\";s:54:\"http://feeds.feedburner.com/WordpressFrancophonePlanet\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}i:2;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:3:\"rel\";s:3:\"hub\";s:4:\"href\";s:32:\"http://pubsubhubbub.appspot.com/\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"updated\";a:1:{i:0;a:5:{s:4:\"data\";s:20:\"2013-12-15T22:39:51Z\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:6:\"author\";a:1:{i:0;a:6:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"name\";a:1:{i:0;a:5:{s:4:\"data\";s:6:\"Author\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}s:5:\"entry\";a:17:{i:0;a:6:{s:4:\"data\";s:68:\"
    \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:62:\"WordPress Francophone : Sortie de WordPress 3.8 « Parker »\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:2:\"id\";a:1:{i:0;a:5:{s:4:\"data\";s:67:\"http://feedproxy.google.com/~r/WordpressFrancophone/~3/ag9OPg37Fmk/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:3:\"rel\";s:9:\"alternate\";s:4:\"href\";s:67:\"http://feedproxy.google.com/~r/WordpressFrancophone/~3/ag9OPg37Fmk/\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"published\";a:1:{i:0;a:5:{s:4:\"data\";s:25:\"2013-12-13T13:43:28+00:00\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:6:\"author\";a:1:{i:0;a:6:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"name\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"anonymous\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}s:7:\"content\";a:1:{i:0;a:5:{s:4:\"data\";s:6937:\"<div>
<p><span style=\"line-height:1.5em;\">La version 3.8 de WordPress, baptis&eacute;e &laquo;&nbsp;Parker&nbsp;&raquo; en hommage &agrave; </span><a rel=\"nofollow\" style=\"line-height:1.5em;\" target=\"_blank\" href=\"https://fr.wikipedia.org/wiki/Charlie_Parker\">Charlie Parker</a><span style=\"line-height:1.5em;\">, l&rsquo;innovateur du be-bop, est <a rel=\"nofollow\" target=\"_blank\" href=\"http://fr.wordpress.org/\">disponible en t&eacute;l&eacute;chargement</a> ou en mise &agrave; jour automatique depuis le tableau de bord de votre WordPress. Nous pensons que c&rsquo;est&nbsp;le&nbsp;WordPress le&nbsp;plus&nbsp;beau &agrave;&nbsp;ce&nbsp;jour.</span></p>
<p><embed width=\"400\" height=\"224\" type=\"application/x-shockwave-flash\" src=\"http://s0.videopress.com/player.swf?v=1.03\"></embed></p> 
<p>&nbsp;</p>
<h2>Un nouveau design moderne</h2>
<p><span style=\"line-height:1.5em;\">WordPress a fait peau neuve. La version 3.8 dispose d&rsquo;une toute nouvelle apparence pour son administration. Termin&eacute;s les d&eacute;grad&eacute;s imposants et les douzines de nuances de gris &mdash; faites place &agrave; un design plus grand, plus audacieux, plus color&eacute;&nbsp;</span></p>
<p><img class=\"alignnone\" alt=\"\" src=\"http://i2.wp.com/wpdotorg.files.wordpress.com/2013/12/design.png?resize=623%2C151\" width=\"623\" height=\"151\"></p>
<h3>Une esth&eacute;tique moderne</h3>
<p>Le nouveau tableau de bord de WordPress propose une mise en page agr&eacute;able et &eacute;pur&eacute;e qui fait la part belle &agrave; la clart&eacute; et la simplicit&eacute;.</p>
<h3>Une typographie nette</h3>
<p>La police de caract&egrave;res Open Sans pr&eacute;sente votre texte de mani&egrave;re simple et conviviale. Elle est optimis&eacute;e tant pour les ordinateurs de bureau que pour l&rsquo;affichage mobile, et elle est open-source, tout comme WordPress.</p>
<h3>Des contrastes affin&eacute;s</h3>
<p>Nous pensons qu&rsquo;un beau design ne devrait jamais sacrifier &agrave; la lisibilit&eacute;. Avec des contrastes larges et sup&eacute;rieurs et une police agr&eacute;able, le nouveau design est facile &agrave; lire et un plaisir &agrave; parcourir.</p>
<h2>WordPress sur tous&nbsp;les&nbsp;terminaux</h2>
<p>Nous acc&eacute;dons tous &agrave; Internet de diff&eacute;rentes mani&egrave;res. T&eacute;l&eacute;phones mobiles, tablettes, ordinateurs portables ou de bureau &mdash; quel que soit votre usage, WordPress s&rsquo;y adaptera et vous vous sentirez chez vous.</p>
<h3>Haute d&eacute;finition &agrave; haute&nbsp;vitesse</h3>
<p>WordPress est plus classe que jamais avec ses nouvelles ic&ocirc;nes vectorielles qui s&rsquo;adaptent &agrave; votre &eacute;cran. En &eacute;liminant les pixels, nous avons &eacute;galement am&eacute;lior&eacute; le temps de chargement de la page.</p>
<h2>Des jeux de couleurs adapt&eacute;s &agrave; votre personnalit&eacute;</h2>
<p>WordPress a re&ccedil;u une mise &agrave; jour haute en couleurs.&nbsp;Nous avons ajout&eacute; 8 jeux de couleurs afin que vous trouviez votre pr&eacute;f&eacute;r&eacute;.</p>
<p>Vous pouvez pr&eacute;visualiser et changer le jeu de couleurs &agrave; n&rsquo;importe quel moment en vous rendant sur&nbsp;votre profil.</p>
<h2>Une gestion des th&egrave;mes affin&eacute;e</h2>
<p>Le nouvel &eacute;cran des th&egrave;mes vous permet d&rsquo;inspecter tous vos th&egrave;mes d&rsquo;un coup d&rsquo;&oelig;il. Mais peut-&ecirc;tre voulez-vous plus d&rsquo;information&nbsp;? Cliquez pour en apprendre plus, puis naviguez tranquillement d&rsquo;un th&egrave;me &agrave; l&rsquo;autre &agrave; l&rsquo;aide de votre clavier.</p>
<h3>Une utilisation plus souple des widgets</h3>
<p>Glisser-d&eacute;poser, glisser-d&eacute;poser, glisser-d&eacute;poser. Faire d&eacute;filer, faire d&eacute;filer, faire d&eacute;filer. La gestion des widgets peut s&rsquo;av&eacute;rer laborieuse. Avec le nouveau design, nous avons rationalis&eacute; l&rsquo;&eacute;cran des widgets.</p>
<p>Vous avez un grand moniteur&nbsp;? Les nombreuses zones de widgets se placent les unes &agrave; c&ocirc;t&eacute; des autres pour utiliser tout l&rsquo;espace disponible. Vous utilisez une tablette&nbsp;? Touchez simplement un widget pour l&rsquo;ajouter.</p>
<h2>Twenty Fourteen, un superbe nouveau th&egrave;me&nbsp;&nbsp;&raquo;magazine&nbsp;&raquo;</h2>
<h3>Transformez votre blog en&nbsp;un&nbsp;magazine</h3>
<p>Cr&eacute;ez un magnifique site de style magazine avec WordPress et Twenty Fourteen. Choisissez d&rsquo;afficher votre contenu mis en avant dans une grille ou un diaporama sur votre page d&rsquo;accueil. Personnalisez votre site avec trois zones de widgets, ou modifiez votre mise en page gr&acirc;ce &agrave; deux mod&egrave;les de pages.</p>
<p>Avec une nouvelle conception remarquable qui ne remet pas en question notre simplicit&eacute; l&eacute;gendaire, Twenty Fourteen est notre th&egrave;me par d&eacute;faut le plus intr&eacute;pide &agrave; ce jour.</p>
<h2>Le d&eacute;but d&rsquo;une nouvelle &egrave;re</h2>
<p>Cette version a &eacute;t&eacute; men&eacute;e par Matt Mullenweg. C&rsquo;est notre deuxi&egrave;me version &agrave; utiliser le nouveau processus de d&eacute;veloppement passant par des prototypes sous forme d&rsquo;extensions, avec un d&eacute;lai beaucoup plus court que par le pass&eacute;. Nous pensons que ce processus s&rsquo;est tr&egrave;s bien d&eacute;roul&eacute;. Vous pouvez d&eacute;couvrir les fonctionnalit&eacute;s en cours de mise en place le blog&nbsp;<a rel=\"nofollow\" title=\"Make WordPress Core\" target=\"_blank\" href=\"http://make.wordpress.org/core/\">make/core</a>.</p>
<p>Merci &agrave; vous d&rsquo;avoir choisi WordPress, et &agrave; bient&ocirc;t pour WordPress 3.9 !</p>
<p><em>(cet article est une adaptation de <a rel=\"nofollow\" target=\"_blank\" href=\"http://wordpress.org/news/2013/12/parker/\">l&rsquo;annonce originale</a>)</em></p>
<div class=\"feedflare\">
<a rel=\"nofollow\" target=\"_blank\" href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=ag9OPg37Fmk:4CYF5WimPC8:yIl2AUoC8zA\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=yIl2AUoC8zA\" border=\"0\"></a> <a rel=\"nofollow\" target=\"_blank\" href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=ag9OPg37Fmk:4CYF5WimPC8:V_sGLiPBpWU\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=ag9OPg37Fmk:4CYF5WimPC8:V_sGLiPBpWU\" border=\"0\"></a> <a rel=\"nofollow\" target=\"_blank\" href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=ag9OPg37Fmk:4CYF5WimPC8:qj6IDK7rITs\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?d=qj6IDK7rITs\" border=\"0\"></a> <a rel=\"nofollow\" target=\"_blank\" href=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?a=ag9OPg37Fmk:4CYF5WimPC8:gIN9vFwOqvQ\"><img src=\"http://feeds.feedburner.com/~ff/WordpressFrancophone?i=ag9OPg37Fmk:4CYF5WimPC8:gIN9vFwOqvQ\" border=\"0\"></a>
</div>
<img src=\"http://feeds.feedburner.com/~r/WordpressFrancophone/~4/ag9OPg37Fmk\" height=\"1\" width=\"1\">
</div>\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:1;a:6:{s:4:\"data\";s:68:\"
    \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:39:\"Blog Tool Box : Sortie de WordPress 3.8\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:2:\"id\";a:1:{i:0;a:5:{s:4:\"data\";s:43:\"http://blogtoolbox.fr/sortie-wordpress-3-8/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:3:\"rel\";s:9:\"alternate\";s:4:\"href\";s:43:\"http://blogtoolbox.fr/sortie-wordpress-3-8/\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"published\";a:1:{i:0;a:5:{s:4:\"data\";s:25:\"2013-12-13T10:16:59+00:00\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:6:\"author\";a:1:{i:0;a:6:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"name\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"anonymous\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}s:7:\"content\";a:1:{i:0;a:5:{s:4:\"data\";s:1525:\"<div>
<p><img src=\"http://blogtoolbox.fr/wp-content/uploads/wordpress-38-1.jpg\" alt=\"WordPress 3.8\"></p>
<p>Une petite br&egrave;ve pour signaler la sortie de WordPress 3.8, &agrave; peine 1 mois et demi apr&egrave;s la sortie de la version 3.7.1 (29 octobre). Vous pouvez d&egrave;s &agrave; pr&eacute;sent <a rel=\"nofollow\" target=\"_blank\" href=\"http://fr.wordpress.org/\">t&eacute;l&eacute;charger cette nouvelle version en fran&ccedil;ais</a>.</p>
<p><img src=\"http://blogtoolbox.fr/wp-content/uploads/wordpress-38-2.jpg\" alt=\"WordPress 3.8\"></p>
<p>Sans entrer dans les d&eacute;tails, on notera pour cette version de grandes avanc&eacute;es c&ocirc;t&eacute; interface graphique. Un nouveau design d&eacute;clin&eacute; en plusieurs couleurs pour l&rsquo;administration et qui s&rsquo;adapte aux smartphones/tablettes/desktops. La gestion des th&egrave;mes est &eacute;galement retravaill&eacute;e et accueille l&rsquo;arriv&eacute;e de Twenty Fourteen, un nouveau th&egrave;me par d&eacute;faut.</p>
<p><img src=\"http://blogtoolbox.fr/wp-content/uploads/wordpress-38-3.jpg\" alt=\"WordPress 3.8\"></p>
<p>Ci-dessous, la vid&eacute;o de pr&eacute;sentation de WordPress 3.8 :</p>
<p><embed type=\"application/x-shockwave-flash\" src=\"http://s0.videopress.com/player.swf?v=1.03\" width=\"600\" height=\"336\"></embed></p>
<p>Plus d&rsquo;information sur le blog officiel de WordPress : <a rel=\"nofollow\" target=\"_blank\" href=\"http://wordpress.org/news/2013/12/parker/\">WordPress 3.8 &laquo;&nbsp;Parker&nbsp;&raquo;</a>.</p>
</div>\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:2;a:6:{s:4:\"data\";s:68:\"
    \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:90:\"BoiteAWeb : Gagnez 2 places pour le WordCamp Paris 2014 : Vendredi 17 et Samedi 18 Janvier\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:2:\"id\";a:1:{i:0;a:5:{s:4:\"data\";s:58:\"http://boiteaweb.fr/concours-wordcamp-paris-2014-7866.html\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:3:\"rel\";s:9:\"alternate\";s:4:\"href\";s:58:\"http://boiteaweb.fr/concours-wordcamp-paris-2014-7866.html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"published\";a:1:{i:0;a:5:{s:4:\"data\";s:25:\"2013-12-12T11:15:38+00:00\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:6:\"author\";a:1:{i:0;a:6:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"name\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"anonymous\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}s:7:\"content\";a:1:{i:0;a:5:{s:4:\"data\";s:421:\"<div>
<p>Pour tenter de gagner une de ces 2 places pour le WordCamp Paris il suffit, comme toujours de</p>
<p>Cet article <a rel=\"nofollow\" target=\"_blank\" href=\"http://boiteaweb.fr/concours-wordcamp-paris-2014-7866.html\">Gagnez 2 places pour le WordCamp Paris 2014 : Vendredi 17 et Samedi 18 Janvier</a> est apparu en premier sur <a rel=\"nofollow\" target=\"_blank\" href=\"http://boiteaweb.fr/\">BoiteAWeb.fr</a>.</p>
</div>\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:3;a:6:{s:4:\"data\";s:68:\"
    \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:72:\"WordPress Channel : Divi Builder by Elegant Themes : 3 licence à GAGNER\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:2:\"id\";a:1:{i:0;a:5:{s:4:\"data\";s:49:\"http://wpchannel.com/divi-builder-elegant-themes/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:3:\"rel\";s:9:\"alternate\";s:4:\"href\";s:49:\"http://wpchannel.com/divi-builder-elegant-themes/\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"published\";a:1:{i:0;a:5:{s:4:\"data\";s:25:\"2013-12-10T19:52:25+00:00\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:6:\"author\";a:1:{i:0;a:6:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"name\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"anonymous\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}s:7:\"content\";a:1:{i:0;a:5:{s:4:\"data\";s:967:\"<div>
<p></p>
<p style=\"float:right;margin:0 0 10px 15px;width:240px;\">
		<img src=\"http://wpchannel.com/images/2013/12/Logo.jpg\" width=\"240\"></p>Alors que les f&ecirc;tes de fin d&rsquo;ann&eacute;e approchent, on ne ch&ocirc;me pas chez Elegant Themes qui pr&eacute;pare activement la sortie d&rsquo;un th&egrave;me un peu particulier : Divi Builder. Sa particularit&eacute; ? Pouvoir concevoir votre site par glisser / d&eacute;poser sans toucher une ligne de code et en ne faisant appel qu&rsquo;&agrave; votre imagination. Un concept [&hellip;]<p><a rel=\"nofollow\" target=\"_blank\" href=\"http://wpchannel.com/author/aurelien-denis/\">Aur&eacute;lien Denis</a> - <a rel=\"nofollow\" target=\"_blank\" href=\"http://wpchannel.com/\">WordPress Channel - Tutoriels, th&egrave;mes &amp; plugins WordPress</a> - <a rel=\"nofollow\" target=\"_blank\" href=\"http://wpchannel.com/divi-builder-elegant-themes/\">Divi Builder by Elegant Themes : 3 licence &agrave; GAGNER</a></p>
</div>\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:4;a:6:{s:4:\"data\";s:68:\"
    \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:56:\"L\'écho des plugins WordPress : Preview Posts Everywhere\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:2:\"id\";a:1:{i:0;a:5:{s:4:\"data\";s:68:\"http://www.echodesplugins.li-an.fr/plugins/preview-posts-everywhere/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:3:\"rel\";s:9:\"alternate\";s:4:\"href\";s:68:\"http://www.echodesplugins.li-an.fr/plugins/preview-posts-everywhere/\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"published\";a:1:{i:0;a:5:{s:4:\"data\";s:25:\"2013-12-10T10:49:01+00:00\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:6:\"author\";a:1:{i:0;a:6:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"name\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"anonymous\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}s:7:\"content\";a:1:{i:0;a:5:{s:4:\"data\";s:86:\"<div>Pr&eacute;visualisez vos brouillons &agrave; n\'importe quel endroit du site</div>\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:5;a:6:{s:4:\"data\";s:68:\"
    \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:50:\"SEOMix : Réaliser un thème multilingue avec WPML\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:2:\"id\";a:1:{i:0;a:5:{s:4:\"data\";s:44:\"http://www.seomix.fr/theme-multilingue-wpml/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:3:\"rel\";s:9:\"alternate\";s:4:\"href\";s:44:\"http://www.seomix.fr/theme-multilingue-wpml/\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"published\";a:1:{i:0;a:5:{s:4:\"data\";s:25:\"2013-12-03T08:30:49+00:00\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:6:\"author\";a:1:{i:0;a:6:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"name\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"anonymous\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}s:7:\"content\";a:1:{i:0;a:5:{s:4:\"data\";s:1506:\"<div>
<div><img width=\"180\" height=\"144\" src=\"http://www.seomix.fr/wp-content/uploads/2013/12/wordpress-wpml-180x144.png\" class=\"attachment-thumbnail wp-post-image\" alt=\"wpml wordpress\"></div>Comment concevoir proprement un th&egrave;me WordPress multilingue avec WPML ? Willy Bahuaud passe en revue tout ce qu\'il vous faut savoir pour avoir un WordPress correctement traduit.
      <p><strong>Article original :</strong> <a rel=\"nofollow\" target=\"_blank\" href=\"http://www.seomix.fr/theme-multilingue-wpml/\">R&eacute;aliser un th&egrave;me multilingue avec WPML</a>.</p>
      <p><strong>Debut du contenu :</strong> Lorsque l\'on souhaite r&eacute;aliser un site multilingue sur WordPress, on a le choix entre quelques extensions : qTranslate, Xili language, Multilingual Press... et WPML. Cette derni&egrave;re se d&eacute;tache vraiment du lot. Ce plugin, actuellement en version 3.0.1, existe depuis 2009, b&eacute;n&eacute;ficie d\'une large communaut&eacute; et d\'un support r&eacute;actif. Sa licence compl&egrave;te co&ucirc;te 79$, un prix tout &agrave; fait raisonnable et qui s\'amorti rapidement au fil des projets. S\'il est test&eacute; et approuv&eacute;, il faut tout de m&ecirc;me comprendre son fonctionnement pour concevoir des th&egrave;mes compatibles. Cet article va vous donner les bases n&eacute;cessaires pour appr&eacute;hender la b&ecirc;te et &eacute;viter les [&hellip;]</p>
<hr>
<img src=\"http://feeds.feedburner.com/~r/seomix-wordpress/~4/b97vhMCXwOs\" height=\"1\" width=\"1\">
</div>\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:6;a:6:{s:4:\"data\";s:68:\"
    \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:48:\"Cree1site : Votre cadeau pour la V2 de Cree1site\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:2:\"id\";a:1:{i:0;a:5:{s:4:\"data\";s:47:\"http://www.cree1site.com/theme-storyline-board/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:3:\"rel\";s:9:\"alternate\";s:4:\"href\";s:47:\"http://www.cree1site.com/theme-storyline-board/\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"published\";a:1:{i:0;a:5:{s:4:\"data\";s:25:\"2013-12-02T23:09:28+00:00\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:6:\"author\";a:1:{i:0;a:6:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"name\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"anonymous\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}s:7:\"content\";a:1:{i:0;a:5:{s:4:\"data\";s:521:\"<div>
<p>Vu sur le site de <a rel=\"nofollow\" target=\"_blank\" href=\"http://www.cree1site.com/\">l\'agence web</a> Cree1site.com<br></p>
<p>Je l&rsquo;ai annonc&eacute; sur twitter il y a quelques jours, je met en jeu le th&egrave;me Storyline Board pour WordPress d&rsquo;une valeur de 40$. Ce dernier a re&ccedil;u le grand</p>
<p>Cet article <a rel=\"nofollow\" target=\"_blank\" href=\"http://www.cree1site.com/theme-storyline-board/\">Votre cadeau pour la V2 de Cree1site</a> est apparu en premier sur Cree1site.com</p>
</div>\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:7;a:6:{s:4:\"data\";s:68:\"
    \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:34:\"WP Formation : Ma veille WordPress\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:2:\"id\";a:1:{i:0;a:5:{s:4:\"data\";s:43:\"http://wpformation.com/ma-veille-wordpress/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:3:\"rel\";s:9:\"alternate\";s:4:\"href\";s:43:\"http://wpformation.com/ma-veille-wordpress/\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"published\";a:1:{i:0;a:5:{s:4:\"data\";s:25:\"2013-11-25T08:52:50+00:00\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:6:\"author\";a:1:{i:0;a:6:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"name\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"anonymous\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}s:7:\"content\";a:1:{i:0;a:5:{s:4:\"data\";s:1543:\"<div>
<p><img width=\"300\" height=\"195\" src=\"http://wpformation.com/wp-content/uploads/2013/11/veille-wordpress-300x195.jpg\" class=\"attachment-medium wp-post-image\" alt=\"veille-wordpress\" style=\"float:right;margin:0 0 10px 10px;\">Suivre un domaine d&rsquo;int&eacute;r&ecirc;t particulier implique de s&eacute;lectionner des sources et si elles sont nombreuses il vous faudra n&eacute;cessairement des outils pour les suivre... Retrouvez ci-apr&egrave;s ma veille WordPress et quelques outils efficaces pour optimiser la v&ocirc;tre! Les sources pour ma veille WordPress Sur Twitter Mon r&eacute;seau pr&eacute;f&eacute;r&eacute; et certainement l\'un des plus r&eacute;actif. Il ...</p>
<p></p>
<hr>
<a rel=\"nofollow\" target=\"_blank\" href=\"http://wpformation.com/ma-veille-wordpress/\">Ma veille WordPress</a> est un article de <a rel=\"nofollow\" title=\"Formation Internet WordPress Ecommerce\" target=\"_blank\" href=\"http://wpformation.com/\">WP Formation</a><br><a rel=\"nofollow\" target=\"_blank\" href=\"http://wpformation.com/formation-wordpress/\">Formation WordPress</a> &amp; <a rel=\"nofollow\" target=\"_blank\" href=\"http://wpformation.com/apprendre-creer-e-commerce/\">eCommerce</a> - Retrouvez-moi sur <a rel=\"nofollow\" title=\"Ajouter sur Facebook\" target=\"_blank\" href=\"http://www.facebook.com/Wibeweb\">Facebook</a> - <a rel=\"nofollow\" title=\"Suivre sur Twitter\" target=\"_blank\" href=\"http://twitter.com/wpformation\">Twitter</a> - <a rel=\"nofollow\" target=\"_blank\" href=\"http://plus.google.com/107614015687669785725/\">Google+</a>
<br><hr>
</div>\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:8;a:6:{s:4:\"data\";s:68:\"
    \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:79:\"Grégoire Noyelle : Genesis :: Utiliser les Widgets Pages et Articles à la Une\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:2:\"id\";a:1:{i:0;a:5:{s:4:\"data\";s:75:\"http://www.gregoirenoyelle.com/genesis-utiliser-widgets-pages-articles-une/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:3:\"rel\";s:9:\"alternate\";s:4:\"href\";s:75:\"http://www.gregoirenoyelle.com/genesis-utiliser-widgets-pages-articles-une/\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"published\";a:1:{i:0;a:5:{s:4:\"data\";s:25:\"2013-11-20T10:15:19+00:00\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:6:\"author\";a:1:{i:0;a:6:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"name\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"anonymous\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}s:7:\"content\";a:1:{i:0;a:5:{s:4:\"data\";s:7011:\"<div>
<p>Dans ce tutoriel, je me concentrerai sur les deux widgets majeurs de Genesis. Tous les liens sont exempts d&rsquo;affiliation.</p>
<h3>Les th&egrave;mes enfants StudioPress ou reposant sur Genesis</h3>
<p>La plupart des <a rel=\"nofollow\" target=\"_blank\" href=\"http://my.studiopress.com/themes/\" title=\"Ouverture\">th&egrave;mes enfants de StudioPress</a> utilisent pour leur page d&rsquo;accueil deux widgets principaux: <strong>Genesis &ndash; Page caract&eacute;ristique</strong> et <strong>Genesis &ndash; Article caract&eacute;ristique</strong>. Ces noms risquent de changer dans la nouvelle traduction. Ils deviendront <strong>Genesis &ndash; Page &agrave; la Une</strong> et <strong>Genesis &ndash; Articles &agrave; la Une</strong>. Ces widgets ne sont pas li&eacute;s au th&egrave;me enfant, mais au framework Genesis lui-m&ecirc;me.</p>
<p>Par d&eacute;faut, pour que &ccedil;a fonctionne correctement, votre site doit avoir le r&eacute;glage par d&eacute;faut suivant. Dans votre back-office, ouvrez R&eacute;glages &gt; Lecture et choisissez <strong>La page d&rsquo;accueil affiche</strong> : <strong>Les derniers articles</strong>.</p>
<h4>Analyse d&rsquo;un th&egrave;me</h4>
<p>Le th&egrave;me Magazine Pro (<a rel=\"nofollow\" target=\"_blank\" href=\"http://demo.studiopress.com/magazine/\" title=\"Ouverture\">lien vers la d&eacute;monstration en ligne</a>), sorti fin octobre 2013 propose sur la page d&rsquo;accueil plusieurs zones de widget. En voici le d&eacute;tail avec la capture qui suit. </p>
<p>Dancs ce th&egrave;me, chaque bloc avec un ent&ecirc;te noir correspond &agrave; une zone de Widget. Celles-ci s&rsquo;activent d&egrave;s que vous placez un widget dans une des zones. Pour cette d&eacute;monstration, &agrave; chaque fois que vous avez une image, c&rsquo;est le widget <strong>Genesis &ndash; Article caract&eacute;ristique</strong> qui est utilis&eacute;.</p>
<p><img src=\"http://cdn.gregoirenoyelle.com/gnm/wpgen-niv1/wpgen-n1-widget-theme-magazine.jpg\" title=\"Widget Genesis du th&egrave;me WordPress Magazine de StudioPress\" alt=\"Capture: Widget Genesis du th&egrave;me WordPress Magazine de StudioPress\" width=\"600\" height=\"1103\"></p>
<p><span id=\"more-7541\"></span></p>
<h4>Emplacement dans le back-office</h4>
<p>Pour installer ces widgets vous vous rendez dans votre back-office Apparence &gt; Widgets et vous avez &agrave; votre disposition les deux widgets principaux de Genesis.</p>
<p><img src=\"http://cdn.gregoirenoyelle.com/gnm/wpgen-niv1/wpgen-n1-widget-articles-pages.jpg\" title=\"Widget Genesis pour les page et articles\" alt=\"Capture: Widget Genesis pour les page et articles\" width=\"600\" height=\"386\"></p>
<p>Ensuite, il s&rsquo;agit d&rsquo;identifier la zone de widget ajout&eacute;e par le th&egrave;me enfant dans laquelle vous allez glisser votre widget.</p>
<p><img src=\"http://cdn.gregoirenoyelle.com/gnm/wpgen-niv1/wpgen-n1-widget-zones-theme.jpg\" title=\"Widget Genesis et zone de widget du th&egrave;me\" alt=\"Capture: Widget Genesis et zone de widget du th&egrave;me\" width=\"600\" height=\"602\"></p>
<h4>Autres th&egrave;mes enfant</h4>
<p>Tous les th&egrave;mes enfants qui reposent sur Genesis ont des zones sp&eacute;cifiques qui peuvent varier d&rsquo;un th&egrave;me &agrave; l&rsquo;autre. Bien s&ucirc;r, ces zones sont optionnelles et nous ne sommes pas oblig&eacute;s d&rsquo;utiliser ces widgets. Nous verrons &ccedil;a dans un autre tutoriel.</p>
<p>Le plus facile est de se reposer sur la d&eacute;monstration en ligne en testant les diff&eacute;rentes zones de widget propos&eacute;es par le th&egrave;me.</p>
<h3>Utilisation des Widget Genesis</h3>
<h4>Widget Page Caract&eacute;ristique</h4>
<p>Ce Widget comme son nom l&rsquo;indique affiche une page. &Agrave; chaque fois plusieurs options sont possibles et il est possible dans une m&ecirc;me zone de widget d&rsquo;empiler plusieurs fois le m&ecirc;me widget avec des r&eacute;glages diff&eacute;rents.</p>
<p><strong>Affichage du Widget en ligne</strong></p>
<p>Ici, j&rsquo;ai plac&eacute; deux widgets <strong>Page caract&eacute;ristiques</strong> dans deux zones de widget diff&eacute;rentes. Dans chaque zone, le widget appelle une page diff&eacute;rente.</p>
<p><img src=\"http://cdn.gregoirenoyelle.com/gnm/wpgen-niv1/wpgen-n1-widget-page-front.jpg\" title=\"Widget Genesis Page &agrave; la Une c&ocirc;t&eacute; Front\" alt=\"Capture: Widget Genesis Page &agrave; la Une c&ocirc;t&eacute; Front\" width=\"600\" height=\"444\"></p>
<p><strong>R&eacute;glage du widget</strong></p>
<p><img src=\"http://cdn.gregoirenoyelle.com/gnm/wpgen-niv1/wpgen-n1-widget-page-back.jpg\" title=\"Widget Genesis Page &agrave; la Une c&ocirc;t&eacute; Back office\" alt=\"Capture: Widget Genesis Page &agrave; la Une c&ocirc;t&eacute; Back office\" width=\"600\" height=\"618\"></p>
<p>En plus de la capture d&rsquo;&eacute;cran, il faut retenir: </p>
<ul>
<li>le titre du Widget est optionnel</li>
<li>une seule page peut &ecirc;tre choisie, mais il est possible d&rsquo;empiler plusieurs fois le m&ecirc;me widget</li>
<li>les autres &eacute;l&eacute;ments, Image &agrave; la Une, titre et contenu sont optionnels et ils ont &agrave; chaque fois des options sp&eacute;cifiques</li>
</ul>
<h3>Widget Articles Caract&eacute;ristique</h3>
<p>Ce widget fonctionne globalement comme le Widget Page Caract&eacute;ristique. La diff&eacute;rence r&eacute;side surtout dans le fait qui permet d&rsquo;afficher un flux d&rsquo;article avec un nombre, des classements sur mesure. Le widget qui concerne les pages ne le permet pas.</p>
<p><strong>Affichage du Widget en ligne</strong></p>
<p><img src=\"http://cdn.gregoirenoyelle.com/gnm/wpgen-niv1/wpgen-n1-widget-article-front.jpg\" title=\"Widget Article &agrave; la Une de Genesis dans le front\" alt=\"Capture: Widget Article &agrave; la Une de Genesis dans le front\" width=\"601\" height=\"646\"></p>
<p><strong>R&eacute;glage du widget</strong></p>
<p><img src=\"http://cdn.gregoirenoyelle.com/gnm/wpgen-niv1/wpgen-n1-widget-article-back.jpg\" title=\"Widget Genesis Articles &agrave; Une dans le back-office\" alt=\"Capture: Widget Genesis Articles &agrave; Une dans le back-office\" width=\"600\" height=\"585\"></p>
<p>En plus de la capture d&rsquo;&eacute;cran, il faut retenir:</p>
<ul>
<li>le titre de la zone de widget est optionnel</li>
<li>si le nombre d&rsquo;articles est r&eacute;gl&eacute; sur <strong>&ndash;1</strong>, cela affiche tout (oui, je sais)</li>
<li>il est possible de passer un N nombre d&rsquo;article ou d&rsquo;omettre un article d&eacute;j&agrave; affich&eacute; (<strong>Exclude Previously Displayed Posts</strong>) pour &eacute;viter les doublons</li>
<li>les informations de l&rsquo;article sont &eacute;ditables avec les shortcodes</li>
<li>il est possible d&rsquo;afficher un lien direct vers une archive de la cat&eacute;gorie s&eacute;lectionn&eacute;e</li>
</ul>
<h3>La suite</h3>
<p>Nous verrons ult&eacute;rieurement pour les utilisateurs un peu plus avanc&eacute;s comment cr&eacute;er ses propres zones de Widget dans Genesis.</p>
</div>\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:9;a:6:{s:4:\"data\";s:68:\"
    \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:85:\"Lumière de Lune : Utilisateurs de Mystique, attention aux mises à jour de WordPress\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:2:\"id\";a:1:{i:0;a:5:{s:4:\"data\";s:78:\"http://www.lumieredelune.com/encrelune/utilisateurs-mystique-attention,2013,11\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:3:\"rel\";s:9:\"alternate\";s:4:\"href\";s:78:\"http://www.lumieredelune.com/encrelune/utilisateurs-mystique-attention,2013,11\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"published\";a:1:{i:0;a:5:{s:4:\"data\";s:25:\"2013-11-14T12:00:00+00:00\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:6:\"author\";a:1:{i:0;a:6:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"name\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"anonymous\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}s:7:\"content\";a:1:{i:0;a:5:{s:4:\"data\";s:476:\"<div>Mystique est un th&egrave;me tr&egrave;s utilis&eacute;, notamment parce qu&rsquo;il est recommand&eacute; par de nombreux &laquo;&nbsp;webmarketeurs&nbsp;&raquo;. N&eacute;anmoins, un probl&egrave;me se pose pour ceux qui l&rsquo;utilisent : le th&egrave;me semble ne pas &ecirc;tre compatible avec la derni&egrave;re mise &agrave; jour de WordPress. Le probl&egrave;me est dans la gestion des options du th&egrave;me : si, apr&egrave;s avoir install&eacute; [...]</div>\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:10;a:6:{s:4:\"data\";s:68:\"
    \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:84:\"GeekPress : Mention comment’s Authors :  Enrichir vos fils de discussion WordPress\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:2:\"id\";a:1:{i:0;a:5:{s:4:\"data\";s:74:\"http://www.geekpress.fr/wordpress/extension/mention-comments-authors-1961/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:3:\"rel\";s:9:\"alternate\";s:4:\"href\";s:74:\"http://www.geekpress.fr/wordpress/extension/mention-comments-authors-1961/\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"published\";a:1:{i:0;a:5:{s:4:\"data\";s:25:\"2013-11-14T10:30:14+00:00\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:6:\"author\";a:1:{i:0;a:6:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"name\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"anonymous\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}s:7:\"content\";a:1:{i:0;a:5:{s:4:\"data\";s:215:\"<div>Le syst&egrave;me de r&eacute;ponse propos&eacute; par WordPress n&rsquo;est pas id&eacute;al. Le plugin Mention comment&rsquo;s Authors est une alternative pour enrichir vos fils de discussion WordPress.</div>\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:11;a:6:{s:4:\"data\";s:68:\"
    \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:49:\"Lashon : WordPress 3.7 est peinard et sécurisant\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:2:\"id\";a:1:{i:0;a:5:{s:4:\"data\";s:46:\"http://lashon.fr/wordpress-3-7-super-securite/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:3:\"rel\";s:9:\"alternate\";s:4:\"href\";s:46:\"http://lashon.fr/wordpress-3-7-super-securite/\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"published\";a:1:{i:0;a:5:{s:4:\"data\";s:25:\"2013-10-28T07:15:50+00:00\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:6:\"author\";a:1:{i:0;a:6:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"name\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"anonymous\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}s:7:\"content\";a:1:{i:0;a:5:{s:4:\"data\";s:855:\"<div>
<p>&nbsp; Juste quelques mots brefs pour dire que cette fois &ccedil;a y est, WordPress 3.7, nomm&eacute; &laquo;&nbsp;Basie&nbsp;&raquo; est une version de WordPress fort r&eacute;ussie. D&rsquo;ailleurs ce blog a saut&eacute; dedans &agrave; pieds joints et les yeux ferm&eacute;s. D&eacute;sormais vous tournerez vos blogs avec plus de s&eacute;curit&eacute; quasi en dormant. La grande nouveaut&eacute; est un processus [&hellip;]</p>
<p>Exigez l\'original ! ;-) 
L\'original de cet article est l&agrave; <a rel=\"nofollow\" target=\"_blank\" href=\"http://lashon.fr/wordpress-3-7-super-securite/\">WordPress 3.7 est peinard et s&eacute;curisant</a> - &eacute;crit par  <a rel=\"nofollow\" target=\"_blank\" href=\"http://lashon.fr/\">WordPress Cr&eacute;ation Sites Internet - lashon.fr, le blog work in progress de Tikoun, cr&eacute;ateur Web</a></p>
</div>\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:12;a:6:{s:4:\"data\";s:68:\"
    \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:76:\"GD6D : Créer une présentation et la diffuser dans son site avec… JETPACK\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:2:\"id\";a:1:{i:0;a:5:{s:4:\"data\";s:51:\"http://feedproxy.google.com/~r/Gd6d/~3/Rng_PI-XPNg/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:3:\"rel\";s:9:\"alternate\";s:4:\"href\";s:51:\"http://feedproxy.google.com/~r/Gd6d/~3/Rng_PI-XPNg/\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"published\";a:1:{i:0;a:5:{s:4:\"data\";s:25:\"2013-09-12T01:19:54+00:00\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:6:\"author\";a:1:{i:0;a:6:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"name\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"anonymous\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}s:7:\"content\";a:1:{i:0;a:5:{s:4:\"data\";s:13524:\"<div>
<img src=\"http://i1.wp.com/www.gd6d.fr/wordpressfr/wp-content/uploads/2013/09/shortcodes.jpg?fit=1024%2C1024\" class=\"attachment-large wp-post-image\" alt=\"shortcodes\"><p>L&rsquo;extension <a rel=\"nofollow\" title=\"Par WordPress.com\" target=\"_blank\" href=\"http://jetpack.me/\"><strong>Jetpack</strong></a> propose des fonctions indispensables (formulaire de contact, statistique, partage, <a rel=\"nofollow\" title=\"Nouveaut&eacute; JetPack : la fonction &laquo; Widget Visibility &raquo;\" target=\"_blank\" href=\"http://www.gd6d.fr/wordpressfr/blog/plugin-jetpack-visibility/\">visibilit&eacute; des widgets</a>&hellip;), d&rsquo;autres moins (<a rel=\"nofollow\" title=\"Nouveau : cr&eacute;ez un diaporama ou une mosa&iuml;que d&rsquo;images avec Jetpack\" target=\"_blank\" href=\"http://www.gd6d.fr/wordpressfr/blog/diaporama-slideshow-mosaique-images-avec-plugin-jetpack/\">mosa&iuml;que d&rsquo;image</a>) et enfin il y a une troisi&egrave;me cat&eacute;gorie : les fonctions &laquo;&nbsp;gadget&nbsp;&raquo;. &laquo;&nbsp;C&rsquo;est totalement <em>inutile</em> et <em>donc</em> rigoureusement <em>indispensable</em> !&nbsp;&raquo; comme dirait J&eacute;rome Bonaldi, donc voyons comment l&rsquo;utiliser !</p>
<h2>Remplacer gratuitement Powerpoint et Slideshare !!!</h2>
<p>La premi&egrave;re utilit&eacute; du module &laquo;&nbsp;Presentation&nbsp;&raquo; est comme son nom l&rsquo;indique de pouvoir cr&eacute;er des pages qui pourront ainsi &ecirc;tre projet&eacute;es, exactement des diapos PowerPoint ou Keynote. Ces pages seront visible sous forme d&rsquo;un diaporama interactif int&eacute;gr&eacute; dans sa page et pouvant occuper tout l&rsquo;&eacute;cran. Voil&agrave; un exemple : </p>
<p></p>
<p class=\"not-supported-msg\" style=\"display:inherit;padding:25%;text-align:center;\">Impossible de lancer ce diaporama. Raffra&icirc;chissez la page&hellip; ou essayez un autre navigateur.</p>
<br><p></p>
<div class=\"shortcode-toggle toggle-voir-le-code closed default border\">
<h4 class=\"toggle-trigger\"><a rel=\"nofollow\">Voir le code</a></h4>
<div class=\"toggle-content\">
<br>
Code utilis&eacute; pour la pr&eacute;sentation ci-dessus.<br><em>Attention, j&rsquo;ai rajout&eacute; un espace autour des crochets !</em>
<p>[ slide transition=\"down\" bgimg=\"http://www.gd6d.fr/wordpressfr/wp-content/uploads/2013/09/bg-gd6d.jpg\" ]<br>
&lt;h3&gt;Presentations Shortcode Plugin&lt;/h3&gt;<br>
&lt;h4&gt;with JETPACK&lt;/h4&gt;<br>
[ /slide ][ slide bgcolor=#CEE4EA ]<br>
Who doesn&rsquo;t love awesome presentations?</p>
<p>This presentations plugin provides shortcodes to let you quickly and easily put together amazing presentations!</p>
<p>Supported features include:<br>
&lt;ul&gt;<br>
&lt;li&gt;Choosing slide transitions&lt;/li&gt;<br>
&lt;li&gt;Rotating and scaling slides for extra awesomeness&lt;/li&gt;<br>
&lt;li&gt;Setting presentation backgrounds with solid colors or images&lt;/li&gt;<br>
&lt;li&gt;Setting transition durations and sizes&lt;/li&gt;<br>
&lt;/ul&gt;<br>
[ /slide ]<br>
[ slide bgimg=\"http://www.gd6d.fr/wordpressfr/wp-content/uploads/2013/09/bg-arrow.jpg\" ]<br>
&lt;h3&gt;Viewing&lt;/h3&gt;<br>
[ twocol_one ]Presentations can be navigated either using the onscreen arrows, or by using keyboard arrow keys.<br>
Tab or space will also navigate the slideshow forward.Fullscreen mode is toggled using the icon on the lower right.</p>
<p>Hitting ESC on the keyboard will also exit fullscreen.[ /twocol_one ] [ twocol_one_last ][ /twocol_one_last ]<br>
[ /slide ]<br>
[ slide ]<br>
To begin, simply start with the presentation shortcode. Then put all your individual slide content inside slide shortcodes and you are good to go!<br>
[ /slide ]<br>
[ slide transition=\"down\" ]<br>
&lt;h2&gt;Down&lt;/h2&gt;<br>
The default transition!<br>
[ /slide ]<br>
[ slide transition=\"right\" ]<br>
&lt;h2&gt;Right&lt;/h2&gt;<br>
[ /slide ]<br>
[ slide transition=\"up\" ]<br>
&lt;h2&gt;Up&lt;/h2&gt;<br>
[ /slide ]<br>
[ slide transition=\"left\" ]<br>
&lt;h2&gt;Left&lt;/h2&gt;<br>
[ /slide ]<br>
[ slide ]<br>
&lt;h2&gt;Or none!&lt;/h2&gt;<br>
Which only really works when fading is enabled.<br>
[ /slide ]<br>
[ slide rotate=45 ]<br>
Rotation</p>
<p>Slides can be rotated using [ slide rotate= ] where the value is in degrees.<br>
[ /slide ]<br>
[ slide ]<br>
Scaling</p>
<p>Emphasize your big ideas or explain the tiny details using [ slide scale= ].<br>
[ /slide ]<br>
[ slide scale=5 ]<br>
Backgrounds</p>
<p>Solid color backgrounds can be set using slide bgcolor= where the value can be any valid HTML color.</p>
<p>Alternatively slide bgimg= with a valid image url will set it as the background, stretching the image to fill the slide.<br>
[ /slide ]<br>
[ slide fade=off ]<br>
Fading</p>
<p>Fading between is enabled by default. It can easily be disabled via slide fade= with a value of &ldquo;off&rdquo; or &ldquo;false&rdquo;.<br>
[ /slide ]<br>
[ slide ]<br>
Enjoy making your own presentations <img src=\"http://i1.wp.com/www.gd6d.fr/wordpressfr/wp-includes/images/smilies/icon_smile.gif\" alt=\":)\" class=\"wp-smiley\"></p>
<p>[ /slide ]<br>
[ /presentation ]</p>
<p></p>
</div>
<input type=\"hidden\" name=\"title_open\" value=\"Fermer\"><input type=\"hidden\" name=\"title_closed\" value=\"Voir le code\">
</div>
<br><div class=\"woo-sc-hr\"></div>
<h2>Pour int&eacute;grer une pr&eacute;sentation</h2>
<p>Ce module fonctionne tout naturellement avec des shortcodes et des variables qui permettent d&rsquo;en ajuster le comportement :</p>
<ul>
<li>S&eacute;lectionner diff&eacute;rentes transitions</li>
<li>Effets de rotation et zoom</li>
<li>Image ou couleur de fond</li>
<li>Dur&eacute;e de transition</li>
</ul>
<p>On est donc en pr&eacute;sence d&rsquo;un outil minimaliste, mais complet et qui peut rendre d&rsquo;autres services, comme nous allons le voir.</p>
<h3>Les shortcodes &agrave; utiliser</h3>
<p>Pour cr&eacute;er une pr&eacute;sentation, utilisez simplement :<br>
[ <code>presentation</code> ]</p>
<p>Pour cr&eacute;er une diapo, utilisez :<br>
[ <code>slide</code> ]</p>
<p>Tous les [ <code>slide</code> ] doivent &ecirc;tre encadr&eacute;es par le shortcode [ <code>presentation</code> ], sinon les diapositives ne seront pas affich&eacute;es.</p>
<p>Les param&egrave;tres tels que <em>la hauteur,</em> la <em>largeur</em> et la <em>dur&eacute;e de transition</em> (en seconde) peuvent tous &ecirc;tre configur&eacute;s en utilisant les attributs respectifs dans le shortcode [ <code>presentation</code> ] .</p>
<p>Par exemple:</p>
<p>Pour cr&eacute;er une pr&eacute;sentation de dimension 600 &times; 375 (comme dans l&rsquo;exemple ci-dessus), utilisez:<br>
[ <code>presentation width=600 height=375</code> ]</p>
<p>Pour d&eacute;finir une dur&eacute;e de transition pour chaque diapositive &eacute;gale &agrave; 5 secondes, utilisez:<br>
[ <code>presentation duration=5</code> ]</p>
<div class=\"shortcode-toggle toggle-les-codes-de-transition-et-dhabillage-en-detail closed default border\">
<h4 class=\"toggle-trigger\"><a rel=\"nofollow\">Les codes de transition et d\'habillage en d&eacute;tail</a></h4>
<div class=\"toggle-content\">
<h4 id=\"transitions\">Quelques effets de transition</h4>
<p>Voici une liste des effets de transition disponibles (&agrave; utiliser avec mod&eacute;ration&hellip;) :</p>
<p>Pour cr&eacute;er une transition qui se d&eacute;place vers le bas (la transition par d&eacute;faut), utilisez:<br>
[ <code>slide transition=\"down\"</code> ]</p>
<p>Pour cr&eacute;er une transition qui se d&eacute;place &agrave; droite, utilisez:<br>
[ <code>slide transition=\"right\"</code> ]</p>
<p>Pour cr&eacute;er une transition qui se d&eacute;place de bas en haut, utilisez:<br>
[ <code>slide transition=\"up\"</code> ]</p>
<p>Pour cr&eacute;er une transition qui se d&eacute;place &agrave; gauche, utilisez:<br>
[ <code>slide transition=\"left\"</code> ]</p>
<p>Pour ne pas afficher de transition, utilisez:<br>
[ <code>slide transition=\"none\"</code> ]</p>
<h4 id=\"rotation-and-scaling\">L&rsquo;effet rotation et mise &agrave; l&rsquo;&eacute;chelle</h4>
<p>Vous pouvez faire pivoter et modifier la taille des diapositives pour cr&eacute;er diff&eacute;rents effets. Pour faire pivoter une diapositive, utilisez:</p>
<p>[ <code>slide rotate=</code> ]</p>
<p>La valeur est en degr&eacute;s. Par exemple: [ <code>slide rotate=45</code> ].</p>
<p>Pour redimensionner une diapositive, utilisez:</p>
<p>[ <code>slide scale=</code> ]</p>
<p>Par exemple: [ <code>slide scale=5</code> ] ou [ <code>slide scale=1.75</code> ].</p>
<h4 id=\"fading\">Effet de fondu enchain&eacute;</h4>
<p>Fondu encha&icirc;n&eacute; entre les diapositives est activ&eacute;e par d&eacute;faut. Pour le d&eacute;sactiver, utilisez:</p>
<p>[ <code>slide fade=\"off\"</code> ] ou [ <code>slide fade=\"false\"</code> ]</p>
<p>Pour le r&eacute;activer, utilisez:</p>
<p>[ <code>slide fade=\"on\"</code> ] ou [ <code>slide fade=\"true\"</code> ]</p>
<h4 id=\"background\">Fond de diapo</h4>
<p>Vous pouvez habiller un diaporama avec un fond de couleur ou une image personnalis&eacute;e. Pour d&eacute;finir une couleur unie, utilisez:</p>
<p>[ <code>slide bgcolor=</code> ]</p>
<p>La valeur est une couleur HTML valide. Par exemple: [ <code>slide bgcolor=#d3e7f8</code> ].</p>
<p>Pour d&eacute;finir une image de fond, utilisez:</p>
<p>[ <code>slide bgimg=</code> ]</p>
<p>La valeur est l&rsquo;adresse URL de l&rsquo;image valide. la taille de l&rsquo;image s&rsquo;ajustera automatiquement &agrave; la diapositive.</p>
<p><em>Astuce:</em> Toutes ces options peuvent &ecirc;tre r&eacute;gl&eacute;es sur la balise [ <code>presentation</code> ] pour les d&eacute;finir comme param&egrave;tres par d&eacute;faut.</p>
</div>
<input type=\"hidden\" name=\"title_open\" value=\"Fermer\"><input type=\"hidden\" name=\"title_closed\" value=\"Les codes de transition et d\'habillage en d&eacute;tail\">
</div>
<br><div class=\"woo-sc-divider\"></div> 
<h4>Lecture de la pr&eacute;sentation</h4>
<p>Vous pouvez visionner une pr&eacute;sentation en plein &eacute;cran en cliquant sur l&rsquo;ic&ocirc;ne &agrave; quatre fl&egrave;che en bas &agrave; droite du diaporama. La touche ESC du clavier permet alors de quitter le mode plein &eacute;cran.</p>
<p>Pour naviguer, on peut utiliser les fl&egrave;ches &agrave; l&rsquo;&eacute;cran ou les touches fl&eacute;ch&eacute;es du clavier. Vous pouvez &eacute;galement utiliser les touches tabulation ou d&rsquo;espace pour afficher la diapositive suivante.</p>
<h4 id=\"viewing\">Et pour les diaporamas photos, on a le droit ???</h4>
<p>C&rsquo;est une utilisation int&eacute;ressante de cette fonctionnalit&eacute; : int&eacute;grer un diaporama photo avec fonction zoom plein &eacute;cran. C&rsquo;est tr&egrave;s simple &agrave; mettre en place et en plus on peut rajouter du texte, comme ici :</p>
<p></p>
<p class=\"not-supported-msg\" style=\"display:inherit;padding:25%;text-align:center;\">Impossible de lancer ce diaporama. Raffra&icirc;chissez la page&hellip; ou essayez un autre navigateur.</p>
<br><div class=\"shortcode-toggle toggle-voir-le-code closed default border\">
<h4 class=\"toggle-trigger\"><a rel=\"nofollow\">Voir le code</a></h4>
<div class=\"toggle-content\">
<br>
Code utilis&eacute; pour le diaporama&hellip; pas compliqu&eacute;, non ?<br><em>Attention, j&rsquo;ai rajout&eacute; un espace autour des crochets !</em>
<pre>[ presentation width=500 height=313 ]
[ slide bgimg=\"http://www.gd6d.fr/wordpressfr/wp-content/uploads/2013/09/LangageWeb12.jpg\" transition=\"right\" ]
&lt;h2&gt;Formation WordPress&lt;/h2&gt;
&lt;h3&gt;pour l\'IIM&lt;/h3&gt;
[ /slide ]
[ slide transition=\"right\" bgimg=\"http://www.gd6d.fr/wordpressfr/wp-content/uploads/2013/09/LangageWeb19.jpg\" ]
[ /slide]
[ slide transition=\"right\" bgimg=\"http://www.gd6d.fr/wordpressfr/wp-content/uploads/2013/09/LangageWeb06.jpg\" ]
[ /slide]
[ slide transition=\"right\" bgimg=\"http://www.gd6d.fr/wordpressfr/wp-content/uploads/2013/09/LangageWeb03.jpg\" ]
[ /slide ]
[ /presentation ]</pre>
<p></p>
</div>
<input type=\"hidden\" name=\"title_open\" value=\"Fermer\"><input type=\"hidden\" name=\"title_closed\" value=\"Voir le code\">
</div>
<div class=\"woo-sc-hr\"></div>
<h4>Conclusion</h4>
<p>Au final, Le shortcode &laquo;&nbsp;Presentation&nbsp;&raquo; propos&eacute; avec JetPack est une fonction int&eacute;ressante &agrave; conna&icirc;tre. Elle peut rendre de nombreux services. Gageons qu&rsquo;elle sera l&rsquo;outil indispensable de tout d&eacute;veloppeur WordPress pour ses propres pr&eacute;sentations ! A voir au prochain WordCamp ?</p>
<div class=\"woo-sc-divider\"></div>
<p>PS : Attention, j&rsquo;ai &eacute;t&eacute; confront&eacute; &agrave; un petit bug (avec Woothemes ??). Apr&egrave;s chaque pr&eacute;sentation, la balise &lt; <code>section</code> &gt; se referme et la mise en page explose ! J&rsquo;ai d&ucirc; rajouter dans le code html la balise correspondante pour r&eacute;-ouvrir la section&hellip;</p>
<div class=\"woo-sc-box info  rounded full\">Article source &laquo;&nbsp;<a rel=\"nofollow\" title=\"Voir l\'article\" target=\"_blank\" href=\"http://en.support.wordpress.com/presentations/\">Shortcode : presentation</a>&nbsp;&raquo; sur le support de WordPress.com </div>
<p>Cet article <a rel=\"nofollow\" target=\"_blank\" href=\"http://www.gd6d.fr/wordpressfr/blog/creer-presentation-diffuser-site-jetpack/\">Cr&eacute;er une pr&eacute;sentation et la diffuser dans son site avec&hellip; JETPACK</a> est apparu en premier sur <a rel=\"nofollow\" target=\"_blank\" href=\"http://www.gd6d.fr/wordpressfr\">Gd6d - sp&eacute;cialiste WordPress</a>.</p>
<img src=\"http://feeds.feedburner.com/~r/Gd6d/~4/Rng_PI-XPNg\" height=\"1\" width=\"1\">
</div>\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:13;a:6:{s:4:\"data\";s:68:\"
    \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:53:\"Here With Me : Stress test de l’extension WP Rocket\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:2:\"id\";a:1:{i:0;a:5:{s:4:\"data\";s:68:\"http://www.herewithme.fr/2013/09/08/stress-test-extension-wp-rocket/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:3:\"rel\";s:9:\"alternate\";s:4:\"href\";s:68:\"http://www.herewithme.fr/2013/09/08/stress-test-extension-wp-rocket/\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"published\";a:1:{i:0;a:5:{s:4:\"data\";s:25:\"2013-09-08T02:16:18+00:00\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:6:\"author\";a:1:{i:0;a:6:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"name\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"anonymous\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}s:7:\"content\";a:1:{i:0;a:5:{s:4:\"data\";s:16907:\"<div>
<p><img class=\"alignright size-full wp-image-1394\" alt=\"wp rocket Stress test de lextension WP Rocket\" src=\"http://www.herewithme.fr/wp-content/uploads/2013/09/wp-rocket.png\" width=\"231\" height=\"198\" title=\"Stress test de lextension WP Rocket\">Difficile&nbsp;exercice que de faire le test d&rsquo;une extension WordPress,&nbsp;d&eacute;velopp&eacute;e&nbsp;par des personnalit&eacute;s &eacute;minentes de la communaut&eacute; fran&ccedil;aise. (Xavier me dirait : diplomatie, diplomatie&hellip;)</p>
<p>Je me lance tout de m&ecirc;me dans l&rsquo;aventure&hellip;</p>
<p>Apr&egrave;s avoir lu beaucoup d&rsquo;articles d&eacute;di&eacute;s &agrave; l&rsquo;extension (dont beaucoup s&rsquo;apparentent davantage &agrave; la de publi-information qu&rsquo;&agrave; une v&eacute;ritable analyse), j&rsquo;ai profit&eacute; d&rsquo;une promo sur Twitter pour acqu&eacute;rir une licence &laquo;&nbsp;professionnelle&nbsp;&raquo; et j&rsquo;ai test&eacute; l&rsquo;extension sur une plateforme de d&eacute;veloppement ainsi que sur 2 projets en phase de production pour me forger MON opinion.</p>
<p>Voici le premier article de la s&eacute;rie stress test d&rsquo;un plugin WP !</p>
<h2>La promesse cliente</h2>
<p>WP-Rocket est une extension pour WordPress qui a comme vocation d&rsquo;am&eacute;liorer les performances de votre site internet. C&rsquo;est une extension&nbsp;&nbsp;&raquo;premium&nbsp;&raquo;, vendue sous 3 licences : personnelle, business et professionnelle.</p>
<p>Les&nbsp;fonctionnalit&eacute;s&nbsp;propos&eacute;es sont :</p>
<ul>
<li>Mise en cache des pages</li>
<li>Pr&eacute;chargement du cache</li>
<li>Compression des fichiers statiques</li>
<li>Chargement diff&eacute;r&eacute; des images</li>
<li>Optimisation pour le navigateur</li>
<li>Optimisation des images</li>
<li>Chargement diff&eacute;r&eacute; des fichiers JavaScript</li>
</ul>
<p>Juste que l&agrave;, c&rsquo;est du tr&egrave;s classique on retrouve ni plus ni moins que tous les ingr&eacute;dients de la &laquo;&nbsp;performance web&nbsp;&raquo;.</p>
<p>Mais la vraie promesse cliente du plugin &agrave; mes yeux, c&rsquo;est &laquo;&nbsp;une configuration rapide&nbsp;&raquo;.</p>
<p>Dans l&rsquo;univers des plugins WordPress, on trouve des centaines d&rsquo;extensions ayant comme objectif d&rsquo;am&eacute;liorer la performance web d&rsquo;un site internet, et ces extensions partagent syst&eacute;matiquement le m&ecirc;me point commun : elles sont complexes et proposent des tonnes d&rsquo;options. Et le pire, c&rsquo;est que ces options ne sont utilis&eacute;es que par une minorit&eacute; de personnes.</p>
<p>WP Rocket, &agrave; l&rsquo;image de l&rsquo;extension WYSIJA, mise sur le c&ocirc;t&eacute;&nbsp;simple, rapide et p&eacute;dagogique de leur extension. (un principe de base de la philosophie WP : <a rel=\"nofollow\" target=\"_blank\" href=\"http://wordpress.org/about/philosophy/#decisions\">D&eacute;cisions, not options</a>)</p>
<p>Et de ce c&ocirc;t&eacute;, on est tr&egrave;s bien servi ! L&rsquo;interface est&nbsp;extr&ecirc;mement&nbsp;propre, les r&eacute;glages de base permettent d&rsquo;activer facilement les fonctionnalit&eacute;s&nbsp;principales de l&rsquo;extension.</p>
<p>A noter qu&rsquo;il ne semble pas possible de d&eacute;sactiver le cache statique, donc impossible de n&rsquo;utiliser que les fonctionnalit&eacute;s de minification ou de chargement diff&eacute;r&eacute; des images.</p>
<h2>Analyse technique</h2>
<p>La question que l&rsquo;on peut se poser face &agrave; une extension qui promet d&rsquo;am&eacute;liorer la performance, c&rsquo;est comment ? Quels m&eacute;canismes sont mis en place ? Les voici en d&eacute;tails</p>
<h3>Cache statique</h3>
<p>LA fonctionnalit&eacute; la plus r&eacute;pandue pour am&eacute;liorer la performance d&rsquo;un site WordPress, c&rsquo;est d&rsquo;installer et configurer un cache statique. Le principe est simple, le premier visiteur consulte une page de votre site, le code HTML est g&eacute;n&eacute;r&eacute; dynamiquement par WP et il est stock&eacute; dans le cache pour une dur&eacute;e d&eacute;finie, les visiteurs suivants consultent alors la copie HTML.</p>
<p>Sur ce point, le plugin est tr&egrave;s classique, les fichiers de cache sont stock&eacute;s sur le syst&egrave;me de fichiers (dans le dossier wp-rocket-cache), ce choix exclut &laquo;&nbsp;en grande majorit&eacute;&nbsp;&raquo; les architectures multi-serveurs (rare c&rsquo;est vrai). A noter, que le cache peut &ecirc;tre diff&eacute;renci&eacute; pour les mobiles, ceci afin de permettre l&rsquo;utilisation de th&egrave;me sp&eacute;cifique. (WP-Touch notamment)</p>
<p>Ma grande surprise concernant le cache statique et WP Rocket, c&rsquo;est la non-utilisation &nbsp;du &laquo;&nbsp;drop-in&nbsp;&raquo; <em>advanced-cache.php</em>. Ce fichier, que l&rsquo;on trouve g&eacute;n&eacute;ralement dans le dossier <em>wp-content </em>pour peu que votre installation WP utilise un plugin de cache statique &laquo;&nbsp;traditionnel&nbsp;&raquo; permet de g&eacute;rer la fonctionnalit&eacute; de cache statique assez t&ocirc;t dans l&rsquo;ex&eacute;cution de WordPress.</p>
<p>Ici, point de dropin advanced-cache.php, le plugin utilise exclusivement les r&egrave;gles de r&eacute;&eacute;critures (automatiquement ajout&eacute; dans le fichier .htaccess) pour permettre au serveur HTTP de charger les copies HTML en cache sans solliciter WordPress, ni PHP. C&rsquo;est la technique la plus performante, mais elle requiert l&rsquo;utilisation du serveur HTTP Apache2, cela veut dire que si votre serveur web est NGINX, ou bien Microsoft IIS, le plugin ne sollicitera jamais le cache statique g&eacute;n&eacute;r&eacute;.</p>
<p>Il faut donc pr&eacute;voir le fait que &laquo;&nbsp;WP-Rocket&nbsp;&raquo; apporte des restrictions suppl&eacute;mentaires par rapport aux pr&eacute;requis de WordPress. Ce qui est dommage, c&rsquo;est qu&rsquo;il est techniquement possible de cumuler les 2 fonctionnalit&eacute;s, c&rsquo;est notamment ce que r&eacute;alise l&rsquo;extension WP-Super-Cache, elle propose de servir les fichiers directement avec le serveur HTTP, et &agrave; d&eacute;faut elle utilise le dropin pour charger la copie en cache un peu plus tard dans le processus.</p>
<h3>LazyLoad</h3>
<p>La technique LazyLoad permet de diff&eacute;rer le chargement des images, pour r&eacute;sum&eacute;, seules les images affich&eacute;es r&eacute;ellement sur l&rsquo;&eacute;cran de vos internautes sont t&eacute;l&eacute;charg&eacute;es. Les images pr&eacute;sentes en base de page ne seront t&eacute;l&eacute;charg&eacute;es que si l&rsquo;utilisateur scroll dans son navigateur et affiche cette partie du site.</p>
<p>La fonctionnalit&eacute; agit uniquement sur le contenu des articles/pages, les widgets, les images &agrave; la une et les avatars. A noter que le code JS n&eacute;cessaire pour cette fonctionnalit&eacute; est ajout&eacute; automatiquement dans le code HTML de votre page afin d&rsquo;&eacute;viter de charger une ressource suppl&eacute;mentaire.</p>
<h3>Concat&eacute;nation &amp; Minification des JS/CSS</h3>
<p>La concat&eacute;nation et la minification des ressources JavaScript et des feuilles de style CSS permettent de diminuer le nombre de requ&ecirc;tes HTTP n&eacute;cessaires &agrave; l&rsquo;affichage d&rsquo;une page internet. Le plugin fait appel &agrave; la librairie PHP &laquo;&nbsp;minify&nbsp;&raquo;, &eacute;galement utilis&eacute; par le plugin WP-Minify/BWP-Minify.</p>
<p>C&rsquo;est donc une valeur s&ucirc;re en terme de technologie, par contre on ne peut &ecirc;tre que d&eacute;&ccedil;u que ce script ne g&eacute;n&egrave;re pas de vrais fichiers CSS/JS comme peut notamment le faire AssetsMinify ou W3 Total Cache.</p>
<p>Les requ&ecirc;tes vers les ressources JS/CSS font syst&eacute;matiquement appel &agrave; une ressource PHP, moins performante et qui peut s&rsquo;av&eacute;rer probl&eacute;matique &nbsp;lors de la mise en place d&rsquo;un CDN notamment.</p>
<h3>Compression, expiration des ressources statiques</h3>
<p>Le plugin affine largement la configuration du serveur web HTTP Apache2 (via le fichier .htaccess) en red&eacute;finissant les propri&eacute;t&eacute;s de mise en cache, de compression des ressources statiques, etc. Cela permet notamment de sp&eacute;cifier que les ressources JS/CSS doivent &ecirc;tre mises en cache par les navigateurs pour une dur&eacute;e d&eacute;finie, et non rafraichies &agrave; chaque visite de la page.</p>
<p>Les r&egrave;gles ajout&eacute;es sont tr&egrave;s r&eacute;pandues sur la toile, on peut notamment les retrouver dans le starter-kit de r&eacute;f&eacute;rence <a rel=\"nofollow\" target=\"_blank\" href=\"http://html5boilerplate.com/\">HTML5 Boilerplate</a>.</p>
<h3>Divers</h3>
<p>Enfin, je passe tr&egrave;s rapidement sur les autres fonctionnalit&eacute;s de l&rsquo;extension que je consid&egrave;re comme mineure ou comme trop &laquo;&nbsp;avanc&eacute;e&nbsp;&raquo; :</p>
<ul>
<li>Cookie de 3 minutes pour les personnes ayant post&eacute; un commentaire</li>
<li>JavaScript avec l&rsquo;attribut &laquo;&nbsp;deferred&nbsp;&raquo; + utilisation du script LABjs</li>
<li>Suppression des num&eacute;ros de version dans les ressources JS/CSS, notamment s&rsquo;il s&rsquo;agit du num&eacute;ro de version de WP</li>
<li>Sp&eacute;cification syst&eacute;matique des dimensions des images</li>
</ul>
<h2>La sp&eacute;cificit&eacute; du projet et de l&rsquo;extension : Le pr&eacute;chargement</h2>
<p>Une fonctionnalit&eacute; int&eacute;ressante du projet, c&rsquo;est le pr&eacute;chargement du cache. Au lieu de laisser vos visiteurs patienter lors de la g&eacute;n&eacute;ration des copies HTML et leur mise en cache, le plugin sollicite un robot qui parcourt pour vous les pages les plus visit&eacute;es de votre site pour pr&eacute;charger le cache.</p>
<p>Notez que ce n&rsquo;est pas une fonctionnalit&eacute; exclusive &agrave; ce projet, le plugin WP Super Cache propose d&eacute;j&agrave; la m&ecirc;me chose. (le c&ocirc;t&eacute; complexe et mystique en plus)</p>
<p>La diff&eacute;rence, c&rsquo;est que le robot n&rsquo;est pas int&eacute;gr&eacute; au plugin, le robot fonctionne depuis les serveurs de WP-Rocket et ce choix technique est contestable &agrave; mes yeux&nbsp;pour plusieurs points :</p>
<ul>
<li>Confidentialit&eacute;, un robot de cette nature fournit indirectement des donn&eacute;es concernant l&rsquo;activit&eacute; d&rsquo;un site internet</li>
<li>Confidentialit&eacute;, la pr&eacute;sence du robot indique aux auteurs l&rsquo;existence d&rsquo;un site</li>
<li>Restriction d&rsquo;utilisation, notamment dans la cadre d&rsquo;un site intranet, sans domaine public</li>
<li>Restriction d&rsquo;utilisation, notamment si le site est prot&eacute;g&eacute; par une authentification</li>
</ul>
<p>Alors effectivement, dans la cadre d&rsquo;un site personnel, d&rsquo;un site de TPE/PME, &ccedil;a peut sembler assez anodin. Mais en entreprise, ma cible pr&eacute;f&eacute;r&eacute;e pour WP, c&rsquo;est clairement un mode de fonctionnement&nbsp;r&eacute;dhibitoire.&nbsp;Par ailleurs, la fonctionnalit&eacute; n&rsquo;est pas d&eacute;sactivable&hellip;</p>
<p>Dans le m&ecirc;me th&egrave;me, l&rsquo;obligation de d&eacute;clarer chaque site utilisant le plugin via le site officiel est quelque chose d&rsquo;assez contraignant. Sur ce point, le mode de fonctionnement de la licence GravityForms est bien plus pratique. Bien heureusement, le contr&ocirc;le de licence est facilement d&eacute;sactivable dans le code source PHP&hellip;</p>
<h2>Benchmark : Avant/Apr&egrave;s ?</h2>
<p>Pour &ecirc;tre tout &agrave; fait honn&ecirc;te, j&rsquo;avais initialement pr&eacute;vu d&rsquo;afficher les notes GTmetrix avant et apr&egrave;s.Mais je suis revenu sur ma d&eacute;cision car l&rsquo;impact de l&rsquo;extension sur les performances d&rsquo;un site WordPress d&eacute;pend d&rsquo;un trop grand nombre de param&egrave;tres, notamment :</p>
<ul>
<li>L&rsquo;h&eacute;bergement, le plugin peut tr&egrave;s bien fonctionner chez un prestataire A, et avoir un effet quasiment nul chez un prestataire B, notamment s&rsquo;il manque des modules Apache2&hellip;</li>
<li>Le projet, le th&egrave;me et les extensions utilis&eacute;s, le plugin fonctionne parfaitement avec une installation fraiche, mais il peut g&eacute;n&eacute;rer des conflits JS important sur les th&egrave;mes premiums ou les projets &laquo;&nbsp;complexes&nbsp;&raquo;&hellip;</li>
</ul>
<p>Personnellement, j&rsquo;ai rencontr&eacute; des probl&egrave;mes de minification sur les 2 sites de production que j&rsquo;ai test&eacute;, et une fois les probl&egrave;mes r&eacute;solus (ou plut&ocirc;t contourn&eacute;) j&rsquo;ai constat&eacute; une baisse de la notation GTmetrix, malgr&eacute; une am&eacute;lioration de l&rsquo;impression de fluidit&eacute;&hellip;</p>
<p>Mais tout cela n&rsquo;est gu&egrave;re &eacute;tonnant car ces projets n&rsquo;ont pas &eacute;t&eacute; b&acirc;tis autour de cette extension, il n&rsquo;est donc pas anormal de rencontrer des conflits de cette nature&hellip;</p>
<p>En conclusion de ce chapitre, je pense que le benchmark comparatif de la performance des plugins de cache est possible, mais il ne refl&egrave;te absolument pas le niveau de performance que vous allez pouvoir obtenir sur votre installation.</p>
<p>Par exp&eacute;rience, selon les projets et les plugins utilis&eacute;s, j&rsquo;obtiens parfois de meilleurs r&eacute;sultats avec Hyper-Cache, et parfois c&rsquo;est WP-Super-Cache qui fait des merveilles&hellip;</p>
<h2>Faut-il passer son chemin ?</h2>
<p>C&rsquo;est une question tr&egrave;s compliqu&eacute;e et la r&eacute;ponse varie selon moi d&rsquo;apr&egrave;s votre niveau technique et l&rsquo;environnement technique de votre projet.</p>
<p>On peut d&eacute;j&agrave; &eacute;liminer WP-Rocket dans les situations suivantes :</p>
<ul>
<li>Utilisation d&rsquo;un serveur web exotique NGINX, Cherokee, Microsoft IIS</li>
<li>Utilisation d&rsquo;un reverse-proxy avec Varnish ou autre (bien que compatible)</li>
<li>Architecture multi-serveur, car le cache aura davantage sa place dans un cache objet (cf Batcache)</li>
<li>Projet d&rsquo;intranet</li>
</ul>
<p>Dans ce type d&rsquo;environnement, vous n&rsquo;utiliserez pas 100% des fonctionnalit&eacute;s de WP-Rocket et d&rsquo;autres solutions sont &agrave; consid&eacute;rer &agrave; mon avis.</p>
<p>Ensuite&hellip;</p>
<h3>Si vous &ecirc;tes fauch&eacute;s/radins/amateurs des logiciels libres et que vous &ecirc;tes un bidouilleur :<br>
ou<br>
Si vous &ecirc;tes un professionnel/d&eacute;veloppeur WP :</h3>
<p>Passez votre chemin, et installez le jeu d&rsquo;extensions suivant</p>
<ul>
<li>LazyLoad</li>
<li>WP Super Cache ou HyperCache</li>
<li>BWP Minify</li>
<li>+ fichier HTACCESS optimis&eacute; en s&rsquo;inspirant des r&egrave;gles de HTML5 Boilerplate</li>
</ul>
<p>Vous obtiendrez un p&eacute;rim&egrave;tre fonctionnel tr&egrave;s proche de WP-Rocket, des plugins interchangeables et un niveau de performances comparable.</p>
<h3>Si vous n&rsquo;aviez rien compris &agrave; cet article, que vous n&rsquo;&ecirc;tes pas un bidouilleur ou que vous n&rsquo;avez tout simplement pas de temps &agrave; investir dans la performance web :</h3>
<p>Vous devriez probablement ouvrir un blog sur WordPress.com ou &agrave; d&eacute;faut prendre une licence WP-Rocket car, et c&rsquo;est ma conclusion,<strong> WP-Rocket ce n&rsquo;est pas simplement une extension de plus &agrave; installer, c&rsquo;est &eacute;galement un service de support (gratuit le temps de la licence &ndash; 1 an) pour vous assister &agrave; la bonne mise en place de la solution.</strong></p>
<p>Pour avoir fait un tour rapide dans le forum de support, on constate qu&rsquo;une extension de cette nature g&eacute;n&egrave;re beaucoup de discussion, car chaque installation est unique et les bugs rencontr&eacute;s sont &agrave; g&eacute;rer au cas par cas.</p>
<p>Et donc m&ecirc;me si je ne partage pas l&rsquo;ensemble des choix techniques r&eacute;alis&eacute;s, et que je pr&eacute;f&egrave;re une solution compos&eacute;e de diff&eacute;rentes extensions, je tiens juste &agrave; tirer mon chapeau pour le travail de support que r&eacute;alise les auteurs de cette belle et prometteuse extension.</p>
<h3>Ma wishlist</h3>
<p>Assez courte :</p>
<ul>
<li>Support de advanced-cache.php</li>
<li>Documentation &eacute;largie au serveur HTTP Nginx</li>
<li>Robot de pr&eacute;chargement, en local ou &agrave; distance (au choix)</li>
<li>Am&eacute;lioration du moteur de minification
<ul>
<li>Notamment le filtre d&rsquo;exclusion, qui exclut bien le JS de la minification mais qui le d&eacute;place tout de m&ecirc;me dans l&rsquo;ent&ecirc;te de la page :(</li>
<li>Utilisation de AssetsMinify ?</li>
</ul>
</li>
<li>Suppression du contr&ocirc;le de licence en mode GravityForms (utilis&eacute; pour les MAJ)</li>
</ul>
</div>\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:14;a:6:{s:4:\"data\";s:68:\"
    \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:52:\"Insidedaweb : Les Solutions Flipbooks pour WordPress\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:2:\"id\";a:1:{i:0;a:5:{s:4:\"data\";s:152:\"http://www.insidedaweb.com/wordpress-seo/solutions-flipbooks-wordpress/?utm_source=rss&amp;utm_medium=rss&amp;utm_campaign=solutions-flipbooks-wordpress\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:3:\"rel\";s:9:\"alternate\";s:4:\"href\";s:152:\"http://www.insidedaweb.com/wordpress-seo/solutions-flipbooks-wordpress/?utm_source=rss&amp;utm_medium=rss&amp;utm_campaign=solutions-flipbooks-wordpress\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"published\";a:1:{i:0;a:5:{s:4:\"data\";s:25:\"2013-06-12T11:29:47+00:00\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:6:\"author\";a:1:{i:0;a:6:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"name\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"anonymous\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}s:7:\"content\";a:1:{i:0;a:5:{s:4:\"data\";s:652:\"<div>
<p>Aujourd&rsquo;hui, les magazines ou les brochures feuilletables en ligne sont de plus en plus pr&eacute;sents sur la toile, en effet, cela constitue une solution avantageuse pour diffuser vos documents, PDF et autres e-books. Les flipbooks sont [&hellip;]</p>
<p>Cet article <a rel=\"nofollow\" target=\"_blank\" href=\"http://www.insidedaweb.com/wordpress-seo/solutions-flipbooks-wordpress/\">Les Solutions Flipbooks pour WordPress</a> est apparu en premier sur <a rel=\"nofollow\" target=\"_blank\" href=\"http://www.insidedaweb.com/\">Blog WordPress, Blog eCommerce, Blog R&eacute;f&eacute;rencement, Emailing &amp; FOSS. Le site 5 en 1</a>.</p>
</div>\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:15;a:6:{s:4:\"data\";s:68:\"
    \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:65:\"Fran6art : Ma veille en détails: historique et outils utilisés.\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:2:\"id\";a:1:{i:0;a:5:{s:4:\"data\";s:61:\"http://feedproxy.google.com/~r/Fran6artLeBlog/~3/SqxjR5rCujU/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:3:\"rel\";s:9:\"alternate\";s:4:\"href\";s:61:\"http://feedproxy.google.com/~r/Fran6artLeBlog/~3/SqxjR5rCujU/\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"published\";a:1:{i:0;a:5:{s:4:\"data\";s:25:\"2012-10-16T14:35:34+00:00\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:6:\"author\";a:1:{i:0;a:6:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"name\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"anonymous\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}s:7:\"content\";a:1:{i:0;a:5:{s:4:\"data\";s:14479:\"<div>
<p>Hier, mon ami Aur&eacute;lien d&rsquo;All For Design a publi&eacute; <a rel=\"nofollow\" target=\"_blank\" href=\"http://all-for-design.com/web-design/les-outils-de-ma-veille/\">un article int&eacute;ressant</a> expliquant comment il fait sa veille. Je trouve la d&eacute;marche tr&egrave;s int&eacute;ressante parce qu&rsquo;elle permet &agrave; tous de d&eacute;couvrir de nouveaux sites et de nouveaux services. On faisait pas mal &ccedil;a il y a quelques ann&eacute;es, quand le blogging &eacute;tait &agrave; son apog&eacute;e et on a un peu perdu cette habitude. Donc j&rsquo;ai voulu, d&rsquo;une certaine mani&egrave;re, r&eacute;pondre &agrave; Aur&eacute;lien en d&eacute;taillant ici comment je fais ma veille en ligne, comment je me tiens au courant des news du web.</p>
<p><strong>Une question de temps</strong></p>
<p>Je crois qu&rsquo;avant de d&eacute;tailler comment je fais ma veille, il est important de parler du facteur temps. Quand j&rsquo;ai commenc&eacute; ce m&eacute;tier en 2005, je blogais beaucoup, je faisais &eacute;norm&eacute;ment de veille. Je &ldquo;veillais&rdquo; jusqu&rsquo;&agrave; 4/5 heures par jour &agrave; ce moment-l&agrave;. Puis ma fille est arriv&eacute;e en 2007, et tout &agrave; chang&eacute;. La charge de travail a aussi consid&eacute;rablement augment&eacute;, me laissant peu de temps pour me tenir au courant chaque jour.</p>
<p>Jusqu&rsquo;&agrave; il y a peu, ma veille &eacute;tait tr&egrave;s simple. Elle se limitait &agrave; Google Reader et &agrave; <a rel=\"nofollow\" target=\"_blank\" href=\"https://twitter.com/Fran6/\">Twitter</a>. Un grand classique. J&rsquo;ai fait le tri dans mes flux RSS mais sans parvenir &agrave; tout lire r&eacute;guli&egrave;rement. Je me retrouvais donc avec plus de 1000 &eacute;l&eacute;ments &agrave; lire par moment et au final des articles datant de trop longtemps, sans oublier parfois le fait que je les avais d&eacute;j&agrave; vu passer sur Twitter.</p>
<p>J&rsquo;ai donc laiss&eacute; de c&ocirc;t&eacute; les flux RSS et ai d&eacute;cid&eacute; de concentrer ma veille <a rel=\"nofollow\" target=\"_blank\" href=\"https://twitter.com/Fran6/\">sur Twitter</a>. Je suis 500 personnes environ et j&rsquo;ai d&eacute;j&agrave; l&rsquo;impression que c&rsquo;est trop. Aur&eacute;lien, dans son article, parle de veille passive et active. La veille sur Twitter est un m&eacute;lange des deux pour moi. Elle est active parce que je suis l&agrave; pour &ccedil;a mais aussi passive parce que je lis globalement ce qui se dit sans pour autant faire vraiment de la veille. J&rsquo;esp&egrave;re que je me fais bien comprendre ! <img src=\"http://www.fran6art.com/wp-includes/images/smilies/icon_biggrin.gif\" alt=\":D\" class=\"wp-smiley\"></p>
<p>Mais l&agrave; encore, je ne suis pas toujours dispo, donc je loupe pas mal de choses int&eacute;ressantes aussi sur Twitter. L&rsquo;info y file &agrave; une vitesse grand V.</p>
<p><strong>L&rsquo;iPad, compagnon id&eacute;al pour la veille</strong></p>
<p>Tout a commenc&eacute; &agrave; changer quand je me suis achet&eacute; un iPad. Je lis &eacute;norm&eacute;ment de livres mais tr&egrave;s peu sur ordinateur. Du coup, la tablette &eacute;tait le bon compromis pour faire de la veille sans pour autant &ecirc;tre bloqu&eacute; derri&egrave;re mon bureau. J&rsquo;avais Twitter en poche, puis j&rsquo;ai adopt&eacute; <a rel=\"nofollow\" target=\"_blank\" href=\"http://www.instapaper.com/\">Instapaper</a>. Super outil pour bookmarker des articles et les lire plus tard. <strong>A condition de les lire plus tard</strong>. J&rsquo;ai accumul&eacute;, tout comme je faisais auparavant avec mes flux RSS et je n&rsquo;arrivais pas &agrave; m&rsquo;en sortir. J&rsquo;ai donc &ldquo;ressorti&rdquo; aussi mes flux RSS en nettoyant bien la liste des sites que je suivais pour me concentrer sur le principal: A list Apart, Smashing Magazine, quelques sites d&rsquo;UX, beaucoup de typo et puis basta. J&rsquo;ai donc utilis&eacute; <a rel=\"nofollow\" target=\"_blank\" href=\"http://reederapp.com/\">Reeder</a>, comme pas mal de monde sur OSX ou iOS, mais l&rsquo;exp&eacute;rience utilisateur pour moi s&rsquo;est av&eacute;r&eacute;e pas terrible. Je trouve que Reeder est tr&egrave;s chouette au niveau style mais n&rsquo;apporte pas vraiment grand chose de plus que Google Reader comme exp&eacute;rience. Ca reste un lecteur plut&ocirc;t classique de flux RSS.</p>
<p>J&rsquo;ai donc test&eacute; <a rel=\"nofollow\" target=\"_blank\" href=\"http://flipboard.com/\">Flipboard</a>. Super interface, on peut y ajouter nos flux RSS. Pr&eacute;sentation magazine, c&rsquo;&eacute;tait g&eacute;nial. J&rsquo;en ai profit&eacute; pour utiliser l&rsquo;application pour d&rsquo;autres sites que mes flux RSS. J&rsquo;y ai m&ecirc;me install&eacute; Twitter pour tout faire au m&ecirc;me endroit. Super exp&eacute;rience mais un souci. Flipboard ne marque pas tr&egrave;s bien les articles lus sur Google Reader. C&rsquo;est un peu p&eacute;nible &agrave; g&eacute;rer. Si on trouve des articles qui nous int&eacute;ressent pas, il faut quand m&ecirc;me les ouvrir pour les &ldquo;marquer comme lu&rdquo;.</p>
<p>Puis sont apparus trois outils qui ont consid&eacute;rablement chang&eacute; ma mani&egrave;re de faire de la veille.</p>
<p><strong>1. Zite</strong></p>
<p><img class=\"aligncenter size-medium wp-image-1920\" title=\"ipad\" src=\"http://www.fran6art.com/wp-content/uploads/2012/10/ipad-470x396.png\" alt=\"\" width=\"470\" height=\"396\"></p>
<p><a rel=\"nofollow\" target=\"_blank\" href=\"http://zite.com/\">Zite</a> est une application qui vous propose toutes une s&eacute;ries d&rsquo;articles, selon ce qui vous int&eacute;resse. En gros et pour faire simple, vous commencez avec une s&eacute;rie d&rsquo;articles, propos&eacute;s un peu &agrave; la mani&egrave;re de Flipboard et &agrave; chaque fois vous avez la possibilit&eacute; de dire si vous avez aim&eacute; l&rsquo;article ou pas, et l&rsquo;application vous proposera plus d&rsquo;articles que vous aimez. Plus vous allez lire, plus les articles propos&eacute;s seront proches de ce que vous voulez lire. Et pas besoin de compte Google Reader ici, les sources sont propos&eacute;es par l&rsquo;application. Donc pas de stress inutile &agrave; devoir lire &ldquo;500 &eacute;l&eacute;ments non lus&rdquo;. Les articles sont globalement de tr&egrave;s bonne qualit&eacute; et correspondent aux nouveaut&eacute;s du jour. Tr&egrave;s int&eacute;ressant quand vous avez un moment tranquille sur votre sofa, on a un peu l&rsquo;impression de lire son journal sans trop savoir sur quoi on va tomber.</p>
<p><strong>2. Feedly</strong></p>
<p> 
</p>
<p><a rel=\"nofollow\" target=\"_blank\" href=\"http://vimeo.com/49048256\">The New Feedly Mobile</a> from <a rel=\"nofollow\" target=\"_blank\" href=\"http://vimeo.com/feedly\">Feedly</a> on <a rel=\"nofollow\" target=\"_blank\" href=\"http://vimeo.com/\">Vimeo</a>.</p>
<p>J&rsquo;avais test&eacute; l&rsquo;application &agrave; ses d&eacute;buts mais c&rsquo;&eacute;tait trop bugg&eacute; pour moi et pas assez original par rapport &agrave; un Flipboard. Sauf que les derni&egrave;res versions se sont fortement am&eacute;lior&eacute;s et qu&rsquo;elles apportent quelques nouveaut&eacute;s qui ont chang&eacute; pas mal de choses pour moi.</p>
<p>Pour ceux qui ne connaissent pas, Feedly est un lecteur de flux RSS, mais pas seulement, il propose maintenant des flux un peu comme Flipboard.</p>
<p>Le souci de Feedly au d&eacute;part &eacute;tait le m&ecirc;me, justement, que pour Flipboard. Que faire quand on tombe sur une page avec des articles qu&rsquo;on n&rsquo;a pas trop envie de lire ? Il faut les ouvrir un par un pour les marquer comme lu. Relou. Avec la derni&egrave;re version de Feedly, un swipe vers le bas sur la page marque les articles comme lus. Tip top et super simple. Je lis quelques blogs Apple ou encore The Verge et chez certains la quantit&eacute; d&rsquo;articles r&eacute;dig&eacute;s par jour est &eacute;norme et seulement une partie m&rsquo;int&eacute;resse. Avec Reeder, j&rsquo;aurais vir&eacute; le flux parce que &ccedil;a aurait rapidement &eacute;t&eacute; impossible &agrave; g&eacute;rer ou j&rsquo;aurais tout marqu&eacute; comme lu. Avec Feedly, je parcoure les pages rapidement sur mon iPad, je swipe vers le bas pour marquer comme lu chaque page et je lis ce qui m&rsquo;int&eacute;resse. Ca peut para&icirc;tre con mais j&rsquo;ai retrouv&eacute; le plaisir de lire mes flux RSS de mani&egrave;re simple et intuitive gr&acirc;ce &agrave; une tr&egrave;s belle mise en page magazine. J&rsquo;en profite aussi pour bookmarker certains articles sur <a rel=\"nofollow\" target=\"_blank\" href=\"http://http://getpocket.com\">Pocket</a> ( qui a remplac&eacute; Instapaper chez moi&hellip;) m&ecirc;me si comme expliqu&eacute; plus haut, je bookmarke plus que je lis.</p>
<p>En fait, le souci de ces outils comme Pocket, Instapaper ou m&ecirc;me Google Reader, c&rsquo;est que la plupart du temps, il est publi&eacute; plus d&rsquo;articles qu&rsquo;on ne peut en lire. Donc c&rsquo;est vou&eacute; &agrave; l&rsquo;&eacute;chec d&rsquo;une certaine mani&egrave;re, sauf si on a du temps&hellip;</p>
<p><strong>3. Pinterest</strong></p>
<p><img class=\"aligncenter size-medium wp-image-1921\" title=\"Pinterest-application-ipad-android\" src=\"http://www.fran6art.com/wp-content/uploads/2012/10/Pinterest-application-ipad-android-470x310.jpg\" alt=\"\" width=\"470\" height=\"310\"></p>
<p>Pendant un moment j&rsquo;ai utilis&eacute; <a rel=\"nofollow\" target=\"_blank\" href=\"https://gimmebar.com/\">Gimme Bar</a> comme Aur&eacute;lien pour toute une veille graphique: sites web, UX, illustration, photo et typo. Mais j&rsquo;ai quelques soucis avec l&rsquo;interface et pour bookmarker des sites web, j&rsquo;utilise Little Snapper, malgr&eacute; que ce soit loin d&rsquo;&ecirc;tre parfait. Gimme Bar a pourtant sorti une application iPhone mais elle n&rsquo;est m&ecirc;me pas finalis&eacute;, certaines fonctions affichent m&ecirc;me un message &ldquo;Coming soon&rdquo;. Super.</p>
<p>Et il y a quelques semaines, je d&eacute;cide de rouvrir <a rel=\"nofollow\" target=\"_blank\" href=\"http://pinterest.com/fran6/\">mon compte Pinterest</a> et d&rsquo;aller &agrave; la p&ecirc;che aux id&eacute;es. Et l&agrave;, je suis surpris par la qualit&eacute; des &eacute;l&eacute;ments partag&eacute;s. C&rsquo;est clair que si vous y recherchez des exemples de sites web, vous allez &ecirc;tre d&eacute;&ccedil;us. Il y en a mais pas des masses. Par contre, pour toute autre inspiration, je trouve le site g&eacute;nial.</p>
<p>D&eacute;j&agrave; il y a une communaut&eacute; impressionnante. Difficile m&ecirc;me parfois de suivre plus de 100 personnes tellement certaines proposent de nouvelles choses constamment. Les &eacute;l&eacute;ments que je pr&eacute;f&egrave;re sont bien entendu <a rel=\"nofollow\" target=\"_blank\" href=\"http://pinterest.com/fran6/typography/\">la typographie</a>. Je n&rsquo;ai encore trouv&eacute; nulle part ailleurs autant de choses de qualit&eacute; et aussi diversifi&eacute;es. Vraiment sympa. Pas mal d&rsquo;inspiration au niveau d&eacute;co d&rsquo;int&eacute;rieur, print, illustration &eacute;galement. Personnellement, j&rsquo;aime aussi aller chercher parfois mon inspiration dans des domaines qui sont plus annexes &agrave; mon activit&eacute;. Et l&agrave; j&rsquo;y trouve mon compte.</p>
<p>Un gros plus de Pinterest c&rsquo;est aussi ses applications iPhone et iPad. O&ugrave; que je sois, je peux acc&eacute;der &agrave; ce que j&rsquo;ai bookmark&eacute;. Et tout comme Zite par exemple, ici pas de chiffre d&rsquo;&eacute;l&eacute;ments non lus. On surfe sur une page, on peut naviguer aussi selon des cat&eacute;gories. Encore une super veille &agrave; faire sur son sofa en soir&eacute;e ! <img src=\"http://www.fran6art.com/wp-includes/images/smilies/icon_wink.gif\" alt=\";-)\" class=\"wp-smiley\"></p>
<p>Enfin, c&rsquo;est aussi un r&eacute;seau social donc vous pouvez suivre vos amis ou vos designers pr&eacute;f&eacute;r&eacute;s.</p>
<p>Donc, vous l&rsquo;aurez s&ucirc;rement compris, je fais maintenant principalement ma veille sur tablette et quasiment plus sur mon ordinateur. Je suis aussi plus disponible pour faire cette veille via la tablette. Sur l&rsquo;ordi, je ne consulte que Twitter au final, sur lequel je retweete pas mal de choses que je d&eacute;couvre. Le reste se fait le matin apr&egrave;s le petit dej ou le soir apr&egrave;s le travail. Je consulte rapidement Zite, puis Pinterest et d&eacute;roule mes flux RSS. Et si j&rsquo;ai le temps je lis quelques articles bookmark&eacute;s sur Pocket. C&rsquo;est tout, mais c&rsquo;est d&eacute;j&agrave; pas mal, vu le temps qui m&rsquo;est donn&eacute;. Je dois avouer que l&rsquo;iPad joue un r&ocirc;le important ici. Sans lui je ne ferai probablement plus beaucoup de veille. A noter aussi que toujours gr&acirc;ce &agrave; ma tablette, je suis plus productif dans la journ&eacute;e car je ne fais pas vraiment de veille, hormis un peu Twitter.</p>
<p>Voici donc ma m&eacute;thode pour suivre l&rsquo;actu web. Largement moins d&rsquo;outils et de services qu&rsquo;Aur&eacute;lien mais plut&ocirc;t un retour d&rsquo;exp&eacute;rience comme j&rsquo;aime les faire. Je ne dis pas non plus que c&rsquo;est la mani&egrave;re id&eacute;ale de faire de la veille mais c&rsquo;est celle qui me correspond le mieux, avec le temps qui m&rsquo;est donn&eacute; <img src=\"http://www.fran6art.com/wp-includes/images/smilies/icon_smile.gif\" alt=\":)\" class=\"wp-smiley\"></p>
<p>N&rsquo;h&eacute;sitez pas &agrave; partager vos m&eacute;thodes pour faire de la veille, que ce soit en commentaire ou, comme j&rsquo;ai pu le faire apr&egrave;s la lecture de l&rsquo;article d&rsquo;Aur&eacute;lien, sur votre blog, si vous en avez un ! <img src=\"http://www.fran6art.com/wp-includes/images/smilies/icon_smile.gif\" alt=\":)\" class=\"wp-smiley\"></p>
<div class=\"feedflare\">
<a rel=\"nofollow\" target=\"_blank\" href=\"http://feeds.feedburner.com/~ff/Fran6artLeBlog?a=SqxjR5rCujU:k3kXTXqFDpU:D7DqB2pKExk\"><img src=\"http://feeds.feedburner.com/~ff/Fran6artLeBlog?i=SqxjR5rCujU:k3kXTXqFDpU:D7DqB2pKExk\" border=\"0\"></a> <a rel=\"nofollow\" target=\"_blank\" href=\"http://feeds.feedburner.com/~ff/Fran6artLeBlog?a=SqxjR5rCujU:k3kXTXqFDpU:guobEISWfyQ\"><img src=\"http://feeds.feedburner.com/~ff/Fran6artLeBlog?i=SqxjR5rCujU:k3kXTXqFDpU:guobEISWfyQ\" border=\"0\"></a>
</div>
<img src=\"http://feeds.feedburner.com/~r/Fran6artLeBlog/~4/SqxjR5rCujU\" height=\"1\" width=\"1\">
</div>\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:16;a:6:{s:4:\"data\";s:68:\"
    \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:6:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:57:\"Webinventif : [Wordpress] iTypo: thème WordPress gratuit\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:2:\"id\";a:1:{i:0;a:5:{s:4:\"data\";s:33:\"http://www.webinventif.com/itypo/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:2:{s:3:\"rel\";s:9:\"alternate\";s:4:\"href\";s:33:\"http://www.webinventif.com/itypo/\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"published\";a:1:{i:0;a:5:{s:4:\"data\";s:25:\"2011-01-01T15:36:24+00:00\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:6:\"author\";a:1:{i:0;a:6:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"name\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"anonymous\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}s:7:\"content\";a:1:{i:0;a:5:{s:4:\"data\";s:5703:\"<div>
<p>Apr&egrave;s 1 an sans rien avoir &eacute;crit sur ce blog (bouhhhh), je fais mon retour en vous proposant un joli <strong>th&egrave;me WordPress</strong>, le 1er que je distribue publiquement et <em>gratuitement</em> &eacute;videment. D\'ailleurs si les retours sont bons, &ccedil;a sera peut-&ecirc;tre le d&eacute;but d\'une s&eacute;rie plus ou moins longue.</p>
<p><strong>iTypo</strong> est donc un th&egrave;me WordPress simple et l&eacute;ger. A la base, je cherchais un th&egrave;me pour un petit projet personnel qui soit assez &eacute;pur&eacute; et l&eacute;ger pour mettre le contenu du blog en avant. Apr&egrave;s de nombreuses recherches dans la jungle des th&egrave;mes WordPress, rien ne me convenait, et comme j\'avais une id&eacute;e tr&egrave;s pr&eacute;cise de ce que je voulais, j\'ai fini par le faire moi-m&ecirc;me ... l\'histoire classique de la naissance d\'un th&egrave;me en somme <img src=\"http://www.webinventif.com/wp-includes/images/smilies/icon_wink.gif\" alt=\";)\" class=\"wp-smiley\" title=\"Icon Wink\"></p>
<p>Il est &eacute;videment \"Widgets ready\", compatible WordPress 3.0+, disponible en 4 coloris et 6 langues, convient parfaitement pour un blog perso ou un mini blog \"&agrave; la Tumblr\" et est absolument gratuit. <img src=\"http://www.webinventif.com/wp-includes/images/smilies/icon_wink.gif\" alt=\";)\" class=\"wp-smiley\" title=\"Icon Wink\"></p>
<p><img src=\"http://www.webinventif.com/wp-content/uploads/2011/01/pres1.png\" alt=\"pres1\" title=\"pres\" class=\"aligncenter wp-image-621\"></p>
<h3>Telecharger le th&egrave;me gratuitement</h3>
<p> iTypo est sous license GPL, vous pouvez l\'utiliser gratuitement pour vos projets sans restrictions</p>
<p><a rel=\"nofollow\" target=\"_blank\" href=\"http://www.webinventif.com/wp-content/uploads/2011/01/capture-complete.png\"><img src=\"http://www.webinventif.com/wp-content/uploads/2011/01/capture-complete-300x740.png\" alt=\"capture-complete-300x740\" title=\"capture-complete\" class=\"alignnone size-medium wp-image-610\"></a></p>
<ul>
<li><a rel=\"nofollow\" target=\"_blank\" href=\"http://themes.webinventif.fr/\">Live demo</a></li>
<li><a rel=\"nofollow\" target=\"_blank\" href=\"http://www.webinventif.com/wp-content/uploads/2011/01/capture-complete.png\">Large preview (.png, 310 Kb)</a></li>
<li><a rel=\"nofollow\" target=\"_blank\" href=\"http://www.webinventif.com/itypo/2/\">Guide d\'installation et documentation</a></li>
<li><a rel=\"nofollow\" target=\"_blank\" href=\"http://goo.gl/H7i2c\">Telecharger le .zip, v1.0.2 (zip, 353 kb)</a></li>
</ul>
<h3>Features</h3>
<ul>
<li>Widget ready (footer et sidebar)</li>
<li>Disponible par d&eacute;faut en 6 langues (et plus si n&eacute;cessaire)</li>
<li>4 coloris diff&eacute;rents</li>
<li>Facile &agrave; personnaliser avec sa page d\'options</li>
<li>Choix de miniatures carr&eacute; ou large sur la page d\'accueil</li>
<li>Choix d\'afficher un extrait ou le post complet sur la home</li>
<li>WordPress Post Thumbnail activ&eacute;</li>
<li>Shortcode pour miniature</li>
<li>JQuery Colorbox Lightbox (d&eacute;sactivable via la page d\'options)</li>
<li>\"Sticky post\" skinn&eacute;</li>
<li>Commentaires en \"thread\" (discussion)</li>
<li>Mise en avant de l\'auteur</li>
<li>Logo personnalisable (upload d\'image)</li>
<li>Compatible Feedburner</li>
<li>WP-pagenavi ready</li>
<li>Et encore plein de petites choses ...</li>
</ul>
<h3>Captures</h3>
<p><a rel=\"nofollow\" target=\"_blank\" href=\"http://www.webinventif.com/wp-content/uploads/2011/01/commentaires.png\"><img src=\"http://www.webinventif.com/wp-content/uploads/2011/01/commentaires-300x894.png\" alt=\"commentaires-300x894\" title=\"commentaires\" class=\"alignnone size-medium wp-image-615\"></a><br>
(Commentaires)</p>
<p><a rel=\"nofollow\" target=\"_blank\" href=\"http://www.webinventif.com/wp-content/uploads/2011/01/avec-thumb-wide.png\"><img src=\"http://www.webinventif.com/wp-content/uploads/2011/01/avec-thumb-wide-300x823.png\" alt=\"avec-thumb-wide-300x823\" title=\"avec-thumb-wide\" class=\"alignnone size-medium wp-image-609\"></a><br>
(Avec miniatures larges)</p>
<p><a rel=\"nofollow\" target=\"_blank\" href=\"http://www.webinventif.com/wp-content/uploads/2011/01/capture-complet-fullpost.png\"><img src=\"http://www.webinventif.com/wp-content/uploads/2011/01/capture-complet-fullpost-300x1932.png\" alt=\"capture-complet-fullpost-300x1932\" title=\"capture-complet-fullpost\" class=\"alignnone size-medium wp-image-614\"></a><br>
(Accueil avec les billets complets)</p>
<p><a rel=\"nofollow\" target=\"_blank\" href=\"http://www.webinventif.com/wp-content/uploads/2011/01/options-page.png\"><img src=\"http://www.webinventif.com/wp-content/uploads/2011/01/options-page-300x192.png\" alt=\"options-page-300x192\" title=\"options-page\" class=\"alignnone size-medium wp-image-618\"></a><br>
(Page d\'options)</p>
<p><a rel=\"nofollow\" target=\"_blank\" href=\"http://www.webinventif.com/wp-content/uploads/2011/01/upload-logo.png\"><img src=\"http://www.webinventif.com/wp-content/uploads/2011/01/upload-logo-300x202.png\" alt=\"upload-logo-300x202\" title=\"upload-logo\" class=\"alignnone size-medium wp-image-619\"></a><br>
(Page d\'upload de logo)</p>
<p>A noter que m&ecirc;me si il fonctionne sous IE6 et sup&eacute;rieur, certains effets CSS3 ne seront pas pris en charge (typographie personnalis&eacute;e, ombres, inclinaisons, ...)</p>
<p>Voil&agrave;, en esp&eacute;rant qu\'il plaira &agrave; certains d\'entre vous ! Comme je le distribue gratuitement, il serait fairplay de laisser les cr&eacute;dits du footer <img src=\"http://www.webinventif.com/wp-includes/images/smilies/icon_wink.gif\" alt=\";)\" class=\"wp-smiley\" title=\"Icon Wink\"></p>
<img src=\"http://www.webinventif.com/wp-content/plugins/mycompteur/compte.php?idpage=608\" width=\"0\" height=\"0\" alt=\"\" title=\"\">
</div>\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:4:\"type\";s:4:\"html\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}}s:42:\"http://rssnamespace.org/feedburner/ext/1.0\";a:1:{s:4:\"info\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:3:\"uri\";s:26:\"wordpressfrancophoneplanet\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}}}s:4:\"type\";i:512;s:7:\"headers\";a:10:{s:12:\"content-type\";s:23:\"text/xml; charset=UTF-8\";s:4:\"etag\";s:27:\"BLVMUdUcLs1tHdd5tgJO823TL1o\";s:13:\"last-modified\";s:29:\"Sun, 15 Dec 2013 21:39:52 GMT\";s:4:\"date\";s:29:\"Sun, 15 Dec 2013 21:55:22 GMT\";s:7:\"expires\";s:29:\"Sun, 15 Dec 2013 21:55:22 GMT\";s:13:\"cache-control\";s:18:\"private, max-age=0\";s:22:\"x-content-type-options\";s:7:\"nosniff\";s:16:\"x-xss-protection\";s:13:\"1; mode=block\";s:6:\"server\";s:3:\"GSE\";s:18:\"alternate-protocol\";s:7:\"80:quic\";}s:5:\"build\";s:14:\"20130911030210\";}","no");
INSERT INTO wp_options VALUES("308","_transient_timeout_feed_mod_2fb9572e3d6a42f680e36370936a57ae","1387187708","no");
INSERT INTO wp_options VALUES("309","_transient_feed_mod_2fb9572e3d6a42f680e36370936a57ae","1387144508","no");
INSERT INTO wp_options VALUES("310","_transient_timeout_feed_b9388c83948825c1edaef0d856b7b109","1387187708","no");
INSERT INTO wp_options VALUES("311","_transient_feed_b9388c83948825c1edaef0d856b7b109","a:4:{s:5:\"child\";a:1:{s:0:\"\";a:1:{s:3:\"rss\";a:1:{i:0;a:6:{s:4:\"data\";s:3:\"
\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:7:\"version\";s:3:\"2.0\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:0:\"\";a:1:{s:7:\"channel\";a:1:{i:0;a:6:{s:4:\"data\";s:72:\"
	\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:2:{s:0:\"\";a:7:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:39:\"WordPress Plugins » View: Most Popular\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:44:\"http://wordpress.org/plugins/browse/popular/\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:39:\"WordPress Plugins » View: Most Popular\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:8:\"language\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"en-US\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Sun, 15 Dec 2013 21:36:27 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:9:\"generator\";a:1:{i:0;a:5:{s:4:\"data\";s:25:\"http://bbpress.org/?v=1.1\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"item\";a:15:{i:0;a:6:{s:4:\"data\";s:30:\"
					\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:2:{s:0:\"\";a:5:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:14:\"Contact Form 7\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:54:\"http://wordpress.org/plugins/contact-form-7/#post-2141\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Thu, 02 Aug 2007 12:45:03 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:34:\"2141@http://wordpress.org/plugins/\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:54:\"Just another contact form plugin. Simple but flexible.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:16:\"Takayuki Miyoshi\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:1;a:6:{s:4:\"data\";s:30:\"
					\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:2:{s:0:\"\";a:5:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:24:\"Jetpack by WordPress.com\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:48:\"http://wordpress.org/plugins/jetpack/#post-23862\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Thu, 20 Jan 2011 02:21:38 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"23862@http://wordpress.org/plugins/\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:104:\"Supercharge your WordPress site with powerful features previously only available to WordPress.com users.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"Tim Moore\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:2;a:6:{s:4:\"data\";s:30:\"
					\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:2:{s:0:\"\";a:5:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:19:\"Google XML Sitemaps\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:63:\"http://wordpress.org/plugins/google-sitemap-generator/#post-132\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Fri, 09 Mar 2007 22:31:32 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:33:\"132@http://wordpress.org/plugins/\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:105:\"This plugin will generate a special XML sitemap which will help search engines to better index your blog.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:5:\"Arnee\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:3;a:6:{s:4:\"data\";s:30:\"
					\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:2:{s:0:\"\";a:5:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:18:\"Better WP Security\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:59:\"http://wordpress.org/plugins/better-wp-security/#post-21738\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Fri, 22 Oct 2010 22:06:05 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"21738@http://wordpress.org/plugins/\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:107:\"The easiest, most effective way to secure WordPress. Improve the security of any WordPress site in seconds.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:13:\"Chris Wiegman\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:4;a:6:{s:4:\"data\";s:30:\"
					\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:2:{s:0:\"\";a:5:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:19:\"All in One SEO Pack\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:58:\"http://wordpress.org/plugins/all-in-one-seo-pack/#post-753\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Fri, 30 Mar 2007 20:08:18 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:33:\"753@http://wordpress.org/plugins/\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:126:\"All in One SEO Pack is a WordPress SEO plugin to automatically optimize your Wordpress blog for Search Engines such as Google.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:8:\"uberdose\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:5;a:6:{s:4:\"data\";s:30:\"
					\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:2:{s:0:\"\";a:5:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:22:\"WordPress SEO by Yoast\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:53:\"http://wordpress.org/plugins/wordpress-seo/#post-8321\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Thu, 01 Jan 2009 20:34:44 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:34:\"8321@http://wordpress.org/plugins/\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:131:\"Improve your WordPress SEO: Write better content and have a fully optimized WordPress site using the WordPress SEO plugin by Yoast.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:13:\"Joost de Valk\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:6;a:6:{s:4:\"data\";s:30:\"
					\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:2:{s:0:\"\";a:5:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:7:\"Akismet\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:45:\"http://wordpress.org/plugins/akismet/#post-15\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Fri, 09 Mar 2007 22:11:30 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:32:\"15@http://wordpress.org/plugins/\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:98:\"Akismet checks your comments against the Akismet web service to see if they look like spam or not.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:14:\"Matt Mullenweg\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:7;a:6:{s:4:\"data\";s:30:\"
					\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:2:{s:0:\"\";a:5:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:16:\"TinyMCE Advanced\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:56:\"http://wordpress.org/plugins/tinymce-advanced/#post-2082\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Wed, 27 Jun 2007 15:00:26 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:34:\"2082@http://wordpress.org/plugins/\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:71:\"Enables the advanced features of TinyMCE, the WordPress WYSIWYG editor.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:10:\"Andrew Ozz\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:8;a:6:{s:4:\"data\";s:30:\"
					\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:2:{s:0:\"\";a:5:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:21:\"WPtouch Mobile Plugin\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:47:\"http://wordpress.org/plugins/wptouch/#post-5468\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Thu, 01 May 2008 04:58:09 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:34:\"5468@http://wordpress.org/plugins/\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:63:\"Create a slick mobile WordPress website with just a few clicks.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:17:\"BraveNewCode Inc.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:9;a:6:{s:4:\"data\";s:30:\"
					\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:2:{s:0:\"\";a:5:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:18:\"WordPress Importer\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:59:\"http://wordpress.org/plugins/wordpress-importer/#post-18101\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Thu, 20 May 2010 17:42:45 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"18101@http://wordpress.org/plugins/\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:101:\"Import posts, pages, comments, custom fields, categories, tags and more from a WordPress export file.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:14:\"Brian Colinger\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:10;a:6:{s:4:\"data\";s:30:\"
					\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:2:{s:0:\"\";a:5:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:15:\"NextGEN Gallery\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:55:\"http://wordpress.org/plugins/nextgen-gallery/#post-1169\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Mon, 23 Apr 2007 20:08:06 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:34:\"1169@http://wordpress.org/plugins/\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:122:\"The most popular WordPress gallery plugin and one of the most popular plugins of all time with over 7.5 million downloads.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"Alex Rabe\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:11;a:6:{s:4:\"data\";s:30:\"
					\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:2:{s:0:\"\";a:5:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:33:\"WooCommerce - excelling eCommerce\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:52:\"http://wordpress.org/plugins/woocommerce/#post-29860\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Mon, 05 Sep 2011 08:13:36 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"29860@http://wordpress.org/plugins/\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:97:\"WooCommerce is a powerful, extendable eCommerce plugin that helps you sell anything. Beautifully.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"WooThemes\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:12;a:6:{s:4:\"data\";s:30:\"
					\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:2:{s:0:\"\";a:5:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:18:\"Wordfence Security\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:50:\"http://wordpress.org/plugins/wordfence/#post-29832\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Sun, 04 Sep 2011 03:13:51 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"29832@http://wordpress.org/plugins/\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:148:\"Wordfence Security is a free enterprise class security plugin that includes a firewall, virus scanning, real-time traffic with geolocation and more.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:9:\"Wordfence\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:13;a:6:{s:4:\"data\";s:30:\"
					\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:2:{s:0:\"\";a:5:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:7:\"Captcha\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:48:\"http://wordpress.org/plugins/captcha/#post-26129\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Wed, 27 Apr 2011 05:53:50 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"26129@http://wordpress.org/plugins/\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:79:\"This plugin allows you to implement super security captcha form into web forms.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:11:\"bestwebsoft\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}i:14;a:6:{s:4:\"data\";s:30:\"
					\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:2:{s:0:\"\";a:5:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:24:\"Fast Secure Contact Form\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:56:\"http://wordpress.org/plugins/si-contact-form/#post-12636\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:7:\"pubDate\";a:1:{i:0;a:5:{s:4:\"data\";s:31:\"Thu, 27 Aug 2009 01:20:04 +0000\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"guid\";a:1:{i:0;a:5:{s:4:\"data\";s:35:\"12636@http://wordpress.org/plugins/\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:11:\"isPermaLink\";s:5:\"false\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:131:\"An easy and powerful form builder that lets your visitors send you email. Blocks all automated spammers. No templates to mess with.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:32:\"http://purl.org/dc/elements/1.1/\";a:1:{s:7:\"creator\";a:1:{i:0;a:5:{s:4:\"data\";s:12:\"Mike Challis\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}}s:27:\"http://www.w3.org/2005/Atom\";a:1:{s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:0:\"\";s:7:\"attribs\";a:1:{s:0:\"\";a:3:{s:4:\"href\";s:45:\"http://wordpress.org/plugins/rss/view/popular\";s:3:\"rel\";s:4:\"self\";s:4:\"type\";s:19:\"application/rss+xml\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}}}}}}}s:4:\"type\";i:128;s:7:\"headers\";a:10:{s:6:\"server\";s:5:\"nginx\";s:4:\"date\";s:29:\"Sun, 15 Dec 2013 21:55:22 GMT\";s:12:\"content-type\";s:23:\"text/xml; charset=UTF-8\";s:10:\"connection\";s:5:\"close\";s:4:\"vary\";s:15:\"Accept-Encoding\";s:7:\"expires\";s:29:\"Sun, 15 Dec 2013 22:11:27 GMT\";s:13:\"cache-control\";s:0:\"\";s:6:\"pragma\";s:0:\"\";s:13:\"last-modified\";s:31:\"Sun, 15 Dec 2013 21:36:27 +0000\";s:4:\"x-nc\";s:11:\"HIT lax 249\";}s:5:\"build\";s:14:\"20130911030210\";}","no");
INSERT INTO wp_options VALUES("312","_transient_timeout_feed_mod_b9388c83948825c1edaef0d856b7b109","1387187708","no");
INSERT INTO wp_options VALUES("313","_transient_feed_mod_b9388c83948825c1edaef0d856b7b109","1387144508","no");
INSERT INTO wp_options VALUES("326","disable_page_comments","1","yes");
INSERT INTO wp_options VALUES("328","disable_post_comments","1","yes");
INSERT INTO wp_options VALUES("329","legacy_mode","","yes");
INSERT INTO wp_options VALUES("333","disable_comments_options","a:4:{s:19:\"disabled_post_types\";a:3:{i:0;s:4:\"post\";i:1;s:4:\"page\";i:2;s:10:\"attachment\";}s:17:\"remove_everywhere\";b:1;s:9:\"permanent\";b:0;s:10:\"db_version\";i:5;}","yes");
INSERT INTO wp_options VALUES("344","_transient_twentyfourteen_category_count","1","yes");
INSERT INTO wp_options VALUES("347","jetpack_activated","1","yes");
INSERT INTO wp_options VALUES("348","jetpack_options","a:4:{s:7:\"version\";s:14:\"2.7:1387187510\";s:11:\"old_version\";s:14:\"2.7:1387187510\";s:28:\"fallback_no_verify_ssl_certs\";i:0;s:9:\"time_diff\";i:0;}","yes");
INSERT INTO wp_options VALUES("351","_transient_timeout_jetpack_https_test","1387273913","no");
INSERT INTO wp_options VALUES("352","_transient_jetpack_https_test","1","no");
INSERT INTO wp_options VALUES("358","tadv_version","3420","yes");
INSERT INTO wp_options VALUES("359","tadv_plugins","a:8:{i:0;s:5:\"layer\";i:1;s:5:\"style\";i:2;s:8:\"emotions\";i:3;s:5:\"table\";i:4;s:5:\"print\";i:5;s:13:\"searchreplace\";i:6;s:10:\"xhtmlxtras\";i:7;s:8:\"advimage\";}","yes");
INSERT INTO wp_options VALUES("360","tadv_options","a:7:{s:8:\"advlink1\";i:0;s:8:\"advimage\";i:1;s:11:\"editorstyle\";i:0;s:11:\"hideclasses\";i:0;s:11:\"contextmenu\";i:0;s:8:\"no_autop\";i:0;s:7:\"advlist\";i:0;}","yes");
INSERT INTO wp_options VALUES("361","tadv_toolbars","a:4:{s:9:\"toolbar_1\";a:23:{i:0;s:4:\"bold\";i:1;s:6:\"italic\";i:2;s:13:\"strikethrough\";i:3;s:9:\"underline\";i:4;s:7:\"bullist\";i:5;s:7:\"numlist\";i:6;s:7:\"outdent\";i:7;s:6:\"indent\";i:8;s:11:\"justifyleft\";i:9;s:13:\"justifycenter\";i:10;s:12:\"justifyright\";i:11;s:11:\"justifyfull\";i:12;s:4:\"link\";i:13;s:6:\"unlink\";i:14;s:5:\"image\";i:15;s:10:\"styleprops\";i:16;s:7:\"wp_more\";i:17;s:7:\"wp_page\";i:18;s:12:\"spellchecker\";i:19;s:6:\"search\";i:20;s:10:\"fullscreen\";i:21;s:2:\"hr\";i:22;s:4:\"cite\";}s:9:\"toolbar_2\";a:18:{i:0;s:14:\"fontsizeselect\";i:1;s:10:\"fontselect\";i:2;s:12:\"formatselect\";i:3;s:9:\"pastetext\";i:4;s:9:\"pasteword\";i:5;s:12:\"removeformat\";i:6;s:7:\"charmap\";i:7;s:5:\"print\";i:8;s:9:\"forecolor\";i:9;s:9:\"backcolor\";i:10;s:8:\"emotions\";i:11;s:3:\"sup\";i:12;s:3:\"sub\";i:13;s:5:\"media\";i:14;s:4:\"undo\";i:15;s:4:\"redo\";i:16;s:7:\"attribs\";i:17;s:7:\"wp_help\";}s:9:\"toolbar_3\";a:0:{}s:9:\"toolbar_4\";a:3:{i:0;s:13:\"tablecontrols\";i:1;s:9:\"visualaid\";i:2;s:5:\"layer\";}}","no");
INSERT INTO wp_options VALUES("362","tadv_btns1","a:23:{i:0;s:4:\"bold\";i:1;s:6:\"italic\";i:2;s:13:\"strikethrough\";i:3;s:9:\"underline\";i:4;s:7:\"bullist\";i:5;s:7:\"numlist\";i:6;s:7:\"outdent\";i:7;s:6:\"indent\";i:8;s:11:\"justifyleft\";i:9;s:13:\"justifycenter\";i:10;s:12:\"justifyright\";i:11;s:11:\"justifyfull\";i:12;s:4:\"link\";i:13;s:6:\"unlink\";i:14;s:5:\"image\";i:15;s:10:\"styleprops\";i:16;s:7:\"wp_more\";i:17;s:7:\"wp_page\";i:18;s:12:\"spellchecker\";i:19;s:6:\"search\";i:20;s:10:\"fullscreen\";i:21;s:2:\"hr\";i:22;s:4:\"cite\";}","no");
INSERT INTO wp_options VALUES("363","tadv_btns2","a:18:{i:0;s:14:\"fontsizeselect\";i:1;s:10:\"fontselect\";i:2;s:12:\"formatselect\";i:3;s:9:\"pastetext\";i:4;s:9:\"pasteword\";i:5;s:12:\"removeformat\";i:6;s:7:\"charmap\";i:7;s:5:\"print\";i:8;s:9:\"forecolor\";i:9;s:9:\"backcolor\";i:10;s:8:\"emotions\";i:11;s:3:\"sup\";i:12;s:3:\"sub\";i:13;s:5:\"media\";i:14;s:4:\"undo\";i:15;s:4:\"redo\";i:16;s:7:\"attribs\";i:17;s:7:\"wp_help\";}","no");
INSERT INTO wp_options VALUES("364","tadv_btns3","a:0:{}","no");
INSERT INTO wp_options VALUES("365","tadv_btns4","a:7:{i:0;s:13:\"tablecontrols\";i:1;s:13:\"delete_table,\";i:2;s:9:\"visualaid\";i:3;s:11:\"insertlayer\";i:4;s:11:\"moveforward\";i:5;s:12:\"movebackward\";i:6;s:8:\"absolute\";}","no");
INSERT INTO wp_options VALUES("366","tadv_allbtns","a:66:{i:0;s:2:\"hr\";i:1;s:6:\"wp_adv\";i:2;s:10:\"blockquote\";i:3;s:4:\"bold\";i:4;s:6:\"italic\";i:5;s:13:\"strikethrough\";i:6;s:9:\"underline\";i:7;s:7:\"bullist\";i:8;s:7:\"numlist\";i:9;s:7:\"outdent\";i:10;s:6:\"indent\";i:11;s:11:\"justifyleft\";i:12;s:13:\"justifycenter\";i:13;s:12:\"justifyright\";i:14;s:11:\"justifyfull\";i:15;s:3:\"cut\";i:16;s:4:\"copy\";i:17;s:5:\"paste\";i:18;s:4:\"link\";i:19;s:6:\"unlink\";i:20;s:5:\"image\";i:21;s:7:\"wp_more\";i:22;s:7:\"wp_page\";i:23;s:6:\"search\";i:24;s:7:\"replace\";i:25;s:10:\"fontselect\";i:26;s:14:\"fontsizeselect\";i:27;s:7:\"wp_help\";i:28;s:10:\"fullscreen\";i:29;s:11:\"styleselect\";i:30;s:12:\"formatselect\";i:31;s:9:\"forecolor\";i:32;s:9:\"backcolor\";i:33;s:9:\"pastetext\";i:34;s:9:\"pasteword\";i:35;s:12:\"removeformat\";i:36;s:7:\"cleanup\";i:37;s:12:\"spellchecker\";i:38;s:7:\"charmap\";i:39;s:5:\"print\";i:40;s:4:\"undo\";i:41;s:4:\"redo\";i:42;s:13:\"tablecontrols\";i:43;s:4:\"cite\";i:44;s:3:\"ins\";i:45;s:3:\"del\";i:46;s:4:\"abbr\";i:47;s:7:\"acronym\";i:48;s:7:\"attribs\";i:49;s:5:\"layer\";i:50;s:5:\"advhr\";i:51;s:4:\"code\";i:52;s:11:\"visualchars\";i:53;s:11:\"nonbreaking\";i:54;s:3:\"sub\";i:55;s:3:\"sup\";i:56;s:9:\"visualaid\";i:57;s:10:\"insertdate\";i:58;s:10:\"inserttime\";i:59;s:6:\"anchor\";i:60;s:10:\"styleprops\";i:61;s:8:\"emotions\";i:62;s:5:\"media\";i:63;s:7:\"iespell\";i:64;s:9:\"separator\";i:65;s:1:\"|\";}","no");
INSERT INTO wp_options VALUES("368","jetpack_log","a:6:{i:0;a:5:{s:4:\"time\";i:1387189623;s:7:\"user_id\";i:1;s:7:\"blog_id\";b:0;s:4:\"code\";s:8:\"activate\";s:4:\"data\";s:10:\"sharedaddy\";}i:1;a:5:{s:4:\"time\";i:1387189651;s:7:\"user_id\";i:1;s:7:\"blog_id\";b:0;s:4:\"code\";s:8:\"activate\";s:4:\"data\";s:17:\"widget-visibility\";}i:2;a:5:{s:4:\"time\";i:1387189670;s:7:\"user_id\";i:1;s:7:\"blog_id\";b:0;s:4:\"code\";s:8:\"activate\";s:4:\"data\";s:10:\"shortcodes\";}i:3;a:5:{s:4:\"time\";i:1387189680;s:7:\"user_id\";i:1;s:7:\"blog_id\";b:0;s:4:\"code\";s:8:\"activate\";s:4:\"data\";s:7:\"widgets\";}i:4;a:5:{s:4:\"time\";i:1387228178;s:7:\"user_id\";i:1;s:7:\"blog_id\";b:0;s:4:\"code\";s:8:\"activate\";s:4:\"data\";s:10:\"omnisearch\";}i:5;a:5:{s:4:\"time\";i:1387228192;s:7:\"user_id\";i:1;s:7:\"blog_id\";b:0;s:4:\"code\";s:8:\"activate\";s:4:\"data\";s:10:\"custom-css\";}}","no");
INSERT INTO wp_options VALUES("369","jetpack_active_modules","a:7:{i:0;s:10:\"vaultpress\";i:1;s:10:\"sharedaddy\";i:3;s:17:\"widget-visibility\";i:5;s:10:\"shortcodes\";i:7;s:7:\"widgets\";i:9;s:10:\"omnisearch\";i:11;s:10:\"custom-css\";}","yes");
INSERT INTO wp_options VALUES("370","sharing-options","a:1:{s:6:\"global\";a:5:{s:12:\"button_style\";s:9:\"icon-text\";s:13:\"sharing_label\";b:0;s:10:\"open_links\";s:3:\"new\";s:4:\"show\";a:2:{i:0;s:4:\"post\";i:1;s:4:\"page\";}s:6:\"custom\";a:0:{}}}","yes");
INSERT INTO wp_options VALUES("377","sharing-services","a:2:{s:7:\"visible\";a:6:{i:0;s:8:\"facebook\";i:1;s:8:\"linkedin\";i:2;s:13:\"google-plus-1\";i:3;s:7:\"twitter\";i:4;s:5:\"email\";i:5;s:5:\"print\";}s:6:\"hidden\";a:0:{}}","yes");
INSERT INTO wp_options VALUES("378","sharedaddy_disable_resources","0","yes");
INSERT INTO wp_options VALUES("380","jetpack_holiday_snow_enabled","letitsnow","yes");
INSERT INTO wp_options VALUES("406","rewrite_rules","a:68:{s:47:\"category/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:52:\"index.php?category_name=$matches[1]&feed=$matches[2]\";s:42:\"category/(.+?)/(feed|rdf|rss|rss2|atom)/?$\";s:52:\"index.php?category_name=$matches[1]&feed=$matches[2]\";s:35:\"category/(.+?)/page/?([0-9]{1,})/?$\";s:53:\"index.php?category_name=$matches[1]&paged=$matches[2]\";s:17:\"category/(.+?)/?$\";s:35:\"index.php?category_name=$matches[1]\";s:44:\"tag/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?tag=$matches[1]&feed=$matches[2]\";s:39:\"tag/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?tag=$matches[1]&feed=$matches[2]\";s:32:\"tag/([^/]+)/page/?([0-9]{1,})/?$\";s:43:\"index.php?tag=$matches[1]&paged=$matches[2]\";s:14:\"tag/([^/]+)/?$\";s:25:\"index.php?tag=$matches[1]\";s:45:\"type/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?post_format=$matches[1]&feed=$matches[2]\";s:40:\"type/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?post_format=$matches[1]&feed=$matches[2]\";s:33:\"type/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?post_format=$matches[1]&paged=$matches[2]\";s:15:\"type/([^/]+)/?$\";s:33:\"index.php?post_format=$matches[1]\";s:48:\".*wp-(atom|rdf|rss|rss2|feed|commentsrss2)\\.php$\";s:18:\"index.php?feed=old\";s:20:\".*wp-app\\.php(/.*)?$\";s:19:\"index.php?error=403\";s:18:\".*wp-register.php$\";s:23:\"index.php?register=true\";s:32:\"feed/(feed|rdf|rss|rss2|atom)/?$\";s:27:\"index.php?&feed=$matches[1]\";s:27:\"(feed|rdf|rss|rss2|atom)/?$\";s:27:\"index.php?&feed=$matches[1]\";s:20:\"page/?([0-9]{1,})/?$\";s:28:\"index.php?&paged=$matches[1]\";s:27:\"comment-page-([0-9]{1,})/?$\";s:38:\"index.php?&page_id=2&cpage=$matches[1]\";s:41:\"comments/feed/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?&feed=$matches[1]&withcomments=1\";s:36:\"comments/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?&feed=$matches[1]&withcomments=1\";s:44:\"search/(.+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?s=$matches[1]&feed=$matches[2]\";s:39:\"search/(.+)/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?s=$matches[1]&feed=$matches[2]\";s:32:\"search/(.+)/page/?([0-9]{1,})/?$\";s:41:\"index.php?s=$matches[1]&paged=$matches[2]\";s:14:\"search/(.+)/?$\";s:23:\"index.php?s=$matches[1]\";s:47:\"author/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?author_name=$matches[1]&feed=$matches[2]\";s:42:\"author/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?author_name=$matches[1]&feed=$matches[2]\";s:35:\"author/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?author_name=$matches[1]&paged=$matches[2]\";s:17:\"author/([^/]+)/?$\";s:33:\"index.php?author_name=$matches[1]\";s:69:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:80:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]\";s:64:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$\";s:80:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]\";s:57:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/page/?([0-9]{1,})/?$\";s:81:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&paged=$matches[4]\";s:39:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/?$\";s:63:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]\";s:56:\"([0-9]{4})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:64:\"index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]\";s:51:\"([0-9]{4})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$\";s:64:\"index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]\";s:44:\"([0-9]{4})/([0-9]{1,2})/page/?([0-9]{1,})/?$\";s:65:\"index.php?year=$matches[1]&monthnum=$matches[2]&paged=$matches[3]\";s:26:\"([0-9]{4})/([0-9]{1,2})/?$\";s:47:\"index.php?year=$matches[1]&monthnum=$matches[2]\";s:43:\"([0-9]{4})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?year=$matches[1]&feed=$matches[2]\";s:38:\"([0-9]{4})/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?year=$matches[1]&feed=$matches[2]\";s:31:\"([0-9]{4})/page/?([0-9]{1,})/?$\";s:44:\"index.php?year=$matches[1]&paged=$matches[2]\";s:13:\"([0-9]{4})/?$\";s:26:\"index.php?year=$matches[1]\";s:27:\".?.+?/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:37:\".?.+?/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:57:\".?.+?/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\".?.+?/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\".?.+?/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:20:\"(.?.+?)/trackback/?$\";s:35:\"index.php?pagename=$matches[1]&tb=1\";s:40:\"(.?.+?)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:47:\"index.php?pagename=$matches[1]&feed=$matches[2]\";s:35:\"(.?.+?)/(feed|rdf|rss|rss2|atom)/?$\";s:47:\"index.php?pagename=$matches[1]&feed=$matches[2]\";s:28:\"(.?.+?)/page/?([0-9]{1,})/?$\";s:48:\"index.php?pagename=$matches[1]&paged=$matches[2]\";s:35:\"(.?.+?)/comment-page-([0-9]{1,})/?$\";s:48:\"index.php?pagename=$matches[1]&cpage=$matches[2]\";s:20:\"(.?.+?)(/[0-9]+)?/?$\";s:47:\"index.php?pagename=$matches[1]&page=$matches[2]\";s:27:\"[^/]+/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:37:\"[^/]+/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:57:\"[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\"[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\"[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:20:\"([^/]+)/trackback/?$\";s:31:\"index.php?name=$matches[1]&tb=1\";s:40:\"([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?name=$matches[1]&feed=$matches[2]\";s:35:\"([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?name=$matches[1]&feed=$matches[2]\";s:28:\"([^/]+)/page/?([0-9]{1,})/?$\";s:44:\"index.php?name=$matches[1]&paged=$matches[2]\";s:35:\"([^/]+)/comment-page-([0-9]{1,})/?$\";s:44:\"index.php?name=$matches[1]&cpage=$matches[2]\";s:20:\"([^/]+)(/[0-9]+)?/?$\";s:43:\"index.php?name=$matches[1]&page=$matches[2]\";s:16:\"[^/]+/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:26:\"[^/]+/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:46:\"[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:41:\"[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:41:\"[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";}","yes");
INSERT INTO wp_options VALUES("409","widget_rss_links","a:2:{i:2;a:5:{s:5:\"title\";s:0:\"\";s:7:\"display\";s:5:\"posts\";s:6:\"format\";s:5:\"image\";s:9:\"imagesize\";s:6:\"medium\";s:10:\"imagecolor\";s:5:\"green\";}s:12:\"_multiwidget\";i:1;}","yes");
INSERT INTO wp_options VALUES("410","widget_bavotasan_custom_text_widget","a:5:{i:2;a:5:{s:5:\"title\";s:14:\"Que fait Aboo?\";s:3:\"url\";s:39:\"http://localhost/gestabo/que-fait-aboo/\";s:5:\"image\";s:76:\"http://localhost/gestabo/wp-content/uploads/2013/12/Points-interrogation.jpg\";s:4:\"text\";s:0:\"\";s:6:\"filter\";b:0;}i:3;a:5:{s:5:\"title\";s:9:\"Pour qui?\";s:3:\"url\";s:34:\"http://localhost/gestabo/pour-qui/\";s:5:\"image\";s:63:\"http://localhost/gestabo/wp-content/uploads/2013/12/target2.jpg\";s:4:\"text\";s:0:\"\";s:6:\"filter\";b:0;}i:4;a:5:{s:5:\"title\";s:6:\"Tarifs\";s:3:\"url\";s:32:\"http://localhost/gestabo/tarifs/\";s:5:\"image\";s:60:\"http://localhost/gestabo/wp-content/uploads/2013/12/prix.jpg\";s:4:\"text\";s:0:\"\";s:6:\"filter\";b:0;}i:5;a:5:{s:5:\"title\";s:14:\"Contactez-nous\";s:3:\"url\";s:39:\"http://localhost/gestabo/contactez-moi/\";s:5:\"image\";s:61:\"http://localhost/gestabo/wp-content/uploads/2013/12/zen21.gif\";s:4:\"text\";s:0:\"\";s:6:\"filter\";b:0;}s:12:\"_multiwidget\";i:1;}","yes");
INSERT INTO wp_options VALUES("417","category_children","a:0:{}","yes");
INSERT INTO wp_options VALUES("424","widget_image","a:1:{s:12:\"_multiwidget\";i:1;}","yes");
INSERT INTO wp_options VALUES("434","_site_transient_timeout_wporg_theme_feature_list","1387239422","yes");
INSERT INTO wp_options VALUES("435","_site_transient_wporg_theme_feature_list","a:5:{s:6:\"Colors\";a:15:{i:0;s:5:\"black\";i:1;s:4:\"blue\";i:2;s:5:\"brown\";i:3;s:4:\"gray\";i:4;s:5:\"green\";i:5;s:6:\"orange\";i:6;s:4:\"pink\";i:7;s:6:\"purple\";i:8;s:3:\"red\";i:9;s:6:\"silver\";i:10;s:3:\"tan\";i:11;s:5:\"white\";i:12;s:6:\"yellow\";i:13;s:4:\"dark\";i:14;s:5:\"light\";}s:7:\"Columns\";a:6:{i:0;s:10:\"one-column\";i:1;s:11:\"two-columns\";i:2;s:13:\"three-columns\";i:3;s:12:\"four-columns\";i:4;s:12:\"left-sidebar\";i:5;s:13:\"right-sidebar\";}s:6:\"Layout\";a:3:{i:0;s:12:\"fixed-layout\";i:1;s:12:\"fluid-layout\";i:2;s:17:\"responsive-layout\";}s:8:\"Features\";a:20:{i:0;s:19:\"accessibility-ready\";i:1;s:8:\"blavatar\";i:2;s:10:\"buddypress\";i:3;s:17:\"custom-background\";i:4;s:13:\"custom-colors\";i:5;s:13:\"custom-header\";i:6;s:11:\"custom-menu\";i:7;s:12:\"editor-style\";i:8;s:21:\"featured-image-header\";i:9;s:15:\"featured-images\";i:10;s:15:\"flexible-header\";i:11;s:20:\"front-page-post-form\";i:12;s:19:\"full-width-template\";i:13;s:12:\"microformats\";i:14;s:12:\"post-formats\";i:15;s:20:\"rtl-language-support\";i:16;s:11:\"sticky-post\";i:17;s:13:\"theme-options\";i:18;s:17:\"threaded-comments\";i:19;s:17:\"translation-ready\";}s:7:\"Subject\";a:3:{i:0;s:7:\"holiday\";i:1;s:13:\"photoblogging\";i:2;s:8:\"seasonal\";}}","yes");
INSERT INTO wp_options VALUES("436","theme_mods_ward","a:8:{i:0;b:0;s:16:\"background_color\";s:6:\"6b6b6b\";s:16:\"background_image\";s:0:\"\";s:17:\"background_repeat\";s:6:\"repeat\";s:21:\"background_position_x\";s:4:\"left\";s:21:\"background_attachment\";s:5:\"fixed\";s:18:\"nav_menu_locations\";a:1:{s:7:\"primary\";i:2;}s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1387229272;s:4:\"data\";a:3:{s:19:\"wp_inactive_widgets\";a:0:{}s:7:\"sidebar\";a:4:{i:0;s:8:\"search-2\";i:1;s:14:\"recent-posts-2\";i:2;s:12:\"categories-2\";i:3;s:11:\"rss_links-2\";}s:18:\"home-page-top-area\";a:4:{i:0;s:30:\"bavotasan_custom_text_widget-2\";i:1;s:30:\"bavotasan_custom_text_widget-3\";i:2;s:30:\"bavotasan_custom_text_widget-4\";i:3;s:30:\"bavotasan_custom_text_widget-5\";}}}}","yes");
INSERT INTO wp_options VALUES("437","safecss_rev","11","yes");
INSERT INTO wp_options VALUES("439","ward_theme_options","a:12:{s:5:\"width\";s:4:\"1200\";s:6:\"layout\";s:1:\"6\";s:7:\"primary\";s:9:\"col-md-10\";s:15:\"excerpt_content\";s:7:\"content\";s:11:\"home_widget\";s:2:\"on\";s:10:\"home_posts\";s:2:\"on\";s:20:\"jumbo_headline_title\";s:4:\"Aboo\";s:19:\"jumbo_headline_text\";s:134:\"Got something important to say? Then make it stand out by using the jumbo headline option and get your visitor\'s attention right away.\";s:18:\"display_categories\";s:0:\"\";s:14:\"display_author\";s:2:\"on\";s:12:\"display_date\";s:2:\"on\";s:21:\"display_comment_count\";b:0;}","yes");
INSERT INTO wp_options VALUES("440","_site_transient_update_themes","O:8:\"stdClass\":4:{s:12:\"last_checked\";i:1387792464;s:7:\"checked\";a:3:{s:9:\"customizr\";s:5:\"3.1.5\";s:5:\"tonic\";s:7:\"1.0.8.1\";s:19:\"wordpress-bootstrap\";s:3:\"2.1\";}s:8:\"response\";a:0:{}s:12:\"translations\";a:0:{}}","yes");
INSERT INTO wp_options VALUES("447","safecss_revision_migrated","1387237933","yes");
INSERT INTO wp_options VALUES("480","_site_transient_timeout_poptags_40cd750bba9870f18aada2478b24840a","1387465652","yes");
INSERT INTO wp_options VALUES("481","_site_transient_poptags_40cd750bba9870f18aada2478b24840a","a:40:{s:6:\"widget\";a:3:{s:4:\"name\";s:6:\"widget\";s:4:\"slug\";s:6:\"widget\";s:5:\"count\";s:4:\"3898\";}s:4:\"post\";a:3:{s:4:\"name\";s:4:\"Post\";s:4:\"slug\";s:4:\"post\";s:5:\"count\";s:4:\"2456\";}s:6:\"plugin\";a:3:{s:4:\"name\";s:6:\"plugin\";s:4:\"slug\";s:6:\"plugin\";s:5:\"count\";s:4:\"2344\";}s:5:\"admin\";a:3:{s:4:\"name\";s:5:\"admin\";s:4:\"slug\";s:5:\"admin\";s:5:\"count\";s:4:\"1930\";}s:5:\"posts\";a:3:{s:4:\"name\";s:5:\"posts\";s:4:\"slug\";s:5:\"posts\";s:5:\"count\";s:4:\"1856\";}s:7:\"sidebar\";a:3:{s:4:\"name\";s:7:\"sidebar\";s:4:\"slug\";s:7:\"sidebar\";s:5:\"count\";s:4:\"1583\";}s:7:\"twitter\";a:3:{s:4:\"name\";s:7:\"twitter\";s:4:\"slug\";s:7:\"twitter\";s:5:\"count\";s:4:\"1329\";}s:6:\"google\";a:3:{s:4:\"name\";s:6:\"google\";s:4:\"slug\";s:6:\"google\";s:5:\"count\";s:4:\"1325\";}s:8:\"comments\";a:3:{s:4:\"name\";s:8:\"comments\";s:4:\"slug\";s:8:\"comments\";s:5:\"count\";s:4:\"1310\";}s:6:\"images\";a:3:{s:4:\"name\";s:6:\"images\";s:4:\"slug\";s:6:\"images\";s:5:\"count\";s:4:\"1260\";}s:4:\"page\";a:3:{s:4:\"name\";s:4:\"page\";s:4:\"slug\";s:4:\"page\";s:5:\"count\";s:4:\"1225\";}s:5:\"image\";a:3:{s:4:\"name\";s:5:\"image\";s:4:\"slug\";s:5:\"image\";s:5:\"count\";s:4:\"1121\";}s:9:\"shortcode\";a:3:{s:4:\"name\";s:9:\"shortcode\";s:4:\"slug\";s:9:\"shortcode\";s:5:\"count\";s:4:\"1000\";}s:8:\"facebook\";a:3:{s:4:\"name\";s:8:\"Facebook\";s:4:\"slug\";s:8:\"facebook\";s:5:\"count\";s:3:\"982\";}s:5:\"links\";a:3:{s:4:\"name\";s:5:\"links\";s:4:\"slug\";s:5:\"links\";s:5:\"count\";s:3:\"974\";}s:3:\"seo\";a:3:{s:4:\"name\";s:3:\"seo\";s:4:\"slug\";s:3:\"seo\";s:5:\"count\";s:3:\"950\";}s:9:\"wordpress\";a:3:{s:4:\"name\";s:9:\"wordpress\";s:4:\"slug\";s:9:\"wordpress\";s:5:\"count\";s:3:\"844\";}s:7:\"gallery\";a:3:{s:4:\"name\";s:7:\"gallery\";s:4:\"slug\";s:7:\"gallery\";s:5:\"count\";s:3:\"821\";}s:6:\"social\";a:3:{s:4:\"name\";s:6:\"social\";s:4:\"slug\";s:6:\"social\";s:5:\"count\";s:3:\"780\";}s:3:\"rss\";a:3:{s:4:\"name\";s:3:\"rss\";s:4:\"slug\";s:3:\"rss\";s:5:\"count\";s:3:\"722\";}s:7:\"widgets\";a:3:{s:4:\"name\";s:7:\"widgets\";s:4:\"slug\";s:7:\"widgets\";s:5:\"count\";s:3:\"686\";}s:6:\"jquery\";a:3:{s:4:\"name\";s:6:\"jquery\";s:4:\"slug\";s:6:\"jquery\";s:5:\"count\";s:3:\"681\";}s:5:\"pages\";a:3:{s:4:\"name\";s:5:\"pages\";s:4:\"slug\";s:5:\"pages\";s:5:\"count\";s:3:\"678\";}s:5:\"email\";a:3:{s:4:\"name\";s:5:\"email\";s:4:\"slug\";s:5:\"email\";s:5:\"count\";s:3:\"623\";}s:4:\"ajax\";a:3:{s:4:\"name\";s:4:\"AJAX\";s:4:\"slug\";s:4:\"ajax\";s:5:\"count\";s:3:\"615\";}s:5:\"media\";a:3:{s:4:\"name\";s:5:\"media\";s:4:\"slug\";s:5:\"media\";s:5:\"count\";s:3:\"595\";}s:10:\"javascript\";a:3:{s:4:\"name\";s:10:\"javascript\";s:4:\"slug\";s:10:\"javascript\";s:5:\"count\";s:3:\"572\";}s:5:\"video\";a:3:{s:4:\"name\";s:5:\"video\";s:4:\"slug\";s:5:\"video\";s:5:\"count\";s:3:\"570\";}s:10:\"buddypress\";a:3:{s:4:\"name\";s:10:\"buddypress\";s:4:\"slug\";s:10:\"buddypress\";s:5:\"count\";s:3:\"541\";}s:4:\"feed\";a:3:{s:4:\"name\";s:4:\"feed\";s:4:\"slug\";s:4:\"feed\";s:5:\"count\";s:3:\"539\";}s:7:\"content\";a:3:{s:4:\"name\";s:7:\"content\";s:4:\"slug\";s:7:\"content\";s:5:\"count\";s:3:\"530\";}s:5:\"photo\";a:3:{s:4:\"name\";s:5:\"photo\";s:4:\"slug\";s:5:\"photo\";s:5:\"count\";s:3:\"522\";}s:4:\"link\";a:3:{s:4:\"name\";s:4:\"link\";s:4:\"slug\";s:4:\"link\";s:5:\"count\";s:3:\"506\";}s:6:\"photos\";a:3:{s:4:\"name\";s:6:\"photos\";s:4:\"slug\";s:6:\"photos\";s:5:\"count\";s:3:\"505\";}s:5:\"login\";a:3:{s:4:\"name\";s:5:\"login\";s:4:\"slug\";s:5:\"login\";s:5:\"count\";s:3:\"471\";}s:4:\"spam\";a:3:{s:4:\"name\";s:4:\"spam\";s:4:\"slug\";s:4:\"spam\";s:5:\"count\";s:3:\"458\";}s:5:\"stats\";a:3:{s:4:\"name\";s:5:\"stats\";s:4:\"slug\";s:5:\"stats\";s:5:\"count\";s:3:\"453\";}s:8:\"category\";a:3:{s:4:\"name\";s:8:\"category\";s:4:\"slug\";s:8:\"category\";s:5:\"count\";s:3:\"452\";}s:7:\"youtube\";a:3:{s:4:\"name\";s:7:\"youtube\";s:4:\"slug\";s:7:\"youtube\";s:5:\"count\";s:3:\"436\";}s:7:\"comment\";a:3:{s:4:\"name\";s:7:\"comment\";s:4:\"slug\";s:7:\"comment\";s:5:\"count\";s:3:\"432\";}}","yes");
INSERT INTO wp_options VALUES("485","qpp_curr","a:2:{s:4:\"aboo\";s:3:\"EUR\";s:0:\"\";s:0:\"\";}","yes");
INSERT INTO wp_options VALUES("486","qpp_setup","a:4:{s:11:\"alternative\";s:5:\"aboo,\";s:5:\"email\";s:19:\"frederic@meyrou.com\";s:7:\"current\";s:4:\"aboo\";s:9:\"dashboard\";N;}","yes");
INSERT INTO wp_options VALUES("487","qpp_optionsaboo","a:15:{s:5:\"title\";s:24:\"Paiement Aboo par Paypal\";s:5:\"blurb\";s:0:\"\";s:14:\"inputreference\";s:23:\"Abonnement Aboo 12 mois\";s:11:\"inputamount\";s:7:\"Montant\";s:18:\"shortcodereference\";s:15:\"Paiement pour :\";s:12:\"use_quantity\";s:0:\"\";s:13:\"quantitylabel\";s:0:\"\";s:15:\"shortcodeamount\";s:9:\"Montant :\";s:16:\"shortcode_labels\";s:0:\"\";s:13:\"submitcaption\";s:5:\"Payer\";s:10:\"cancelurl,\";s:0:\"\";s:9:\"thanksurl\";s:0:\"\";s:6:\"target\";s:0:\"\";s:10:\"paypal-url\";s:0:\"\";s:15:\"paypal-location\";s:10:\"imagebelow\";}","yes");
INSERT INTO wp_options VALUES("488","qpp_styleaboo","a:19:{s:4:\"font\";s:6:\"plugin\";s:11:\"font-family\";s:17:\"arial, sans-serif\";s:9:\"font-size\";s:5:\"1.2em\";s:11:\"font-colour\";s:7:\"#465069\";s:12:\"input-border\";s:17:\"1px solid #415063\";s:14:\"input-required\";s:0:\"\";s:6:\"border\";s:5:\"plain\";s:5:\"width\";s:3:\"280\";s:9:\"widthtype\";s:5:\"pixel\";s:10:\"background\";s:5:\"white\";s:13:\"backgroundhex\";s:4:\"#FFF\";s:7:\"corners\";s:5:\"round\";s:6:\"custom\";s:17:\"#qpp-style {
}\";s:10:\"use_custom\";s:0:\"\";s:8:\"usetheme\";s:0:\"\";s:6:\"styles\";s:0:\"\";s:13:\"submit-colour\";s:4:\"#FFF\";s:17:\"submit-background\";s:7:\"#343838\";s:13:\"submit-button\";s:0:\"\";}","yes");
INSERT INTO wp_options VALUES("489","qpp_send","a:4:{s:7:\"waiting\";s:20:\"Veuillez attendre...\";s:9:\"cancelurl\";s:0:\"\";s:9:\"thanksurl\";s:0:\"\";s:6:\"target\";s:7:\"current\";}","yes");
INSERT INTO wp_options VALUES("490","qpp_erroraboo","a:2:{s:10:\"errortitle\";s:23:\"Erreur de traitement...\";s:10:\"errorblurb\";s:41:\"Veuiller vérifier le détail du paiement\";}","yes");
INSERT INTO wp_options VALUES("493","qpp_messageoptions","a:2:{s:10:\"messageqty\";s:3:\"all\";s:12:\"messageorder\";s:6:\"newest\";}","yes");
INSERT INTO wp_options VALUES("530","_site_transient_timeout_browser_534c07c4c06c14de70178e1296fcf30e","1388397160","yes");
INSERT INTO wp_options VALUES("531","_site_transient_browser_534c07c4c06c14de70178e1296fcf30e","a:9:{s:8:\"platform\";s:7:\"Windows\";s:4:\"name\";s:6:\"Chrome\";s:7:\"version\";s:13:\"30.0.1599.101\";s:10:\"update_url\";s:28:\"http://www.google.com/chrome\";s:7:\"img_src\";s:49:\"http://s.wordpress.org/images/browsers/chrome.png\";s:11:\"img_src_ssl\";s:48:\"https://wordpress.org/images/browsers/chrome.png\";s:15:\"current_version\";s:2:\"18\";s:7:\"upgrade\";b:0;s:8:\"insecure\";b:0;}","yes");
INSERT INTO wp_options VALUES("532","_site_transient_timeout_theme_roots","1387794167","yes");
INSERT INTO wp_options VALUES("533","_site_transient_theme_roots","a:4:{s:9:\"customizr\";s:7:\"/themes\";s:11:\"tonic-child\";s:7:\"/themes\";s:5:\"tonic\";s:7:\"/themes\";s:19:\"wordpress-bootstrap\";s:7:\"/themes\";}","yes");
INSERT INTO wp_options VALUES("537","_site_transient_update_plugins","O:8:\"stdClass\":4:{s:12:\"last_checked\";i:1387792470;s:7:\"checked\";a:15:{s:26:\"secure-wordpress/index.php\";s:5:\"3.0.1\";s:43:\"all-in-one-seo-pack/all_in_one_seo_pack.php\";s:5:\"2.1.1\";s:95:\"clean-and-simple-contact-form-by-meg-nicholas/clean-and-simple-contact-form-by-meg-nicholas.php\";s:5:\"4.3.1\";s:36:\"contact-form-7/wp-contact-form-7.php\";s:3:\"3.6\";s:37:\"disable-comments/disable-comments.php\";s:5:\"1.0.3\";s:53:\"easy-bootstrap-shortcodes/osc_bootstrap_shortcode.php\";s:5:\"2.4.2\";s:50:\"google-analytics-for-wordpress/googleanalytics.php\";s:5:\"4.3.3\";s:49:\"google-xml-sitemaps-v3-for-qtranslate/sitemap.php\";s:5:\"3.2.9\";s:38:\"hide-title/dojo-digital-hide-title.php\";s:5:\"1.0.3\";s:19:\"jetpack/jetpack.php\";s:3:\"2.7\";s:47:\"quick-paypal-payments/quick-paypal-payments.php\";s:3:\"3.3\";s:37:\"tinymce-advanced/tinymce-advanced.php\";s:7:\"3.5.9.1\";s:33:\"w3-total-cache/w3-total-cache.php\";s:5:\"0.9.3\";s:41:\"wordpress-importer/wordpress-importer.php\";s:5:\"0.6.1\";s:29:\"wp-mail-smtp/wp_mail_smtp.php\";s:5:\"0.9.4\";}s:8:\"response\";a:0:{}s:12:\"translations\";a:0:{}}","yes");
INSERT INTO wp_options VALUES("538","WSD-PLUGIN-CAN-RUN-TASKS","1","yes");
INSERT INTO wp_options VALUES("539","wsdplugin_settings","a:15:{s:17:\"fix_hideWpVersion\";a:3:{s:4:\"name\";s:17:\"fix_hideWpVersion\";s:5:\"value\";i:0;s:4:\"desc\";s:55:\"Hide WordPress version for all users but administrators\";}s:34:\"fix_removeWpMetaGeneratorsFrontend\";a:3:{s:4:\"name\";s:34:\"fix_removeWpMetaGeneratorsFrontend\";s:5:\"value\";i:0;s:4:\"desc\";s:84:\"Remove various meta tags generators from the blog\'s head tag for non-administrators.\";}s:31:\"fix_removeReallySimpleDiscovery\";a:3:{s:4:\"name\";s:31:\"fix_removeReallySimpleDiscovery\";s:5:\"value\";i:0;s:4:\"desc\";s:55:\"Remove Really Simple Discovery meta tags from front-end\";}s:27:\"fix_removeWindowsLiveWriter\";a:3:{s:4:\"name\";s:27:\"fix_removeWindowsLiveWriter\";s:5:\"value\";i:0;s:4:\"desc\";s:51:\"Remove Windows Live Writer meta tags from front-end\";}s:25:\"fix_disableErrorReporting\";a:3:{s:4:\"name\";s:25:\"fix_disableErrorReporting\";s:5:\"value\";i:0;s:4:\"desc\";s:61:\"Disable error reporting (php + db) for all but administrators\";}s:32:\"fix_removeCoreUpdateNotification\";a:3:{s:4:\"name\";s:32:\"fix_removeCoreUpdateNotification\";s:5:\"value\";i:0;s:4:\"desc\";s:73:\"Remove core update notifications from back-end for all but administrators\";}s:35:\"fix_removePluginUpdateNotifications\";a:3:{s:4:\"name\";s:35:\"fix_removePluginUpdateNotifications\";s:5:\"value\";i:0;s:4:\"desc\";s:50:\"Remove plug-ins update notifications from back-end\";}s:34:\"fix_removeThemeUpdateNotifications\";a:3:{s:4:\"name\";s:34:\"fix_removeThemeUpdateNotifications\";s:5:\"value\";i:0;s:4:\"desc\";s:48:\"Remove themes update notifications from back-end\";}s:41:\"fix_removeLoginErrorNotificationsFrontEnd\";a:3:{s:4:\"name\";s:41:\"fix_removeLoginErrorNotificationsFrontEnd\";s:5:\"value\";i:0;s:4:\"desc\";s:47:\"Remove login error notifications from front-end\";}s:26:\"fix_hideAdminNotifications\";a:3:{s:4:\"name\";s:26:\"fix_hideAdminNotifications\";s:5:\"value\";i:0;s:4:\"desc\";s:40:\"Hide admin notifications for non admins.\";}s:27:\"fix_preventDirectoryListing\";a:3:{s:4:\"name\";s:27:\"fix_preventDirectoryListing\";s:5:\"value\";i:0;s:4:\"desc\";s:153:\"Try to create the index.php file in the wp-content, wp-content/plugins, wp-content/themes and wp-content/uploads directories to prevent directory listing\";}s:28:\"fix_removeWpVersionFromLinks\";a:3:{s:4:\"name\";s:28:\"fix_removeWpVersionFromLinks\";s:5:\"value\";i:0;s:4:\"desc\";s:38:\"Remove the version parameter from urls\";}s:27:\"fix_emptyReadmeFileFromRoot\";a:3:{s:4:\"name\";s:27:\"fix_emptyReadmeFileFromRoot\";s:5:\"value\";i:0;s:4:\"desc\";s:66:\"Empty the content of the readme.html file from the root directory.\";}s:25:\"keepNumEntriesLiveTraffic\";i:500;s:26:\"liveTrafficRefreshRateAjax\";i:10;}","yes");
DROP TABLE IF EXISTS wp_postmeta;CREATE TABLE `wp_postmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=InnoDB AUTO_INCREMENT=345 DEFAULT CHARSET=utf8;
INSERT INTO wp_postmeta VALUES("1","2","_wp_page_template","page-full-width.php");
INSERT INTO wp_postmeta VALUES("2","4","_form","<p>Votre nom (obligatoire)<br />
    [text* your-name] </p>
<p>Votre email (obligatoire)<br />
    [email* your-email] </p>
<p>Sujet<br />
    [text your-subject] </p>
<p>Votre message<br />
    [textarea your-message] </p>
<p>[submit \"Envoyer\"]</p>");
INSERT INTO wp_postmeta VALUES("3","4","_mail","a:7:{s:7:\"subject\";s:14:\"[your-subject]\";s:6:\"sender\";s:26:\"[your-name] <[your-email]>\";s:4:\"body\";s:193:\"De : [your-name] <[your-email]>
Sujet : [your-subject]
Corps du message :
[your-message]
--
Cet email a été envoyé via le formulaire de contact de Aboo (http://localhost/gestabo/wordpress)\";s:9:\"recipient\";s:19:\"frederic@meyrou.com\";s:18:\"additional_headers\";s:0:\"\";s:11:\"attachments\";s:0:\"\";s:8:\"use_html\";b:0;}");
INSERT INTO wp_postmeta VALUES("4","4","_mail_2","a:8:{s:6:\"active\";b:0;s:7:\"subject\";s:14:\"[your-subject]\";s:6:\"sender\";s:26:\"[your-name] <[your-email]>\";s:4:\"body\";s:137:\"Corps du message :
[your-message]
--
Cet email a été envoyé via le formulaire de contact de Aboo (http://localhost/gestabo/wordpress)\";s:9:\"recipient\";s:12:\"[your-email]\";s:18:\"additional_headers\";s:0:\"\";s:11:\"attachments\";s:0:\"\";s:8:\"use_html\";b:0;}");
INSERT INTO wp_postmeta VALUES("5","4","_messages","a:21:{s:12:\"mail_sent_ok\";s:42:\"Votre message a bien été envoyé. Merci.\";s:12:\"mail_sent_ng\";s:116:\"Erreur lors de l\'envoi du message. Veuillez réessayer plus tard ou contacter l\'administrateur d\'une autre manière.\";s:16:\"validation_error\";s:76:\"Erreur de validation. Veuillez vérifier les champs et soumettre à nouveau.\";s:4:\"spam\";s:116:\"Erreur lors de l\'envoi du message. Veuillez réessayer plus tard ou contacter l\'administrateur d\'une autre manière.\";s:12:\"accept_terms\";s:61:\"Merci de bien vouloir accepter les conditions pour continuer.\";s:16:\"invalid_required\";s:38:\"Veuillez remplir le champ obligatoire.\";s:17:\"captcha_not_match\";s:29:\"Le code entré est incorrect.\";s:14:\"invalid_number\";s:37:\"Le format numérique semble invalide.\";s:16:\"number_too_small\";s:25:\"Ce nombre est trop petit.\";s:16:\"number_too_large\";s:25:\"Ce nombre est trop grand.\";s:13:\"invalid_email\";s:32:\"L\'adresse email semble invalide.\";s:11:\"invalid_url\";s:22:\"L\'URL semble invalide.\";s:11:\"invalid_tel\";s:42:\"Le numéro de téléphone semble invalide.\";s:23:\"quiz_answer_not_correct\";s:30:\"Votre réponse est incorrecte.\";s:12:\"invalid_date\";s:34:\"Le format de date semble invalide.\";s:14:\"date_too_early\";s:25:\"Cette date est trop tôt.\";s:13:\"date_too_late\";s:25:\"Cette date est trop tard.\";s:13:\"upload_failed\";s:39:\"Impossible de télécharger le fichier.\";s:24:\"upload_file_type_invalid\";s:39:\"Ce type de fichier n\'est pas autorisé.\";s:21:\"upload_file_too_large\";s:31:\"Ce fichier est trop volumineux.\";s:23:\"upload_failed_php_error\";s:66:\"Impossible de mettre en ligne le fichier. Une erreur est survenue.\";}");
INSERT INTO wp_postmeta VALUES("6","4","_additional_settings","");
INSERT INTO wp_postmeta VALUES("7","4","_locale","fr_FR");
INSERT INTO wp_postmeta VALUES("8","2","_edit_lock","1387454715:1");
INSERT INTO wp_postmeta VALUES("9","1","_edit_lock","1387200325:1");
INSERT INTO wp_postmeta VALUES("28","1","_edit_last","1");
INSERT INTO wp_postmeta VALUES("30","1","post_slider_check_key","0");
INSERT INTO wp_postmeta VALUES("31","1","_wp_old_slug","bonjour-tout-le-monde");
INSERT INTO wp_postmeta VALUES("33","2","_edit_last","1");
INSERT INTO wp_postmeta VALUES("34","2","post_slider_check_key","0");
INSERT INTO wp_postmeta VALUES("35","13","_menu_item_type","custom");
INSERT INTO wp_postmeta VALUES("36","13","_menu_item_menu_item_parent","0");
INSERT INTO wp_postmeta VALUES("37","13","_menu_item_object_id","13");
INSERT INTO wp_postmeta VALUES("38","13","_menu_item_object","custom");
INSERT INTO wp_postmeta VALUES("39","13","_menu_item_target","");
INSERT INTO wp_postmeta VALUES("40","13","_menu_item_classes","a:1:{i:0;s:0:\"\";}");
INSERT INTO wp_postmeta VALUES("41","13","_menu_item_xfn","");
INSERT INTO wp_postmeta VALUES("42","13","_menu_item_url","http://localhost/gestabo/connexion.php");
INSERT INTO wp_postmeta VALUES("53","15","_edit_last","1");
INSERT INTO wp_postmeta VALUES("54","15","_wp_page_template","default");
INSERT INTO wp_postmeta VALUES("55","15","post_slider_check_key","0");
INSERT INTO wp_postmeta VALUES("56","15","_edit_lock","1387044561:1");
INSERT INTO wp_postmeta VALUES("57","17","_edit_last","1");
INSERT INTO wp_postmeta VALUES("58","17","_wp_page_template","default");
INSERT INTO wp_postmeta VALUES("59","17","_edit_lock","1387200092:1");
INSERT INTO wp_postmeta VALUES("60","15","_wp_trash_meta_status","publish");
INSERT INTO wp_postmeta VALUES("61","15","_wp_trash_meta_time","1387061622");
INSERT INTO wp_postmeta VALUES("62","19","_menu_item_type","post_type");
INSERT INTO wp_postmeta VALUES("63","19","_menu_item_menu_item_parent","0");
INSERT INTO wp_postmeta VALUES("64","19","_menu_item_object_id","17");
INSERT INTO wp_postmeta VALUES("65","19","_menu_item_object","page");
INSERT INTO wp_postmeta VALUES("66","19","_menu_item_target","");
INSERT INTO wp_postmeta VALUES("67","19","_menu_item_classes","a:1:{i:0;s:0:\"\";}");
INSERT INTO wp_postmeta VALUES("68","19","_menu_item_xfn","");
INSERT INTO wp_postmeta VALUES("69","19","_menu_item_url","");
INSERT INTO wp_postmeta VALUES("71","20","_menu_item_type","custom");
INSERT INTO wp_postmeta VALUES("72","20","_menu_item_menu_item_parent","0");
INSERT INTO wp_postmeta VALUES("73","20","_menu_item_object_id","20");
INSERT INTO wp_postmeta VALUES("74","20","_menu_item_object","custom");
INSERT INTO wp_postmeta VALUES("75","20","_menu_item_target","");
INSERT INTO wp_postmeta VALUES("76","20","_menu_item_classes","a:1:{i:0;s:0:\"\";}");
INSERT INTO wp_postmeta VALUES("77","20","_menu_item_xfn","");
INSERT INTO wp_postmeta VALUES("78","20","_menu_item_url","http://localhost/gestabo/connexion.php");
INSERT INTO wp_postmeta VALUES("80","2","dojodigital_toggle_title","on");
INSERT INTO wp_postmeta VALUES("84","24","_wp_attached_file","2013/12/abonnements_anim.jpg");
INSERT INTO wp_postmeta VALUES("85","24","_wp_attachment_metadata","a:5:{s:5:\"width\";i:650;s:6:\"height\";i:300;s:4:\"file\";s:28:\"2013/12/abonnements_anim.jpg\";s:5:\"sizes\";a:6:{s:9:\"thumbnail\";a:4:{s:4:\"file\";s:28:\"abonnements_anim-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:6:\"medium\";a:4:{s:4:\"file\";s:28:\"abonnements_anim-300x138.jpg\";s:5:\"width\";i:300;s:6:\"height\";i:138;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:13:\"wpbs-featured\";a:4:{s:4:\"file\";s:28:\"abonnements_anim-638x300.jpg\";s:5:\"width\";i:638;s:6:\"height\";i:300;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:15:\"bones-thumb-600\";a:4:{s:4:\"file\";s:28:\"abonnements_anim-325x150.jpg\";s:5:\"width\";i:325;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:15:\"bones-thumb-300\";a:4:{s:4:\"file\";s:28:\"abonnements_anim-300x100.jpg\";s:5:\"width\";i:300;s:6:\"height\";i:100;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:14:\"post-thumbnail\";a:4:{s:4:\"file\";s:28:\"abonnements_anim-125x125.jpg\";s:5:\"width\";i:125;s:6:\"height\";i:125;s:9:\"mime-type\";s:10:\"image/jpeg\";}}s:10:\"image_meta\";a:10:{s:8:\"aperture\";i:0;s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";i:0;s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";i:0;s:3:\"iso\";i:0;s:13:\"shutter_speed\";i:0;s:5:\"title\";s:0:\"\";}}");
INSERT INTO wp_postmeta VALUES("86","2","_thumbnail_id","24");
INSERT INTO wp_postmeta VALUES("94","1","_wp_old_slug","article");
INSERT INTO wp_postmeta VALUES("95","2","layout_key","");
INSERT INTO wp_postmeta VALUES("96","41","_edit_last","1");
INSERT INTO wp_postmeta VALUES("97","41","_edit_lock","1387454239:1");
INSERT INTO wp_postmeta VALUES("98","42","_edit_last","1");
INSERT INTO wp_postmeta VALUES("99","42","_edit_lock","1387320505:1");
INSERT INTO wp_postmeta VALUES("100","43","_wp_attached_file","2013/12/budget.jpg");
INSERT INTO wp_postmeta VALUES("101","43","_wp_attachment_metadata","a:5:{s:5:\"width\";i:475;s:6:\"height\";i:316;s:4:\"file\";s:18:\"2013/12/budget.jpg\";s:5:\"sizes\";a:3:{s:9:\"thumbnail\";a:4:{s:4:\"file\";s:18:\"budget-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:6:\"medium\";a:4:{s:4:\"file\";s:18:\"budget-300x199.jpg\";s:5:\"width\";i:300;s:6:\"height\";i:199;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:8:\"tc-thumb\";a:4:{s:4:\"file\";s:18:\"budget-270x250.jpg\";s:5:\"width\";i:270;s:6:\"height\";i:250;s:9:\"mime-type\";s:10:\"image/jpeg\";}}s:10:\"image_meta\";a:10:{s:8:\"aperture\";i:0;s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";i:0;s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";i:0;s:3:\"iso\";i:0;s:13:\"shutter_speed\";i:0;s:5:\"title\";s:0:\"\";}}");
INSERT INTO wp_postmeta VALUES("102","44","_wp_attached_file","2013/12/Cailloux.jpg");
INSERT INTO wp_postmeta VALUES("103","44","_wp_attachment_metadata","a:5:{s:5:\"width\";i:282;s:6:\"height\";i:425;s:4:\"file\";s:20:\"2013/12/Cailloux.jpg\";s:5:\"sizes\";a:3:{s:9:\"thumbnail\";a:4:{s:4:\"file\";s:20:\"Cailloux-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:6:\"medium\";a:4:{s:4:\"file\";s:20:\"Cailloux-199x300.jpg\";s:5:\"width\";i:199;s:6:\"height\";i:300;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:8:\"tc-thumb\";a:4:{s:4:\"file\";s:20:\"Cailloux-270x250.jpg\";s:5:\"width\";i:270;s:6:\"height\";i:250;s:9:\"mime-type\";s:10:\"image/jpeg\";}}s:10:\"image_meta\";a:10:{s:8:\"aperture\";d:9;s:6:\"credit\";s:26:\"Valerie Potapova - Fotolia\";s:6:\"camera\";s:10:\"NIKON D300\";s:7:\"caption\";s:23:\"stones in balanced pile\";s:17:\"created_timestamp\";i:1321465297;s:9:\"copyright\";s:26:\"Valerie Potapova - Fotolia\";s:12:\"focal_length\";s:3:\"105\";s:3:\"iso\";s:3:\"160\";s:13:\"shutter_speed\";s:7:\"0.00625\";s:5:\"title\";s:24:\"????????????????????????\";}}");
INSERT INTO wp_postmeta VALUES("104","45","_wp_attached_file","2013/12/images.jpg");
INSERT INTO wp_postmeta VALUES("105","45","_wp_attachment_metadata","a:5:{s:5:\"width\";i:275;s:6:\"height\";i:183;s:4:\"file\";s:18:\"2013/12/images.jpg\";s:5:\"sizes\";a:2:{s:9:\"thumbnail\";a:4:{s:4:\"file\";s:18:\"images-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:8:\"tc-thumb\";a:4:{s:4:\"file\";s:18:\"images-270x183.jpg\";s:5:\"width\";i:270;s:6:\"height\";i:183;s:9:\"mime-type\";s:10:\"image/jpeg\";}}s:10:\"image_meta\";a:10:{s:8:\"aperture\";i:0;s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";i:0;s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";i:0;s:3:\"iso\";i:0;s:13:\"shutter_speed\";i:0;s:5:\"title\";s:0:\"\";}}");
INSERT INTO wp_postmeta VALUES("106","46","_wp_attached_file","2013/12/zen21.gif");
INSERT INTO wp_postmeta VALUES("107","46","_wp_attachment_metadata","a:5:{s:5:\"width\";i:407;s:6:\"height\";i:300;s:4:\"file\";s:17:\"2013/12/zen21.gif\";s:5:\"sizes\";a:3:{s:9:\"thumbnail\";a:4:{s:4:\"file\";s:17:\"zen21-150x150.gif\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:9:\"image/gif\";}s:6:\"medium\";a:4:{s:4:\"file\";s:17:\"zen21-300x221.gif\";s:5:\"width\";i:300;s:6:\"height\";i:221;s:9:\"mime-type\";s:9:\"image/gif\";}s:8:\"tc-thumb\";a:4:{s:4:\"file\";s:17:\"zen21-270x250.gif\";s:5:\"width\";i:270;s:6:\"height\";i:250;s:9:\"mime-type\";s:9:\"image/gif\";}}s:10:\"image_meta\";a:10:{s:8:\"aperture\";i:0;s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";i:0;s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";i:0;s:3:\"iso\";i:0;s:13:\"shutter_speed\";i:0;s:5:\"title\";s:0:\"\";}}");
INSERT INTO wp_postmeta VALUES("108","47","_wp_attached_file","2013/12/help.png");
INSERT INTO wp_postmeta VALUES("109","47","_wp_attachment_metadata","a:5:{s:5:\"width\";i:344;s:6:\"height\";i:300;s:4:\"file\";s:16:\"2013/12/help.png\";s:5:\"sizes\";a:3:{s:9:\"thumbnail\";a:4:{s:4:\"file\";s:16:\"help-150x150.png\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:9:\"image/png\";}s:6:\"medium\";a:4:{s:4:\"file\";s:16:\"help-300x261.png\";s:5:\"width\";i:300;s:6:\"height\";i:261;s:9:\"mime-type\";s:9:\"image/png\";}s:8:\"tc-thumb\";a:4:{s:4:\"file\";s:16:\"help-270x250.png\";s:5:\"width\";i:270;s:6:\"height\";i:250;s:9:\"mime-type\";s:9:\"image/png\";}}s:10:\"image_meta\";a:10:{s:8:\"aperture\";i:0;s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";i:0;s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";i:0;s:3:\"iso\";i:0;s:13:\"shutter_speed\";i:0;s:5:\"title\";s:0:\"\";}}");
INSERT INTO wp_postmeta VALUES("112","49","_wp_attached_file","2013/12/budget-2.jpg");
INSERT INTO wp_postmeta VALUES("113","49","_wp_attachment_metadata","a:5:{s:5:\"width\";i:570;s:6:\"height\";i:330;s:4:\"file\";s:20:\"2013/12/budget-2.jpg\";s:5:\"sizes\";a:3:{s:9:\"thumbnail\";a:4:{s:4:\"file\";s:20:\"budget-2-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:6:\"medium\";a:4:{s:4:\"file\";s:20:\"budget-2-300x173.jpg\";s:5:\"width\";i:300;s:6:\"height\";i:173;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:8:\"tc-thumb\";a:4:{s:4:\"file\";s:20:\"budget-2-270x250.jpg\";s:5:\"width\";i:270;s:6:\"height\";i:250;s:9:\"mime-type\";s:10:\"image/jpeg\";}}s:10:\"image_meta\";a:10:{s:8:\"aperture\";i:0;s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";i:0;s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";i:0;s:3:\"iso\";i:0;s:13:\"shutter_speed\";i:0;s:5:\"title\";s:0:\"\";}}");
INSERT INTO wp_postmeta VALUES("115","42","_wp_page_template","page-full-width.php");
INSERT INTO wp_postmeta VALUES("116","50","dojodigital_toggle_title","on");
INSERT INTO wp_postmeta VALUES("117","42","layout_key","");
INSERT INTO wp_postmeta VALUES("118","42","post_slider_check_key","0");
INSERT INTO wp_postmeta VALUES("119","50","_menu_item_type","post_type");
INSERT INTO wp_postmeta VALUES("120","50","_menu_item_menu_item_parent","0");
INSERT INTO wp_postmeta VALUES("121","50","_menu_item_object_id","42");
INSERT INTO wp_postmeta VALUES("122","50","_menu_item_object","page");
INSERT INTO wp_postmeta VALUES("123","50","_menu_item_target","");
INSERT INTO wp_postmeta VALUES("124","50","_menu_item_classes","a:1:{i:0;s:0:\"\";}");
INSERT INTO wp_postmeta VALUES("125","50","_menu_item_xfn","");
INSERT INTO wp_postmeta VALUES("126","50","_menu_item_url","");
INSERT INTO wp_postmeta VALUES("127","42","dojodigital_toggle_title","on");
INSERT INTO wp_postmeta VALUES("128","52","_wp_attached_file","2013/12/tarifs.jpg");
INSERT INTO wp_postmeta VALUES("129","52","_wp_attachment_metadata","a:5:{s:5:\"width\";i:1024;s:6:\"height\";i:707;s:4:\"file\";s:18:\"2013/12/tarifs.jpg\";s:5:\"sizes\";a:5:{s:9:\"thumbnail\";a:4:{s:4:\"file\";s:18:\"tarifs-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:6:\"medium\";a:4:{s:4:\"file\";s:18:\"tarifs-300x207.jpg\";s:5:\"width\";i:300;s:6:\"height\";i:207;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:8:\"tc-thumb\";a:4:{s:4:\"file\";s:18:\"tarifs-270x250.jpg\";s:5:\"width\";i:270;s:6:\"height\";i:250;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:11:\"slider-full\";a:4:{s:4:\"file\";s:19:\"tarifs-1024x500.jpg\";s:5:\"width\";i:1024;s:6:\"height\";i:500;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:6:\"slider\";a:4:{s:4:\"file\";s:19:\"tarifs-1024x500.jpg\";s:5:\"width\";i:1024;s:6:\"height\";i:500;s:9:\"mime-type\";s:10:\"image/jpeg\";}}s:10:\"image_meta\";a:10:{s:8:\"aperture\";i:0;s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";i:0;s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";i:0;s:3:\"iso\";i:0;s:13:\"shutter_speed\";i:0;s:5:\"title\";s:0:\"\";}}");
INSERT INTO wp_postmeta VALUES("130","41","_thumbnail_id","52");
INSERT INTO wp_postmeta VALUES("131","41","_wp_page_template","default");
INSERT INTO wp_postmeta VALUES("132","41","layout_key","");
INSERT INTO wp_postmeta VALUES("133","41","post_slider_check_key","0");
INSERT INTO wp_postmeta VALUES("134","53","_menu_item_type","post_type");
INSERT INTO wp_postmeta VALUES("135","53","_menu_item_menu_item_parent","0");
INSERT INTO wp_postmeta VALUES("136","53","_menu_item_object_id","41");
INSERT INTO wp_postmeta VALUES("137","53","_menu_item_object","page");
INSERT INTO wp_postmeta VALUES("138","53","_menu_item_target","");
INSERT INTO wp_postmeta VALUES("139","53","_menu_item_classes","a:1:{i:0;s:0:\"\";}");
INSERT INTO wp_postmeta VALUES("140","53","_menu_item_xfn","");
INSERT INTO wp_postmeta VALUES("141","53","_menu_item_url","");
INSERT INTO wp_postmeta VALUES("142","55","_edit_last","1");
INSERT INTO wp_postmeta VALUES("143","55","_edit_lock","1387453669:1");
INSERT INTO wp_postmeta VALUES("144","55","_thumbnail_id","43");
INSERT INTO wp_postmeta VALUES("145","55","_wp_page_template","page-full-width.php");
INSERT INTO wp_postmeta VALUES("146","55","layout_key","");
INSERT INTO wp_postmeta VALUES("147","55","post_slider_check_key","0");
INSERT INTO wp_postmeta VALUES("148","56","_menu_item_type","post_type");
INSERT INTO wp_postmeta VALUES("149","56","_menu_item_menu_item_parent","0");
INSERT INTO wp_postmeta VALUES("150","56","_menu_item_object_id","55");
INSERT INTO wp_postmeta VALUES("151","56","_menu_item_object","page");
INSERT INTO wp_postmeta VALUES("152","56","_menu_item_target","");
INSERT INTO wp_postmeta VALUES("153","56","_menu_item_classes","a:1:{i:0;s:0:\"\";}");
INSERT INTO wp_postmeta VALUES("154","56","_menu_item_xfn","");
INSERT INTO wp_postmeta VALUES("155","56","_menu_item_url","");
INSERT INTO wp_postmeta VALUES("203","66","_edit_last","1");
INSERT INTO wp_postmeta VALUES("204","66","_edit_lock","1387191848:1");
INSERT INTO wp_postmeta VALUES("205","66","_wp_page_template","default");
INSERT INTO wp_postmeta VALUES("206","66","layout_key","");
INSERT INTO wp_postmeta VALUES("207","66","post_slider_check_key","0");
INSERT INTO wp_postmeta VALUES("216","66","_wp_trash_meta_status","publish");
INSERT INTO wp_postmeta VALUES("217","66","_wp_trash_meta_time","1387198787");
INSERT INTO wp_postmeta VALUES("218","45","_edit_lock","1387199769:1");
INSERT INTO wp_postmeta VALUES("219","52","_edit_lock","1387237602:1");
INSERT INTO wp_postmeta VALUES("220","52","_edit_last","1");
INSERT INTO wp_postmeta VALUES("221","49","_edit_lock","1387200120:1");
INSERT INTO wp_postmeta VALUES("222","49","_edit_last","1");
INSERT INTO wp_postmeta VALUES("223","44","_edit_lock","1387321640:1");
INSERT INTO wp_postmeta VALUES("224","44","_edit_last","1");
INSERT INTO wp_postmeta VALUES("226","47","_edit_lock","1387200177:1");
INSERT INTO wp_postmeta VALUES("227","47","_edit_last","1");
INSERT INTO wp_postmeta VALUES("228","46","_edit_lock","1387200901:1");
INSERT INTO wp_postmeta VALUES("229","46","_edit_last","1");
INSERT INTO wp_postmeta VALUES("230","70","_wp_attached_file","2013/12/ampoule-economie-energie-fluo-compacte-forme-cerveau-600x566.jpg");
INSERT INTO wp_postmeta VALUES("231","70","_wp_attachment_metadata","a:5:{s:5:\"width\";i:600;s:6:\"height\";i:566;s:4:\"file\";s:72:\"2013/12/ampoule-economie-energie-fluo-compacte-forme-cerveau-600x566.jpg\";s:5:\"sizes\";a:2:{s:9:\"thumbnail\";a:4:{s:4:\"file\";s:72:\"ampoule-economie-energie-fluo-compacte-forme-cerveau-600x566-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:6:\"medium\";a:4:{s:4:\"file\";s:72:\"ampoule-economie-energie-fluo-compacte-forme-cerveau-600x566-300x283.jpg\";s:5:\"width\";i:300;s:6:\"height\";i:283;s:9:\"mime-type\";s:10:\"image/jpeg\";}}s:10:\"image_meta\";a:10:{s:8:\"aperture\";i:0;s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";i:0;s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";i:0;s:3:\"iso\";i:0;s:13:\"shutter_speed\";i:0;s:5:\"title\";s:0:\"\";}}");
INSERT INTO wp_postmeta VALUES("232","70","_edit_lock","1387237410:1");
INSERT INTO wp_postmeta VALUES("233","70","_edit_last","1");
INSERT INTO wp_postmeta VALUES("234","24","_edit_lock","1387233481:1");
INSERT INTO wp_postmeta VALUES("235","71","_wp_attached_file","2013/12/aboologo.png");
INSERT INTO wp_postmeta VALUES("236","71","_wp_attachment_metadata","a:5:{s:5:\"width\";i:140;s:6:\"height\";i:110;s:4:\"file\";s:20:\"2013/12/aboologo.png\";s:5:\"sizes\";a:0:{}s:10:\"image_meta\";a:10:{s:8:\"aperture\";i:0;s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";i:0;s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";i:0;s:3:\"iso\";i:0;s:13:\"shutter_speed\";i:0;s:5:\"title\";s:0:\"\";}}");
INSERT INTO wp_postmeta VALUES("237","71","_edit_lock","1387236102:1");
INSERT INTO wp_postmeta VALUES("238","71","_edit_last","1");
INSERT INTO wp_postmeta VALUES("239","43","_edit_lock","1387237524:1");
INSERT INTO wp_postmeta VALUES("240","72","custom_css_add","yes");
INSERT INTO wp_postmeta VALUES("241","72","content_width","");
INSERT INTO wp_postmeta VALUES("242","72","custom_css_preprocessor","");
INSERT INTO wp_postmeta VALUES("243","73","custom_css_add","yes");
INSERT INTO wp_postmeta VALUES("244","73","content_width","");
INSERT INTO wp_postmeta VALUES("245","73","custom_css_preprocessor","");
INSERT INTO wp_postmeta VALUES("246","74","custom_css_add","yes");
INSERT INTO wp_postmeta VALUES("247","74","content_width","");
INSERT INTO wp_postmeta VALUES("248","74","custom_css_preprocessor","");
INSERT INTO wp_postmeta VALUES("249","75","custom_css_add","yes");
INSERT INTO wp_postmeta VALUES("250","75","content_width","");
INSERT INTO wp_postmeta VALUES("251","75","custom_css_preprocessor","");
INSERT INTO wp_postmeta VALUES("252","76","custom_css_add","yes");
INSERT INTO wp_postmeta VALUES("253","76","content_width","");
INSERT INTO wp_postmeta VALUES("254","76","custom_css_preprocessor","");
INSERT INTO wp_postmeta VALUES("255","77","custom_css_add","yes");
INSERT INTO wp_postmeta VALUES("256","77","content_width","");
INSERT INTO wp_postmeta VALUES("257","77","custom_css_preprocessor","");
INSERT INTO wp_postmeta VALUES("258","78","custom_css_add","yes");
INSERT INTO wp_postmeta VALUES("259","78","content_width","");
INSERT INTO wp_postmeta VALUES("260","78","custom_css_preprocessor","");
INSERT INTO wp_postmeta VALUES("261","79","custom_css_add","yes");
INSERT INTO wp_postmeta VALUES("262","79","content_width","");
INSERT INTO wp_postmeta VALUES("263","79","custom_css_preprocessor","");
INSERT INTO wp_postmeta VALUES("264","80","custom_css_add","yes");
INSERT INTO wp_postmeta VALUES("265","80","content_width","");
INSERT INTO wp_postmeta VALUES("266","80","custom_css_preprocessor","");
INSERT INTO wp_postmeta VALUES("276","82","_edit_last","1");
INSERT INTO wp_postmeta VALUES("277","82","_edit_lock","1387453722:1");
INSERT INTO wp_postmeta VALUES("286","85","_menu_item_type","custom");
INSERT INTO wp_postmeta VALUES("287","85","_menu_item_menu_item_parent","13");
INSERT INTO wp_postmeta VALUES("288","85","_menu_item_object_id","85");
INSERT INTO wp_postmeta VALUES("289","85","_menu_item_object","custom");
INSERT INTO wp_postmeta VALUES("290","85","_menu_item_target","");
INSERT INTO wp_postmeta VALUES("291","85","_menu_item_classes","a:1:{i:0;s:0:\"\";}");
INSERT INTO wp_postmeta VALUES("292","85","_menu_item_xfn","");
INSERT INTO wp_postmeta VALUES("293","85","_menu_item_url","http://localhost/gestabo/register.php");
INSERT INTO wp_postmeta VALUES("295","86","_menu_item_type","custom");
INSERT INTO wp_postmeta VALUES("296","86","_menu_item_menu_item_parent","13");
INSERT INTO wp_postmeta VALUES("297","86","_menu_item_object_id","86");
INSERT INTO wp_postmeta VALUES("298","86","_menu_item_object","custom");
INSERT INTO wp_postmeta VALUES("299","86","_menu_item_target","");
INSERT INTO wp_postmeta VALUES("300","86","_menu_item_classes","a:1:{i:0;s:0:\"\";}");
INSERT INTO wp_postmeta VALUES("301","86","_menu_item_xfn","");
INSERT INTO wp_postmeta VALUES("302","86","_menu_item_url","http://localhost/gestabo/oubli.php");
INSERT INTO wp_postmeta VALUES("304","90","_menu_item_type","post_type");
INSERT INTO wp_postmeta VALUES("305","90","_menu_item_menu_item_parent","0");
INSERT INTO wp_postmeta VALUES("306","90","_menu_item_object_id","82");
INSERT INTO wp_postmeta VALUES("307","90","_menu_item_object","page");
INSERT INTO wp_postmeta VALUES("308","90","_menu_item_target","");
INSERT INTO wp_postmeta VALUES("309","90","_menu_item_classes","a:1:{i:0;s:0:\"\";}");
INSERT INTO wp_postmeta VALUES("310","90","_menu_item_xfn","");
INSERT INTO wp_postmeta VALUES("311","90","_menu_item_url","");
INSERT INTO wp_postmeta VALUES("313","91","_wp_attached_file","2013/12/Points-interrogation.jpg");
INSERT INTO wp_postmeta VALUES("314","91","_wp_attachment_metadata","a:5:{s:5:\"width\";i:299;s:6:\"height\";i:266;s:4:\"file\";s:32:\"2013/12/Points-interrogation.jpg\";s:5:\"sizes\";a:1:{s:9:\"thumbnail\";a:4:{s:4:\"file\";s:32:\"Points-interrogation-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";}}s:10:\"image_meta\";a:10:{s:8:\"aperture\";i:0;s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";i:0;s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";i:0;s:3:\"iso\";i:0;s:13:\"shutter_speed\";i:0;s:5:\"title\";s:0:\"\";}}");
INSERT INTO wp_postmeta VALUES("315","92","_wp_attached_file","2013/12/chaise.jpg");
INSERT INTO wp_postmeta VALUES("316","92","_wp_attachment_metadata","a:5:{s:5:\"width\";i:540;s:6:\"height\";i:360;s:4:\"file\";s:18:\"2013/12/chaise.jpg\";s:5:\"sizes\";a:2:{s:9:\"thumbnail\";a:4:{s:4:\"file\";s:18:\"chaise-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:6:\"medium\";a:4:{s:4:\"file\";s:18:\"chaise-300x200.jpg\";s:5:\"width\";i:300;s:6:\"height\";i:200;s:9:\"mime-type\";s:10:\"image/jpeg\";}}s:10:\"image_meta\";a:10:{s:8:\"aperture\";i:0;s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";i:0;s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";i:0;s:3:\"iso\";i:0;s:13:\"shutter_speed\";i:0;s:5:\"title\";s:0:\"\";}}");
INSERT INTO wp_postmeta VALUES("317","91","_edit_lock","1387321363:1");
INSERT INTO wp_postmeta VALUES("318","92","_edit_lock","1387321413:1");
INSERT INTO wp_postmeta VALUES("319","94","_wp_attached_file","2013/12/k15748982.jpg");
INSERT INTO wp_postmeta VALUES("320","94","_wp_attachment_context","custom-background");
INSERT INTO wp_postmeta VALUES("321","94","_wp_attachment_metadata","a:5:{s:5:\"width\";i:170;s:6:\"height\";i:128;s:4:\"file\";s:21:\"2013/12/k15748982.jpg\";s:5:\"sizes\";a:1:{s:9:\"thumbnail\";a:4:{s:4:\"file\";s:21:\"k15748982-150x128.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:128;s:9:\"mime-type\";s:10:\"image/jpeg\";}}s:10:\"image_meta\";a:10:{s:8:\"aperture\";i:0;s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";i:0;s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";i:0;s:3:\"iso\";i:0;s:13:\"shutter_speed\";i:0;s:5:\"title\";s:0:\"\";}}");
INSERT INTO wp_postmeta VALUES("322","94","_wp_attachment_is_custom_background","tonic");
INSERT INTO wp_postmeta VALUES("323","97","_wp_attached_file","2013/12/target2.jpg");
INSERT INTO wp_postmeta VALUES("324","97","_wp_attachment_metadata","a:5:{s:5:\"width\";i:1286;s:6:\"height\";i:1050;s:4:\"file\";s:19:\"2013/12/target2.jpg\";s:5:\"sizes\";a:3:{s:9:\"thumbnail\";a:4:{s:4:\"file\";s:19:\"target2-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:6:\"medium\";a:4:{s:4:\"file\";s:19:\"target2-300x244.jpg\";s:5:\"width\";i:300;s:6:\"height\";i:244;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:5:\"large\";a:4:{s:4:\"file\";s:20:\"target2-1024x836.jpg\";s:5:\"width\";i:1024;s:6:\"height\";i:836;s:9:\"mime-type\";s:10:\"image/jpeg\";}}s:10:\"image_meta\";a:10:{s:8:\"aperture\";i:0;s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";i:0;s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";i:0;s:3:\"iso\";i:0;s:13:\"shutter_speed\";i:0;s:5:\"title\";s:0:\"\";}}");
INSERT INTO wp_postmeta VALUES("325","98","_wp_attached_file","2013/12/people-marketing-strategies-for-small-business.jpg");
INSERT INTO wp_postmeta VALUES("326","98","_wp_attachment_metadata","a:5:{s:5:\"width\";i:1698;s:6:\"height\";i:1131;s:4:\"file\";s:58:\"2013/12/people-marketing-strategies-for-small-business.jpg\";s:5:\"sizes\";a:3:{s:9:\"thumbnail\";a:4:{s:4:\"file\";s:58:\"people-marketing-strategies-for-small-business-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:6:\"medium\";a:4:{s:4:\"file\";s:58:\"people-marketing-strategies-for-small-business-300x199.jpg\";s:5:\"width\";i:300;s:6:\"height\";i:199;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:5:\"large\";a:4:{s:4:\"file\";s:59:\"people-marketing-strategies-for-small-business-1024x682.jpg\";s:5:\"width\";i:1024;s:6:\"height\";i:682;s:9:\"mime-type\";s:10:\"image/jpeg\";}}s:10:\"image_meta\";a:10:{s:8:\"aperture\";d:9;s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:13:\"Canon EOS 20D\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";i:1203279394;s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:3:\"105\";s:3:\"iso\";s:3:\"100\";s:13:\"shutter_speed\";s:5:\"0.005\";s:5:\"title\";s:0:\"\";}}");
INSERT INTO wp_postmeta VALUES("327","97","_edit_lock","1387325527:1");
INSERT INTO wp_postmeta VALUES("328","99","_wp_attached_file","2013/12/prix.jpg");
INSERT INTO wp_postmeta VALUES("329","99","_wp_attachment_metadata","a:5:{s:5:\"width\";i:300;s:6:\"height\";i:300;s:4:\"file\";s:16:\"2013/12/prix.jpg\";s:5:\"sizes\";a:1:{s:9:\"thumbnail\";a:4:{s:4:\"file\";s:16:\"prix-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";}}s:10:\"image_meta\";a:10:{s:8:\"aperture\";i:0;s:6:\"credit\";s:12:\"Erick Nguyen\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";i:1140141897;s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";i:0;s:3:\"iso\";i:0;s:13:\"shutter_speed\";i:0;s:5:\"title\";s:73:\"A chrome-plated Euro symbol isolated on a white background (3D rendering)\";}}");
INSERT INTO wp_postmeta VALUES("330","99","_edit_lock","1387325692:1");
INSERT INTO wp_postmeta VALUES("331","101","_wp_attached_file","2013/12/crm-contacts-rolodex.jpg");
INSERT INTO wp_postmeta VALUES("332","101","_wp_attachment_metadata","a:5:{s:5:\"width\";i:400;s:6:\"height\";i:444;s:4:\"file\";s:32:\"2013/12/crm-contacts-rolodex.jpg\";s:5:\"sizes\";a:2:{s:9:\"thumbnail\";a:4:{s:4:\"file\";s:32:\"crm-contacts-rolodex-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:6:\"medium\";a:4:{s:4:\"file\";s:32:\"crm-contacts-rolodex-270x300.jpg\";s:5:\"width\";i:270;s:6:\"height\";i:300;s:9:\"mime-type\";s:10:\"image/jpeg\";}}s:10:\"image_meta\";a:10:{s:8:\"aperture\";i:0;s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";i:0;s:9:\"copyright\";s:26:\"Copyright: Niklas Carlsson\";s:12:\"focal_length\";i:0;s:3:\"iso\";i:0;s:13:\"shutter_speed\";i:0;s:5:\"title\";s:0:\"\";}}");
INSERT INTO wp_postmeta VALUES("333","105","_edit_last","1");
INSERT INTO wp_postmeta VALUES("334","105","_edit_lock","1387455784:1");
INSERT INTO wp_postmeta VALUES("335","106","dojodigital_toggle_title","on");
INSERT INTO wp_postmeta VALUES("336","106","_menu_item_type","post_type");
INSERT INTO wp_postmeta VALUES("337","106","_menu_item_menu_item_parent","0");
INSERT INTO wp_postmeta VALUES("338","106","_menu_item_object_id","105");
INSERT INTO wp_postmeta VALUES("339","106","_menu_item_object","page");
INSERT INTO wp_postmeta VALUES("340","106","_menu_item_target","");
INSERT INTO wp_postmeta VALUES("341","106","_menu_item_classes","a:1:{i:0;s:0:\"\";}");
INSERT INTO wp_postmeta VALUES("342","106","_menu_item_xfn","");
INSERT INTO wp_postmeta VALUES("343","106","_menu_item_url","");
INSERT INTO wp_postmeta VALUES("344","105","dojodigital_toggle_title","on");
DROP TABLE IF EXISTS wp_posts;CREATE TABLE `wp_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext NOT NULL,
  `post_title` text NOT NULL,
  `post_excerpt` text NOT NULL,
  `post_status` varchar(20) NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) NOT NULL DEFAULT 'open',
  `post_password` varchar(20) NOT NULL DEFAULT '',
  `post_name` varchar(200) NOT NULL DEFAULT '',
  `to_ping` text NOT NULL,
  `pinged` text NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `guid` varchar(255) NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8;
INSERT INTO wp_posts VALUES("1","1","2013-12-14 14:35:17","2013-12-14 14:35:17","Bienvenue dans le Blog.
Bientôt ici des informations sur l\'actualité de Aboo!
&nbsp;
&nbsp;
&nbsp;","Bienvenue!","","publish","open","open","","bienvenue","","","2013-12-16 14:27:12","2013-12-16 13:27:12","","0","http://localhost/gestabo/wordpress/?p=1","0","post","","0");
INSERT INTO wp_posts VALUES("2","1","2013-12-14 14:35:17","2013-12-14 14:35:17","<p style=\"text-align: center;\">[notification type=\"alert-danger\" close=\"true\" ]</p>
<h1 style=\"text-align: center;\"><span style=\"font-family: verdana, geneva; font-size: xx-large;\"><strong>Ouverture du site le 1er Mars 2014!</strong></span></h1>
<p style=\"text-align: center;\">[/notification]</p>","Accueil","","publish","closed","closed","","accueil","","","2013-12-19 13:06:45","2013-12-19 12:06:45","","0","http://localhost/gestabo/wordpress/?page_id=2","0","page","","0");
INSERT INTO wp_posts VALUES("4","1","2013-12-14 17:59:45","2013-12-14 16:59:45","<p>Votre nom (obligatoire)<br />
    [text* your-name] </p>
<p>Votre email (obligatoire)<br />
    [email* your-email] </p>
<p>Sujet<br />
    [text your-subject] </p>
<p>Votre message<br />
    [textarea your-message] </p>
<p>[submit \"Envoyer\"]</p>
[your-subject]
[your-name] <[your-email]>
De : [your-name] <[your-email]>
Sujet : [your-subject]
Corps du message :
[your-message]
--
Cet email a été envoyé via le formulaire de contact de Aboo (http://localhost/gestabo/wordpress)
frederic@meyrou.com
[your-subject]
[your-name] <[your-email]>
Corps du message :
[your-message]
--
Cet email a été envoyé via le formulaire de contact de Aboo (http://localhost/gestabo/wordpress)
[your-email]
Votre message a bien été envoyé. Merci.
Erreur lors de l\'envoi du message. Veuillez réessayer plus tard ou contacter l\'administrateur d\'une autre manière.
Erreur de validation. Veuillez vérifier les champs et soumettre à nouveau.
Erreur lors de l\'envoi du message. Veuillez réessayer plus tard ou contacter l\'administrateur d\'une autre manière.
Merci de bien vouloir accepter les conditions pour continuer.
Veuillez remplir le champ obligatoire.
Le code entré est incorrect.
Le format numérique semble invalide.
Ce nombre est trop petit.
Ce nombre est trop grand.
L\'adresse email semble invalide.
L\'URL semble invalide.
Le numéro de téléphone semble invalide.
Votre réponse est incorrecte.
Le format de date semble invalide.
Cette date est trop tôt.
Cette date est trop tard.
Impossible de télécharger le fichier.
Ce type de fichier n\'est pas autorisé.
Ce fichier est trop volumineux.
Impossible de mettre en ligne le fichier. Une erreur est survenue.","Contactez-moi","","publish","open","open","","formulaire-de-contact-1","","","2013-12-16 11:20:00","2013-12-16 10:20:00","","0","http://localhost/gestabo/wordpress/?post_type=wpcf7_contact_form&#038;p=4","0","wpcf7_contact_form","","0");
INSERT INTO wp_posts VALUES("7","1","2013-12-14 18:46:22","2013-12-14 17:46:22","Bienvenue dans WordPress. Ceci est votre premier article. Modifiez-le ou supprimez-le, puis lancez-vous !
[notification type=\"alert-info\" close=\"true\" ]Title: Lorem ipsum dolor sit amet...[/notification]
[button style=\"btn-primary btn-lg\" icon=\"glyphicon-warning-sign\" align=\"left\" type=\"button\" title=\"Button\"]
&nbsp;
&nbsp;","Article!","","inherit","open","open","","1-autosave-v1","","","2013-12-14 18:46:22","2013-12-14 17:46:22","","1","http://localhost/gestabo/wordpress/1-autosave-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("8","1","2013-12-14 18:45:21","2013-12-14 17:45:21","Bienvenue dans WordPress. Ceci est votre premier article. Modifiez-le ou supprimez-le, puis lancez-vous !
[notification type=\"alert-info\" close=\"true\" ]Title: Lorem ipsum dolor sit amet...[/notification]","Article!","","inherit","open","open","","1-revision-v1","","","2013-12-14 18:45:21","2013-12-14 17:45:21","","1","http://localhost/gestabo/wordpress/1-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("9","1","2013-12-14 18:48:17","2013-12-14 17:48:17","Bienvenue dans WordPress. Ceci est votre premier article. Modifiez-le ou supprimez-le, puis lancez-vous !
[notification type=\"alert-info\" close=\"true\" ]Title: Lorem ipsum dolor sit amet...[/notification]
[button style=\"btn-primary btn-lg\" icon=\"glyphicon-warning-sign\" align=\"left\" type=\"button\" title=\"Button\"]
&nbsp;
[row]
[column lg=\"12\" md=\"12\" sm=\"12\" xs=\"12\" ]
text
[/column]
[column lg=\"2\" md=\"12\" sm=\"12\" xs=\"12\" ]
text
[/column]
[column lg=\"5\" md=\"12\" sm=\"12\" xs=\"12\" ]
text
[/column]
[column lg=\"1\" md=\"12\" sm=\"12\" xs=\"12\" ]
text
[/column]
[/row]","Article!","","inherit","open","open","","1-revision-v1","","","2013-12-14 18:48:17","2013-12-14 17:48:17","","1","http://localhost/gestabo/wordpress/1-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("10","1","2013-12-19 13:02:29","2013-12-19 12:02:29","&nbsp;
<h1><span style=\"font-size: xx-large; font-family: tahoma, arial, helvetica, sans-serif;\"><strong><span style=\"color: #800080;\"><em>Ouverture du site le 1er Mars 2014!</em></span></strong></span></h1>","Accueil","","inherit","open","open","","2-autosave-v1","","","2013-12-19 13:02:29","2013-12-19 12:02:29","","2","http://localhost/gestabo/wordpress/2-autosave-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("11","1","2013-12-14 19:03:07","2013-12-14 18:03:07","Bienvenue sur \"Aboo\" la seule offre en ligne qui va faciliter votre quotidien de professionnel.
Que vous soyez professeur de Yoga, de Pilate, de Danse, enseignant de musique, professionnel du bien-être, vous devez gérer des revenus périodiques basé sur des abonnements.
Vos clients de s’inscrivent pas tous en même temps, sur des périodes distinctes, des tarifs différents, vous devez gérer aussi l\'étalement des paiements... avec tous ces paramètres comment calculer vos revenus mois par mois, quel salaire pouvez-vous vous servir?
\"Aboo\" va vous permettre de répondre à cette question existentielle : \"combien est-ce que je gagne à la fin du mois?!\".
&nbsp;","Accueil","","inherit","open","open","","2-revision-v1","","","2013-12-14 19:03:07","2013-12-14 18:03:07","","2","http://localhost/gestabo/2-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("12","1","2013-12-14 19:03:33","2013-12-14 18:03:33","<strong>Bienvenue sur \"Aboo\" la seule offre en ligne qui va faciliter votre quotidien de professionnel.</strong>
Que vous soyez professeur de Yoga, de Pilate, de Danse, enseignant de musique, professionnel du bien-être, vous devez gérer des revenus périodiques basé sur des abonnements.
Vos clients de s’inscrivent pas tous en même temps, sur des périodes distinctes, des tarifs différents, vous devez gérer aussi l\'étalement des paiements... avec tous ces paramètres comment calculer vos revenus mois par mois, quel salaire pouvez-vous vous servir?
\"Aboo\" va vous permettre de répondre à cette question existentielle : \"combien est-ce que je gagne à la fin du mois?!\".
&nbsp;","Accueil","","inherit","open","open","","2-revision-v1","","","2013-12-14 19:03:33","2013-12-14 18:03:33","","2","http://localhost/gestabo/2-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("13","1","2013-12-14 19:05:24","2013-12-14 18:05:24","","Connexion","","publish","open","open","","connexion","","","2013-12-17 23:48:02","2013-12-17 22:48:02","","0","http://localhost/gestabo/?p=13","6","nav_menu_item","","0");
INSERT INTO wp_posts VALUES("15","1","2013-12-14 19:09:21","2013-12-14 18:09:21","","homepage","","trash","open","open","","homepage","","","2013-12-14 23:53:42","2013-12-14 22:53:42","","0","http://localhost/gestabo/wordpress/?page_id=15","0","page","","0");
INSERT INTO wp_posts VALUES("16","1","2013-12-14 19:09:21","2013-12-14 18:09:21","","homepage","","inherit","open","open","","15-revision-v1","","","2013-12-14 19:09:21","2013-12-14 18:09:21","","15","http://localhost/gestabo/wordpress/15-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("17","1","2013-12-14 23:53:22","2013-12-14 22:53:22","","News","","publish","open","open","","news","","","2013-12-14 23:53:22","2013-12-14 22:53:22","","0","http://localhost/gestabo/wordpress/?page_id=17","0","page","","0");
INSERT INTO wp_posts VALUES("18","1","2013-12-14 23:53:22","2013-12-14 22:53:22","","News","","inherit","open","open","","17-revision-v1","","","2013-12-14 23:53:22","2013-12-14 22:53:22","","17","http://localhost/gestabo/wordpress/17-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("19","1","2013-12-14 23:54:03","2013-12-14 22:54:03"," ","","","publish","open","open","","19","","","2013-12-17 23:48:02","2013-12-17 22:48:02","","0","http://localhost/gestabo/wordpress/?p=19","4","nav_menu_item","","0");
INSERT INTO wp_posts VALUES("20","1","2013-12-14 23:55:15","2013-12-14 22:55:15","","Connexion","","publish","open","open","","connexion-2","","","2013-12-14 23:55:32","2013-12-14 22:55:32","","0","http://localhost/gestabo/wordpress/?p=20","1","nav_menu_item","","0");
INSERT INTO wp_posts VALUES("21","1","2013-12-15 00:00:23","2013-12-14 23:00:23","[notification type=\"alert-info\" close=\"false\" ]<strong>Bienvenue sur \"Aboo\" la seule offre en ligne qui va faciliter votre quotidien de professionnel.</strong>[/notification]
<strong>Bienvenue sur \"Aboo\" la seule offre en ligne qui va faciliter votre quotidien de professionnel.</strong>
Que vous soyez professeur de Yoga, de Pilate, de Danse, enseignant de musique, professionnel du bien-être, vous devez gérer des revenus périodiques basé sur des abonnements.
Vos clients de s’inscrivent pas tous en même temps, sur des périodes distinctes, des tarifs différents, vous devez gérer aussi l\'étalement des paiements... avec tous ces paramètres comment calculer vos revenus mois par mois, quel salaire pouvez-vous vous servir?
\"Aboo\" va vous permettre de répondre à cette question existentielle : \"combien est-ce que je gagne à la fin du mois?!\".
&nbsp;","Accueil","","inherit","open","open","","2-revision-v1","","","2013-12-15 00:00:23","2013-12-14 23:00:23","","2","http://localhost/gestabo/wordpress/2-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("22","1","2013-12-15 00:01:45","2013-12-14 23:01:45","[notification type=\"alert-info\" close=\"false\" ]<strong>Bienvenue sur \"Aboo\" la seule offre en ligne qui va faciliter votre quotidien de professionnel.</strong>[/notification]
<span style=\"line-height: 1.5em;\">Que vous soyez professeur de Yoga, de Pilate, de Danse, enseignant de musique, professionnel du bien-être, vous devez gérer des revenus périodiques basé sur des abonnements.</span>
Vos clients de s’inscrivent pas tous en même temps, sur des périodes distinctes, des tarifs différents, vous devez gérer aussi l\'étalement des paiements... avec tous ces paramètres comment calculer vos revenus mois par mois, quel salaire pouvez-vous vous servir?
\"Aboo\" va vous permettre de répondre à cette question existentielle : \"combien est-ce que je gagne à la fin du mois?!\".
&nbsp;","Accueil","","inherit","open","open","","2-revision-v1","","","2013-12-15 00:01:45","2013-12-14 23:01:45","","2","http://localhost/gestabo/wordpress/2-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("23","1","2013-12-15 00:16:15","2013-12-14 23:16:15","Bienvenue dans WordPress. Ceci est votre premier article. Modifiez-le ou supprimez-le, puis lancez-vous !
[notification type=\"alert-info\" close=\"true\" ]Title: Lorem ipsum dolor sit amet...[/notification]
[button style=\"btn-primary btn-lg\" icon=\"glyphicon-warning-sign\" align=\"left\" type=\"button\" title=\"Button\"]
[button style=\"btn-success btn-lg\" icon=\"glyphicon-lock\" align=\"right\" type=\"link\" target=\"true\" title=\"Connexion\" link=\"http://localhost/gestabo/connexion.php\"]
[row]
[column lg=\"12\" md=\"12\" sm=\"12\" xs=\"12\" ]
text
[/column]
[column lg=\"2\" md=\"12\" sm=\"12\" xs=\"12\" ]
text
[/column]
[column lg=\"5\" md=\"12\" sm=\"12\" xs=\"12\" ]
text
[/column]
[column lg=\"1\" md=\"12\" sm=\"12\" xs=\"12\" ]
text
[/column]
[/row]","Article!","","inherit","open","open","","1-revision-v1","","","2013-12-15 00:16:15","2013-12-14 23:16:15","","1","http://localhost/gestabo/wordpress/1-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("24","1","2013-12-15 01:06:23","2013-12-15 00:06:23","","abonnements_anim","","inherit","closed","open","","abonnements_anim","","","2013-12-15 01:06:23","2013-12-15 00:06:23","","2","http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/abonnements_anim.jpg","0","attachment","image/jpeg","0");
INSERT INTO wp_posts VALUES("25","1","2013-12-15 01:07:07","2013-12-15 00:07:07","<img class=\"size-medium wp-image-24 alignleft\" alt=\"abonnements_anim\" src=\"http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/abonnements_anim-300x138.jpg\" width=\"300\" height=\"138\" />[notification type=\"alert-info\" close=\"false\" ]<strong>Bienvenue sur \"Aboo\" la seule offre en ligne qui va faciliter votre quotidien de professionnel.</strong>[/notification]
<span style=\"line-height: 1.5em;\">Que vous soyez professeur de Yoga, de Pilate, de Danse, enseignant de musique, professionnel du bien-être, vous devez gérer des revenus périodiques basé sur des abonnements.</span>
Vos clients de s’inscrivent pas tous en même temps, sur des périodes distinctes, des tarifs différents, vous devez gérer aussi l\'étalement des paiements... avec tous ces paramètres comment calculer vos revenus mois par mois, quel salaire pouvez-vous vous servir?
\"Aboo\" va vous permettre de répondre à cette question existentielle : \"combien est-ce que je gagne à la fin du mois?!\".
&nbsp;","Accueil","","inherit","closed","open","","2-revision-v1","","","2013-12-15 01:07:07","2013-12-15 00:07:07","","2","http://localhost/gestabo/wordpress/2-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("26","1","2013-12-15 01:07:53","2013-12-15 00:07:53","[notification type=\"alert-info\" close=\"false\" ]<strong>Bienvenue sur \"Aboo\" la seule offre en ligne qui va faciliter votre quotidien de professionnel.</strong>[/notification]
&nbsp;
<img class=\"size-medium wp-image-24 alignleft\" alt=\"abonnements_anim\" src=\"http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/abonnements_anim-300x138.jpg\" width=\"300\" height=\"138\" />
<span style=\"line-height: 1.5em;\">Que vous soyez professeur de Yoga, de Pilate, de Danse, enseignant de musique, professionnel du bien-être, vous devez gérer des revenus périodiques basé sur des abonnements.</span>
Vos clients de s’inscrivent pas tous en même temps, sur des périodes distinctes, des tarifs différents, vous devez gérer aussi l\'étalement des paiements... avec tous ces paramètres comment calculer vos revenus mois par mois, quel salaire pouvez-vous vous servir?
\"Aboo\" va vous permettre de répondre à cette question existentielle : \"combien est-ce que je gagne à la fin du mois?!\".
&nbsp;","Accueil","","inherit","closed","open","","2-revision-v1","","","2013-12-15 01:07:53","2013-12-15 00:07:53","","2","http://localhost/gestabo/wordpress/2-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("27","1","2013-12-15 01:08:34","2013-12-15 00:08:34","[notification type=\"alert-info\" close=\"false\" ]<strong>Bienvenue sur \"Aboo\" la seule offre en ligne qui va faciliter votre quotidien de professionnel.</strong>[/notification]
<img class=\"size-medium wp-image-24 alignleft\" style=\"border: 0px; margin: 10px;\" alt=\"abonnements_anim\" src=\"http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/abonnements_anim-300x138.jpg\" width=\"300\" height=\"138\" />
<span style=\"line-height: 1.5em;\">Que vous soyez professeur de Yoga, de Pilate, de Danse, enseignant de musique, professionnel du bien-être, vous devez gérer des revenus périodiques basé sur des abonnements.</span>
Vos clients de s’inscrivent pas tous en même temps, sur des périodes distinctes, des tarifs différents, vous devez gérer aussi l\'étalement des paiements... avec tous ces paramètres comment calculer vos revenus mois par mois, quel salaire pouvez-vous vous servir?
\"Aboo\" va vous permettre de répondre à cette question existentielle : \"combien est-ce que je gagne à la fin du mois?!\".
&nbsp;","Accueil","","inherit","closed","open","","2-revision-v1","","","2013-12-15 01:08:34","2013-12-15 00:08:34","","2","http://localhost/gestabo/wordpress/2-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("28","1","2013-12-15 01:09:28","2013-12-15 00:09:28","[notification type=\"alert-info\" close=\"false\" ]<strong>Bienvenue sur \"Aboo\" la seule offre en ligne qui va faciliter votre quotidien de professionnel.</strong>[/notification]
<img class=\"size-medium wp-image-24 alignleft\" style=\"border: 0px; margin: 10px;\" alt=\"abonnements_anim\" src=\"http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/abonnements_anim-300x138.jpg\" width=\"300\" height=\"138\" />
<p style=\"text-align: left;\"><span style=\"line-height: 1.5em;\">Que vous soyez professeur de Yoga, de Pilate, de Danse, enseignant de musique, professionnel du bien-être, vous devez gérer des revenus périodiques basé sur des abonnements.</span></p>
<p style=\"text-align: left;\">Vos clients de s’inscrivent pas tous en même temps, sur des périodes distinctes, des tarifs différents, vous devez gérer aussi l\'étalement des paiements... avec tous ces paramètres comment calculer vos revenus mois par mois, quel salaire pouvez-vous vous servir?</p>
<p style=\"text-align: left;\">\"Aboo\" va vous permettre de répondre à cette question existentielle : \"combien est-ce que je gagne à la fin du mois?!\".</p>
 [well type=\"well-sm\"]<br class=\"osc\" />test<br class=\"osc\" />[/well]","Accueil","","inherit","closed","open","","2-revision-v1","","","2013-12-15 01:09:28","2013-12-15 00:09:28","","2","http://localhost/gestabo/wordpress/2-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("29","1","2013-12-15 01:10:00","2013-12-15 00:10:00","[notification type=\"alert-info\" close=\"false\" ]<strong>Bienvenue sur \"Aboo\" la seule offre en ligne qui va faciliter votre quotidien de professionnel.</strong>[/notification]
<img class=\"size-medium wp-image-24 alignleft\" style=\"border: 0px; margin: 10px;\" alt=\"abonnements_anim\" src=\"http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/abonnements_anim-300x138.jpg\" width=\"300\" height=\"138\" />
<p style=\"text-align: left;\"><span style=\"line-height: 1.5em;\">Que vous soyez professeur de Yoga, de Pilate, de Danse, enseignant de musique, professionnel du bien-être, vous devez gérer des revenus périodiques basé sur des abonnements.</span></p>
<p style=\"text-align: left;\">Vos clients de s’inscrivent pas tous en même temps, sur des périodes distinctes, des tarifs différents, vous devez gérer aussi l\'étalement des paiements... avec tous ces paramètres comment calculer vos revenus mois par mois, quel salaire pouvez-vous vous servir?</p>
<p style=\"text-align: left;\"></p>
 [well type=\"well-sm\"]<br class=\"osc\" />\"Aboo\" va vous permettre de répondre à cette question existentielle : \"combien est-ce que je gagne à la fin du mois?!\".<br class=\"osc\" />[/well]","Accueil","","inherit","closed","open","","2-revision-v1","","","2013-12-15 01:10:00","2013-12-15 00:10:00","","2","http://localhost/gestabo/wordpress/2-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("31","1","2013-12-15 01:13:47","2013-12-15 00:13:47","[notification type=\"alert-info\" close=\"false\" ]<strong>Bienvenue sur \"Aboo\" la seule offre en ligne qui va faciliter votre quotidien de professionnel.</strong>[/notification]
[image src=\"http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/abonnements_anim-300x138.jpg\" shape=\"img-rounded\"]
<img class=\"size-medium wp-image-24 alignleft\" style=\"border: 0px; margin-top: 0px; margin-bottom: 0px;\" alt=\"abonnements_anim\" src=\"http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/abonnements_anim-300x138.jpg\" width=\"300\" height=\"138\" />
<p style=\"text-align: left;\"><span style=\"line-height: 1.5em;\">Que vous soyez professeur de Yoga, de Pilate, de Danse, enseignant de musique, professionnel du bien-être, vous devez gérer des revenus périodiques basé sur des abonnements.</span></p>
<p style=\"text-align: left;\">Vos clients de s’inscrivent pas tous en même temps, sur des périodes distinctes, des tarifs différents, vous devez gérer aussi l\'étalement des paiements... avec tous ces paramètres comment calculer vos revenus mois par mois, quel salaire pouvez-vous vous servir?</p>
 [well type=\"well-sm\"]<br class=\"osc\" />\"Aboo\" va vous permettre de répondre à cette question existentielle : \"combien est-ce que je gagne à la fin du mois?!\".<br class=\"osc\" />[/well]","Accueil","","inherit","closed","open","","2-revision-v1","","","2013-12-15 01:13:47","2013-12-15 00:13:47","","2","http://localhost/gestabo/wordpress/2-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("32","1","2013-12-15 01:14:18","2013-12-15 00:14:18","[notification type=\"alert-info\" close=\"false\" ]<strong>Bienvenue sur \"Aboo\" la seule offre en ligne qui va faciliter votre quotidien de professionnel.</strong>[/notification]
[image src=\"http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/abonnements_anim-300x138.jpg\" shape=\"img-rounded\"] <span style=\"line-height: 1.5em;\">Que vous soyez professeur de Yoga, de Pilate, de Danse, enseignant de musique, professionnel du bien-être, vous devez gérer des revenus périodiques basé sur des abonnements.</span>
<p style=\"text-align: left;\">Vos clients de s’inscrivent pas tous en même temps, sur des périodes distinctes, des tarifs différents, vous devez gérer aussi l\'étalement des paiements... avec tous ces paramètres comment calculer vos revenus mois par mois, quel salaire pouvez-vous vous servir?</p>
 [well type=\"well-sm\"]<br class=\"osc\" />\"Aboo\" va vous permettre de répondre à cette question existentielle : \"combien est-ce que je gagne à la fin du mois?!\".<br class=\"osc\" />[/well]","Accueil","","inherit","closed","open","","2-revision-v1","","","2013-12-15 01:14:18","2013-12-15 00:14:18","","2","http://localhost/gestabo/wordpress/2-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("33","1","2013-12-15 01:15:52","2013-12-15 00:15:52","[notification type=\"alert-info\" close=\"false\" ]<strong>Bienvenue sur \"Aboo\" la seule offre en ligne qui va faciliter votre quotidien de professionnel.</strong>[/notification]
[image src=\"http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/abonnements_anim-300x138.jpg\" shape=\"img-circle\"] <span style=\"line-height: 1.5em;\">Que vous soyez professeur de Yoga, de Pilate, de Danse, enseignant de musique, professionnel du bien-être, vous devez gérer des revenus périodiques basé sur des abonnements. </span>Vos clients de s’inscrivent pas tous en même temps, sur des périodes distinctes, des tarifs différents, vous devez gérer aussi l\'étalement des paiements... avec tous ces paramètres comment calculer vos revenus mois par mois, quel salaire pouvez-vous vous servir?
[well type=\"well-sm\"]<br class=\"osc\" />\"Aboo\" va vous permettre de répondre à cette question existentielle : \"combien est-ce que je gagne à la fin du mois?!\".<br class=\"osc\" />[/well]","Accueil","","inherit","closed","open","","2-revision-v1","","","2013-12-15 01:15:52","2013-12-15 00:15:52","","2","http://localhost/gestabo/wordpress/2-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("34","1","2013-12-15 01:16:33","2013-12-15 00:16:33","[notification type=\"alert-info\" close=\"false\" ]<strong>Bienvenue sur \"Aboo\" la seule offre en ligne qui va faciliter votre quotidien de professionnel.</strong>[/notification]
<p style=\"text-align: center;\">[image src=\"http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/abonnements_anim-300x138.jpg\" shape=\"img-circle\"]</p>
<p style=\"text-align: center;\"><span style=\"line-height: 1.5em;\">Que vous soyez professeur de Yoga, de Pilate, de Danse, enseignant de musique, professionnel du bien-être, vous devez gérer des revenus périodiques basé sur des abonnements. </span>Vos clients de s’inscrivent pas tous en même temps, sur des périodes distinctes, des tarifs différents, vous devez gérer aussi l\'étalement des paiements... avec tous ces paramètres comment calculer vos revenus mois par mois, quel salaire pouvez-vous vous servir?</p>
[well type=\"well-sm\"]<br class=\"osc\" />\"Aboo\" va vous permettre de répondre à cette question existentielle : \"combien est-ce que je gagne à la fin du mois?!\".<br class=\"osc\" />[/well]","Accueil","","inherit","closed","open","","2-revision-v1","","","2013-12-15 01:16:33","2013-12-15 00:16:33","","2","http://localhost/gestabo/wordpress/2-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("35","1","2013-12-15 01:20:17","2013-12-15 00:20:17","Bienvenue dans WordPress. Ceci est votre premier article. Modifiez-le ou supprimez-le, puis lancez-vous !
[notification type=\"alert-info\" close=\"true\" ]Title: Lorem ipsum dolor sit amet...[/notification]
[button style=\"btn-primary btn-lg\" icon=\"glyphicon-warning-sign\" align=\"left\" type=\"button\" title=\"Button\"]
[button style=\"btn-success btn-lg\" icon=\"glyphicon-lock\" align=\"right\" type=\"link\" target=\"true\" title=\"Connexion\" link=\"http://localhost/gestabo/connexion.php\"]
&nbsp;","Article!","","inherit","closed","open","","1-revision-v1","","","2013-12-15 01:20:17","2013-12-15 00:20:17","","1","http://localhost/gestabo/wordpress/1-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("36","1","2013-12-15 01:20:26","2013-12-15 00:20:26","Bienvenue dans WordPress. Ceci est votre premier article. Modifiez-le ou supprimez-le, puis lancez-vous !
[button style=\"btn-primary btn-lg\" icon=\"glyphicon-warning-sign\" align=\"left\" type=\"button\" title=\"Button\"]
[button style=\"btn-success btn-lg\" icon=\"glyphicon-lock\" align=\"right\" type=\"link\" target=\"true\" title=\"Connexion\" link=\"http://localhost/gestabo/connexion.php\"]
&nbsp;","Article!","","inherit","closed","open","","1-revision-v1","","","2013-12-15 01:20:26","2013-12-15 00:20:26","","1","http://localhost/gestabo/wordpress/1-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("37","1","2013-12-15 01:21:18","2013-12-15 00:21:18","Bienvenue dans WordPress. Ceci est votre premier article. Modifiez-le ou supprimez-le, puis lancez-vous !
&nbsp;
&nbsp;
&nbsp;","Article!","","inherit","closed","open","","1-revision-v1","","","2013-12-15 01:21:18","2013-12-15 00:21:18","","1","http://localhost/gestabo/wordpress/1-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("38","1","2013-12-16 10:29:39","2013-12-16 09:29:39","Bienvenue dans le Blog.
Bientôt ici des informations sur l\'actualité de Aboo!
&nbsp;
&nbsp;
&nbsp;","Article!","","inherit","closed","open","","1-revision-v1","","","2013-12-16 10:29:39","2013-12-16 09:29:39","","1","http://localhost/gestabo/wordpress/1-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("39","1","2013-12-16 10:30:08","2013-12-16 09:30:08","Bienvenue dans le Blog.
Bientôt ici des informations sur l\'actualité de Aboo!
&nbsp;
&nbsp;
&nbsp;","Bienvenue!","","inherit","closed","open","","1-revision-v1","","","2013-12-16 10:30:08","2013-12-16 09:30:08","","1","http://localhost/gestabo/wordpress/1-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("40","1","2013-12-16 10:53:58","2013-12-16 09:53:58","<p style=\"text-align: center;\">[notification type=\"alert-info\" close=\"false\" ]<strong>Bienvenue sur \"Aboo\" la seule offre en ligne qui va faciliter votre quotidien de professionnel.</strong>[/notification]</p>
<p style=\"text-align: center;\">[image src=\"http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/abonnements_anim-300x138.jpg\" shape=\"img-circle\"]</p>
<p style=\"text-align: center;\"><span style=\"line-height: 1.5em;\">Que vous soyez professeur de Yoga, de Pilate, de Danse, enseignant de musique, professionnel du bien-être, vous devez gérer des revenus périodiques basé sur des abonnements. </span>Vos clients de s’inscrivent pas tous en même temps, sur des périodes distinctes, des tarifs différents, vous devez gérer aussi l\'étalement des paiements... avec tous ces paramètres comment calculer vos revenus mois par mois, quel salaire pouvez-vous vous servir?</p>
<p style=\"text-align: center;\">[well type=\"well-sm\"]<br class=\"osc\" />\"Aboo\" va vous permettre de répondre à cette question existentielle : \"combien est-ce que je gagne à la fin du mois?!\".<br class=\"osc\" />[/well]</p>","Accueil","","inherit","closed","open","","2-revision-v1","","","2013-12-16 10:53:58","2013-12-16 09:53:58","","2","http://localhost/gestabo/wordpress/2-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("41","1","2013-12-16 11:35:34","2013-12-16 10:35:34","<img class=\"alignleft  wp-image-52\" alt=\"tarifs\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/tarifs-150x150.jpg\" /><span style=\"font-family: verdana, geneva; font-size: x-large;\">L\'accès à <strong><span style=\"color: #008000;\"><em>Aboo</em> </span></strong>est <strong>gratuit</strong> à l\'essai les deux premiers mois!</span>
<span style=\"font-family: verdana, geneva; font-size: large;\"><em>(Lors de votre demande de compte utilisateur, une licence temporaire gratuite vous sera accordée pour essayer le site.)</em></span>
&nbsp;
&nbsp;
<span style=\"font-family: verdana, geneva; font-size: large;\">Le tarif d\'Aboo est unique et forfaitaire : <span style=\"color: #ff6600;\"><strong>25€ TTC</strong></span>/Exercice (12 mois).</span>
<span style=\"font-family: verdana, geneva; font-size: large;\">Le paiement peut se faire en ligne par paiement <span style=\"color: #ff6600;\">Paypal</span> ou par envoi de chèque.</span>","Tarifs","","publish","closed","closed","","tarifs","","","2013-12-19 12:59:10","2013-12-19 11:59:10","","0","http://localhost/gestabo/wordpress/?page_id=41","0","page","","0");
INSERT INTO wp_posts VALUES("42","1","2013-12-16 11:25:58","2013-12-16 10:25:58","&nbsp;
<span style=\"color: #ff6600;\"><strong><em><a href=\"http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/zen21.gif\"><img class=\"alignleft  wp-image-46\" alt=\"zen21\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/zen21-150x150.gif\" /></a>Frédéric MEYROU</em></strong></span>
<strong>Tel : <span style=\"color: #008000;\">06.7226.8111</span></strong>
&nbsp;
&nbsp;
&nbsp;
&nbsp;
[contact-form-7 id=\"4\" title=\"Contactez-moi\"]
&nbsp;","Contactez-nous","","publish","closed","closed","","contactez-nous","","","2013-12-17 23:48:24","2013-12-17 22:48:24","","0","http://localhost/gestabo/wordpress/?page_id=42","0","page","","0");
INSERT INTO wp_posts VALUES("43","1","2013-12-16 11:22:44","2013-12-16 10:22:44","","budget","","inherit","closed","open","","budget","","","2013-12-16 11:22:44","2013-12-16 10:22:44","","42","http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/budget.jpg","0","attachment","image/jpeg","0");
INSERT INTO wp_posts VALUES("44","1","2013-12-16 11:22:49","2013-12-16 10:22:49","stones in balanced pile","equilible","","inherit","closed","open","","44","","","2013-12-16 11:22:49","2013-12-16 10:22:49","","42","http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/Cailloux.jpg","0","attachment","image/jpeg","0");
INSERT INTO wp_posts VALUES("45","1","2013-12-16 11:22:51","2013-12-16 10:22:51","","images","","inherit","closed","open","","images","","","2013-12-16 11:22:51","2013-12-16 10:22:51","","42","http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/images.jpg","0","attachment","image/jpeg","0");
INSERT INTO wp_posts VALUES("46","1","2013-12-16 11:22:52","2013-12-16 10:22:52","","zen21","","inherit","closed","open","","zen21","","","2013-12-16 11:22:52","2013-12-16 10:22:52","","42","http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/zen21.gif","0","attachment","image/gif","0");
INSERT INTO wp_posts VALUES("47","1","2013-12-16 11:22:55","2013-12-16 10:22:55","","help","","inherit","closed","open","","help","","","2013-12-16 11:22:55","2013-12-16 10:22:55","","42","http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/help.png","0","attachment","image/png","0");
INSERT INTO wp_posts VALUES("49","1","2013-12-16 11:22:59","2013-12-16 10:22:59","","budget 2","","inherit","closed","open","","budget-2","","","2013-12-16 11:22:59","2013-12-16 10:22:59","","42","http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/budget-2.jpg","0","attachment","image/jpeg","0");
INSERT INTO wp_posts VALUES("50","1","2013-12-16 11:25:58","2013-12-16 10:25:58","","Contactez-nous","","publish","closed","open","","50","","","2013-12-17 23:48:02","2013-12-17 22:48:02","","0","http://localhost/gestabo/wordpress/50/","5","nav_menu_item","","0");
INSERT INTO wp_posts VALUES("51","1","2013-12-16 11:25:58","2013-12-16 10:25:58","<a href=\"http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/contactez-moi.gif\"><img class=\"alignleft size-thumbnail wp-image-48\" alt=\"contactez-moi\" src=\"http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/contactez-moi-150x150.gif\" width=\"150\" height=\"150\" /></a>
&nbsp;
<span style=\"color: #ff6600;\"><strong><em>Frédéric MEYROU</em></strong></span>
<strong>Tel : <span style=\"color: #008000;\">06.7226.8111</span></strong>
&nbsp;
&nbsp;
&nbsp;
&nbsp;
[contact-form-7 id=\"4\" title=\"Contactez-moi\"]
&nbsp;","Contactez-moi","","inherit","closed","open","","42-revision-v1","","","2013-12-16 11:25:58","2013-12-16 10:25:58","","42","http://localhost/gestabo/wordpress/42-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("52","1","2013-12-16 11:35:17","2013-12-16 10:35:17","","tarifs","","inherit","closed","open","","tarifs-2","","","2013-12-16 11:35:17","2013-12-16 10:35:17","","41","http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/tarifs.jpg","0","attachment","image/jpeg","0");
INSERT INTO wp_posts VALUES("53","1","2013-12-16 11:35:34","2013-12-16 10:35:34"," ","","","publish","closed","open","","53","","","2013-12-17 23:48:02","2013-12-17 22:48:02","","0","http://localhost/gestabo/wordpress/53/","3","nav_menu_item","","0");
INSERT INTO wp_posts VALUES("54","1","2013-12-16 11:35:34","2013-12-16 10:35:34","<img class=\"alignleft size-thumbnail wp-image-52\" alt=\"tarifs\" src=\"http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/tarifs-150x150.jpg\" width=\"150\" height=\"150\" />Ici les tarifs de Aboo","Tarifs","","inherit","closed","open","","41-revision-v1","","","2013-12-16 11:35:34","2013-12-16 10:35:34","","41","http://localhost/gestabo/wordpress/41-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("55","1","2013-12-16 11:37:29","2013-12-16 10:37:29","<p style=\"text-align: left;\"></p>
<p style=\"text-align: left;\"><em><span style=\"font-size: x-large; font-family: verdana, geneva;\"><strong><em><span style=\"color: #008000;\">Aboo</span> est un outil de gestion en ligne</em><em> </em>qui va faciliter votre quotidien.</strong></span></em></p>
<em style=\"font-family: \'Times New Roman\'; font-variant: normal; line-height: 22px; font-size: 16px;\"><span style=\"font-size: x-large; font-family: verdana, geneva; color: #ff6600;\"><img class=\"size-thumbnail wp-image-70 alignright\" style=\"border: 0px;\" alt=\"ampoule-economie-energie-fluo-compacte-forme-cerveau-600x566\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/ampoule-economie-energie-fluo-compacte-forme-cerveau-600x566-150x150.jpg\" width=\"150\" height=\"150\" /></span></em>
<p style=\"text-align: left;\"><span style=\"font-size: large; font-family: verdana, geneva;\"><span style=\"line-height: 1.5em;\">Que</span></span><span style=\"font-size: large; font-family: verdana, geneva;\"><span style=\"line-height: 1.5em;\"> vous soyez professeur de <span style=\"color: #800080;\">Yoga</span>, <span style=\"color: #800080;\">Pilate</span>, <span style=\"color: #800080;\">Sophrologie</span>, <span style=\"color: #800080;\">Danse</span>, <span style=\"color: #800080;\">Sport</span>... <span style=\"color: #800080;\">Enseignant de musique</span>, <span style=\"color: #800080;\">professionnel du bien-être </span>(<span style=\"color: #800080;\">wellness</span>), vous devez gérer des revenus périodiques basé sur des abonnements.</span></span></p>
<p style=\"text-align: left;\"><span style=\"font-size: large; font-family: verdana, geneva;\">Le problème est toujours le même : vos clients s’inscrivent tout au long de l\'année sur des périodes distinctes (mois / trim</span><span style=\"font-size: large; font-family: verdana, geneva;\">estre / année / ...), avec des tarifs différents, et vous devez gérez l\'étalement des paiements... </span><span style=\"font-size: large; font-family: verdana, geneva;\">avec tous ces paramètres comment </span><span style=\"font-size: large; font-family: verdana, geneva;\">calculer vos revenus mois par mois, quel salaire pouvez-vous vous réellement vous servir en fonction des charges à venir?</span></p>
<p style=\"text-align: left;\"><span style=\"font-size: large; font-family: verdana, geneva;\"><img class=\"size-medium wp-image-49 alignleft\" style=\"border: 0px;\" alt=\"budget 2\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/budget-2-300x173.jpg\" width=\"300\" height=\"173\" />Ce calcul est un vrai casse tête chinois et si vous le faite mal, cela peu vous faire faire de grave erreur de gestion de trésorerie...</span></p>
<p style=\"text-align: left;\"><span style=\"font-size: large; font-family: verdana, geneva;\"><strong><em> <span style=\"color: #008000;\">Aboo</span></em></strong><span style=\"color: #008000;\"> </span>va vous permettre de répondre à cette question existentielle : \"<span style=\"color: #ff6600;\"><strong>combien est-ce que je gagne à la fin du mois?!</strong></span>\"</span></p>
<p style=\"text-align: left;\"><span style=\"font-family: verdana, geneva; font-size: large;\">D\'autre part <strong>Aboo</strong> va aussi vous aider à gérer votre fichier de clients pour les relances (paiements / abonnements / campagnes...).</span></p>
&nbsp;
<span style=\"font-size: 16px;\"> </span>
<span style=\"text-decoration: underline;\"><span style=\"font-family: verdana, geneva;\"><strong><em style=\"font-size: 16px;\"><img class=\"alignright size-medium wp-image-43\" style=\"border: 0px;\" alt=\"budget\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/budget-300x199.jpg\" width=\"300\" height=\"199\" />Les fonctionnalités principales sont les suivantes :</em><span style=\"font-size: 16px;\"> </span></strong></span></span>
<ul>
	<li><span style=\"font-family: verdana, geneva; font-size: medium; color: #ff6600;\">Journal des dépenses / recettes</span></li>
	<li><span style=\"font-family: verdana, geneva; font-size: medium;\">Ventilation des abonnements de 2 à 12 mois sur l\'exercice</span></li>
	<li><span style=\"font-family: verdana, geneva; font-size: medium; color: #ff6600;\">Suivi des paiements</span></li>
	<li><span style=\"font-family: verdana, geneva; font-size: medium;\">Démarrage de l\'exercice de 12 mois paramétrable</span></li>
	<li><span style=\"font-family: verdana, geneva; font-size: medium; color: #ff6600;\">Calcul du salaire mensuel</span></li>
	<li><span style=\"font-family: verdana, geneva; font-size: medium;\">Calcul de la trésorerie</span></li>
	<li><span style=\"font-family: verdana, geneva; font-size: medium;\"><span style=\"color: #ff6600;\">Tableau de bord</span> mensuel et annuel (Chiffre d\'affaire / Dépenses / Salaire / Trésorerie ...)</span></li>
</ul>
&nbsp;
<ul>
	<li><strong style=\"font-family: sans-serif; font-size: medium; font-style: normal; font-variant: normal; line-height: normal;\"><span style=\"font-size: large;\"><span style=\"font-family: verdana, geneva;\">Gestion du fichier client :</span></span></strong></li>
</ul>
<span style=\"font-size: large;\"><em><span style=\"font-family: verdana, geneva;\"><img class=\" wp-image-101 alignleft\" style=\"border: 0px; margin-left: 15px; margin-right: 15px;\" alt=\"crm-contacts-rolodex\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/crm-contacts-rolodex-270x300.jpg\" width=\"216\" height=\"240\" /></span></em></span>
<span style=\"font-family: verdana, geneva; font-size: medium;\">- Suivi du listing client</span>
<span style=\"font-family: verdana, geneva; font-size: medium;\">- Relance par eMail des abonnements expirés</span>
<span style=\"font-family: verdana, geneva; font-size: medium;\">- Relance par eMail des paiements</span>
<span style=\"font-family: verdana, geneva; font-size: medium;\">- Campagne d\'information </span>","Que fait Aboo?","","publish","closed","closed","","que-fait-aboo","","","2013-12-19 12:49:41","2013-12-19 11:49:41","","0","http://localhost/gestabo/wordpress/?page_id=55","0","page","","0");
INSERT INTO wp_posts VALUES("56","1","2013-12-16 11:37:29","2013-12-16 10:37:29"," ","","","publish","closed","open","","56","","","2013-12-17 23:48:02","2013-12-17 22:48:02","","0","http://localhost/gestabo/wordpress/56/","1","nav_menu_item","","0");
INSERT INTO wp_posts VALUES("57","1","2013-12-16 11:37:29","2013-12-16 10:37:29","Aboo est un service en ligne ....<a href=\"http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/budget.jpg\"><img class=\"alignleft size-thumbnail wp-image-43\" alt=\"budget\" src=\"http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/budget-150x150.jpg\" width=\"150\" height=\"150\" /></a>","Que fait Aboo?","","inherit","closed","open","","55-revision-v1","","","2013-12-16 11:37:29","2013-12-16 10:37:29","","55","http://localhost/gestabo/wordpress/55-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("58","1","2013-12-16 11:37:49","2013-12-16 10:37:49","Aboo est un service en ligne ....<img class=\"alignleft size-thumbnail wp-image-43\" style=\"margin: 10px; border: 0px;\" alt=\"budget\" src=\"http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/budget-150x150.jpg\" width=\"150\" height=\"150\" />","Que fait Aboo?","","inherit","closed","open","","55-revision-v1","","","2013-12-16 11:37:49","2013-12-16 10:37:49","","55","http://localhost/gestabo/wordpress/55-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("65","1","2013-12-16 12:00:53","2013-12-16 11:00:53","&nbsp;
<span style=\"color: #ff6600;\"><strong><em><img class=\"alignleft size-thumbnail wp-image-64\" alt=\"ContactUs\" src=\"http://localhost/gestabo/wordpress/wp-content/uploads/2013/12/ContactUs-150x150.jpg\" width=\"150\" height=\"150\" />Frédéric MEYROU</em></strong></span>
<strong>Tel : <span style=\"color: #008000;\">06.7226.8111</span></strong>
&nbsp;
&nbsp;
&nbsp;
&nbsp;
[contact-form-7 id=\"4\" title=\"Contactez-moi\"]
&nbsp;","Contactez-moi","","inherit","closed","open","","42-revision-v1","","","2013-12-16 12:00:53","2013-12-16 11:00:53","","42","http://localhost/gestabo/wordpress/42-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("66","1","2013-12-16 12:05:33","2013-12-16 11:05:33","[button style=\"btn-success btn-lg\" icon=\"glyphicon-lock\" align=\"left\" type=\"link\" target=\"true\" title=\"Connexion\" link=\"http://localhost/gestabo/connexion.php\"]","Connexion","","trash","closed","open","","connexion","","","2013-12-16 13:59:47","2013-12-16 12:59:47","","0","http://localhost/gestabo/wordpress/?page_id=66","0","page","","0");
INSERT INTO wp_posts VALUES("68","1","2013-12-16 12:05:33","2013-12-16 11:05:33","[button style=\"btn-success btn-lg\" icon=\"glyphicon-lock\" align=\"left\" type=\"link\" target=\"true\" title=\"Connexion\" link=\"http://localhost/gestabo/connexion.php\"]","Connexion","","inherit","closed","open","","66-revision-v1","","","2013-12-16 12:05:33","2013-12-16 11:05:33","","66","http://localhost/gestabo/wordpress/66-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("69","1","2013-12-16 14:24:14","2013-12-16 13:24:14","<img class=\"alignleft  wp-image-52\" alt=\"tarifs\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/tarifs-150x150.jpg\" />Ici les tarifs de Aboo","Tarifs","","inherit","closed","open","","41-revision-v1","","","2013-12-16 14:24:14","2013-12-16 13:24:14","","41","http://localhost/gestabo/41-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("70","1","2013-12-16 23:31:31","2013-12-16 22:31:31","","ampoule-economie-energie-fluo-compacte-forme-cerveau-600x566","","inherit","closed","open","","ampoule-economie-energie-fluo-compacte-forme-cerveau-600x566","","","2013-12-16 23:31:31","2013-12-16 22:31:31","","2","http://localhost/gestabo/wp-content/uploads/2013/12/ampoule-economie-energie-fluo-compacte-forme-cerveau-600x566.jpg","0","attachment","image/jpeg","0");
INSERT INTO wp_posts VALUES("71","1","2013-12-17 00:05:44","2013-12-16 23:05:44","","aboologo","","inherit","closed","open","","aboologo","","","2013-12-17 00:05:44","2013-12-16 23:05:44","","0","http://localhost/gestabo/wp-content/uploads/2013/12/aboologo.png","0","attachment","image/png","0");
INSERT INTO wp_posts VALUES("72","1","2013-12-17 00:51:35","2013-12-16 23:51:35","/*
Bienvenue dans l&rsquo;éditeur CSS de l&rsquo;extension Design !
CSS (Cascading Style Sheets) est un langage qui fournit des informations à
votre navigateur concernant le style de la page web que vous visitez. Vous
pouvez maintenant supprimer ces commentaires et commencer à ajouter votre
propre code CSS.
Par défaut, cette feuille de style sera chargée après la feuille de
style de votre thème, ce qui veut dire que les nouvelles règles que vous
ajouterez ici pourront remplacer celles créées par le thème.
Vous pouvez donc ajouter ici les changements que vous souhaitez apporter à
votre thème, sans avoir à copier la feuille de style existante de
celui-ci, ou avoir à recréer toutes les règles de style de votre thème.
*/
#site-title a {
	font-size: 110px;
	text-decoration: none;
	margin: 40px;
}
.basic .jumbotron h1 {
	color: #BDBDBD;
	margin-top: 20px;
	text-shadow: 2px 2px 2px rgba(0,0,0,1);
	font-style: italic;
}
img {
	box-shadow: 1px 1px 12px #555;
	border-radius: 5px;
	-moz-border-radius: 5px;
	-moz-box-shadow: 1px 1px 12px #555;
	-webkit-box-shadow: 1px 1px 12px #555;
	-webkit-border-radius: 5px;
}","safecss","Tonic","publish","closed","open","","safecss","","","2013-12-17 01:00:44","2013-12-17 00:00:44","#site-title a{font-size:110px;text-decoration:none;margin:40px}.basic .jumbotron h1{color:#BDBDBD;margin-top:20px;text-shadow:2px 2px 2px rgba(0,0,0,1);font-style:italic}img{box-shadow:1px 1px 12px #555;border-radius:5px;-moz-border-radius:5px;-moz-box-shadow:1px 1px 12px #555;-webkit-box-shadow:1px 1px 12px #555;-webkit-border-radius:5px}","0","http://localhost/gestabo/?safecss=safecss","0","safecss","","0");
INSERT INTO wp_posts VALUES("73","1","2013-12-17 00:52:13","2013-12-16 23:52:13","/*
Bienvenue dans l&rsquo;éditeur CSS de l&rsquo;extension Design !
CSS (Cascading Style Sheets) est un langage qui fournit des informations à
votre navigateur concernant le style de la page web que vous visitez. Vous
pouvez maintenant supprimer ces commentaires et commencer à ajouter votre
propre code CSS.
Par défaut, cette feuille de style sera chargée après la feuille de
style de votre thème, ce qui veut dire que les nouvelles règles que vous
ajouterez ici pourront remplacer celles créées par le thème.
Vous pouvez donc ajouter ici les changements que vous souhaitez apporter à
votre thème, sans avoir à copier la feuille de style existante de
celui-ci, ou avoir à recréer toutes les règles de style de votre thème.
*/
#site-title a {
	font-size: 110px;
}","safecss","Tonic","inherit","closed","open","","72-revision-v1","","","2013-12-17 00:52:13","2013-12-16 23:52:13","","72","http://localhost/gestabo/72-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("74","1","2013-12-17 00:53:42","2013-12-16 23:53:42","/*
Bienvenue dans l&rsquo;éditeur CSS de l&rsquo;extension Design !
CSS (Cascading Style Sheets) est un langage qui fournit des informations à
votre navigateur concernant le style de la page web que vous visitez. Vous
pouvez maintenant supprimer ces commentaires et commencer à ajouter votre
propre code CSS.
Par défaut, cette feuille de style sera chargée après la feuille de
style de votre thème, ce qui veut dire que les nouvelles règles que vous
ajouterez ici pourront remplacer celles créées par le thème.
Vous pouvez donc ajouter ici les changements que vous souhaitez apporter à
votre thème, sans avoir à copier la feuille de style existante de
celui-ci, ou avoir à recréer toutes les règles de style de votre thème.
*/
#site-title a {
	font-size: 110px;
	text-decoration: none;
}","safecss","Tonic","inherit","closed","open","","72-revision-v1","","","2013-12-17 00:53:42","2013-12-16 23:53:42","","72","http://localhost/gestabo/72-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("75","1","2013-12-17 00:55:26","2013-12-16 23:55:26","/*
Bienvenue dans l&rsquo;éditeur CSS de l&rsquo;extension Design !
CSS (Cascading Style Sheets) est un langage qui fournit des informations à
votre navigateur concernant le style de la page web que vous visitez. Vous
pouvez maintenant supprimer ces commentaires et commencer à ajouter votre
propre code CSS.
Par défaut, cette feuille de style sera chargée après la feuille de
style de votre thème, ce qui veut dire que les nouvelles règles que vous
ajouterez ici pourront remplacer celles créées par le thème.
Vous pouvez donc ajouter ici les changements que vous souhaitez apporter à
votre thème, sans avoir à copier la feuille de style existante de
celui-ci, ou avoir à recréer toutes les règles de style de votre thème.
*/
#site-title a {
	font-size: 110px;
	text-decoration: none;
}
.basic .jumbotron h1 {
	color: #BDBDBD;
	margin-top: 20px;
	text-shadow: 2px 2px 2px rgba(0,0,0,1);
	font-style: italic;
}
img {
	box-shadow: 1px 1px 12px #555;
	border-radius: 5px;
	-moz-border-radius: 5px;
	-moz-box-shadow: 1px 1px 12px #555;
	-webkit-box-shadow: 1px 1px 12px #555;
	-webkit-border-radius: 5px;
}","safecss","Tonic","inherit","closed","open","","72-revision-v1","","","2013-12-17 00:55:26","2013-12-16 23:55:26","","72","http://localhost/gestabo/72-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("76","1","2013-12-17 00:56:58","2013-12-16 23:56:58","/*
Bienvenue dans l&rsquo;éditeur CSS de l&rsquo;extension Design !
CSS (Cascading Style Sheets) est un langage qui fournit des informations à
votre navigateur concernant le style de la page web que vous visitez. Vous
pouvez maintenant supprimer ces commentaires et commencer à ajouter votre
propre code CSS.
Par défaut, cette feuille de style sera chargée après la feuille de
style de votre thème, ce qui veut dire que les nouvelles règles que vous
ajouterez ici pourront remplacer celles créées par le thème.
Vous pouvez donc ajouter ici les changements que vous souhaitez apporter à
votre thème, sans avoir à copier la feuille de style existante de
celui-ci, ou avoir à recréer toutes les règles de style de votre thème.
*/
#site-title a {
	font-size: 110px;
	text-decoration: none;
}
img {
	box-shadow: 1px 1px 12px #555;
	border-radius: 5px;
	-moz-border-radius: 5px;
	-moz-box-shadow: 1px 1px 12px #555;
	-webkit-box-shadow: 1px 1px 12px #555;
	-webkit-border-radius: 5px;
}","safecss","Tonic","inherit","closed","open","","72-revision-v1","","","2013-12-17 00:56:58","2013-12-16 23:56:58","","72","http://localhost/gestabo/72-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("77","1","2013-12-17 00:57:13","2013-12-16 23:57:13","/*
Bienvenue dans l&rsquo;éditeur CSS de l&rsquo;extension Design !
CSS (Cascading Style Sheets) est un langage qui fournit des informations à
votre navigateur concernant le style de la page web que vous visitez. Vous
pouvez maintenant supprimer ces commentaires et commencer à ajouter votre
propre code CSS.
Par défaut, cette feuille de style sera chargée après la feuille de
style de votre thème, ce qui veut dire que les nouvelles règles que vous
ajouterez ici pourront remplacer celles créées par le thème.
Vous pouvez donc ajouter ici les changements que vous souhaitez apporter à
votre thème, sans avoir à copier la feuille de style existante de
celui-ci, ou avoir à recréer toutes les règles de style de votre thème.
*/
#site-title a {
	font-size: 110px;
	text-decoration: none;
}
.basic .jumbotron h1 {
	color: #BDBDBD;
	margin-top: 20px;
	text-shadow: 2px 2px 2px rgba(0,0,0,1);
	font-style: italic;
}
img {
	box-shadow: 1px 1px 12px #555;
	border-radius: 5px;
	-moz-border-radius: 5px;
	-moz-box-shadow: 1px 1px 12px #555;
	-webkit-box-shadow: 1px 1px 12px #555;
	-webkit-border-radius: 5px;
}","safecss","Tonic","inherit","closed","open","","72-revision-v1","","","2013-12-17 00:57:13","2013-12-16 23:57:13","","72","http://localhost/gestabo/72-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("78","1","2013-12-17 00:59:51","2013-12-16 23:59:51","/*
Bienvenue dans l&rsquo;éditeur CSS de l&rsquo;extension Design !
CSS (Cascading Style Sheets) est un langage qui fournit des informations à
votre navigateur concernant le style de la page web que vous visitez. Vous
pouvez maintenant supprimer ces commentaires et commencer à ajouter votre
propre code CSS.
Par défaut, cette feuille de style sera chargée après la feuille de
style de votre thème, ce qui veut dire que les nouvelles règles que vous
ajouterez ici pourront remplacer celles créées par le thème.
Vous pouvez donc ajouter ici les changements que vous souhaitez apporter à
votre thème, sans avoir à copier la feuille de style existante de
celui-ci, ou avoir à recréer toutes les règles de style de votre thème.
*/
#site-title a {
	font-size: 110px;
	text-decoration: none;
	margin: 10px;
}
.basic .jumbotron h1 {
	color: #BDBDBD;
	margin-top: 20px;
	text-shadow: 2px 2px 2px rgba(0,0,0,1);
	font-style: italic;
}
img {
	box-shadow: 1px 1px 12px #555;
	border-radius: 5px;
	-moz-border-radius: 5px;
	-moz-box-shadow: 1px 1px 12px #555;
	-webkit-box-shadow: 1px 1px 12px #555;
	-webkit-border-radius: 5px;
}","safecss","Tonic","inherit","closed","open","","72-revision-v1","","","2013-12-17 00:59:51","2013-12-16 23:59:51","","72","http://localhost/gestabo/72-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("79","1","2013-12-17 01:00:15","2013-12-17 00:00:15","/*
Bienvenue dans l&rsquo;éditeur CSS de l&rsquo;extension Design !
CSS (Cascading Style Sheets) est un langage qui fournit des informations à
votre navigateur concernant le style de la page web que vous visitez. Vous
pouvez maintenant supprimer ces commentaires et commencer à ajouter votre
propre code CSS.
Par défaut, cette feuille de style sera chargée après la feuille de
style de votre thème, ce qui veut dire que les nouvelles règles que vous
ajouterez ici pourront remplacer celles créées par le thème.
Vous pouvez donc ajouter ici les changements que vous souhaitez apporter à
votre thème, sans avoir à copier la feuille de style existante de
celui-ci, ou avoir à recréer toutes les règles de style de votre thème.
*/
#site-title a {
	font-size: 110px;
	text-decoration: none;
	margin: 25px;
}
.basic .jumbotron h1 {
	color: #BDBDBD;
	margin-top: 20px;
	text-shadow: 2px 2px 2px rgba(0,0,0,1);
	font-style: italic;
}
img {
	box-shadow: 1px 1px 12px #555;
	border-radius: 5px;
	-moz-border-radius: 5px;
	-moz-box-shadow: 1px 1px 12px #555;
	-webkit-box-shadow: 1px 1px 12px #555;
	-webkit-border-radius: 5px;
}","safecss","Tonic","inherit","closed","open","","72-revision-v1","","","2013-12-17 01:00:15","2013-12-17 00:00:15","","72","http://localhost/gestabo/72-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("80","1","2013-12-17 01:00:44","2013-12-17 00:00:44","/*
Bienvenue dans l&rsquo;éditeur CSS de l&rsquo;extension Design !
CSS (Cascading Style Sheets) est un langage qui fournit des informations à
votre navigateur concernant le style de la page web que vous visitez. Vous
pouvez maintenant supprimer ces commentaires et commencer à ajouter votre
propre code CSS.
Par défaut, cette feuille de style sera chargée après la feuille de
style de votre thème, ce qui veut dire que les nouvelles règles que vous
ajouterez ici pourront remplacer celles créées par le thème.
Vous pouvez donc ajouter ici les changements que vous souhaitez apporter à
votre thème, sans avoir à copier la feuille de style existante de
celui-ci, ou avoir à recréer toutes les règles de style de votre thème.
*/
#site-title a {
	font-size: 110px;
	text-decoration: none;
	margin: 40px;
}
.basic .jumbotron h1 {
	color: #BDBDBD;
	margin-top: 20px;
	text-shadow: 2px 2px 2px rgba(0,0,0,1);
	font-style: italic;
}
img {
	box-shadow: 1px 1px 12px #555;
	border-radius: 5px;
	-moz-border-radius: 5px;
	-moz-box-shadow: 1px 1px 12px #555;
	-webkit-box-shadow: 1px 1px 12px #555;
	-webkit-border-radius: 5px;
}","safecss","Tonic","inherit","closed","open","","72-revision-v1","","","2013-12-17 01:00:44","2013-12-17 00:00:44","","72","http://localhost/gestabo/72-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("82","1","2013-12-17 21:33:01","2013-12-17 20:33:01","&nbsp;
<a style=\"font-size: 16px;\" href=\"http://localhost/gestabo/wp-content/uploads/2013/12/Cailloux.jpg\"><img class=\"alignleft size-thumbnail wp-image-44\" alt=\"equilible\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/Cailloux-150x150.jpg\" width=\"150\" height=\"150\" /><span style=\"font-family: verdana, geneva; font-size: large;\"><em><span style=\"color: #000000;\">Aboo est un système de gestion particulièrement adapté pour les petites structures utilisant les régimes suivants :</span></em></span></a>
<p style=\"padding-left: 90px;\"><span style=\"font-family: verdana, geneva; font-size: large;\"><span style=\"color: #ff6600;\">Auto-Entrepreneur</span><span style=\"font-variant: inherit; font-weight: inherit; line-height: inherit; text-align: right;\"> et </span><span style=\"color: #ff6600;\">Entreprise Individuelle</span><span style=\"font-variant: inherit; font-weight: inherit; line-height: inherit; text-align: right;\"> (Micro BNC) en </span><span style=\"color: #ff6600;\">franchise de TVA</span><span style=\"font-variant: inherit; font-weight: inherit; line-height: inherit; text-align: right;\">.</span></span></p>
<p style=\"padding-left: 90px;\"><span style=\"font-family: verdana, geneva; font-size: large;\"><span style=\"color: #ff6600;\">Association loi 1901</span><span style=\"font-variant: inherit; font-weight: inherit; line-height: inherit; text-align: right;\"> en franchise de TVA.</span></span></p>
<p style=\"padding-left: 90px;\"><span style=\"font-family: verdana, geneva; font-size: large;\">Toutes ces structures juridiques et fiscales n\'ont pas </span><span style=\"font-family: verdana, geneva; font-size: large;\">obligation de faire une comptabilité classique avec un bilan annuel. Par conséquent Aboo apportera une réponse simple et efficace pour l\'entrepreneur soucieux de piloter ses finances au mieux et répondra aux obligations légales.</span></p>
<span style=\"font-family: verdana, geneva; font-size: large;\"> </span>","Pour qui?","","publish","closed","closed","","pour-qui","","","2013-12-19 12:50:33","2013-12-19 11:50:33","","0","http://localhost/gestabo/?page_id=82","0","page","","0");
INSERT INTO wp_posts VALUES("84","1","2013-12-17 21:33:01","2013-12-17 20:33:01","<a href=\"http://localhost/gestabo/wp-content/uploads/2013/12/Cailloux.jpg\"><img class=\"alignleft size-thumbnail wp-image-44\" alt=\"equilible\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/Cailloux-150x150.jpg\" width=\"150\" height=\"150\" /></a>","Pour qui?","","inherit","closed","open","","82-revision-v1","","","2013-12-17 21:33:01","2013-12-17 20:33:01","","82","http://localhost/gestabo/82-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("85","1","2013-12-17 23:47:27","2013-12-17 22:47:27","","Créer mon compte","","publish","closed","open","","creer-mon-compte","","","2013-12-17 23:48:02","2013-12-17 22:48:02","","0","http://localhost/gestabo/?p=85","7","nav_menu_item","","0");
INSERT INTO wp_posts VALUES("86","1","2013-12-17 23:47:27","2013-12-17 22:47:27","","Mot de passe oublié?","","publish","closed","open","","mot-de-passe-oublie","","","2013-12-17 23:48:02","2013-12-17 22:48:02","","0","http://localhost/gestabo/?p=86","8","nav_menu_item","","0");
INSERT INTO wp_posts VALUES("88","1","2013-12-18 01:01:45","2013-12-18 00:01:45","<a style=\"font-size: 16px;\" href=\"http://localhost/gestabo/wp-content/uploads/2013/12/Cailloux.jpg\"><img class=\"alignleft size-thumbnail wp-image-44\" alt=\"equilible\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/Cailloux-150x150.jpg\" width=\"150\" height=\"150\" /><span style=\"font-family: verdana, geneva; font-size: large;\"><em><span style=\"color: #000000;\">Aboo est un système de gestion particulièrement adapté pour les petites structures utilisant les régimes suivants :</span></em></span></a>
<p style=\"padding-left: 90px;\"><span style=\"font-family: verdana, geneva; font-size: large;\"><span style=\"color: #ff6600;\">Auto-Entrepreneur</span><span style=\"font-variant: inherit; font-weight: inherit; line-height: inherit; text-align: right;\"> et </span><span style=\"color: #ff6600;\">Entreprise Individuelle</span><span style=\"font-variant: inherit; font-weight: inherit; line-height: inherit; text-align: right;\"> (Micro BNC) en </span><span style=\"color: #ff6600;\">franchise de TVA</span><span style=\"font-variant: inherit; font-weight: inherit; line-height: inherit; text-align: right;\">.</span></span></p>
<p style=\"padding-left: 90px;\"><span style=\"font-family: verdana, geneva; font-size: large;\"><span style=\"color: #ff6600;\">Association loi 1901</span><span style=\"font-variant: inherit; font-weight: inherit; line-height: inherit; text-align: right;\"> en franchise de TVA.</span></span></p>
<p style=\"padding-left: 90px;\"><span style=\"font-family: verdana, geneva; font-size: large;\">Toutes ces structures juridiques et fiscales n\'ont pas </span><span style=\"font-family: verdana, geneva; font-size: large;\">obligation de faire une comptabilité classique avec un bilan annuel. Par conséquent Aboo apportera une réponse simple et efficace pour l\'entrepreneur soucieux de piloter ses finances au mieux et répondra aux obligations légales.</span></p>
<span style=\"font-family: verdana, geneva; font-size: large;\"> </span>","Pour qui?","","inherit","closed","open","","82-autosave-v1","","","2013-12-18 01:01:45","2013-12-18 00:01:45","","82","http://localhost/gestabo/82-autosave-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("89","1","2013-12-17 23:46:59","2013-12-17 22:46:59","<a style=\"font-size: 16px;\" href=\"http://localhost/gestabo/wp-content/uploads/2013/12/Cailloux.jpg\"><img class=\"alignleft size-thumbnail wp-image-44\" alt=\"equilible\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/Cailloux-150x150.jpg\" width=\"150\" height=\"150\" /><span style=\"font-family: verdana, geneva;\"><em><span style=\"color: #000000;\">Aboo est un système de gestion particulièrement adapté pour les petites structures utilisant les régimes suivants :</span></em></span></a>
<ul>
	<li><span style=\"font-family: verdana, geneva;\"><span style=\"background-color: #ffffff; color: #ff6600;\">Auto-Entrepreneur</span> et <span style=\"color: #ff6600;\">Entreprise Individuelle</span> (Micro BNC) en <span style=\"color: #ff6600;\">franchise de TVA</span>.</span></li>
	<li><span style=\"font-family: verdana, geneva;\"><span style=\"color: #ff6600;\">Association loi 1901</span> en franchise de TVA.</span></li>
</ul>
<span style=\"font-family: verdana, geneva;\">Toutes ces structures juridiques et fiscales n\'ont pas obligation de faire une comptabilité classique avec un bilan annuel. Par conséquent Aboo apportera une réponse simple et efficace pour l\'entrepreneur soucieux de piloter ses finances au mieux et répondra aux obligations légales.
</span>
&nbsp;","Pour qui?","","inherit","closed","open","","82-revision-v1","","","2013-12-17 23:46:59","2013-12-17 22:46:59","","82","http://localhost/gestabo/82-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("90","1","2013-12-17 23:48:02","2013-12-17 22:48:02"," ","","","publish","closed","open","","90","","","2013-12-17 23:48:02","2013-12-17 22:48:02","","0","http://localhost/gestabo/?p=90","2","nav_menu_item","","0");
INSERT INTO wp_posts VALUES("91","1","2013-12-18 00:02:15","2013-12-17 23:02:15","","Points-interrogation","","inherit","closed","open","","points-interrogation","","","2013-12-18 00:02:15","2013-12-17 23:02:15","","0","http://localhost/gestabo/wp-content/uploads/2013/12/Points-interrogation.jpg","0","attachment","image/jpeg","0");
INSERT INTO wp_posts VALUES("92","1","2013-12-18 00:02:16","2013-12-17 23:02:16","","chaise","","inherit","closed","open","","chaise","","","2013-12-18 00:02:16","2013-12-17 23:02:16","","0","http://localhost/gestabo/wp-content/uploads/2013/12/chaise.jpg","0","attachment","image/jpeg","0");
INSERT INTO wp_posts VALUES("93","1","2013-12-18 00:05:52","2013-12-17 23:05:52","<a style=\"font-size: 16px;\" href=\"http://localhost/gestabo/wp-content/uploads/2013/12/Cailloux.jpg\"><img class=\"alignleft size-thumbnail wp-image-44\" alt=\"equilible\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/Cailloux-150x150.jpg\" width=\"150\" height=\"150\" /><span style=\"font-family: verdana, geneva;\"><em><span style=\"color: #000000;\">Aboo est un système de gestion particulièrement adapté pour les petites structures utilisant les régimes suivants :</span></em></span></a>
<ul>
	<li><span style=\"font-family: verdana, geneva;\"><span style=\"color: #ff6600;\">Auto-Entrepreneur</span> et <span style=\"color: #ff6600;\">Entreprise Individuelle</span> (Micro BNC) en <span style=\"color: #ff6600;\">franchise de TVA</span>.</span></li>
	<li><span style=\"font-family: verdana, geneva;\"><span style=\"color: #ff6600;\">Association loi 1901</span> en franchise de TVA.</span></li>
</ul>
<span style=\"font-family: verdana, geneva;\">Toutes ces structures juridiques et fiscales n\'ont pas obligation de faire une comptabilité classique avec un bilan annuel. Par conséquent Aboo apportera une réponse simple et efficace pour l\'entrepreneur soucieux de piloter ses finances au mieux et répondra aux obligations légales.
</span>
&nbsp;","Pour qui?","","inherit","closed","open","","82-revision-v1","","","2013-12-18 00:05:52","2013-12-17 23:05:52","","82","http://localhost/gestabo/82-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("94","1","2013-12-18 00:13:25","2013-12-17 23:13:25","","k15748982","","inherit","closed","open","","k15748982","","","2013-12-18 00:13:25","2013-12-17 23:13:25","","0","http://localhost/gestabo/wp-content/uploads/2013/12/k15748982.jpg","0","attachment","image/jpeg","0");
INSERT INTO wp_posts VALUES("95","1","2013-12-18 00:58:44","2013-12-17 23:58:44","<a style=\"font-size: 16px;\" href=\"http://localhost/gestabo/wp-content/uploads/2013/12/Cailloux.jpg\"><img class=\"alignleft size-thumbnail wp-image-44\" alt=\"equilible\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/Cailloux-150x150.jpg\" width=\"150\" height=\"150\" /><span style=\"font-family: verdana, geneva;\"><em><span style=\"color: #000000;\">Aboo est un système de gestion particulièrement adapté pour les petites structures utilisant les régimes suivants :</span></em></span></a>
<cite class=\"fr\"><span style=\"color: #ff6600;\">Auto-Entrepreneur</span><span style=\"font-size: 16px; font-variant: inherit; font-weight: inherit; line-height: inherit; text-align: right;\"> et </span><span style=\"color: #ff6600;\">Entreprise Individuelle</span><span style=\"font-size: 16px; font-variant: inherit; font-weight: inherit; line-height: inherit; text-align: right;\"> (Micro BNC) en </span><span style=\"color: #ff6600;\">franchise de TVA</span><span style=\"font-size: 16px; font-variant: inherit; font-weight: inherit; line-height: inherit; text-align: right;\">.</span>
</cite>
<cite class=\"fr\"><span style=\"color: #ff6600;\">Association loi 1901</span><span style=\"font-size: 16px; font-variant: inherit; font-weight: inherit; line-height: inherit; text-align: right;\"> en franchise de TVA.</span></cite>
<span style=\"font-family: verdana, geneva;\">Toutes ces structures juridiques et fiscales n\'ont pas obligation de faire une comptabilité classique avec un bilan annuel. Par conséquent Aboo apportera une réponse simple et efficace pour l\'entrepreneur soucieux de piloter ses finances au mieux et répondra aux obligations légales.
</span>
&nbsp;","Pour qui?","","inherit","closed","open","","82-revision-v1","","","2013-12-18 00:58:44","2013-12-17 23:58:44","","82","http://localhost/gestabo/82-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("96","1","2013-12-18 01:02:09","2013-12-18 00:02:09","<a style=\"font-size: 16px;\" href=\"http://localhost/gestabo/wp-content/uploads/2013/12/Cailloux.jpg\"><img class=\"alignleft size-thumbnail wp-image-44\" alt=\"equilible\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/Cailloux-150x150.jpg\" width=\"150\" height=\"150\" /><span style=\"font-family: verdana, geneva; font-size: large;\"><em><span style=\"color: #000000;\">Aboo est un système de gestion particulièrement adapté pour les petites structures utilisant les régimes suivants :</span></em></span></a>
<p style=\"padding-left: 90px;\"><span style=\"font-family: verdana, geneva; font-size: large;\"><span style=\"color: #ff6600;\">Auto-Entrepreneur</span><span style=\"font-variant: inherit; font-weight: inherit; line-height: inherit; text-align: right;\"> et </span><span style=\"color: #ff6600;\">Entreprise Individuelle</span><span style=\"font-variant: inherit; font-weight: inherit; line-height: inherit; text-align: right;\"> (Micro BNC) en </span><span style=\"color: #ff6600;\">franchise de TVA</span><span style=\"font-variant: inherit; font-weight: inherit; line-height: inherit; text-align: right;\">.</span></span></p>
<p style=\"padding-left: 90px;\"><span style=\"font-family: verdana, geneva; font-size: large;\"><span style=\"color: #ff6600;\">Association loi 1901</span><span style=\"font-variant: inherit; font-weight: inherit; line-height: inherit; text-align: right;\"> en franchise de TVA.</span></span></p>
<p style=\"padding-left: 90px;\"><span style=\"font-family: verdana, geneva; font-size: large;\">Toutes ces structures juridiques et fiscales n\'ont pas </span><span style=\"font-family: verdana, geneva; font-size: large;\">obligation de faire une comptabilité classique avec un bilan annuel. Par conséquent Aboo apportera une réponse simple et efficace pour l\'entrepreneur soucieux de piloter ses finances au mieux et répondra aux obligations légales.</span></p>
<span style=\"font-family: verdana, geneva; font-size: large;\"> </span>","Pour qui?","","inherit","closed","open","","82-revision-v1","","","2013-12-18 01:02:09","2013-12-18 00:02:09","","82","http://localhost/gestabo/82-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("97","1","2013-12-18 01:11:59","2013-12-18 00:11:59","","target2","","inherit","closed","open","","target2","","","2013-12-18 01:11:59","2013-12-18 00:11:59","","0","http://localhost/gestabo/wp-content/uploads/2013/12/target2.jpg","0","attachment","image/jpeg","0");
INSERT INTO wp_posts VALUES("98","1","2013-12-18 01:12:00","2013-12-18 00:12:00","","people-marketing-strategies-for-small-business","","inherit","closed","open","","people-marketing-strategies-for-small-business","","","2013-12-18 01:12:00","2013-12-18 00:12:00","","0","http://localhost/gestabo/wp-content/uploads/2013/12/people-marketing-strategies-for-small-business.jpg","0","attachment","image/jpeg","0");
INSERT INTO wp_posts VALUES("99","1","2013-12-18 01:14:49","2013-12-18 00:14:49","","A chrome-plated Euro symbol isolated on a white background (3D rendering)","","inherit","closed","open","","a-chrome-plated-euro-symbol-isolated-on-a-white-background-3d-rendering","","","2013-12-18 01:14:49","2013-12-18 00:14:49","","0","http://localhost/gestabo/wp-content/uploads/2013/12/prix.jpg","0","attachment","image/jpeg","0");
INSERT INTO wp_posts VALUES("100","1","2013-12-19 12:47:44","2013-12-19 11:47:44","<p style=\"text-align: left;\"><em><span style=\"font-size: x-large; font-family: verdana, geneva;\"><strong><em><span style=\"color: #008000;\">Aboo</span> est un outil de gestion en ligne</em><em> </em>qui va faciliter votre quotidien.</strong></span></em></p>
<em style=\"font-family: \'Times New Roman\'; font-variant: normal; line-height: 22px; font-size: 16px;\"><span style=\"font-size: x-large; font-family: verdana, geneva; color: #ff6600;\"><img class=\"size-thumbnail wp-image-70 alignright\" style=\"border: 0px;\" alt=\"ampoule-economie-energie-fluo-compacte-forme-cerveau-600x566\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/ampoule-economie-energie-fluo-compacte-forme-cerveau-600x566-150x150.jpg\" width=\"150\" height=\"150\" /></span></em>
<p style=\"text-align: left;\"><span style=\"font-size: large; font-family: verdana, geneva;\"><span style=\"line-height: 1.5em;\">Que</span></span><span style=\"font-size: large; font-family: verdana, geneva;\"><span style=\"line-height: 1.5em;\"> vous soyez professeur de <span style=\"color: #800080;\">Yoga</span>, <span style=\"color: #800080;\">Pilate</span>, <span style=\"color: #800080;\">Sophrologie</span>, <span style=\"color: #800080;\">Danse</span>, <span style=\"color: #800080;\">Sport</span>... <span style=\"color: #800080;\">Enseignant de musique</span>, <span style=\"color: #800080;\">professionnel du bien-être </span>(<span style=\"color: #800080;\">wellness</span>), vous devez gérer des revenus périodiques basé sur des abonnements.</span></span></p>
<p style=\"text-align: left;\"><span style=\"font-size: large; font-family: verdana, geneva;\">Le problème est toujours le même : vos clients s’inscrivent tout au long de l\'année sur des périodes distinctes (mois / trim</span><span style=\"font-size: large; font-family: verdana, geneva;\">estre / année / ...), avec des tarifs différents, et vous devez gérez l\'étalement des paiements... </span><span style=\"font-size: large; font-family: verdana, geneva;\">avec tous ces paramètres comment </span><span style=\"font-size: large; font-family: verdana, geneva;\">calculer vos revenus mois par mois, quel salaire pouvez-vous vous réellement vous servir en fonction des charges à venir?</span></p>
<p style=\"text-align: left;\"><span style=\"font-size: large; font-family: verdana, geneva;\"><img class=\"size-medium wp-image-49 alignleft\" style=\"border: 0px;\" alt=\"budget 2\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/budget-2-300x173.jpg\" width=\"300\" height=\"173\" />Ce calcul est un vrai casse tête chinois et si vous le faite mal, cela peu vous faire faire de grave erreur de gestion de trésorerie...</span></p>
<p style=\"text-align: left;\"><span style=\"font-size: large; font-family: verdana, geneva;\"><strong><em> <span style=\"color: #008000;\">Aboo</span></em></strong><span style=\"color: #008000;\"> </span>va vous permettre de répondre à cette question existentielle : \"<span style=\"color: #ff6600;\"><strong>combien est-ce que je gagne à la fin du mois?!</strong></span>\"</span></p>
<p style=\"text-align: left;\"><span style=\"font-family: verdana, geneva; font-size: large;\">D\'autre part <strong>Aboo</strong> va aussi vous aider à gérer votre fichier de clients pour les relances (paiements / abonnements / campagnes...).</span></p>
&nbsp;
<span style=\"font-size: 16px;\"> </span>
<span style=\"text-decoration: underline;\"><span style=\"font-family: verdana, geneva;\"><strong><em style=\"font-size: 16px;\"><img class=\"alignright size-medium wp-image-43\" style=\"border: 0px;\" alt=\"budget\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/budget-300x199.jpg\" width=\"300\" height=\"199\" />Les fonctionnalités principales sont les suivantes :</em><span style=\"font-size: 16px;\"> </span></strong></span></span>
<ul>
	<li><span style=\"font-family: verdana, geneva; font-size: medium; color: #ff6600;\">Journal des dépenses / recettes</span></li>
	<li><span style=\"font-family: verdana, geneva; font-size: medium;\">Ventilation des abonnements de 2 à 12 mois sur l\'exercice</span></li>
	<li><span style=\"font-family: verdana, geneva; font-size: medium; color: #ff6600;\">Suivi des paiements</span></li>
	<li><span style=\"font-family: verdana, geneva; font-size: medium;\">Démarrage de l\'exercice de 12 mois paramétrable</span></li>
	<li><span style=\"font-family: verdana, geneva; font-size: medium; color: #ff6600;\">Calcul du salaire mensuel</span></li>
	<li><span style=\"font-family: verdana, geneva; font-size: medium;\">Calcul de la trésorerie</span></li>
	<li><span style=\"font-family: verdana, geneva; font-size: medium;\"><span style=\"color: #ff6600;\">Tableau de bord</span> mensuel et annuel (Chiffre d\'affaire / Dépenses / Salaire / Trésorerie ...)</span></li>
</ul>
&nbsp;
<ul>
	<li><strong style=\"font-family: sans-serif; font-size: medium; font-style: normal; font-variant: normal; line-height: normal;\"><span style=\"font-size: large;\"><span style=\"font-family: verdana, geneva;\">Gestion du fichier client :</span></span></strong></li>
</ul>
<span style=\"font-size: large;\"><em><span style=\"font-family: verdana, geneva;\"><img class=\"size-medium wp-image-101 alignleft\" style=\"border: 0px; margin-left: 15px; margin-right: 15px;\" alt=\"crm-contacts-rolodex\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/crm-contacts-rolodex-270x300.jpg\" width=\"270\" height=\"300\" /></span></em></span>
<ul>
	<li><span style=\"font-family: verdana, geneva; font-size: large;\">Suivi du listing client</span></li>
	<li><span style=\"font-family: verdana, geneva; font-size: large;\">Relance par eMail des abonnements expirés</span></li>
	<li><span style=\"font-family: verdana, geneva; font-size: large;\">Relance par eMail des paiements</span></li>
	<li><span style=\"font-family: verdana, geneva; font-size: large;\">Campagne d\'information </span></li>
</ul>","Que fait Aboo?","","inherit","closed","open","","55-autosave-v1","","","2013-12-19 12:47:44","2013-12-19 11:47:44","","55","http://localhost/gestabo/55-autosave-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("101","1","2013-12-19 12:45:25","2013-12-19 11:45:25","","crm-contacts-rolodex","","inherit","closed","open","","crm-contacts-rolodex","","","2013-12-19 12:45:25","2013-12-19 11:45:25","","55","http://localhost/gestabo/wp-content/uploads/2013/12/crm-contacts-rolodex.jpg","0","attachment","image/jpeg","0");
INSERT INTO wp_posts VALUES("102","1","2013-12-19 12:50:33","2013-12-19 11:50:33","&nbsp;
<a style=\"font-size: 16px;\" href=\"http://localhost/gestabo/wp-content/uploads/2013/12/Cailloux.jpg\"><img class=\"alignleft size-thumbnail wp-image-44\" alt=\"equilible\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/Cailloux-150x150.jpg\" width=\"150\" height=\"150\" /><span style=\"font-family: verdana, geneva; font-size: large;\"><em><span style=\"color: #000000;\">Aboo est un système de gestion particulièrement adapté pour les petites structures utilisant les régimes suivants :</span></em></span></a>
<p style=\"padding-left: 90px;\"><span style=\"font-family: verdana, geneva; font-size: large;\"><span style=\"color: #ff6600;\">Auto-Entrepreneur</span><span style=\"font-variant: inherit; font-weight: inherit; line-height: inherit; text-align: right;\"> et </span><span style=\"color: #ff6600;\">Entreprise Individuelle</span><span style=\"font-variant: inherit; font-weight: inherit; line-height: inherit; text-align: right;\"> (Micro BNC) en </span><span style=\"color: #ff6600;\">franchise de TVA</span><span style=\"font-variant: inherit; font-weight: inherit; line-height: inherit; text-align: right;\">.</span></span></p>
<p style=\"padding-left: 90px;\"><span style=\"font-family: verdana, geneva; font-size: large;\"><span style=\"color: #ff6600;\">Association loi 1901</span><span style=\"font-variant: inherit; font-weight: inherit; line-height: inherit; text-align: right;\"> en franchise de TVA.</span></span></p>
<p style=\"padding-left: 90px;\"><span style=\"font-family: verdana, geneva; font-size: large;\">Toutes ces structures juridiques et fiscales n\'ont pas </span><span style=\"font-family: verdana, geneva; font-size: large;\">obligation de faire une comptabilité classique avec un bilan annuel. Par conséquent Aboo apportera une réponse simple et efficace pour l\'entrepreneur soucieux de piloter ses finances au mieux et répondra aux obligations légales.</span></p>
<span style=\"font-family: verdana, geneva; font-size: large;\"> </span>","Pour qui?","","inherit","closed","open","","82-revision-v1","","","2013-12-19 12:50:33","2013-12-19 11:50:33","","82","http://localhost/gestabo/82-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("103","1","2013-12-19 12:58:57","2013-12-19 11:58:57","<img class=\"alignleft  wp-image-52\" alt=\"tarifs\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/tarifs-150x150.jpg\" /><span style=\"font-family: verdana, geneva; font-size: x-large;\">L\'accès à <strong><span style=\"color: #008000;\"><em>Aboo</em> </span></strong>est <strong>gratuit</strong> à l\'essai les deux premiers mois!</span>
<span style=\"font-family: verdana, geneva; font-size: large;\"><em>(Lors de votre demande de compte utilisateur, une licence temporaire gratuite vous sera accordée pour essayer le site.)</em></span>
&nbsp;
&nbsp;
<span style=\"font-family: verdana, geneva; font-size: large;\">Le tarif d\'Aboo est unique et forfaitaire : <span style=\"color: #ff6600;\"><strong>25€ TTC</strong></span>/Exercice (12 mois).</span>
<span style=\"font-family: verdana, geneva; font-size: large;\">Le paiement peut se faire en ligne par paiement Paypal ou par envoi de chèque.</span>","Tarifs","","inherit","closed","open","","41-autosave-v1","","","2013-12-19 12:58:57","2013-12-19 11:58:57","","41","http://localhost/gestabo/41-autosave-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("104","1","2013-12-19 12:59:10","2013-12-19 11:59:10","<img class=\"alignleft  wp-image-52\" alt=\"tarifs\" src=\"http://localhost/gestabo/wp-content/uploads/2013/12/tarifs-150x150.jpg\" /><span style=\"font-family: verdana, geneva; font-size: x-large;\">L\'accès à <strong><span style=\"color: #008000;\"><em>Aboo</em> </span></strong>est <strong>gratuit</strong> à l\'essai les deux premiers mois!</span>
<span style=\"font-family: verdana, geneva; font-size: large;\"><em>(Lors de votre demande de compte utilisateur, une licence temporaire gratuite vous sera accordée pour essayer le site.)</em></span>
&nbsp;
&nbsp;
<span style=\"font-family: verdana, geneva; font-size: large;\">Le tarif d\'Aboo est unique et forfaitaire : <span style=\"color: #ff6600;\"><strong>25€ TTC</strong></span>/Exercice (12 mois).</span>
<span style=\"font-family: verdana, geneva; font-size: large;\">Le paiement peut se faire en ligne par paiement <span style=\"color: #ff6600;\">Paypal</span> ou par envoi de chèque.</span>","Tarifs","","inherit","closed","open","","41-revision-v1","","","2013-12-19 12:59:10","2013-12-19 11:59:10","","41","http://localhost/gestabo/41-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("105","1","2013-12-19 13:23:13","2013-12-19 12:23:13","&nbsp;
<p style=\"text-align: center;\">[qpp form=\"aboo\" amount=\"25\"]</p>","Paiement","","publish","closed","closed","","paiement","","","2013-12-19 13:24:55","2013-12-19 12:24:55","","0","http://localhost/gestabo/?page_id=105","0","page","","0");
INSERT INTO wp_posts VALUES("106","1","2013-12-19 13:23:13","2013-12-19 12:23:13"," ","","","publish","closed","open","","106","","","2013-12-19 13:23:13","2013-12-19 12:23:13","","0","http://localhost/gestabo/106/","9","nav_menu_item","","0");
INSERT INTO wp_posts VALUES("107","1","2013-12-19 13:23:13","2013-12-19 12:23:13","&nbsp;
<p style=\"text-align: center;\">[qpp form=\"aboo\" amount=\"€25\"]</p>","Paiement","","inherit","closed","open","","105-revision-v1","","","2013-12-19 13:23:13","2013-12-19 12:23:13","","105","http://localhost/gestabo/105-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("108","1","2013-12-19 13:24:55","2013-12-19 12:24:55","&nbsp;
<p style=\"text-align: center;\">[qpp form=\"aboo\" amount=\"25\"]</p>","Paiement","","inherit","closed","open","","105-revision-v1","","","2013-12-19 13:24:55","2013-12-19 12:24:55","","105","http://localhost/gestabo/105-revision-v1/","0","revision","","0");
INSERT INTO wp_posts VALUES("109","1","2013-12-23 10:52:40","0000-00-00 00:00:00","","Brouillon auto","","auto-draft","closed","open","","","","","2013-12-23 10:52:40","0000-00-00 00:00:00","","0","http://localhost/gestabo/?p=109","0","post","","0");
DROP TABLE IF EXISTS wp_term_relationships;CREATE TABLE `wp_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO wp_term_relationships VALUES("1","6","0");
INSERT INTO wp_term_relationships VALUES("13","2","0");
INSERT INTO wp_term_relationships VALUES("19","2","0");
INSERT INTO wp_term_relationships VALUES("20","3","0");
INSERT INTO wp_term_relationships VALUES("50","2","0");
INSERT INTO wp_term_relationships VALUES("53","2","0");
INSERT INTO wp_term_relationships VALUES("56","2","0");
INSERT INTO wp_term_relationships VALUES("85","2","0");
INSERT INTO wp_term_relationships VALUES("86","2","0");
INSERT INTO wp_term_relationships VALUES("90","2","0");
INSERT INTO wp_term_relationships VALUES("106","2","0");
DROP TABLE IF EXISTS wp_term_taxonomy;CREATE TABLE `wp_term_taxonomy` (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
INSERT INTO wp_term_taxonomy VALUES("1","1","category","","0","0");
INSERT INTO wp_term_taxonomy VALUES("2","2","nav_menu","","0","9");
INSERT INTO wp_term_taxonomy VALUES("3","3","nav_menu","","0","1");
INSERT INTO wp_term_taxonomy VALUES("4","4","post_format","","0","0");
INSERT INTO wp_term_taxonomy VALUES("6","6","category","","0","1");
DROP TABLE IF EXISTS wp_terms;CREATE TABLE `wp_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `slug` varchar(200) NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
INSERT INTO wp_terms VALUES("1","Non classé","non-classe","0");
INSERT INTO wp_terms VALUES("2","Header","header","0");
INSERT INTO wp_terms VALUES("3","footer","footer","0");
INSERT INTO wp_terms VALUES("4","post-format-status","post-format-status","0");
INSERT INTO wp_terms VALUES("6","News","news","0");
DROP TABLE IF EXISTS wp_usermeta;CREATE TABLE `wp_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
INSERT INTO wp_usermeta VALUES("1","1","first_name","Frédéric");
INSERT INTO wp_usermeta VALUES("2","1","last_name","MEYROU");
INSERT INTO wp_usermeta VALUES("3","1","nickname","admin");
INSERT INTO wp_usermeta VALUES("4","1","description","");
INSERT INTO wp_usermeta VALUES("5","1","rich_editing","true");
INSERT INTO wp_usermeta VALUES("6","1","comment_shortcuts","false");
INSERT INTO wp_usermeta VALUES("7","1","admin_color","ectoplasm");
INSERT INTO wp_usermeta VALUES("8","1","use_ssl","0");
INSERT INTO wp_usermeta VALUES("9","1","show_admin_bar_front","true");
INSERT INTO wp_usermeta VALUES("10","1","wp_capabilities","a:1:{s:13:\"administrator\";b:1;}");
INSERT INTO wp_usermeta VALUES("11","1","wp_user_level","10");
INSERT INTO wp_usermeta VALUES("12","1","dismissed_wp_pointers","wp330_toolbar,wp330_saving_widgets,wp340_choose_image_from_library,wp340_customize_current_theme_link,wp350_media,wp360_revisions,wp360_locks");
INSERT INTO wp_usermeta VALUES("13","1","show_welcome_panel","0");
INSERT INTO wp_usermeta VALUES("14","1","wp_dashboard_quick_press_last_post_id","109");
INSERT INTO wp_usermeta VALUES("15","1","managenav-menuscolumnshidden","a:4:{i:0;s:11:\"link-target\";i:1;s:11:\"css-classes\";i:2;s:3:\"xfn\";i:3;s:11:\"description\";}");
INSERT INTO wp_usermeta VALUES("16","1","metaboxhidden_nav-menus","a:3:{i:0;s:8:\"add-post\";i:1;s:12:\"add-post_tag\";i:2;s:15:\"add-post_format\";}");
INSERT INTO wp_usermeta VALUES("17","1","wp_user-settings","mfold=o&ed_size=553&editor=tinymce&libraryContent=browse&align=right&urlbutton=none&imgsize=medium&hidetb=1");
INSERT INTO wp_usermeta VALUES("18","1","wp_user-settings-time","1387453322");
INSERT INTO wp_usermeta VALUES("19","1","nav_menu_recently_edited","2");
INSERT INTO wp_usermeta VALUES("20","1","closedpostboxes_dashboard","a:0:{}");
INSERT INTO wp_usermeta VALUES("21","1","metaboxhidden_dashboard","a:2:{i:0;s:21:\"dashboard_quick_press\";i:1;s:17:\"dashboard_primary\";}");
INSERT INTO wp_usermeta VALUES("22","1","meta-box-order_dashboard","a:4:{s:6:\"normal\";s:19:\"dashboard_right_now\";s:4:\"side\";s:58:\"dashboard_quick_press,dashboard_activity,dashboard_primary\";s:7:\"column3\";s:0:\"\";s:7:\"column4\";s:0:\"\";}");
INSERT INTO wp_usermeta VALUES("23","1","user_fb","");
INSERT INTO wp_usermeta VALUES("24","1","user_tw","");
INSERT INTO wp_usermeta VALUES("25","1","google_profile","");
INSERT INTO wp_usermeta VALUES("26","1","wpcf7_hide_welcome_panel_on","a:1:{i:0;s:3:\"3.6\";}");
DROP TABLE IF EXISTS wp_users;CREATE TABLE `wp_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL DEFAULT '',
  `user_pass` varchar(64) NOT NULL DEFAULT '',
  `user_nicename` varchar(50) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_url` varchar(100) NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(60) NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
INSERT INTO wp_users VALUES("1","admin","$P$B69ddgm0ptaqAHuer9lniIS2uFug.n0","admin","frederic@meyrou.com","","2013-12-14 14:35:17","","0","Frédéric MEYROU");
