<link rel="stylesheet" type="text/css" href="{$_jscssURL}/css/kayako.css" />
<script type="text/javascript" src="{$_jscssURL}/js/kayako.js"></script>

{if isset($_dialog)}
	{include file="$_templateURL/dialog.tpl"}
{/if}

<div class="boxcontainer">
	<div class="boxcontainerlabel">{if $_settings.nw_enablerss == '1'}<div style="float: right;"><a href="{$_swiftPath}rss/index.php?/News/Feed" title="{$_language.rssfeed}" target="_blank"><img src="{$_themePath}images/icon_rss.png" align="absmiddle" alt="{$_language.rssfeed}" border="0" /></a></div>{/if}News</div>

	<div class="boxcontainercontent">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td width="60" align="left" valign="top">
					<div class="datecontainerparent">
					<div class="monthholder"><div class="monthsub">{$_newsItem.parsedmonth}</div></div>
					<div class="dateholder"><div class="datecontainer">{$_newsItem.parseddate}</div></div>
					</div>
				</td>

				<td width="100%" valign="top">
					<div class="newstitle"><a class="newstitlelink" href="{$_baseURL}announcements.php?itemid={$_newsItem.id}" title="{$_newsItem.subject}">{$_newsItem.subject}</a>
					<div class="newsinfo">Posted by {$_newsItem.author} on {$_newsItem.date}</div>
				</td>
			</tr>
			<tr><td colspan="2" class="newscontents">
				{$_newsItem.contents}
			</td></tr>
			<tr>
			<td colspan="2"><hr class="newshr" /></td>
			</tr>
		</table>

		{if $_newsItem.allowcomments == '1'}
		{include file="$_templateURL/comments.tpl"}
		{/if}
	</div>
</div>