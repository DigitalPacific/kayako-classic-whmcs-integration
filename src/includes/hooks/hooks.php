<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/whmcs-integration
 */

if (!defined("WHMCS")) {
	die("This file cannot be accessed directly");
}

function hook_clientarea_tickets()
{
	$moduleDetails = FetchSupportModule();

	if ($moduleDetails['value'] == 'kayako') {
		add_hook('ClientAreaPage', 1, 'hook_clientarea_details');
	}
}

function FetchSupportModule()
{
	$_tableName = "tblconfiguration";
	$_fields    = "value";
	$_where     = array("setting" => 'SupportModule');
	$_result    = select_query($_tableName, $_fields, $_where);
	$data       = mysql_fetch_array($_result);

	return $data;
}

function hook_clientarea_details($_params)
{
	global $smarty;
	include_once(getcwd() . '/modules/support/kayako/fetchOpenTickets.php');

	$_params['clientsstats']['numactivetickets'] = $_numActiveTickets;
	$_params['clientsstats']['numtickets'] = $_totalTicketCount;
	$smarty->assign('clientsstats', $_params['clientsstats']);
	$smarty->assign('tickets', $_ticketContainer);
}

add_hook('ClientAreaHomepage', 10, 'hook_clientarea_tickets');