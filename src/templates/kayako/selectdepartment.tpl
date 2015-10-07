<link rel="stylesheet" type="text/css" href="{$_jscssURL}/css/kayako.css"/>
<script type="text/javascript" src="{$_jscssURL}/js/kayako.js"></script>

<form name="SubmitTicketForm" id="SubmitTicketForm" action="{$_departmentFormURL}" method="post" onsubmit="return validateForm(1);">
	<div style="color:#ff0000;" align ="center">{$_message}</div>
	<div class="boxcontainer">
		<div class="boxcontainerlabel">Select a department</div>

		<div class="boxcontainercontent">
			If you can't find a solution to your problem in our knowledgebase, you can submit a ticket by selecting the appropriate department below.<br><br>
			<table class="hlineheader">
				<tbody>
				<tr>
					<th nowrap="" rowspan="2">Departments</th>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="hlinelower">&nbsp;</td>
				</tr>
				</tbody>
			</table>
			<table width="100%" cellspacing="1" cellpadding="4" border="0">
			{foreach from=$_departments item=_department}

				<tr>
					<td width="16" align="left" valign="middle" class="zebraodd">
						<input type="radio" name="departmentid" onclick="javascript: ToggleTicketSubDepartments('{$_department.departmentid}');" value="{$_department.departmentid}" id="department_{$_department.departmentid}"/>
					</td>
					<td><label for="department_{$_department.departmentid}">{$_department.title}</label></td>
				</tr>

				{foreach item=_subDepartment from=$_department.subdepartments}

					<tr class="ticketsubdepartments_{$_department.departmentid}" style="display: none">
						<td width="16" align="left" valign="middle" class="zebraodd">
							<input type="radio" name="departmentid" value="{$_subDepartment.departmentid}" id="department_{$_subDepartment.departmentid}"/>
						</td>
						<td>
							<div class="ticketsubdepartment"><label for="department_{$_subDepartment.departmentid}">{$_subDepartment.title}</label></div>
						</td>
					</tr>

				{/foreach}

			{/foreach}

			</table>
			<br>

			<div class="subcontent"><input type="submit" name="button" value="Next &raquo; " class="rebuttonwide2"></div>

		</div>
	</div>
</form>