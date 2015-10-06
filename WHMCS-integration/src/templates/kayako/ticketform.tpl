<link rel="stylesheet" type="text/css" href="{$_jscssURL}/css/kayako.css" />
<script type="text/javascript" src="{$_jscssURL}/js/kayako.js"></script>

<form enctype="multipart/form-data" name="SubmitTicketForm" action="{$_ticketFormURL}" method="post" onsubmit="return validateForm(2);">
	<div class="boxcontainer">
		<div class="boxcontainerlabel">Your ticket details</div>

		<div class="boxcontainercontent">
			Enter your ticket details below. If you are reporting a problem, please remember to provide as much information that is relevant to the issue as possible.<br><br>

			<table class="hlineheader">
				<tbody>
					<tr>
						<th nowrap="" rowspan="2">General Information</th>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td class="hlinelower">&nbsp;</td>
					</tr>
				</tbody>
			</table>

			<table width="100%" cellspacing="1" cellpadding="4" border="0">
				<tr>
					<td width="125"  align="left" class="zebraodd" valign="middle">Priority</td>
					<td>
						<select class="swiftselect" name="ticketpriorityid" id="ticketpriorityid">

							{foreach from=$_ticketPriorities item=_ticketPriority}

								<option value="{$_ticketPriority.priorityid}">{$_ticketPriority.title}</option>

							{/foreach}

						</select>
					</td>
				</tr>

			</table>
			<br>

			{include file="$_templateURL/customfields.tpl"}

			<table class="hlineheader">
				<tbody>
					<tr>
						<th nowrap="" rowspan="2">Message Details</th>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td class="hlinelower">&nbsp;</td>
					</tr>
				</tbody>
			</table>

			<table width="100%" cellspacing="1" cellpadding="4" border="0">
				<tbody>
					<tr>
						<td width="125" valign="middle" align="left" class="zebraodd">Subject</td>
						<td width=""><input type="text" value="" id="ticketsubject" class="swifttextwide" size="60" name="ticketsubject"></td>
					</tr>
				</tbody>
			</table>

			<table width="100%" cellspacing="1" cellpadding="4" border="0">
				<tr>
					<td valign="top" align="left" colspan="2"><textarea class="swifttextareawide" rows="15" cols="25" id="ticketmessage" name="ticketmessage"></textarea></td>
				</tr>
			</table>
			<br>

			<table class="hlineheader">
				<tbody>
					<tr>
						<th nowrap="" rowspan="2">Upload File(s) [<div class="addplus"><a onclick="javascript: AddTicketFile();" href="javascript:void(0)">Add File</a></div>]</th>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td class="hlinelower">&nbsp;</td>

					</tr>
				</tbody>
			</table>

			<div id="ticketattachmentcontainer"></div>

			<br>

			<div class="subcontent"><input type="submit" name="button" value="Submit" class="rebuttonwide2"><input type="hidden" value="{$_departmentID}" name="departmentid"></div>

		</div>
	</div>
</form>