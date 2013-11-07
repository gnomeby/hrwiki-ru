HRWiki
======
([Русская версия](https://github.com/gnomeby/hrwiki-ru))

HRWiki - Encyclopedia for managing CVs and related information about candidates

Features:

* Forms for entering information about candidates
* Indexing attached files (Microsoft Office files, Open Office files, PDF)
* Writing comments
* Remote access
* Autocomplete for specialization

*Support*
* [Leave a ticket](https://github.com/gnomeby/hrwiki/issues/new)
* or [Write an email](mailto:hrwiki-support@holey.org)

![gnomeby avatar](http://niakhaichyk.org/andrey/img/lisa_small_32.png)

#### Requirements:
* Apache 2.2
* * mod_rewrite
* * SSL
* * vhost
* * auth_basic
* * authn_file
* * authz_user
* PHP 5.3
* * intl
* * mysqli
* * fileinfo
* * mod_php for apache
* MariaDB 5.1+ or MySQL 5.0.2 or higher
* imagemagick
* SMTP client for sending emails (exim, ssmtp)
* FileIndexer system tools
* * antiword
* * catdoc
* * pdftotext

#### Installation:
* Read *Requirements* and install all software
* Download HRWiki tool [sources](https://github.com/gnomeby/hrwiki/archive/master.zip)
* * Extract it as a new apache VirtualHost site
* * Restore MySQL dump from the file *maintenance/dumps/05_hrwiki_configure_semantic.sql*
* * Open *extensions/FileIndexer/FileIndexer_cfg.php* and check $wgFiCommandPaths section, make sure that yout have such tools with those paths
* * Open *LocalSettings.php* and change the following properties:
* * $wgServer
* * $wgEmergencyContact
* * $wgPasswordSender
* * $wgDBserver, $wgDBname, $wgDBuser, $wgDBpassword
* * $wgImageMagickConvertCommand, $wgShellLocale, $wgDiff3
* * Set owner for HRWiki tool folder to apache user 
* Configure apache VirtualHost
* * Use apache.hrwiki.example.conf as example

#### Usage:
* Open Main page
* Login using the following Admin / password1
* Change password
* Read tutorial

#### Security configuration:
HRWiki may contain import confedential information about candidates. To protect it make the following:
* Configure access to DB [using SSL](http://httpd.apache.org/docs/2.2/ssl/)
* Protect all pages using [Basic authorization](http://httpd.apache.org/docs/2.2/howto/auth.html)

#### Authors:
* [Yauhen Artsiukhou](https://github.com/jsirex)
* [Andrey Niakhaichyk](https://github.com/gnomeby)
