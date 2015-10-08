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
 * @copyright      Copyright (c) 2001-2015, Kayako
 * @license        http://www.kayako.com/license
 * @link           http://www.kayako.com
 *
 * ###############################################
 */

/**
 * File to update a ticket
 *
 * @author Ruchi Kothari
 */
$_ticket = kyTicket::get($_GET['ticketid']);
$_ticket->setStatusId($_POST['ticketstatusid']);
$_ticket->setPriorityId($_POST['ticketpriorityid']);
$_ticket->update();

$_ticket->setCustomFieldValuesFromPOST();
$_ticket->updateCustomFields();