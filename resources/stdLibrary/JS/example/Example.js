
/** If you want your button can be actioned in both Click and Enter**/
/* Click Button pressed(button) */
$("#btgSearchBtn").click(function(){
	excuteSearch();
	postForm.submit();
});

/* Enter Button pressed(Input Textline) */
$("#btgTextInput").on('keyup', function (e) {
	if (e.keyCode == 13) {
		excuteSearch();
	}
});