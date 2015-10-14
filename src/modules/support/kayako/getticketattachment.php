<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/whmcs-integration
 */

//Include config file
require_once __DIR__ . '/config.php';

//Include all necessary classes and helper methods
require_once 'API/kyIncludes.php';

require_once 'functions.php';

//Initialize the client
kyConfig::set(new kyConfig(API_URL, API_KEY, SECRET_KEY));

$_ticketAttachment = kyTicketAttachment::get($_GET['tid'], $_GET['aid']);

Download($_ticketAttachment->getFileName(), $_ticketAttachment->getContents());