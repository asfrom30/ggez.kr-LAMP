/**
 * 
 */

/*window.onload와 window::onload()..
페이지 로딩시 시작할 스크립트 선언에 대해 <body onload="">의 onload를 많이 사용해 보았을 것입니다.
그리고 모든 페이지에서 공통으로 들어갈 스크립트는 페이지 마다 작성을 하지 않고, js 파일을 만들어 연결을 하여 사용을 할 것입니다.

여기서 그럼 모든 페이지에서 load시 공통으로 실행될 스크립트는 어떻게 작업을 할까요??
window.onload를 사용 하면 됩니다.

window.onload = function(){ 시작시 실행될 내용 }
이런식으로 말이죠.

그런데 문제는 window.onload와 <body onload="">는 동시에 사용을 할 수 없습니다.
<body onload="">가 실행이 되면 window.onload는 실행이 되지 않는 문제가 있습니다.

그래서 이를 해결하고자 할때 사용하는 것이
window::onload()입니다.
function window::onload(){ 시작시 실행될 내용 }
이렇게 사용을 하면 됩니다.
실행 순서는 <body onload="">가 먼저 실행되고, 이어서 window::onload()가 실행됩니다.*/

/** Example declare in the javascript **/
//주의 : window.onload의 심각한 단점(긴로딩, 페이지내 1개만 존재가능)
//다른페이지를 로딩할 때 onload가 있으면 동작하지 않음
window.onload = function(){
	// ovLoading.html 가져오기.
}
