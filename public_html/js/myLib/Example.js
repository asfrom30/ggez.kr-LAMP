
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

/** Bootstrap Collapsing **/
/** 컬랩스 DIV를 한번 닫았다가 다시 열개할 수 있음 **/
if($("#search-dropdown").hasClass("in")){
	$("#search-dropdown").collapse("hide");
	$("#search-dropdown").on('hidden.bs.collapse', function(){
		$("#search-dropdown").collapse("show");
    });
} else {
	$("#search-dropdown").collapse("show");
}

/* 클릭 한번만 했는데 두번 동작되는 현상 방지  */
$("#build-hero-table th:nth-child(1) button").unbind('click').bind('click', function(){
	
});