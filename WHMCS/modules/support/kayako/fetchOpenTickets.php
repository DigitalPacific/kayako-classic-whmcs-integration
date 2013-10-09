<?php
/**
 * ###############################################
 *
 * WHMCS Integration
 * _______________________________________________
 *
 * @author         Amarjeet Kaur
 *
 * @package        WHMCS Integration
 * @copyright      Copyright (c) 2001-2013, Kayako
 * @license        http://www.kayako.com/license
 * @link           http://www.kayako.com
 *
 * ###############################################
 */

/**
 * File to fetch Open Support Tickets
 *
 * @author Amarjeet Kaur
 */

//Include config file
require_once __DIR__ . '/config.php';

//Include all necessary classes and helper methods
require_once 'API/kyIncludes.php';

//Include constants file
require_once 'constants.php';

//Initialize the client
kyConfig::set(new kyConfig(API_URL, API_KEY, SECRET_KEY));

$_ticketDepartmentObjectContainer = kyDepartment::getAll()->filterByModule(kyDepartment::MODULE_TICKETS)->filterByType(kyDepartment::TYPE_PUBLIC);

$_ticketStatusObjectContainer = kyTicketStatus::getAll()->filterByType(kyTicketStatus::TYPE_PUBLIC)->first();

$_ticketStatusContainer = array();
foreach ($_ticketStatusObjectContainer as $_ticketStatusObject) {

	$_ticketStatusID                 = $_ticketStatusObject->getId();
	$_ticketStatus['ticketstatusid'] = $_ticketStatusID;
	$_ticketStatus['title']          = $_ticketStatusObject->getTitle();
	$_ticketStatus['markasresolved'] = $_ticketStatusObject->getMarkAsResolved();

	$_ticketStatusContainer[$_ticketStatusID] = $_ticketStatus;
}

$_ticketObjectContainer = kyTicket::getAll($_ticketDepartmentObjectContainer, $_ticketStatusObjectContainer, array(), array(), $_params['clientsdetails']['email'], $_settings['recordsperpage'], 0)
	->orderByLastActivity();

$_ticketContainer  = array();
$_numActiveTickets = 0;
foreach ($_ticketObjectContainer as $_ticketObject) {


	$_ticketID     = $_ticketObject->getId();
	$_ticketStatus = $_ticketObject->getStatusId();

	if (isset($_ticketStatusContainer[$_ticketStatus]) && $_ticketStatusContainer[$_ticketStatus]['markasresolved'] == '1') {
		continue;
	} else {
		$_numActiveTickets++;
	}

	$_ticket               = array();
	$_ticket['tid']        = $_ticketID;
	$_ticket['date']       = $_ticketObject->getCreationTime();
	$_ticket['department'] = $_ticketObject->getDepartment()->getTitle();
	$_ticket['status']     = $_ticketObject->getStatus()->getTitle();
	$_ticket['urgency']    = $_ticketObject->getPriority()->getTitle();
	$_ticket['subject']    = $_ticketObject->getSubject();
	$_ticket['lastreply']  = $_ticketObject->getLastActivity();

	$_ticketContainer[$_ticketID] = $_ticket;
}