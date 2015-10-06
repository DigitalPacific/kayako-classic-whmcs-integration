<link rel="stylesheet" type="text/css" href="{$_jscssURL}/css/kayako.css" />
<script type="text/javascript" src="{$_jscssURL}/js/kayako.js"></script>

{if isset($_dialog)}
	{include file="$_templateURL/dialog.tpl"}
{/if}
<div class="boxcontainer">
	<div class="boxcontainerlabel">Knowledgebase{if $_kbCategoryContainer != false}: <a href="{$_baseURL}knowledgebase.php?catid={$_kbCategoryContainer.id}">{$_kbCategoryContainer.title}</a>{/if}</div>

	<div class="boxcontainercontent">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td valign="top">
					<div class="kbtitle"><span class="kbtitlemain">{$_knowledgebaseArticle.subject}</span></div>
					<div class="kbinfo">Posted by {$_knowledgebaseArticle.author} on {$_knowledgebaseArticle.date}</div>
				</td>
			</tr>
			<tr><td colspan="2" class="kbcontents">
				{$_knowledgebaseArticle.contents}
			</td></tr>
			<tr>
			<td colspan="2">

			{if $_knowledgebaseArticle.hasattachments == '1'}
			<br /><br />
			<div><table class="hlineheader hlinegray"><tr><th rowspan="2" nowrap>Attachments</th><td>&nbsp;</td></tr><tr><td class="hlinelower">&nbsp;</td></tr></table></div>
			<div class="kbattachments">
			{foreach key=_attachmentID item=_kbAttachment from=$_attachmentContainer}
			<div class="kbattachmentitem" onclick="javascript: PopupSmallWindow('{$_kbAttachment.link}');" style="background-image: URL('{$_imageURL}{$_kbAttachment.icon}');">&nbsp;{$_kbAttachment.name} ({$_kbAttachment.size})</div>
			{/foreach}
			</div>
			{/if}

			<hr class="kbhr" /></td>
			</tr>
		</table>

		{if $_knowledgebaseArticle.allowcomments == '1'}
		{include file="$_templateURL/comments.tpl"}
		{/if}
	</div>
</div>