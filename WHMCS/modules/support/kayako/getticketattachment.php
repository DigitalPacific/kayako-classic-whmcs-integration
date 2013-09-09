<?php
/**
 * ###############################################
 *
 * SWIFT Framework
 * _______________________________________________
 *
 * @author         Ruchi Kothari
 *
 * @package        SWIFT
 * @copyright      Copyright (c) 2001-2013, Kayako
 * @license        http://www.kayako.com/license
 * @link           http://www.kayako.com
 *
 * ###############################################
 */

/**
 * File to get ticket attachments
 *
 * @author Ruchi Kothari
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