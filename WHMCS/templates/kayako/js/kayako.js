function ToggleTicketSubDepartments(_departmentID) {
	$("tr[class^='ticketsubdepartments_']").hide();
	$('.ticketsubdepartments_' + _departmentID).show();
}

function AddTicketFile() {
	$('#ticketattachmentcontainer').append('<div class="ticketattachmentitem"><div class="ticketattachmentitemdelete" onclick="javascript: $(this).parent().remove();">&nbsp;</div><input name="ticketattachments[]" type="file" size="20" class="swifttextlarge" /></div>');
};


function ToggleViewHideResolved() {
	if ($("tr[class='ticketresolvedrow']").css('display') == 'none') {
		$("#resolvedviewbutton").hide();
		$("#resolvedhidebutton").show()
		$("tr[class*='ticketresolvedrow']").show();
	} else {
		$("#resolvedviewbutton").show();
		$("#resolvedhidebutton").hide()
		$("tr[class*='ticketresolvedrow']").hide();
	}
}

function CheckForResolved(element) {
	if ($("#resolvedviewbutton").css('display') == 'none') {
		$(element).attr('href', $(element).attr('href') + '&showResolved=true')
	}
}

function ClearDateField(_fieldName) {
	$('#' + _fieldName).val('');
	$('#' + _fieldName + '_hour').val('12');
	$('#' + _fieldName + '_minute').val('0');
	$('#' + _fieldName + '_meridian').val('am');
};

function LinkedSelectChanged(_selectObject, _fieldName) {
	var _selectValue = $(_selectObject).val();

	$('.linkedselectcontainer_' + _fieldName).hide();

	if ($('#selectsuboptioncontainer_' + _selectValue).length) {
		$('#selectsuboptioncontainer_' + _selectValue).show();
	}
};

window._uiOnParseCallbacks = new Array();

function QueueFunction(_functionData) {
	window._uiOnParseCallbacks[_uiOnParseCallbacks.length] = _functionData;

	return true;
};

function PopupSmallWindow(url) {
	screen_width = screen.width;
	screen_height = screen.height;
	widthm = (screen_width-400)/2;
	heightm = (screen_height-300)/2;
	window.open(url,"infowindow"+GetRandom(), "toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,width=400,height=300,left="+widthm+",top="+heightm);
};

function GetRandom()
{
	var num;
	now=new Date();
	num=(now.getSeconds());
	num=num+1;
	return num;
};

function MoveCommentReply(_commentID) {
	$('#commentsformcontainer').appendTo('#commentreplycontainer_' + _commentID);
	$('#commentformparentcommentid').val(_commentID);
	$('#postnewcomment').hide();
	$('#replytocomment').show();
}

function SubmitForm() {
	$("#fetchTicketsForm").submit();
}

function validateForm(submissionStep) {

	if(submissionStep == 1) {
		if ($("input[name='departmentid']:checked").length <= 0) {
			alert('Please select department!');
			return false;
		}
	} else if(submissionStep == 2) {

		if ($("#ticketpriorityid").val() == '') {
			alert('Please select ticket priority!');
			return false;
		}

		if ($("#ticketsubject").val() == '') {
			alert('Please enter ticket subject!');
			return false;
		}

		if ($("#ticketmessage").val() == '') {
			alert('Please enter ticket message!');
			return false;
		}

		var attachments = [];
		$("input[name='ticketattachments[]']").each(function() {
			attachments.push($(this).val());
		});

		for ($i= 0; $i< attachments.length; $i++) {
			if (attachments[$i] == '') {

				alert('Please select a file to upload!');
				return false;
			}
		}

	}

	return true;
}