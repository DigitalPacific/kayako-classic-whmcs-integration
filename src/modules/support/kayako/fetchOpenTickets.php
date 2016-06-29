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

//Include constants file
require_once 'constants.php';

//Initialize the client
kyConfig::set(new kyConfig(API_URL, API_KEY, SECRET_KEY));
$clientsdetails = getclientsdetails($_userid);

$_ticketDepartmentObjectContainer = kyDepartment::getAll()->filterByModule(kyDepartment::MODULE_TICKETS)->filterByType(kyDepartment::TYPE_PUBLIC);

$_ticketStatusObjectContainer = kyTicketStatus::getAll()->filterByType(kyTicketStatus::TYPE_PUBLIC)->first();

$_ticketStatusContainer = [];
foreach ($_ticketStatusObjectContainer as $_ticketStatusObject) {

	$_ticketStatusID                 = $_ticketStatusObject->getId();
	$_ticketStatus['ticketstatusid'] = $_ticketStatusID;
	$_ticketStatus['title']          = $_ticketStatusObject->getTitle();
	$_ticketStatus['markasresolved'] = $_ticketStatusObject->getMarkAsResolved();

	$_ticketStatusContainer[$_ticketStatusID] = $_ticketStatus;
}

$_ticketObjectContainer = kyTicket::getAll($_ticketDepartmentObjectContainer, $_ticketStatusObjectContainer, [], [], $_params['clientsdetails']['email'], $_settings['recordsperpage'], 0)
	->orderByLastActivity();
$_totalTicketCount = kyTicket::getTicketCount($_ticketDepartmentObjectContainer, $_ticketStatusObjectContainer, array(), array(), $clientsdetails['email']);

$_ticketContainer  = [];
$_numActiveTickets = 0;
foreach ($_ticketObjectContainer as $_ticketObject) {


	$_ticketID     = $_ticketObject->getId();
	$_ticketStatus = $_ticketObject->getStatusId();

	if (isset($_ticketStatusContainer[$_ticketStatus]) && $_ticketStatusContainer[$_ticketStatus]['markasresolved'] == '1') {
		continue;
	} else {
		$_numActiveTickets++;
	}

	$_ticket               = [];
	$_ticket['tid']        = $_ticketID;
	$_ticket['date']       = $_ticketObject->getCreationTime();
	$_ticket['department'] = $_ticketObject->getDepartment()->getTitle();
	$_ticket['status']     = $_ticketObject->getStatus()->getTitle();
	$_ticket['urgency']    = $_ticketObject->getPriority()->getTitle();
	$_ticket['subject']    = $_ticketObject->getSubject();
	$_ticket['lastreply']  = $_ticketObject->getLastActivity();

	$_ticketContainer[$_ticketID] = $_ticket;
}
