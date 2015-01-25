About Global-Overflow
=========
Global-Overflow is a stack overflow clone aimed towards people playing the game "Counter-strike:Global Offensive".
There are currently alot of unknown buggs due to this being in early beta. 
If you however still want to sample this clone follow the instructions below and you will have your own "Global-overflow" installed in notime.

This site is based on the anaxMVC template which you can read more about below.

About AnaxMVC
=========

A PHP-based and MVC-inspired (micro) framework / webbtemplate / boilerplate for websites and webbapplications.

Builds upon Anax-base, read article about Anax-base ["Anax - en h�llbar struktur f�r dina webbapplikationer"](http://dbwebb.se/kunskap/anax-en-hallbar-struktur-for-dina-webbapplikationer) to get an overview of its none-MVC variant. 

By Mikael Roos, me@mikaelroos.se.

License 
------------------

This software is free software and carries a MIT license.


The following external modules are included and subject to its own license.



### Modernizr
* Website: http://modernizr.com/
* Version: 2.6.2
* License: MIT license 
* Path: included in `webroot/js/modernizr.js`



### PHP Markdown
* Website: http://michelf.ca/projects/php-markdown/
* Version: 1.4.0, November 29, 2013
* License: PHP Markdown Lib Copyright � 2004-2013 Michel Fortin http://michelf.ca/ 
* Path: included in `3pp/php-markdown`


About Global-Overflow
-----------------------------------
Global-Overflow is a stack overflow clone aimed towards people playing the game "Counter-strike:Global Offensive".
There are currently alot of unknown buggs due to this being in early beta. 
If you however still want to sample this clone follow the instructions below and you will have your own "Global-overflow" installed in notime.

Instructions 
-----------------------------------
Download and extract the entire repository.

There are three important steps. 

Number one :

Edit the config_mysql.php in "app/config/" with database information of your own, you need your own databse to be able to run this.

Number two : 

Import the .sql file from the database folder into your database. Once the site is running the connection to the database
should be made and everything should be working, if not you probably entered the wrong database information.

Number three : 

You need to Edit the .htaccess in "webroot/"

specifically you need to edit line 9 
 RewriteBase /~sege14/phpmvc/kmom07/webroot/
 
 So that it follows the URL of your own site, otherwise everyone will be redirect to my version of this site which we dont want!.
 

That's it, the site should now be working. Good luck!
