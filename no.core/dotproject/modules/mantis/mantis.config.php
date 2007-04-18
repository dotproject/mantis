<?php /* ID: mantis.config.php 2007/04/10 12:46 weboholic */
// Change this to the path to PHPXMLRPC client.php
require_once("../xmlrpc/PHPXMLRPC/client.php");

// Mantis host, w/o http, w/o trailing slash
// either IP or domain name, NO localhost
$cnf['mantishost'] = '172.20.70.63';

// Web mantis path, + trailing slash
$cnf['mantisuri'] = 'm/';

// Web Server Port + Method; 80 = http; 443 = https
$cnf['mantisport'] = '80';
$cnf['mantismethod'] = 'http';


// Web path to mantis' xmlrpc folder, + tailing slash
$cnf['mantisxmlrpc'] = '/m/xmlrpc/dotproject/';

// date format, according to http://php.net/date function specifications
$cnf['dateformat'] = 'n/j/y g:ia';
?>