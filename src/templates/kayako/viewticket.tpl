{if $_noPermissions == true}
<div style="text-align: center;">We are sorry, but you have no permission to view this ticket.</div>
<br/><br/>
{else}
<link rel="stylesheet" type="text/css" href="{$_jscssURL}/css/kayako.css"/>
<script type="text/javascript" src="{$_jscssURL}/js/kayako.js"></script>
<div class="boxcontainer">
<div class="boxcontainerlabel" style="min-height: 30px;">
	<div style="float: right">{if $_canChangeTicketProperties != false}
		<div class="headerbutton" onclick="javascript: $('#ticketpropertiesform').submit();">Update</div>
		{/if}
		<div class="headerbuttongreen" onclick="javascript: $('#postreplycontainer').show(); $('#replycontents').focus();">Post Reply</div>
	</div>
	View Ticket: #{$_ticketContainer.displayticketid}</div>
<div class="boxcontainercontenttight">

<form name="ticketpropertiesform" id="ticketpropertiesform" method="post" action="{$_submitURL}?action=update&ticketid={$_ticketContainer.ticketid}" enctype="multipart/form-data">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tbody>

		<tr>
			<td>
				<div class="ticketgeneralcontainer">
					<div class="ticketgeneraltitlecontainer">
						<div class="ticketgeneraldepartment">{$_ticketContainer.department}</div>
						<div class="ticketgeneraltitle">{$_ticketContainer.subject}</div>
					</div>
					<div class="ticketgeneralinfocontainer">Created: {$_ticketContainer.dateline}&nbsp;&nbsp;&nbsp;&nbsp;Updated: {$_ticketContainer.lastactivity}</div>

				</div>
			</td>
		</tr>

		<tr>
			<td style="background-color: {$_ticketContainer.statusbgcolor};">
				<div class="ticketgeneralcontainer">
					<div style="background-color: {$_ticketContainer.statusbgcolor};" class="ticketgeneralproperties">

						<div class="ticketgeneralpropertiesobject">
							<div class="ticketgeneralpropertiestitle">DEPARTMENT</div>
							<div class="ticketgeneralpropertiescontent">{$_ticketContainer.department}</div>
						</div>

						<div class="ticketgeneralpropertiesdivider"><img border="0" align="middle" src="{$_imageURL}/ticketpropertiesdivider.png"></div>

						<div class="ticketgeneralpropertiesobject">
							<div class="ticketgeneralpropertiestitle">OWNER</div>
							<div class="ticketgeneralpropertiescontent">{$_ticketContainer.fullname}</div>
						</div>

						<div class="ticketgeneralpropertiesdivider"><img border="0" align="middle" src="{$_imageURL}/ticketpropertiesdivider.png"></div>

						<div class="ticketgeneralpropertiesobject">
							<div class="ticketgeneralpropertiestitle">TYPE</div>
							<div class="ticketgeneralpropertiescontent">{$_ticketContainer.type}</div>
						</div>

						<div class="ticketgeneralpropertiesdivider"><img border="0" align="middle" src="{$_imageURL}/ticketpropertiesdivider.png"></div>

						<div class="ticketgeneralpropertiesobject">
							<div class="ticketgeneralpropertiestitle">STATUS</div>

							{if $_canChangeTicketProperties != false}
							<div class="ticketgeneralpropertiesselect">
								<select class="swiftselect" name="ticketstatusid">

									{foreach key=_optionID item=_option from=$_ticketStatusContainer}
									<option value="{$_option.ticketstatusid}"{if $_option.ticketstatusid == $_ticketContainer.ticketstatusid} selected{/if}>{$_option.title}</option>
									{/foreach}

								</select>
							</div>
							{else}
							<div class="ticketgeneralpropertiescontent">{$_ticketContainer.status}</div>
							{/if}

						</div>

						<div class="ticketgeneralpropertiesdivider"><img border="0" align="middle" src="{$_imageURL}/ticketpropertiesdivider.png"></div>

						<div class="ticketgeneralpropertiesobject" style="background-color: {$_ticketContainer.prioritybgcolor};">
							<div class="ticketgeneralpropertiestitle">PRIORITY</div>

							{if $_canChangeTicketProperties != false}
							<div class="ticketgeneralpropertiesselect">
								<select class="swiftselect" name="ticketpriorityid">

									{foreach key=_optionID item=_option from=$_ticketPriorityContainer}
									<option value="{$_option.priorityid}"{if $_option.priorityid == $_ticketContainer.priorityid} selected{/if}>{$_option.title}</option>
									{/foreach}</select></div>
							{else}
							<div class="ticketgeneralpropertiescontent">{$_ticketContainer.priority}</div>
							{/if}

						</div>

						<div class="ticketgeneralpropertiesdivider"><img border="0" align="middle" src="{$_imageURL}/ticketpropertiesdivider.png"></div>

					</div>
				</div>
			</td>
		</tr>

		</tbody>
	</table>
	<br/>

	<div class="viewticketcontentcontainer">

		{include file="$_templateURL/customfields.tpl"}

	</div>
</form>


<div id="postreplycontainer" style="display: {if $_expandPostReply === true}block{else}none{/if};">

	<form method="post" action="{$_submitURL}?action=reply&ticketid={$_ticketContainer.ticketid}" name="TicketReplyForm" enctype="multipart/form-data">
		<div class="ticketpaddingcontainer">
			<table class="hlineheader">
				<tr>
					<th rowspan="2" nowrap>Message Details</th>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="hlinelower">&nbsp;</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="4">
				<tr>
					<td colspan="2" align="left" valign="top"><textarea name="replycontents" cols="25" rows="15" id="replycontents" class="swifttextareawide"></textarea>
					</td>
				</tr>
			</table>
			<br/>
			<table class="hlineheader">
				<tr>
					<th rowspan="2" nowrap>Upload File(s)
						<div class="addplus"><a href="javascript:void(0);" onclick="javascript: AddTicketFile();">Add File</a></div>
						]
					</th>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="hlinelower">&nbsp;</td>
				</tr>
			</table>
			<div id="ticketattachmentcontainer">
			</div>
			<br/>

			<div class="subcontent"><input class="rebuttonwide2" value="Send" type="submit" name="button"/></div>
		</div>
	</form>
</div>


<div class="ticketpostsholder">

	{foreach key=_ticketPostID item=_ticketPost from=$_ticketPostContainer}
	<div class="ticketpostcontainer">
		<div class="ticketpostbar">
			<div class="ticketpostbarname">{$_ticketPost.fullname}</div>
			{if $_ticketPost.designation != ''}
			<div class="ticketpostbardesignation">{$_ticketPost.designation}</div>{/if}
			<div class="{$_ticketPost.badgeclass}">
				<div class="tpbadgetext">{$_ticketPost.badgetext}</div>
			</div>
			{if $_ticketPost.avatar != ''}
			<div class="ticketpostavatar">
				<div class="tpavatar"><img src="{$_ticketPost.avatar}" align="absmiddle" border="0"/></div>
			</div>{/if}
		</div>

		<div style="min-height: {$_ticketPost.minimumheight}px;" class="ticketpostcontents">

			<div class="ticketpostcontentsbar">
				<div onclick=" self.scrollTo(0, 0); return false;" class="ticketbarquote"></div>
				<div class="ticketbarcontents">{$_ticketPost.posttitle}</div>
				<span class="ticketbardatefold"></span></div>

			<div class="ticketpostcontentsdetails">
				{if $_ticketPost.hasattachments == '1'}
				<div class="ticketpostcontentsattachments">{/if}
					{foreach key=_attachmentID item=_ticketAttachment from=$_ticketPost.attachments}
					<div class="ticketpostcontentsattachmentitem" onclick="javascript: PopupSmallWindow('{$_ticketAttachment.link}');"
						 style="background-image: URL('{$_imageURL}/{$_ticketAttachment.icon}');">&nbsp;{$_ticketAttachment.name} ({$_ticketAttachment.size})
					</div>
					{/foreach}
					{if $_ticketPost.hasattachments == '1'}</div>{/if}
				<div class="ticketpostcontentsholder">
					<div class="ticketpostcontentsdetailscontainer">{$_ticketPost.contents}</div>
				</div>
			</div>

			<div class="ticketpostcontentsbottom"><span class="ticketpostbottomright">&nbsp;</span>

				<div class="ticketpostbottomcontents">{$_ticketPost.postfooter}&nbsp;</div>
			</div>
		</div>

		<div class="ticketpostbarbottom">
			<div class="ticketpostbottomcontents">&nbsp;&nbsp;</div>
		</div>

		<div class="ticketpostclearer"></div>
	</div>
	{/foreach}

</div>

</div>
</div>
{/if}