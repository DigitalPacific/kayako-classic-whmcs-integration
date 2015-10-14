<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/whmcs-integration
 */

$_ticket = kyTicket::get($_GET['ticketid']);
$_ticket->setStatusId($_POST['ticketstatusid']);
$_ticket->setPriorityId($_POST['ticketpriorityid']);
$_ticket->update();

$_ticket->setCustomFieldValuesFromPOST();
$_ticket->updateCustomFields();