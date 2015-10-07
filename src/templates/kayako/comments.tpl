<div class="commentslabel">Comments ({$_commentCount})</div>
	<div id="commentscontainer">
	{foreach key=_commentID item=_comment from=$_commentContainer}
	<div style="padding-left: {$_comment.padding}px;">
		<div class="commentavatar{if $_comment.parentcommentid != '0'} commentchild{/if}"><img src="{$_comment.avatarurl}" align="absmiddle" border="0" />
		<div style="">{if $_canPostComments != false}<a href="javascript: void(0);" onclick="javascript: MoveCommentReply('{$_commentID}');">Reply</a>{/if}</div>
		</div>
		<div class="commentdataholder" style="padding-left: {if $_comment.parentcommentid != '0'}80px;{else}60px{/if}">
			<div class="{if $_comment.isstaff == true}commentdataholderstaff{/if}">
				<div class="commentnamelabel">{$_comment.fullname}</div>
				<div class="commentdatelabel">{$_comment.date}</div>
				<div class="commentcontentsholder">{$_comment.contents|nl2br}</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div id="commentreplycontainer_{$_commentID}"></div>
	</div>
	{/foreach}
	{if $_canCaptcha == true && $_settings.security_captchatype == 'recaptcha'}
	<script type="text/javascript" src="https://www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
	{/if}

	<div id="commentparent">
		{if $_canPostComments != false}
		<div id="commentsformcontainer">
		<form method="post" action="{$_commentSubmitURL}" enctype="multipart/form-data" name="CommentsForm">
		<input type="hidden" name="parentcommentid" id="commentformparentcommentid" value="0" />
		<table class="hlineheader"><tr><th rowspan="2" nowrap><div id="postnewcomment">Post a new comment</div><div id="replytocomment" style="display:none;" >Reply to comment</div></th><td>&nbsp;</td></tr><tr><td class="hlinelower">&nbsp;</td></tr></table>
		<table width="100%" border="0" cellspacing="1" cellpadding="4">
			{if $_commentsFullName == ''}
			<tr>
				<td width="200" align="left" valign="middle" class="zebraodd">Full Name:</td>
				<td><input name="fullname" type="text" size="25" class="swifttextlarge" value="{$_commentsPostFullName}" /></td>
			</tr>
			{/if}
			{if $_commentsEmail == ''}
			<tr>
				<td align="left" valign="middle" class="zebraodd">Email:</td>
				<td><input name="email" type="text" size="25" class="swifttextlarge" value="{$_commentsPostEmail}" /></td>
			</tr>
			{/if}
			<tr>
				<td align="left" valign="top" class="zebraodd">Comments:</td>
				<td><textarea name="contents" class="swifttextarea" rows="4" cols="20">{$_commentsPostData}</textarea></td>
			</tr>
		</table>
		{if $_canCaptcha == true}
		<table class="hlineheader"><tr><th rowspan="2">{$_language.captchaverification}</th><td>&nbsp;</td></tr><tr><td class="hlinelower">&nbsp;</td></tr></table>
		<div class="subcontent">{$_language.captchadesc}</div>
		{$_captchaHTML}
		<br />
		{/if}
		<div class="subcontent"><input class="rebuttonwide2" value="Submit" type="submit" name="button" /></div>
		{if $_commentsFullName != ''}<input type="hidden" name="fullname" value="{$_commentsFullName}" />{/if}
		{if $_commentsEmail != ''}<input type="hidden" name="email" value="{$_commentsEmail}" />{/if}
		</form>
		</div>
		{/if}
	</div>
</div>