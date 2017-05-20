/* 자바스크립트로 미디어 쿼리를 구현합니다. */
function mediaQuery(){
	if($(window).width() >= 768){ // PC
		$("#heroContent-selector").removeClass("navbar-fixed-bottom");
	} else { 		// 모바일
		$("#heroContent-selector").addClass("navbar-fixed-bottom");
		$("#selectorWrapper").removeClass("in");
	}
}
