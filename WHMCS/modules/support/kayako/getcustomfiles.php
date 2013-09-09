<?php
/**
 * ###############################################
 *
 * WHMCS Integration
 * _______________________________________________
 *
 * @author         Ruchi Kothari
 *
 * @package        WHMCS Integration
 * @copyright      Copyright (c) 2001-2013, Kayako
 * @license        http://www.kayako.com/license
 * @link           http://www.kayako.com
 *
 * ###############################################
 */

/**
 * File to get custom field files
 *
 * @author Ruchi Kothari
 */

//Include config file
require_once __DIR__ . '/config.php';

//Include all necessary classes and helper methods
require_once 'API/kyIncludes.php';

//Include common functions
require_once 'functions.php';

//Initialize the client
kyConfig::set(new kyConfig(API_URL, API_KEY, SECRET_KEY));

$_ticketObject = kyTicket::get($_GET['tid']);

$_customField = $_ticketObject->getCustomField($_GET['field']);

$_customFieldValue = $_customField->getValue();

Download($_customFieldValue[0], $_customFieldValue[1]);