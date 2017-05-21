/* Relate to Drop down Function */
function addDropDownListener(){
	/* 즐겨찾기 항목 버튼  */
	$("#js-favorite-list-button").click(function(){
		$("#js-dropdown-favorite-list").toggleClass("show");
	});
}

//Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.js-dropdown-button')) {
    var dropdowns = document.getElementsByClassName("js-dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}

/*************************************************************/ 



/* ovLoading fade out, tab-content fade in */
function fadeInTabContent(){
	$(".tab-loading").addClass("fade-out"); 
	$(".tab-loading").css("z-index", "-1");
	$(".tab-loading").css("display", "none");
	$(".tab-content").removeClass("fade-out");
}

function printMyBattleName(battleName){
	$(".js-my-battle-name").text(battleName);
}

function printFriendBattleName(battleId){
	/* 응답을 받은뒤에 친구 이름을 갱신한다. */
	if(typeof window.fBattleName != 'undefined'){
		$("#js-fixed-bottom-friend-name").text(fBattleName);
	}
}

/* 플레이어 아이콘을 설정합니다. */
function drawMyIconImage(){
	var iconUrl = getStat('All', 'zData', 'All', 'etcData', 'iconUrl');
	setBackgroundImg($("#mainTitleImg"), iconUrl);
}

/* 활성 탭에 따라서 period 버튼을 선택 할수도 못할 수도 있습니다. */
function setPeriodButtonActive(){
	/* active 탭을 구합니다. */
	var activePane;
	$(".main-tab-contents .tab-content .tab-pane").siblings().each(function(){
		if($(this).hasClass("active")){
			activePane = $(this).attr('id');
		}
		// TODO: Jquery Return value?
		// 현재와 같은 전역변수 형식이 아니라 RETURN VALUE로 주면 좋을텐대..
	});
	
	if(activePane == 'html-hero-detail'){
		/* 시즌 평균에 블루 라벨을 추가한다. */
		$("#avgBtn").css("border", "2px solid rgba(154,208,245,1)");
		$("#avgBtn").css("color", "white");
		
		$(".tab-content .data-selector .friend-data-selector button").css("border", "0px solid black");
		$(".tab-content .data-selector .friend-data-selector button").addClass("disabled");
	} else if(activePane == 'html-friend-compare'){
		/* 시즌 평균에 있는 블루라벨을 제거한다. */
		$("#avgBtn").css("border", "");
		$("#avgBtn").css("color", "");
		
		$(".tab-content .data-selector .friend-data-selector button").css("border", "");
		$(".tab-content .data-selector .friend-data-selector button").removeClass("disabled");
	}
}

/* 활성탭에 따라 보여지는 영역이 달라지는 것을 컨트롤 합니다. */
function controlTabDisplay(tabId){
	/* Search Bar Display Control */
	$("#js-favorite-search-group").css("display", "none");
	if(tabId == "js-favorite-tab-button"){
		 $("#js-favorite-search-group").css("display", "block");
	}
	
	/* Fixed Bottom Display Control */
	$("#heroContent-selector").css("display", "block");
	if(tabId == "js-total-tab-button"){
		 $("#heroContent-selector").css("display", "none");
	}

	/* 활성 탭에 따라서 period 버튼을 선택 할수도 못할 수도 있습니다. */
	if(tabId == 'js-hero-tab-button'){	// 영웅상세탭
		/* 시즌 평균에 블루 라벨을 추가한다. */
		$("#avgBtn").css("border", "2px soltabId rgba(154,208,245,1)");
		$("#avgBtn").css("color", "white");
		
		$(".tab-content .data-selector .friend-data-selector button").css("border", "0px soltabId black");
		$(".tab-content .data-selector .friend-data-selector button").addClass("disabled");
		
		/* fixed bottom에 있는 라벨을 기간 <-> 친구로 변경합니다. */
		$("#js-fixed-bottom-title-bottom").removeClass("display-none");
		$("#js-fixed-bottom-title-bottom-versus").addClass("display-none");
		
	} else if(tabId == 'js-favorite-tab-button'){	// 친구 비교 탭
		/* 시즌 평균에 있는 블루라벨을 제거한다. */
		$("#avgBtn").css("border", "");
		$("#avgBtn").css("color", "");
		
		$(".tab-content .data-selector .friend-data-selector button").css("border", "");
		$(".tab-content .data-selector .friend-data-selector button").removeClass("disabled");
		
		/* fixed bottom에 있는 라벨을 기간 <-> 친구로 변경합니다. */
		$("#js-fixed-bottom-title-bottom").addClass("display-none");
		$("#js-fixed-bottom-title-bottom-versus").removeClass("display-none");
	}
	
	// Pane을 클릭할때 업데이트 라벨을 같이 변경합니다.
	var periodIndex = getActivePeriodIndex($(".my-data-selector button"));
	var periodfIndex = getActivePeriodIndex($(".friend-data-selector button"));
	setUpdateBadgeWithPane(periodIndex, periodfIndex, tabId);
}

/** Radar Table **/
function drawRadarTableResultSummary(tableId, stdIndex, compIndex){
	/* 결과(마지막) Row 계산 */
	$(tableId +" tbody tr td[colspan='5']").parent().remove();	// 아마 colspan이 5인것을 삭제..
	var stdTitle = $(tableId +" thead th:nth-child(" + stdIndex + ")").text();
	var compTitle = $(tableId + " thead th:nth-child(" + compIndex + ")").text();
	
	if(isNaN(diffSum)){
		$(tableId + " tbody").append('<tr><td colspan="5"><span>결과가 없습니다</span></td></tr>');
		return;
	}
	
	if(diffSum == 0){
		var comment = '<span>--%</span>'
	} else {
		var comment = getResultCellHtml(diffSum);
	}
	$(tableId + " tbody").append('<tr><td colspan="5"><span>'+ compTitle + '</span>보다 <span>' + stdTitle + '</span>가(이) ' + comment + ' 플레이를 했습니다.</td></tr>');
}

function drawRadarTableHeaderIndex(tableId, stdIndex, compIndex){
	// Underline Update(th 안의 항목을 div로 선언한뒤 border-bottom 1px을 줌)
	$(tableId + " th div").css("border-bottom", "initial");
	$(tableId + " th:nth-child(" + stdIndex + ") div").css("border-bottom", "1px solid red");
	$(tableId + " th:nth-child(" + compIndex + ") div").css("border-bottom", "1px solid red");
}

function drawRadarTableResultTd($_targetTd, stdPt, compPt, diffValue){
	
	if(diffValue == null){
		$_targetTd.html(wrapDiv('-'));
	} else if (stdPt >= 100){
		$_targetTd.html(wrapDiv('<span class="perfect">Perfect<span>'));	// prePt가 100점 이상이면 Perfect
	} else {
		var resultCellHtml = getResultCellHtml(diffValue);	// TODO: 코드 정리해야 할것.
		$_targetTd.html(wrapDiv(resultCellHtml));
	}
}

//결과 열에 사용될 요소를 생성합니다.
//span1은 ▲▼, span2는 수치를 대입합니다.
function getResultCellHtml(diffValue){
	var span_1 = '-';
	var span_2 = 0;
	var span_3 = '%';
	
	// (post - pre)/pre // pre가 기준이다. pre 보다 얼마나 성장했냐
	if(diffValue > 0){
		span_1 = '<span style="color: DeepSkyBlue">▼';
	} else if (diffValue < 0){
		span_1 = '<span style="color: red">▲';
	}
	
	span_2 = Math.abs(diffValue);
	
	if(span_2 == 0) {
		span_1 = '<span style="color: Chartreuse">-';
	}
	
	return span_1 + '</span>' + '<span>' + span_2 + '</span><span>' + span_3 + "</span>";
}



/** 전체 요약 View **/
/* 전체 요약의 Trend Section을 설정합니다. */
/* 현재 active된 버튼을 스스로 계산하여 데이터를 업데이트 합니다. 다른 변수가 필요하지 않습니다. */
function updateTrendSection(){
	var selBtnId = getSelTrendBtnId();	// 현재 Active 된 버튼의 id를 가져온다.
	
	// 라벨변경
	setTrendLabel(selBtnId);
	setTrendCptLabel(selBtnId);
	
	// get Pre & post index
	var indexes = getIndex(selBtnId);
	var preIndex = indexes[0]; // today, yesterday, week 값이다.
	var postIndex = indexes[1];	//
	
	// Check Update가 NULL이면 게임을 하지 않았으므로 NOGAME을 반환한다.
	if(!checkUpdate('All', preIndex)){
//		$(".trend > .contentBoxWrapper").collapse('hide');
		// no game panel collapse in
//		return;
	} else {
//		$(".trend > .contentBoxWrapper").collapse('show');
		// no game panel collapse
	}
	
	// Set Data
	setTrendCpt(preIndex, postIndex);
	setTrendWinRating('avg', preIndex);
	setTrendPtRating('avg', preIndex);
	//	setTrendBurnedTime('avg', preIndex);
	//	setTrendKDA('avg', preIndex);
}

/* 받아온 short stats이용하여 해당 리스트들을 디스플레이 한다. */
function updateShortStatsListDisplay($_target, shortStats, isInit){
	/* Initiate Variable */
	var count = 1;	// Nth-child index
	
	/* 기존 리스트 앨리먼트들을 삭제합니다. */
	$_target.find("li").remove();
	
	
	/* 검색된 사용자가 없습니다 처리할 것(이코드가 있으면 즐겨찾기 리스트가 있을때도 알람이 떠버린다. 즐겨찾기 리스트는 예외이므로 예외로 등록 */
	if($_target.attr("id") == "#small-favoirte-list"){// 예외 등록
		
	} else {
		if(!isInit && (Object.keys(shortStats).length == 0)){
			alert("검색된 사용자가 없습니다.");
			return;
		}
	}
	
	/* Make Each Favorite Player List */
	for(var id in shortStats){		//TODO: make for..each Built in https://goo.gl/9JWYMN
		//TODO : Jquery 같은 형식으로 간단하게 만들 것.
		//TODO : null 값은 삭제할것
		//TODO : 경쟁전 점수 -1점..
		/* Initiate Variable */
		var platform = wrapDiv(shortStats[id]["platform"]);
		var iconUrl = shortStats[id]["iconUrl"];
		var battleName = wrapDiv(shortStats[id]["battleName"]);
		var most3 = wrapDiv("");
		
		/* Cpt Pt Display 변경 */
		var cptPt = shortStats[id]["cptPt"]
		if(cptPt == '-1') cptPt = '-';
		cptPt = wrapDiv(cptPt);
		
		/* Add list element with id and innerdiv */
		$_target.append("<li id='" + id + "'><div></div></li>");
		/* Add Div */
		$_target.find("li:nth-child(" + count + ") > div").append(platform).append("<div></div>"/* background div */).append(battleName).append(cptPt).append(most3);
		
		/* 플레이어 아이콘 설정 */
		var jqueryElem = $_target.find(" li:nth-child(" + count + ") > div > div:nth-child(2)");
		setBackgroundImg(jqueryElem, iconUrl);
		
		count += 1;
	}
}

/** Plain Jquery Method (Jquery Elem like Getter and Setter) **/
/* XXX 이런 getter, setter처럼 사소한 코드라도 함수로지정해놓으면 변수명이 바뀌어도 일일히 찾아다니면서 바꿀 필요가 없다. */

function friendLoaderScreen(bool){
	var $_target = $("#js-friend-tap-loader-screen");
	displayFlex($_target, bool);
}




/** Input **/

	


