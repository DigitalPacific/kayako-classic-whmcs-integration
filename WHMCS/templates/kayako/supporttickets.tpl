<link rel="stylesheet" type="text/css" href="{$_jscssURL}/css/kayako.css"/>
<script type="text/javascript" src="{$_jscssURL}/js/kayako.js"></script>
<div class="boxcontainer">
	<div class="boxcontainerlabel">
	{if $_resolvedTicketCount > 0}
		<div style="float: right">
			<div class="headerbuttongreen" id="resolvedviewbutton" onclick="javascript:ToggleViewHideResolved()" {if $_showResolved == 'true'} style="display:none"{/if}>View
				Resolved Tickets ({$_resolvedTicketCount})
			</div>
			<div class="headerbuttongreen" id="resolvedhidebutton" onclick="javascript:ToggleViewHideResolved()" {if $_showResolved == 'false'} style="display:none"{/if}>Hide
				Resolved Tickets
			</div>
		</div>
	{/if}
		View Tickets
	</div>

	<div class="boxcontainercontent" style="clear: both;">

		<form name="fetchTicketsForm" id="fetchTicketsForm" method="post">
			<div style="float: right; ">
				<b>Please select department to fetch related tickets:</b>

				<select name="department" onchange="SubmitForm();">
					{foreach key = _departmentID item = _department from = $_departmentContainer}
						<option value="{$_department.departmentid}" {if $_selectedDepartment == $_department.departmentid} selected="selected"{/if}>{$_department.departmenttitle}</option>
					{/foreach}
				</select>

			</div>
		</form>

		<table border="0" cellpadding="3" cellspacing="1" width="100%">
			<tr>
				<td class="ticketlistheaderrow" align="left" valign="middle" width="150">Ticket ID</td>
				<td class="ticketlistheaderrow" align="center" valign="middle" width="">
					<a href="{$_listTicketURL}?sortby=LastActivity&order={$_sortOrderFlip}" onclick="javascript:CheckForResolved(this)">
						Last Update &nbsp;{if $_sortBy == 'LastActivity'}{if $_sortOrder == 'ASC'}<img src="{$_imageURL}/sortasc.gif" border="0"/>{elseif $_sortOrder == 'DESC'}
						<img src="{$_imageURL}/sortdesc.gif" border="0"/>{/if}{/if}
					</a>
				</td>
				<td class="ticketlistheaderrow" align="center" valign="middle" width="">
					<a href="{$_listTicketURL}?sortby=LastReplier&order={$_sortOrderFlip}" onclick="javascript:CheckForResolved(this)">Last Replier</a>
				</td>
				<td class="ticketlistheaderrow" align="center" valign="middle" width="">
					<a href="{$_listTicketURL}?sortby=DepartmentId&order={$_sortOrderFlip}" onclick="javascript:CheckForResolved(this)">Department</a>
				</td>
				<td class="ticketlistheaderrow" align="center" valign="middle" width="">
					<a href="{$_listTicketURL}?sortby=TypeId&order={$_sortOrderFlip}" onclick="javascript:CheckForResolved(this)">Type</a>
				</td>
				<td class="ticketlistheaderrow" align="center" valign="middle" width="">
					<a href="{$_listTicketURL}?sortby=StatusId&order={$_sortOrderFlip}" onclick="javascript:CheckForResolved(this)">Status</a>
				</td>
				<td class="ticketlistheaderrow" align="center" valign="middle" width="">
					<a href="{$_listTicketURL}?sortby=PriorityId&order={$_sortOrderFlip}" onclick="javascript:CheckForResolved(this)">Priority</a>
				</td>
			</tr>

		{if count($_ticketContainer) > 0}

			{foreach key=_ticketID item=_ticket from=$_ticketContainer}

				<tr {if $_ticket.isresolved ==1} class="ticketresolvedrow" {if $_showResolved == 'false'}style="display:none"{/if}{/if} >
					<td class="ticketlistsubject" align="left" valign="middle" colspan="7"><a href="{$_viewTicketURL}?ticketid={$_ticket.ticketid}">{$_ticket.subject}</a></td>
				</tr>

				{assign var="_displayNone" value="display:none"}

				<tr class="ticketlistproperties{if $_ticket.isresolved ==1} ticketresolvedrow {/if}"
				    style="background: {$_ticket.statusbgcolor};{if $_showResolved =='false' && $_ticket.isresolved ==1}{$_displayNone}{/if}">
					<td class="ticketlistpropertiescontainer" align="left" valign="middle">{$_ticket.displayticketid}</td>
					<td class="ticketlistpropertiescontainer" align="center" valign="middle">{$_ticket.lastactivity}</td>
					<td class="ticketlistpropertiescontainer" align="center" valign="middle">{$_ticket.lastreplier}</td>
					<td class="ticketlistpropertiescontainer" align="center" valign="middle">{$_ticket.department}</td>
					<td class="ticketlistpropertiescontainer" align="center" valign="middle">{$_ticket.type}</td>
					<td class="ticketlistpropertiescontainer" align="center" valign="middle">{$_ticket.status}</td>
					<td class="ticketlistpropertiescontainer" style="background: {$_ticket.prioritybgcolor};" align="center" valign="middle">{$_ticket.priority}</td>
				</tr>

				<tr class="ticketlistpropertiesdivider {if $_ticket.isresolved ==1} ticketresolvedrow {/if}"
					{if $_showResolved =='false' && $_ticket.isresolved ==1}style="display:none"{/if}>
					<td colspan="7">&nbsp;</td>
				</tr>
			{/foreach}

			{else}

			<td class="ticketlistpropertiescontainer" align="center" valign="middle" colspan="7">
				<i>You do not have any open ticket in your account. Please click
					<a href="{$_submitTicketURL}">here</a> to submit new ticket
				</i>
			</td>

		{/if}

		</table>
	</div>
</div>