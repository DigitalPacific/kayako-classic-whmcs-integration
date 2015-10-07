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
	$smarty->assign('clientsstats', $_params['clientsstats']);
	$smarty->assign('tickets', $_ticketContainer);
}

add_hook('ClientAreaHomepage', 10, 'hook_clientarea_tickets');