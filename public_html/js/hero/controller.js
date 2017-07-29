/* Global Variable Delclaration */
/* */
var favShortData;	// Favorite Short Data를 저장한다.
var topShortData;	// Top 10 Short Data를 저장한다.

/* Global Stat Variable(Response뒤에 초기화 됩니다) */
/* update Display에서 이 값을 가져다가 쓴다. */
var stats;			// Store Player Data
var fstats;			// Store Friend Data
var tiers;			// Store Tier Data
var most3Heros;		// Most3 영웅들 저장

/* Radar Chart Data 저장 */
var radarCharts;
var fRadarCharts;

var resultShiftIndex = 0;
var resultfShiftIndex = 0;

var fBattleName;
var fBattleId;
var fTempBattleId;

var ids = {
		friendSearchBar : "js-favorite-search-group",
		
		mainTabButtonsId : "js-main-tab-buttons",
		totalTabButtonId : "js-total-tab-button",
		heroTabButtonId : "js-hero-tab-button",
		favoriteTabButtonId : "js-favorite-tab-button",
		
		totalTabContentId : "js-total-tab-content",
		heroTabContentId : "js-hero-tab-content",	
		favoriteTabContentId : "js-friend-tab-content",
		dataSelectorWrapperId : "heroContent-selector",
	}

/* Util? */
function getJquery(ids){
	return $("#" + ids);
}


/* Ready Java Script */
$(document).ready(function(){
	
	/* All Html Ids */
//	console.log(document.querySelectorAll('*[id]'));
	
	/* 배틀태그가 정의 되지 않으면 홈으로 돌아간다. */
	if(btg == null){
		window.location.replace("ggez.kr");
	} 
	
	/* Favorite Player 쿠키가 있는지 확인하고 없으면 생성한다. */
	if(!checkCookie(cFavoriteName)){
		var cInitValue = JSON.stringify({});	// 빈 Object를 Json 형태로 encode 한다.
		setCookie(cFavoriteName, cInitValue, cFavoriteExp);	// Index.php에 선언 되어 있다. PHP로 부터 파라메터를 전달받는다.
	}
	
	/* Init Chart Property */
	initStarRating();
	window.radarCharts = initChrt($("#radarCanvas")[0]); // Radar Chart 필요한것 data(데이터 정보, 안에 차트 색깔)와 option(라벨링과 폰트)
	window.fRadarCharts = initChrt($("#friend-radarCanvas")[0]);
	//TODO: Hard Wiring(Need pass by reference)
	// http://unikys.tistory.com/324, https://goo.gl/O0F1Vn
	
	/* Set My Battle Name */
	printMyBattleName(window.battleName);
	
	/* 응답을 기다리는 쓰레드를 만듭니다. ajax에 함수 안에 되지만, 여기에 선언하는 이유는 Timer를 설정하여 wait time과 timeout을 설정하기 위함입니다. */
	setResponseTimer(/*waitTime=*/ 0,/*timeout=*/ 200);	// 200ms는 20초	//TODO:광고코드 삽입, Wait Time 수정
	
	/** Ajax 요청 **/
	/* 사용자 Stat 데이터를 Ajax로 요청 합니다. */
	ajaxFetchStats(btg, updateMyStats, "stats", null);
	
	/* Favorite Display Update */
	updateFavoriteListShortStats()
	
	/* 버튼맵을 설정 합니다. */
	initButtonMap();
	
	/* Pane Active에 따라서 Period Button을 Active 유/무를 결정합니다. */
	setPeriodButtonActive();
	
	/* All of global variable has data check */ //TODO : Check Correct Initialize
	console.log("initialize complete");
});

/* Init Button Map */
function initButtonMap(){
	
	/* Tab Button Click(활성탭에 따라 보여지는것과 보여지지 않는 것을 컨트롤합니다. */
	var $_target = getJquery(window.ids.mainTabButtonsId);
	$_target.find("button").unbind('click').bind('click', function(){
		addMainTabListner($(this));
	});
	
	/* Favorite Player Registration(Star Shape Icon Clicked) */
	$("#regFavorite").click(function(){
		favoriteButClicked($(this));
	});
	
	/* 전체 요약 > 트렌드 버튼안에 있는 기간선택 버튼을 클릭 했을때 */
	$('.trendBtn').click(function(){
		$(this).addClass("active").siblings().removeClass("active");
		updateTrendSection();
	});
	
	/** Fixed Bottom Buttons **/
	/* Period Select Button */
	$(".heroPeriodBtn").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
		executeAfterButton();	// Update Flag, For not updating Season Data(false)
	});

	/* Hero Select Button */
	$(".heroList div.heroContainer").siblings().not(".heroList div.roleContainer").click(function(){
		$(".heroList div.heroContainer").siblings().not(".heroList div.roleContainer").removeClass("active");	//TODO JQUERY SIBLING 한줄로 심플하게 바꿀것.
		$(this).addClass("active");
		executeAfterButton(); // Update Flag, set updating Season Data(true) // 영웅을 클릭할때는 계속 update 해야하지만, 어제, 오늘, 주간경기를 클릭할땐 필요없다.
	});
	
	
	/* Tier Select Button */
	$(".tierSelectBtn").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
		executeAfterButton();
	});
	
	/** Radar Table Buttons **/
	/* 영웅상세 레이더 테이블 내의 비교변경 버튼 */
	$("#my-radar-table th:nth-child(5)").click(function(){
		shiftResultCase("resultShiftIndex");
		updateRadarTableResult("#my-radar-table");
	});
	
	/* 즐겨찾기 레이더 테이블 내의 비교 변경 버튼 (추가 삭제) */
	$("#friend-radar-table th:nth-child(5)").click(function(){
		shiftResultCase("resultfShiftIndex");
		updateRadarTableResult("#friend-radar-table");
	});
	
	/* 영웅상세 레이더 테이블 내의 상세정보 버튼 (추가 삭제)*/
	$("#my-radar-table th:nth-child(1) button").unbind('click').bind('click', function(){
		var jqueryElem = $("#my-radar-table td:nth-child(1) > div:nth-child(2)").toggleClass("display-none"); 
	});
	
	/* 친구 비교 레이더 테이블 내의 상세정보 버튼 */
	$("#friend-radar-table th:nth-child(1) button").unbind('click').bind('click', function(){
		var jqueryElem = $("#friend-radar-table td:nth-child(1) > div:nth-child(2)").toggleClass("display-none"); 
	});
	
	/** 친구비교 안에 있는 버튼들 **/
	/* 배틀태그 찾는 기능 Click Button pressed */
	$("#js-compare-search-group button").click(function(){
		friendSearchButClicked();
	});
	
	/* 배틀태그 찾는 기능 Enter Button pressed */
	$("#js-compare-search-group input").on('keyup', function (e) {
		if (e.keyCode == 13) {
			friendSearchButClicked();
		}
	});
	
	/* 지금 친구를 즐겨찾기에 추가 */
	$("#js-favorite-add-button").click(function () {
		addFavorite();
	});
	
	/* Drop Down 메뉴에 토글 버튼 리스너를 추가합니다. */
	addDropDownListener();
	
	/* Top 10 Drop Down button Clicked */
	/* need tier and hero */
	/* data 베이스 어디에 저장할 것인지.. */
}

/** Execute Button **/
function executeAfterButton(){
	
	var periodIndex = getActivePeriodIndex($(".my-data-selector button"));
	var periodfIndex = getActivePeriodIndex($(".friend-data-selector button"));
	var tierIndex = getActiveTierIndex();
	var heroIndex = getActiveHeroIndex();
	
	/** Label Update in Fixed Bottom **/
	/* Fixed Bottom에 있는 Title의 Text를 변경합니다. */
    setFixedBtmTitle(heroIndex, periodIndex);
    /*  When you open Fixed bottom tab, you see '다른티어평균(라인하르트)' */
    setFixedBtmInsideLabel(heroIndex);// Update subTitle(hero) : 다른티어 평균 선택하기
    
    /* Update Tier Player Number Label */
    updateTierNum(heroIndex);

    /* Update Hero Play Game Set Number */
	setUpdateBadge(periodIndex, periodfIndex);
    
	/** Radar Chart Update **/
	/* Radar Chart Data Update */
	setRadarChartData(heroIndex, periodIndex, tierIndex);
	setfRadarChartData(heroIndex, periodIndex, periodfIndex, tierIndex);
	
    /* Radar Chart Label Update */
    setRadarChartLabel(window.radarCharts, periodIndex, tierIndex);
    setfRadarChartLabel(periodIndex, periodfIndex, tierIndex);
    
    /* Radar Chart Update */
	window.radarCharts.update();
	window.fRadarCharts.update();
    
    /** Radar Table Update **/
    /* Radar Table Header Update */
	setRadarTableHeader(periodIndex, tierIndex);
    setfRadarTableHeader(periodIndex, periodfIndex, tierIndex);
 
    /* Radar Table Data Update */
	setRadarTableData("#my-radar-table", heroIndex, periodIndex, tierIndex);
	setRadarfTableData(heroIndex, periodIndex, periodfIndex, tierIndex);
	
	// column-5 결과열 업데이트 + resultComment 업데이트
	updateRadarTableResult("#my-radar-table");
	updateRadarTableResult("#friend-radar-table");
	
	/** Radar Win Rating **/
	// Win Rating Chart Update
	//	setWinRating(heroId, periodId, updateFlag); // 사용전에 必 elem id 변경할 것.trend와 겹침 (Deprecated) setWinRatingbyElem 사용할것
	// Point Bar Chart Update
	//	setPtRating(heroId, periodId, updateFlag); // 사용전에 必 elem id 변경할 것.trend와 겹침
	
	/** Detail Data **/
	//	setDetailData(heroId);
	//	createDataTable(heroId);
}


/* setting minTime and maxTime from wait Response */
function setResponseTimer(waitTime, timeout){

	/* 흐름제어변수 : ajax에서 응답을 받으면 이 흐름제어 변수를 true로 바꿉니다. */
	window.updateStatsReady = false;
	
	var counter = 0;
	var timer = setInterval(function(){
		
		/* waitTime 보다 시간이 흐르고, 응답이 오면 true로 변경합니다. */ 
		if(counter > waitTime && window.updateStatsReady == true){
			
			/* 여기에 선언된 함수들은 Stat Response를 받은뒤 디스플레이를 업데이트 하게 됩니다. */
			executeAfterResponse();
			
			/* 첫 Active로 무엇을 할지 설정합니다.(영웅 most1 자동선택, 티어자동선택) */
			setInitActiveButton();
			
			/* 버튼이 Active 되었기 때문에 After Button을 실행할 수 있습니다. */
			executeAfterButton();
			
			/* 로딩화면을 끄고, 탭컨텐츠 화면을 앞으로 가져옵니다. */
			fadeInTabContent();
			clearInterval(timer);
		}

		/* timeout보다 긴 시간이 흐르게 되면 응답이 만료되었습니다. */ 
		if(counter > timeout){
			console.log('응답시간이 만료되었습니다.');	//TODO:알람띄우고 홈으로 돌아갈것.
			clearInterval(timer);
		}
		counter++;
	}, 100);
}


/* Stat Response를 받은뒤에 실행합니다. Globla Stat Variable이 모두 초기화 되어 있어야 합니다. */ 
function executeAfterResponse(){
	
	/* 플레이어 아이콘을 설정합니다. */
	drawMyIconImage();
	
	/* 전체 요약의 Profile을 설정합니다. */
	updateProfileSection();
	
	/* 전체 요약의 Trend를 설정합니다. */
	setGameNumInTrendSection();	
	updateTrendSection();
	
	/* 전체 요약의 most3을 설정합니다. */
	updateMost3Section();
	
	/* Fixed Bottom의 경기수를 설정합니다. */
	setGameNumInFixedBtm('All');
	
	/* updateFavoriteDisplay */
	updateFavIcon();
	
	/* 탭 상태에 따라 탭컨텐츠를 설정합니다. */
	suppressTabContent();
	
}


/* 전체 요약의 Profile Section을 설정합니다. */
function updateProfileSection(){
	
	/* Initiate Variable */
	var cptPt = getStat('All', 'cptPt', 'avg'); 
	var fileName = getTierImgFileName(cptPt);
	var tierImgUrl = getImgUrl(fileName, 'png');
	var winRating = getStat('All', 'winRating', 'avg');
	
	/* 경쟁전 점수 설정 */
	$("#box-1 > div:nth-child(2)").text(cptPt);
	
	/* 티어 이미지 설정 */
	$("#box-2 > div:nth-child(2)").css("background-image", tierImgUrl);
	
	/* 승률 설정 */
	$("#box-3 > div:nth-child(2) > div:nth-child(1) > span:nth-child(1)").text(winRating[4]);	// 승률
	$("#box-3 > div:nth-child(2) > div:nth-child(2)").text(winRating[1] + "/" + winRating[3]  + "/" + winRating[2] ); // 승 패 무
	
	/* KDA 정보 및 등등 설정 하기 */
	//	$("#main-2").text(window.stats['All']['winRating']['avg'][4]);
	//	$("#main-3").text(window.stats['All']['KDA']['avg']);	//	KDA 정보가 없음 ㅠ
	//	$("#main-4").text(window.stats['All']['update']['ystday']);
	//	$("#main-5").text(window.stats['All']['update']['today']);
	//	$("#main-6").text(window.stats['All']['update']['week']);
}

/* 전체 요약의 Most3 Section을 설정합니다. */
function updateMost3Section(){
	
	/* initialize value */ 
	var most3Heroes = window.most3Heros;
	
	/* 값이 없으면 undefined */
	if(typeof most3Heroes == 'undefined'){
		return null;
	}
	
	/* Most3을 세팅합니다. */
	//$("#most1 > div:nth-child(2)").text(window.stats[most1]['update']['avg']);
	var most3Heroes = window.most3Heros;
	if(most3Heroes[0] != null) setOneHeroInMost(1, most3Heroes[0]);
	if(most3Heroes[1] != null) setOneHeroInMost(2, most3Heroes[1]);
	if(most3Heroes[2] != null) setOneHeroInMost(3, most3Heroes[2]);
}

/* most3중 1개의 영웅을 설정합니다. */
function setOneHeroInMost(order, heroId){
	
	//	var playTime = getStat(heroId, 'update', 'avg');
	var imgUrl = getHeroImgUrl(heroId);
	var heroName = getHeroIndex2Label(heroId);
	
	// 승률에 관한것.
	var winGame = getStat(heroId,'winRating','avg', 1);
	var loseGame = getStat(heroId,'winRating','avg', 3);
	var drawGame = getStat(heroId,'winRating','avg', 2);
	var winRating = getStat(heroId,'winRating','avg', 4);
	
	//평점
	var ptRating = getStat(heroId,'ptRating','avg');
	ptRating = ptRating.toFixed(1);
	
	//KDA
	var kdaDeath = getStat('All', 'zData', heroId, '전투', '목숨당 처치');
	kdaDeath = parseFloat(kdaDeath).toFixed(2);
	
	//폭주
	var burnedTime = getStat('All', 'zData', heroId, '게임', '폭주 시간');
	var playTime = getStat('All', 'zData', heroId, '게임', '치른 게임');
	if(burnedTime != null && playTime != null){
		burnedTime = calcBurnedTime(burnedTime, playTime);
	}
	
	// 영웅이미지와 이름
	$("#most" + order + " > td:nth-child(1) > div:nth-child(1)").css("background-image", imgUrl); 	// Set Hero Image
	$("#most" + order + " > td:nth-child(1)  > div:nth-child(2)").text(heroName); // set Hero Text
	
	// 승률
	$("#most" + order + " > td:nth-child(2) > div:nth-child(1)").text(winRating + "%");	// 승률
	$("#most" + order + " > td:nth-child(2) > div:nth-child(2)").text(winGame + "/" + loseGame + "/" + drawGame); // 승/무/패
	
	$("#most" + order + " > td:nth-child(3)").text(ptRating); // 평점
	$("#most" + order + " > td:nth-child(4)").text(kdaDeath); // KDA
	$("#most" + order + " > td:nth-child(5)").text(burnedTime); // 폭주시간
}



/* Trend Section에 게임 경기수 라벨을 붙입니다. */
function setGameNumInTrendSection(){
	var ystPlayTime = getStat('All', 'update', 'ystday');
	var todayPlayTime = getStat('All', 'update', 'today');
	var weekPlayTime = getStat('All', 'update', 'week');
	
	$("#trend-ystBtn > div:nth-child(2)").text(addGameSetLabel(ystPlayTime));
	$("#trend-todayBtn > div:nth-child(2)").text(addGameSetLabel(todayPlayTime));
	$("#trend-weekBtn > div:nth-child(2)").text(addGameSetLabel(weekPlayTime));
}

/* Fixed Bottom의 영웅별 어제, 오늘, 주간 경기수를 설정합니다. */
function setGameNumInFixedBtm(){
	var ystPlayTime = getStat('All', 'update', 'ystday');	
	var todayPlayTime = getStat('All', 'update', 'today');
	var weekPlayTime = getStat('All', 'update', 'week');
	var avgPlayTime = getStat('All', 'update', 'avg');
	
	$("#yesterDayBtn > div:nth-child(2)").text(addGameSetLabel(ystPlayTime));
	$("#todayBtn > div:nth-child(2)").text(addGameSetLabel(todayPlayTime));
	$("#weekBtn > div:nth-child(2)").text(addGameSetLabel(weekPlayTime));
	$("#avgBtn > div:nth-child(2)").text(addGameSetLabel(avgPlayTime));

}

function setfGameNumInFixedBtm(){
	if(typeof window.fstats != 'undefined'){
		var ystPlayTime = getfStat('All', 'update', 'ystday');
		var todayPlayTime = getfStat('All', 'update', 'today');
		var weekPlayTime = getfStat('All', 'update', 'week');
		var avgPlayTime = getfStat('All', 'update', 'avg');
		
		$("#friend-yesterDayBtn > div:nth-child(2)").text(addGameSetLabel(ystPlayTime));
		$("#friend-todayBtn > div:nth-child(2)").text(addGameSetLabel(todayPlayTime));
		$("#friend-weekBtn > div:nth-child(2)").text(addGameSetLabel(weekPlayTime));
		$("#friend-avgBtn > div:nth-child(2)").text(addGameSetLabel(avgPlayTime));
	}
}

/* 유저데이터를 바탕으로 버튼(영웅, 기간, 티어)을 Active 시킵니다. */
/* window.most3Heros가 반드시 정의 되어 있어야 합니다. */
function setInitActiveButton(){
	
	var heroBtnId = window.most3Heros[0];
	var periodBtnId = 'todayBtn';
	var periodfBtnId = 'friend-todayBtn'
	var tierBtnId = 'silverBtn';
	
	/* TODO: Dynamic select Tier index */
	//	getActiveTierIndex();

	$('#' + heroBtnId).addClass('active');
	$("#" + periodBtnId).addClass('active');
	$("#" + periodfBtnId).addClass('active');
	$("#" + tierBtnId).addClass('active');
	
}

function friendSearchButClicked(){
	
//	$("#js-search-list-button").click(function(){
//		$("#js-dropdown-search-list").toggleClass("show");
//	});
	
	/* 입력필드에서 값을 가져옵니다. */
	var searchValue = $("#js-compare-search-group input").val();
	/* Count # Number */
	var countNumSign = countIncludeStr(searchValue, "#");
	
	$("#js-compare-search-group input input").text("전적을 가져오는 중입니다.");
	
	/* #의 개수에 따라 처리하는 프로세스가 다릅니다. */
	if(countNumSign == 1){	// #이 1개일때는 정상적으로 입력했다고 생각합니다.
		/* 로딩스크린을 불러온다. */
		friendLoaderScreen(true);
		
		/* 검색한 친구 배틀네임을 셋팅 한다. */
		// TODO : GETSTATS에 배틀네임을 받으면 이부분은 삭제할것
		var n = searchValue.indexOf("#");
		window.fBattleName = searchValue.substring(0, n);
		
		var battleTag = searchValue.replace("#","-");
		ajaxFetchStats(battleTag, refreshFriendStats, "fstats", null);
		// 인풋필드 초기화
	} else if(countNumSign == 0) {	// #이 없을때는 배틀태그가 아닌 배틀네임만 입력한 경우로 봅니다.
		var battleName = searchValue;
		updateSearchListShortStats(battleName);
	} else {
		alert("배틀태그를 정확히 입력해 주세요.");	// #이 1개 초과일때는 배틀태그를 잘못 입력한 것으로 간주합니다.
		return;
	}
	
	/* TODO:인풋 필드 초기화 */
}



/** 즐겨찾기 버튼 Clicked Declaration **/
/* 별 모양 아이콘 버튼 클릭 */
function favoriteButClicked(jqElem){
	
	/* 쿠키가 없을 경우 함수를 종료한다. */
	if(!checkCookie(cFavoriteName)){
		return;
	}

	/* 쿠키를 가져와서 Array 형태로 손질한다. */
	var strFavoriteIds = getCookie(cFavoriteName);
	var objFavoriteIds;
	
	try {
		objFavoriteIds = JSON.parse(strFavoriteIds);
	} catch (e) {
		//TODO:Json Parse 에러
		console.log(e);
		return;
	}
	
	/* Add and Remove PlayerId to Array in Deactive and Active */
	var currentId = window.currentId;
	if(jqElem.hasClass('active')){	// Active 상태라면 찾아서 삭제한다.
		if(objFavoriteIds.hasOwnProperty(currentId)){
			delete objFavoriteIds[currentId];
		}
	} else { // Deactive 상태이면 해당 아이디를 추가한다.
		objFavoriteIds[currentId] = null;
	}
	
	/* JSon data를 쿠키에 저장한다. */
	setCookie(cFavoriteName, JSON.stringify(objFavoriteIds), 365);
	
	/* Update Display */
	updateFavIcon();
	
}
/* 쿠키에 해당 유저를 추가한다.*/
function addFavorite(){
	
	var fBattleId = window.fBattleId;
	
	/* 쿠키가 없을 경우 함수를 종료한다. */
	if(!checkCookie(cFavoriteName)){
		return;
	}
	/* 아직 친구 아이디가 없는 경우 */
	if(typeof fBattleId == 'undefined'){
		alert("친구를 검색한 후에 추가가 가능합니다.")
		return;
	}
	
	/* 쿠키를 가져와서 Array 형태로 손질한다. */
	var strFavoriteIds = getCookie(cFavoriteName);
	var objFavoriteIds;
	
	try {
		objFavoriteIds = JSON.parse(strFavoriteIds);
	} catch (e) {
		//TODO:Json Parse 에러
		console.log(e);
		return;
	}
	
	if(fBattleId in objFavoriteIds){
		alert("이미 등록된 사용자입니다.");
		return;
	} else {
		objFavoriteIds[fBattleId] = null;
		alert("등록되었습니다.");
	}
	
	/* JSon data를 쿠키에 저장한다. */
	setCookie(cFavoriteName, JSON.stringify(objFavoriteIds), 365);
}

/* Favorite Icon Display Update */ 
/* 현재 배틀태그가 즐겨찾기 목록에 있는지 확인해서 있으면 active클래스를 추가 하고 없으면 active를 remove 합니다. */
function updateFavIcon(){
	/* 쿠키가 없을 경우 함수를 종료한다. */
	if(!checkCookie(cFavoriteName)){
		return;
	}

	/* Initiate Variable */ 
	var element = $("#regFavorite");
	var strFavoriteIds = getCookie(cFavoriteName);
	var objFavoriteIds = JSON.parse(strFavoriteIds);
	
	if(objFavoriteIds.hasOwnProperty(currentId)){
		element.addClass("active");
		element.html("star");
	} else {
		element.removeClass("active");
		element.html("star_border");
	}
}

/* Fixed Bottom HeroImage and Hero Label Change */
function setFixedBtmTitle(heroIndex, periodIndex, friendName){
	var heroIconUrl = getHeroIconUrl(heroIndex);
	var $_target = $("#js-fixed-bottom-img");
	var topTitle = getHeroIndex2Label(heroIndex);
	var bottomTitle = getPeriodIndex2Label(periodIndex) + " 경기 결과";

	setBackgroundImg($_target, heroIconUrl);
	$("#js-fixed-bottom-title-up").text(topTitle);
	$("#js-fixed-bottom-title-bottom").text(bottomTitle);
}

/* Fixed Bottom 안에 있는 다른티어평균(Label)의 값입니다. */
function setFixedBtmInsideLabel(heroIndex){
	var heroLabel = getHeroIndex2Label(heroIndex);
	$("#tierSubTitle").text("(" + heroLabel + ")");
}



/* Set Radar Chart - 영웅상세 페이지에 있는 레이더차트 설정 */
function setRadarChartData(selectedHeroId, selectedPeriod, tierId){
	// Set Label
	window.radarCharts.data.labels = window.stats[selectedHeroId]['radarLabel'];
	// Set Average
	window.radarCharts.data.datasets[0].data = window.stats[selectedHeroId]['radarPt'][selectedPeriod];
	// Set Period	
	window.radarCharts.data.datasets[1].data = window.stats[selectedHeroId]['radarPt']['avg'];
	// TODO:set third Period
	window.radarCharts.data.datasets[2].data = window.tiers[tierId][selectedHeroId]['radarPt'];
}
/* Set fRadar Chart - 친구비교 페이지에 있는 레이더차트 설정 */
function setfRadarChartData(heroIndex, periodIndex, periodfIndex, tierIndex){
	if(typeof window.fstats != 'undefined'){
		// Set Label
		window.fRadarCharts.data.labels = window.fstats[heroIndex]['radarLabel'];
		/* window.stat 설정(내 데이터) red */
		window.fRadarCharts.data.datasets[0].data = window.stats[heroIndex]['radarPt'][periodIndex];
		/* window.fstat 설정(친구 데이터) blue */	
		window.fRadarCharts.data.datasets[1].data =  window.fstats[heroIndex]['radarPt'][periodfIndex];
		// TODO:set third Period
		window.fRadarCharts.data.datasets[2].data = window.tiers[tierIndex][heroIndex]['radarPt'];
	}
}
//
/* Set Radar Chart Label(Legend 내평균, 내주간, 내어제, 내 오늘, 실버평균, 골드 평균 등등) */ 
function setRadarChartLabel(objRadarCharts, periodIndex, tierIndex){

	/* 라벨을 가져온다. */
	var labelRed  = '내 ' + getPeriodIndex2Label(periodIndex);
	var labelBlue = "내 평균";

	/* 티어 라벨을 설정한다. */
	var labelYellow;
	var label = getTierIndex2Label(tierIndex);
	if(tierIndex == 'grandMaster'){ // 그랜드 마스터의 경우 평균이라는 라벨을 뺀다.
		labelYellow = label;
	} else {
		labelYellow = label + " 평균";
	}
	
	window.radarCharts.data.datasets[0].label = labelRed;
	window.radarCharts.data.datasets[1].label = labelBlue;
	window.radarCharts.data.datasets[2].label = labelYellow;
}
function setfRadarChartLabel(periodIndex, periodfIndex ,tierIndex){
	
	/* 라벨을 가져온다. */
	var labelRed = '내 ' + getPeriodIndex2Label(periodIndex);;
	var labelBlue = '친구 ' + getPeriodIndex2Label(periodIndex);;
	
	/* 티어 라벨을 설정한다. */
	var labelYellow;
	var label = getTierIndex2Label(tierIndex);
	if(tierIndex == 'grandMaster'){ // 그랜드 마스터의 경우 평균이라는 라벨을 뺀다.
		labelYellow = label;
	} else {
		labelYellow = label + " 평균";
	}
	
	window.fRadarCharts.data.datasets[0].label = labelRed;
	window.fRadarCharts.data.datasets[1].label = labelBlue;
	window.fRadarCharts.data.datasets[2].label = labelYellow;
}

function setRadarTableHeader(periodIndex, tierIndex){
	
	var redLabel = "내 " + getPeriodIndex2Label(periodIndex);
	var blueLabel = "내 평균";
	var yellowLabel;
	/* 내 티어 라벨 설정*/
	if(tierIndex == 'grandMaster'){ // Radar Table에 엔터 입력행을 넣기 위함
		yellowLabel = '그랜드<br>마스터';
	} else {
		yellowLabel = getTierIndex2Label(tierIndex) + ' 평균';
	}
	$("#my-radar-table thead th:nth-child(2)").html(wrapDiv(redLabel));
	$("#my-radar-table thead th:nth-child(3)").html(wrapDiv(blueLabel));
	$("#my-radar-table thead th:nth-child(4)").html(wrapDiv(yellowLabel));
}

function setfRadarTableHeader(periodIndex, periodfIndex, tierIndex){
	var labelCol2 = "내 " + getPeriodIndex2Label(periodIndex);
	var labelCol3 = "친구 " + getPeriodIndex2Label(periodfIndex);
	var labelCol4 = getTierIndex2Label(tierIndex);
	if(tierIndex == 'grandMaster'){ // Radar Table에 엔터 입력행을 넣기 위함
		labelCol4 = "그랜드<br>마스터";
	} else {
		labelCol4 = labelCol4 + "<br>평균";
	}
	
	$("#friend-radar-table thead th:nth-child(2)").html(wrapDiv(labelCol2));
	$("#friend-radar-table thead th:nth-child(3)").html(wrapDiv(labelCol3));
	$("#friend-radar-table thead th:nth-child(4)").html(wrapDiv(labelCol4));
	
}



// Set Radar Table Data
function setRadarTableData(tableId, heroIndex, periodIndex, tierIndex){
	
	if(typeof window.stats == 'undefined') return null;
	
	var jquerySelector = tableId;
	
	var redUpdateTime = getStat(heroIndex, 'update', periodIndex);
	var blueUpdateTime = getStat(heroIndex, 'update', 'avg');
	var labels = getStat(heroIndex, 'radarLabel');
	var units = getStat(heroIndex, 'radarUnit');
	var descriptions = getStat(heroIndex, 'radarDescription');
	
	/* 테이블 행을 청소합니다. Delete All child and node */
	$(jquerySelector + " tbody").empty();
	
	for(var i=0; i < labels.length; i++){
		/* 2열 - red, 3열 - blue, 4열 - yellow */
		var redPt = null; var redRaw = null;
		var bluePt = null; var blueRaw = null;
		var yellowPt = null; var yellowRaw = null;
		
		if(redUpdateTime != null && redUpdateTime > 0){
			var redPt = getStat(heroIndex, 'radarPt', periodIndex, i);
			var redRaw = getStat(heroIndex, 'radarRaw', periodIndex, i);
		} else {
			redPt = null;	redRaw = null;
		}
		
		if(blueUpdateTime != null && blueUpdateTime > 0){
			bluePt = getStat(heroIndex, 'radarPt', 'avg', i);
			blueRaw = getStat(heroIndex, 'radarRaw', 'avg', i);
		} else {
			bluePt = null;	blueRaw = null;
		}
		
		tierPt = getTierStat(tierIndex, heroIndex, 'radarPt', i);
		tierRaw = getTierStat(tierIndex, heroIndex, 'radarRaw', i);
		
		/* 소수점 자리 계산*/
		redRaw = roundVal(redRaw); blueRaw = roundVal(blueRaw); tierRaw = roundVal(tierRaw);
		
		//TODO: 여기서 부터 디스플레이 영역이니 어레이형테로 데이터를 전달받아서 따로 함수로 구성하는게 좋을것 같다.
		
		
		/* tr Element 추가 */
		$(jquerySelector + " tbody").append("<tr></tr>");
		
		/* Jquery Selector String 생성 */
		var trSelector = jquerySelector +" tbody tr" + ":nth-child(" + (i+1) + ")";
		
		/* COLUMN 1 상세정보 열 생성 및 추가 */
		$(trSelector).append("<td>" + wrapDiv(labels[i]) + wrapDiv(descriptions[i]) + "</td>");
		
		/* COLUMN 2,3,4 상세정보 열 생성 및 추가 */
		$(trSelector).append(createRadarTdElement(redPt, redRaw, units[i]));
		$(trSelector).append(createRadarTdElement(bluePt, blueRaw, units[i]));
		$(trSelector).append(createRadarTdElement(tierPt, tierRaw, units[i]));
		
		/* COLUMN 5 결과열 빈 td 추가  */
		$(trSelector).append("<td></td>");
	}
	
	/* 상세설명 dispaly-none class 추가 */
	$(jquerySelector + " td:nth-child(1) > div:nth-child(2)").addClass("display-none");
}

function setRadarfTableData(heroIndex, periodIndex, periodfIndex, tierIndex){
	
	if(typeof window.stats == 'undefined') return null;
	if(typeof window.fstats == 'undefined') return null;
	
	/* Initiate Variable */
	var updateTime = getStat(heroIndex, 'update', periodIndex);
	var fupdateTime = getfStat(heroIndex, 'update', periodfIndex);
	var labels = getStat(heroIndex, 'radarLabel');
	var units = getStat(heroIndex, 'radarUnit');
	var descriptions = getStat(heroIndex, 'radarDescription');
	
	/* 테이블 행을 청소합니다. Delete All child and node */
	$("#friend-radar-table tbody").empty();
	
	/* 선택된 영웅을 기점으로 라벨 개수만큼 테이블 행을 생성한다. */
	for(var i=0; i < labels.length; i++){
		/* prePt/postPt로 결과값을 생성한다. */
		var prePt; var preRaw;
		var postPt; var postRaw;
		var tierPt; var tierRaw;
		var result_col;	//결과항목
		
		/* 내 데이터의 Update가 있으면 값을 읽어 오고 없으면 null을 대입한다. */
		if(updateTime != null && updateTime > 0){
			prePt = getStat(heroIndex, 'radarPt', periodIndex, i);
			preRaw = getStat(heroIndex, 'radarRaw', periodIndex, i);
		} else {
			prePt = null;	preRaw = null;
		}
		
		/* 친구 데이터의 Update가 있으면 값을 읽어 오고 없으면 null을 대입한다. */
		if(fupdateTime != null && fupdateTime > 0){
			postPt = getfStat(heroIndex, 'radarPt', periodfIndex, i);
			postRaw = getfStat(heroIndex, 'radarRaw', periodfIndex, i);
		} else {
			postPt = null;	postRaw = null;
		}
		
		/* 티어 데이터 계산 */
		tierPt = getTierStat(tierIndex, heroIndex, 'radarPt', i);
		tierRaw = getTierStat(tierIndex, heroIndex, 'radarRaw', i);

		/* 소수점 자리 환산 */
		preRaw = roundVal(preRaw); postRaw = roundVal(postRaw); tierRaw = roundVal(tierRaw);
		
		//TODO: 여기서 부터 디스플레이 영역이니 어레이형테로 데이터를 전달받아서 따로 함수로 구성하는게 좋을것 같다.
		/* tr Element 추가 */
		$("#friend-radar-table tbody").append("<tr></tr>");
		
		/* Jquery Selector String 생성 */
		var trSelector = "#friend-radar-table tbody tr" + ":nth-child(" + (i+1) + ")";
		
		/* COLUMN 1 상세정보 열 생성 및 추가 */
		$(trSelector).append("<td>" + wrapDiv(labels[i]) + wrapDiv(descriptions[i]) + "</td>");
		
		/* COLUMN 2,3,4 상세정보 열 생성 및 추가 */
		$(trSelector).append(createRadarTdElement(prePt, preRaw, units[i]));
		$(trSelector).append(createRadarTdElement(postPt, postRaw, units[i]));
		$(trSelector).append(createRadarTdElement(tierPt, tierRaw, units[i]));
		
		/* COLUMN 5 결과열 빈 td 추가  */
		$(trSelector).append("<td></td>");
	}
	
	$("#friend-radar-table tbody td:nth-child(1) > div:nth-child(2)").addClass("display-none");
	/* Debug Text */
	//	console.log("prePt : " + prePt + ", postPt : " + postPt + ", tierPt : " + tierPt);
	//	console.log("preRaw : " + preRaw + ", postRaw : " + postRaw + ", tierRaw : " + tierRaw);
}

function createRadarTdElement(pt, raw, unit){
	if(pt == null || raw == null){
		return "<td><div class='noGame'>No<br>Game</div></td>"
	} else {
		return "<td>" + wrapDiv(wrapSpan(pt) + wrapSpan("점")) + wrapDiv(wrapSpan(raw) + wrapSpan(unit)) + "</td>";
	}
}

function updateTierNum(heroId){
	$("#bronzeBtn > div:nth-child(2)").text(getTierStat('bronze', heroId, 'playerNum', 0) + '명');
	$("#silverBtn > div:nth-child(2)").text(getTierStat('silver', heroId, 'playerNum', 0) + '명');
	$("#goldBtn > div:nth-child(2)").text(getTierStat('gold', heroId, 'playerNum', 0) + '명');
	$("#platinumBtn > div:nth-child(2)").text(getTierStat('platinum', heroId, 'playerNum', 0) + '명');
	$("#diaBtn > div:nth-child(2)").text(getTierStat('dia', heroId, 'playerNum', 0)+ '명');
	$("#masterBtn > div:nth-child(2)").text(getTierStat('master', heroId, 'playerNum', 0)+ '명');
	$("#grandMasterBtn > div:nth-child(2)").text(getTierStat('grandMaster', heroId, 'playerNum', 0)+ '명');
}


//case1(내평균 - 내 case2
function shiftResultCase(reference){
	var index = window[reference];
	if(index == 2){
		index = 0;
	} else {
		index += 1;
	}
	window[reference] = index;
}

//주의 : 반드시 radar table이 완전히 갱신된 뒤에 선언할것. 그래야만 현재 그려진 것에서 업데이트가 됨. .html 함수 뒤에 사용할것.
function updateRadarTableResult(tableId){
	
	/* Table Id에 따라서 현재 index를 가져옵니다. */
	/* 어떤 열이 기준열이되고 비교열이 될지 판단한다  */
	var indexes = getColIndexes(tableId);
	var stdIndex = indexes[0];
	var compIndex = indexes[1];
	
	/* 현재 테이블의 행 개수를 가져온다. */
	var tableLength = $(tableId + " tbody tr").length;
	
	/* 결과열 계산에 쓰일 sum 변수 초기화 */
	var stdSum = 0; var compSum =0;
	
	for(var row = 1; row <= tableLength; row++){
		var $_targetRow = $(tableId + " tbody tr:nth-child(" + row + ")");
		
		/* 테이블 내의 값을 직접 가져온다. columnIndex의 행의 preIndex열과 postIndex의 열의 Pt값을 가져온다. */
		/* 해당 테이블에 값이 존재하지 않으면 '' 빈문자열을 반환한다. */
		var $_targetTd = $_targetRow.find("td:nth-child(5)");
		var stdPt = $_targetRow.find("td:nth-child(" + stdIndex + ") div:nth-child(1) > span:nth-child(1)").text();
		var compPt = $_targetRow.find("td:nth-child(" + compIndex + ") div:nth-child(1) > span:nth-child(1)").text();
		
		/* Parse Int(빈문자열도 NaN으로 반환한다) */
		stdPt = parseInt(stdPt); compPt = parseInt(compPt);
		
		var diffValue = null;
		/* 둘중에 하나라도 값이 없으면 값을 -로 세팅하고 진행한다. */
		if(isNaN(stdPt) || isNaN(compPt) || stdPt == 0){
			// Nothing to do
		} else {
			stdPt = parseFloat(stdPt);
			compPt = parseFloat(compPt);
			diffValue = (((compPt - stdPt)*100)/compPt).toFixed(0);
			stdSum += stdPt; compSum+= compPt;
		}
		drawRadarTableResultTd($_targetTd, stdPt, compPt, diffValue);
	}
	/* 헤더를 디스플레이 합니다.*/
	drawRadarTableHeaderIndex(tableId, stdIndex, compIndex);
	
	/* 토탈 결과에 대한 값을 구합니다. */
	diffSum = (((compSum - stdSum)*100)/compSum).toFixed(0);
	drawRadarTableResultSummary(tableId, stdIndex, compIndex, diffSum);
}


//99이하 소수점 2자리, 999이하 소수점 1자리, 이상은 소수점 x
function roundVal(value){
	
	if(value == null){
		return null;
	}
	
	var value = parseFloat(value);
	if(value < 10){
		value = (Math.round(value*100)/100).toFixed(2);;	// 소수점 두자리까지
	} else if(10 <= value && value <= 999){
		value = (Math.round(value*10)/10).toFixed(1);	// 소수점 한자리까지
	} else {
		value = Math.round(value);	
	}
	return value;
}


// '한줄 요약 설정'
function getTableResultAvg(labels, selectedHeroId, periodId){
	var preSum = 0;	var preNum = 0;	var postSum = 0; var postNum = 0;
	for(var i=0; i < labels.length; i++){
		prePt = getStat(selectedHeroId, 'radarPt', 'avg', i);
		postPt = getStat(selectedHeroId, 'radarPt', periodId, i);
		
		if(prePt != null){
			preSum += prePt;
			preNum += 1;
		}
		
		if(postPt != null){
			postSum += prePt;
			postNum += 1;
		}
	}
	
	return [preSum/preNum, postSum/postNum];
}



// 마지막행 '한줄 요약' 설정
function createTableResult(resultAvgArr){
	var preAvg = Math.round(resultAvgArr[0]);
	var postAvg = Math.round(resultAvgArr[1]);
	
	var diffAvg = postAvg - preAvg;
	if(diffAvg > 0){
		return '<td colspan="5"> 평소보다 ' + diffAvg + '점 향상된 플레이를 했습니다.</th>';
	} else if (diffAvg < 0){
		return '<td colspan="5"> 평소보다 ' + diffAvg + '점 하락한 플레이를 했습니다.</th>';
	} else {
		return '<td colspan="5"> 평소처럼 플레이를 했습니다.</th>';
	}
}



function addPtLabel(pt){
	if(pt != '-'){
		return "<span>" + pt + "</span><span>점</span>";
	} else {
		return pt;
	}
}


//z-data 출력
function createDataTable(selectedHeroId){
	$(".tableWrapper").text("");
	
	if(!checkUpdate(hero, 'avg')){
		var detailDatas = window.stats['All']['zData'][selectedHeroId];
		for(var ctgKey in detailDatas){
			if(ctgKey != 'etcData'){
				//Create Table
				$(".tableWrapper").append(
							//Table Head
							'<table class="table table-condensed"><thead>' 
							+ '<tr class="accordion-toggle" data-toggle="collapse" data-target="#' + ctgKey + '">'
							+ '<th>' + ctgKey + '</th>'
							+ '<th><i class="indicator glyphicon glyphicon-chevron-up pull-right"></i></th>'
							+ '<\/thead>'
							//Table Body
							+ '<tbody><tr><td colspan="2">' 
							+ '<div class="collapse" id="' + ctgKey +'">'
							+ '<table class="table table-striped table-hover">'
							+ '</table></div></td></tr></tbody></table>');
				//Set Header
				var datas = detailDatas[ctgKey];
				for(var key in datas){
					$("#" + ctgKey + " table").append("<tr><td>" + key + "<\/td\><td>" + datas[key] + "<\/td></\tr>");
				}
			}
		}
	}
}






function setPtRatingStarNull(elementId){
	$("#" + elementId).rateYo("rating", 0);
}


function setPtRatingNumNull(elementId, hero, period){
	$("#" + elementId).text( '--' );
}

// Deprecated
//function setWinRating(hero, period, updateFlag){
//	if(updateFlag){
//		// Set Average Data
//		$('#avgWinRating > span').remove();
//		if(!checkUpdate(hero, 'avg')){
//			// Set Bar Average Data to zero
//			setWinRatingBarNull('avgWinRating');
//			// Set Number Average Data to zero
//			setWinRatingNumNull('avgWinRatingNum');
//			
//		} else {
//			// Set Bar Average Data
//			setWinRatingBar('avgWinRating', hero, 'avg');
//			// Set Number Average Data
//			setWinRatingNum('avgWinRatingNum', hero, 'avg');
//		}
//	}
//	
//	// Set Period Data 
//	$('#prdWinRating > span').remove();
//	if(!checkUpdate(hero, period)){
//		// Set Bar Period Data to zero
//		setWinRatingBarNull('prdWinRating');
//		setWinRatingNumNull('prdWinRatingNum');
//	} else {
//		// Set Bar Period Data
//		setWinRatingBar('prdWinRating', hero, period);
//		setWinRatingNum('prdWinRatingNum', hero, period);
//	}
//}

//승률 Bar Chart
function setWinRatingBar(elementId, hero, period){
	var totalGame = window.stats[hero]['winRating'][period][0];
	var winGame = window.stats[hero]['winRating'][period][1];
	var drawGame = window.stats[hero]['winRating'][period][2];
	var loseGame = window.stats[hero]['winRating'][period][3];
	var winRate = window.stats[hero]['winRating'][period][4];
	
	$("#" + elementId + " > #success").css("width", winRate+"%").text(winGame +"승");
	$("#" + elementId + " > #danger").css("width", (100 - winRate)+"%").text(loseGame + "패");
}

function setWinRatingBarNull(elementId){
	$("#" + elementId).append( '<span>No Game</span>' );
	$("#" + elementId + " > #success").css("width","0%").text("경기");
	$("#" + elementId + " > #danger").css("width","0%").text("경기");
}

// 승률 text
function setWinRatingNum(elementId, hero, period){
	// animate
	var target = window.stats[hero]['winRating'][period][4];
//	$("#" + elementId + "> div > #value").text( target);
//	console.log("elementId : [" + elementId + "]" + ", hero : [" + hero + "]" + ", period : [" + period + "]" + ", target : [" + target + "]");
	$("#" + elementId).animateNumber({ number: target,}, 500);
}

function setWinRatingNumNull(elementId){
	$("#" + elementId).text( '--' );
}

//After Http Loading

/** Title Data **/
//Json 값이 null 일때 이걸 사용한다.
//'검색된 사용자가 없습니다' 등등 Display


/** Stat Explorer Function **/
/* 배열에서 찾고자 하는 key가 정의되지 않았을 경우 점검하여 null을 return 하는 역할을 하는 함수입니다. */
/* ex) getStat('All', 'cptPt', 'avg'); */
function getStat(){
	/* note : 자바스크립트는 오버로딩을 지원하지 않으므로 arguments를 배열로 이용하여 사용합니다. */
	var args = arguments;
	var array = window.stats;
	
	for(var index in args){
		var key = args[index];
		if(typeof array[key] == undefined){
			console.log("args : " + args + ", 에서 '" + key +"'값이 undefined입니다.");
			return null;
		}
		array = array[key];
	}
	return array;
}

function getfStat(){
	var args = arguments;
	var array = window.fstats;
	
	for(var index in args){
		var key = args[index];
		if(array[key] == undefined){
			console.log("args : " + args + ", 에서 '" + key +"'값이 undefined입니다.");
			return null;
		}
		array = array[key];
	}
	return array;
}

function getTierStat(){
	var args = arguments;
	var array = window.tiers;
	
	for(var index in args){
		var key = args[index];
		if(array[key] == undefined){
			console.log("get tier 메쏘드에서 알립니다. args : " + args + ", 에서 '" + key +"'값이 undefined입니다.");
			return null;
		}
		array = array[key];
	}
	return array;
}

//Set Update Tag on HeroContainer
function setUpdateBadge(periodIndex, periodfIndex){
	
	for(hero in window.stats){
		
		/* Red Label Update */
		var updateNum = getStat(hero, 'update', periodIndex);
		updateNum = parseInt(updateNum);
		if(updateNum >= 1){
			$("#" + hero + " > span:nth-child(1)").text("+" + updateNum);
		}
		else $("#" + hero + " > span:nth-child(1)").text("");
		
		//TODO: 이코드가 없으면 버튼을 누르면 친구 데이터를 불러와버린다. 
		// (코드수정 필요) 똑같은 코드가 세번이나 쓰여있다. setUpdateBadgeWithPane
		if($("#js-favorite-tab-button").hasClass("active")){
			/* Blue Label Update */
			if(typeof window.fstats != 'undefined'){
				var updatefNum = getfStat(hero, 'update', periodfIndex);
				updatefNum = parseInt(updatefNum);
				if(updatefNum >= 1){
					$("#" + hero + " > span:nth-child(2)").text("+" + updatefNum);
				}
				else $("#" + hero + " > span:nth-child(2)").text("");
			}
		} else {	// $("#js-hero-tab-button").hasClass("acitve") 최초 로딩때 안불러와지는 현상이 있어서 else로 뺌.
			var updatefNum = getStat(hero, 'update', 'avg');
			updatefNum = parseInt(updatefNum);
			if(updatefNum >= 1){
				$("#" + hero + " > span:nth-child(2)").text("+" + updatefNum);
			}
			else $("#" + hero + " > span:nth-child(2)").text("");
		}
	}
}

//dy
/* Pane 이동할때 UPDATE를 갱신해줘야 하는 것때문에 만들어져야할 함수임 set updateBadge와 합쳐서 함수를 구성해야함 */
/* 클릭할때 선언되는 함수로 따로 구성해줘야함 getActivePane을 쓰지 못하는 이유는 부트스트랩에서 active 추가가 느림 */
function setUpdateBadgeWithPane(periodIndex, periodfIndex, paneId){
	for(hero in window.stats){
		
		/* Red Label Update */
		var updateNum = getStat(hero, 'update', periodIndex);
		updateNum = parseInt(updateNum);
		if(updateNum >= 1){
			$("#" + hero + " > span:nth-child(1)").text("+" + updateNum);
		}
		else $("#" + hero + " > span:nth-child(1)").text("");
		
		/* Blue Label Update */
		if(paneId == 'js-hero-tab-button'){			// 영웅 상세 탭을 클릭하면
			var updatefNum = getStat(hero, 'update', 'avg');
			updatefNum = parseInt(updatefNum);
			if(updatefNum >= 1){
				$("#" + hero + " > span:nth-child(2)").text("+" + updatefNum);
			}
			else $("#" + hero + " > span:nth-child(2)").text("");
		} else if(paneId == 'js-favorite-tab-button'){	// 즐겨찾기 탭을 클릭하면
			if(typeof window.fstats != 'undefined'){
				var updatefNum = getfStat(hero, 'update', periodfIndex);
				updatefNum = parseInt(updatefNum);
				if(updatefNum >= 1){
					$("#" + hero + " > span:nth-child(2)").text("+" + updatefNum);
				}
				else $("#" + hero + " > span:nth-child(2)").text("");
			} else {
				$("#" + hero + " > span:nth-child(2)").text("");
			}
		}
	}
}


/**
 * 
 *  Utility 
 *
 **/

function calcBurnedTime(burnedTime, playTime){
	var times = burnedTime.split(":");
	var totalBurnedTime_s=0;
	if(times.length == 1){
		totalBurnedTime_s = parseInt(times[0]);
	} else if (times.length == 2) {
		totalBurnedTime_s = parseInt(times[0])*60 + parseInt(times[1]);
	} else if(times.length == 3) {
		totalBurnedTime_s = parseInt(times[0])*3600 + parseInt(times[1])*60 + parseInt(times[2]);
	}
	var avgBurnedTime_s = totalBurnedTime_s/playTime;
	var burnedTime_m = avgBurnedTime_s/60;
	var burnedTime_s = avgBurnedTime_s%60;
	
	burnedTime_m = parseInt(burnedTime_m);
	burnedTime_s = parseInt(burnedTime_s);
	
	if(burnedTime_m > 0){
		return burnedTime_m + "분" + burnedTime_s + "초";
	} else if (burnedTime_m == 0){
		return burnedTime_s + "초";
	}
	
}

//get param from Get Method
function getQueryParam(param) {
	   
    var result = window.location.search.match(
        new RegExp("(\\?|&)" + param + "(\\[\\])?=([^&]*)")
    );
         
    return result ? result[3] : false;
}

function checkUpdate(hero, prdIndex){
	if(getStat(hero, 'update', prdIndex) > 0){
		return true;
	} else {
		return false;
	}
}

/** Get Active Index from Hero Button, Period Button, Tier Button**/
/* Get Active Period Index */ 
function getActivePeriodIndex(jqueryElem){
	var selectedPeriod;
	jqueryElem.siblings().each(function(){
		if($(this).hasClass('active')){
			var periodId = $(this).attr('id');
			/* Button에 따른 Index 형성 */
			if(periodId.includes('weekBtn')) selectedPeriod = 'week';
			else if(periodId.includes('todayBtn'))	selectedPeriod = 'today';
			else if(periodId.includes('yesterDayBtn'))	selectedPeriod = 'ystday';
			else if(periodId.includes('avgBtn'))	selectedPeriod = 'avg';
			return;
		}
	});
	
	/* Active값이 없으면 undefined에 기본값을 대인해서 리턴합니다. */
	if(typeof selectedPeriod == 'undefined'){
		selectedPeriod = 'today';	
		console.log("getActivePeriodIndex()에 Active가 정의되지 않았습니다. ")
	}
	return selectedPeriod;
}

/* Get Active Period Index */ 
function getActiveHeroIndex(){
	var selectedHeroId;
	$(".heroList div.heroContainer").siblings().not(".heroList div.roleContainer").each(function(){
		if($(this).hasClass('active')){
			selectedHeroId = $(this).attr('id');
			return false;
		}
	});
	
	/* Active값이 없으면 undefined에 기본값을 대인해서 리턴합니다. */
	if(typeof selectedHeroId == 'undefined'){
		selectedHeroId = 'Mercy';	
		console.log("getActiveHeroIndex()에 Active가 정의되지 않았습니다. ")
	}
	
	return selectedHeroId;
}

/* Get Active Tier Index */ 
function getActiveTierIndex(){
	var selectedTier;
	
	$(".tierSelectBtn").siblings().each(function(){
		if($(this).hasClass('active')){
			var tierId = $(this).attr('id');
//			// Button에 따른 Index 형성
			if(tierId == 'bronzeBtn'){
				selectedTier = 'bronze';
			} else if(tierId == 'silverBtn'){
				selectedTier = 'silver';
			} else if(tierId == 'goldBtn'){
				selectedTier = 'gold';
			} else if(tierId == 'platinumBtn'){
				selectedTier = 'platinum';
			} else if(tierId == 'diaBtn'){
				selectedTier = 'dia';
			} else if(tierId == 'masterBtn'){
				selectedTier = 'master';
			} else if(tierId == 'grandMasterBtn'){
				selectedTier = 'grandMaster';
			} 
		}
	});
	
	/* Active값이 없으면 undefined에 기본값을 대인해서 리턴합니다. */
	if(typeof selectedTier == 'undefined'){
		selectedTier = 'grandMaster';	
		console.log(" getActiveTierIndex()에 Active가 정의되지 않았습니다. ")
	}
	
	return selectedTier;
}

function makeUrlQuery(urlKey, urlValue){
	return "&" + urlKey + "=" + urlValue;
};

// updateCompetitivePoint()
function updateCptPoint(){
	var cptPoint = window.stats['All']['cptPt']['avg'];
	$("#cptPointNum").text(cptPoint);
}

/* Most3영웅을 계산합니다. */
//TODO: 조금 개선이 필요한듯. 알고리즘이 너무 늘어뜨려서 작성했음. 간결하고 쉽게 다시 작성할 것.
function getMost3Hero(stats){
	
	var most1;
	var most2;
	var most3;
	
	for(var hero in stats){
		if(hero != 'All'){
			var heroPlayTime = stats[hero]['update']['avg'];
			heroPlayTime = parseInt(heroPlayTime);
			
			if(heroPlayTime != 0){
				if(most1 == null){
					most1 = hero;
				} else if(most1 != null) {
					var most1PlayTime = stats[most1]['update']['avg'];
					most1PlayTime = parseInt(most1PlayTime);
					if(most1PlayTime < heroPlayTime){
						most3 = most2;
						most2 = most1;
						most1 = hero;
					} else {
						if(most2 == null){
							most2 = hero;
						} else if(most2 != null){
							var most2PlayTime = stats[most2]['update']['avg'];
							most2PlayTime = parseInt(most2PlayTime);
							if(most2PlayTime < heroPlayTime){
								most3 = most2;
								most2 = hero;
							} else {
								if(most3 == null){
									most3 = hero;
								} else if(most3 != null){
									var most3PlayTime = stats[most3]['update']['avg'];
									most3PlayTime = parseInt(most3PlayTime);
									if(most3PlayTime < heroPlayTime){
//										console.log("most3PlayTime " + most3PlayTime +", heroPlayTime " + heroPlayTime);
										most3 = hero;
									}
								}
							}
						}
					}
				}
			}
		}
	}
	var mosts = [most1, most2, most3]
		
	return mosts;
}

/** Utility for Image Handling **/
//이미지 file name을 정의합니다.
function getTierImgFileName(cptPoint){
	var grade = "";
	
	if(1<= cptPoint && cptPoint <= 1499){
		grade = "bronze";
	} else if(1500 <= cptPoint && cptPoint <= 1999){
		grade = "silver";
	} else if(2000 <= cptPoint && cptPoint <= 2499){
		grade = "gold";
	} else if(2500 <= cptPoint && cptPoint <= 2999){
		grade = "platinum";
	} else if(3000 <= cptPoint && cptPoint <= 3499){
		grade = "dia";
	} else if(3500 <= cptPoint && cptPoint <= 3999){
		grade = "master";
	} else if(4000 <= cptPoint && cptPoint <= 5000){
		grade = "grandmaster";
	}
	grade += "-badge";
	
	return grade;
}

function getHeroImgUrl(heroId){
	fileName = "icon-" + heroId.toLowerCase();
	return getImgUrl(fileName, 'png');
}

function getImgUrl(fileName, imgType){
	return 'url("../img/' + fileName + "." + imgType + '")';
}

/** Dispaly Config **/
function initStarRating(){
	 $(".fiveStar").rateYo({
		    starWidth: "25px",
		    readOnly: true,
	  });
}



/** Label Update Function **/



/** Utility **/
/* Add Label from play gameset number'숫자 + 경기', '0이면 No game' */
function addGameSetLabel(gameSetNum){
	if(gameSetNum == null || gameSetNum == 0){
		return 'No game';
	} else {
		return gameSetNum + " 경기";
	}
}

/** 여기서부터는 Trend에 관련된 함수들입니다. **/
function getSelTrendBtnId(){
	var selTrendBtnId;
	
	$('.trendBtn').siblings().each(function(){
		if($(this).hasClass('active')){
			selTrendBtnId = $(this).attr('id');
			return false;
		}
	});
	
	return selTrendBtnId;
}

function getIndex(btnId){
	var preIndex =  'avg';
	var postIndex =  'avg';

	if(btnId == 'trend-ystBtn'){
		preIndex = 'ystday';
		postIndex = 'today';
	} else if (btnId == 'trend-todayBtn'){
		preIndex = 'today';
		postIndex = 'avg';
	} else if (btnId == 'trend-weekBtn'){
		preIndex = 'week';
		postIndex = 'avg';
	}
	
	return [preIndex, postIndex]; 
}

function setTrendLabel(btnId){
	var preLabel='평균';
	var postLabel='--';
	
	if(btnId == 'trend-ystBtn'){
		postLabel = '어제';
	} else if (btnId == 'trend-todayBtn'){
		postLabel = '오늘';
	} else {
		postLabel = '주간';
	}
	
	$(".trend-preLabel").map(function(){
		$(this).text(preLabel);
	});
	
	$(".trend-postLabel").map(function(){
		$(this).text(postLabel);
	});
}

function setTrendCptLabel(btnId){
	var preLabel='--';
	var postLabel='--';
	
	if(btnId == 'trend-ystBtn'){
		preLabel = '어제시작';
		postLabel = '어제마무리';
	} else if (btnId == 'trend-todayBtn'){
		preLabel = '오늘시작';
		postLabel = '현재';
	} else {
		preLabel = '1주일전';
		postLabel = '현재';
	}
	
	$("#trend-preCptLabel").text(preLabel);
	$("#trend-postCptLabel").text(postLabel);
}

//Competitive Rating Update
function setTrendCpt(preIndex, postIndex){
	
	// Check Update가 NULL이면 게임을 하지 않았으므로 NOGAME을 반환한다.
//	if(!checkUpdate('All', preIndex)){
//		$("#trend-cptPtPrefix").css("color", "White").text("-");
//		$("#trend-diffCptPt").text("No");
//		$("#trend-preCptPt").text( '---');
//		$("#trend-postCptPt").text('---');
//		return;
//	}
	
	var preValue = getStat('All', 'cptPt', preIndex);
	var postValue = getStat('All', 'cptPt', postIndex);
	
	
	//▲▼ pre,postvalue중 값이 하나라도 null이면 계산이 불가능하다.
	if(preValue == null || postValue == null){
		if(preValue == null){
			preValue = '----';
		}
		if(postValue == null){
			postValue = '----';
		}
		$("#trend-cptPtPrefix").css("color", "white ").text("-");
		$("#trend-diffCptPt").text('---');
		$("#trend-preCptPt").text(preValue);
		$("#trend-postCptPt").text(postValue);
	} else {
		var diffValue = postValue - preValue;
		setTrendPrefix("trend-cptPtPrefix", diffValue);
		diffValue = Math.abs(diffValue)
		
		$("#trend-diffCptPt").text(diffValue);
		$("#trend-preCptPt").text(preValue);
		$("#trend-postCptPt").text(postValue);
	}
};
/* Trend Section 안에 있는 Most3 평균 정의 */
function setTrendPtRating(preIndex, postIndex){
	setPtRatingByElem('trend-prePtRatingStar', 'trend-prePtRatingNum', 'All', preIndex, window.most3Heros);
	setPtRatingByElem('trend-postPtRatingStar', 'trend-postPtRatingNum', 'All', postIndex, window.most3Heros) ;
};

function setPtRatingByElem(elemRatingId, elemNumId, heroId, periodId, most3Heroes){
	if(!checkUpdate(heroId, periodId)){
		setPtRatingStarNull(elemRatingId);
		setPtRatingNumNull(elemNumId);
	} else {
		var value = getMost3AvgPt(most3Heroes, periodId);
		if (value == 0){
			setPtRatingStarNull(elemRatingId);
			setPtRatingNumNull(elemNumId);
		} else {
			setPtRatingStar(elemRatingId, heroId, periodId, most3Heroes, value);
			setPtRatingNum(elemNumId, heroId, periodId, most3Heroes, value);
		}
	}
}

function getMost3AvgPt(most3Heroes, periodId){
	var num =0;
	var sum = 0;
	for(var i=0; i<3; i++){
		var heroID = most3Heroes[i];
		if(heroID != null){
			if(checkUpdate(heroID, periodId)){
				num += 1;
				sum += window.stats[heroID]['ptRating'][periodId];
			}
		}
	}
	var value = 0; 
	if(num != 0){
		value = sum/num;
	} 
	value = (Math.round(value*100)/100).toFixed(2);;	// 소수점 두자리까지
	return value;
}

function setPtRatingStar(elementId, hero, period, most3Heroes, value){
	$("#" + elementId).rateYo("rating", value);
}

function setPtRatingNum(elementId, hero, period, most3Heroes, value){
	var rating = value*10;
	var decimal_places = 1;
	var decimal_factor = decimal_places === 0 ? 1 : decimal_places * 10;
	
	$("#" + elementId).animateNumber({
	 number: rating,
	 
	 numberStep: function(now, tween) {
	      var floored_number = Math.floor(now) / decimal_factor, target = $(tween.elem);
	      if (decimal_places > 0) {
	        floored_number = floored_number.toFixed(1);
	      }

	      target.text(floored_number);
	}}, 500);
}

// 일정한 규칙을 이용해서 간단히 만들 수 있을 것같은데.
function setTrendWinRating(preIndex, postIndex){
	setWinRatingByElem('trend-preWinRating', 'trend-preWinRatingNum', 'All', preIndex);
	setWinRatingByElem('trend-postWinRating', 'trend-postWinRatingNum', 'All', postIndex);
};

// RatingBar ID, 숫자ID, 영웅이름, 기간 ID 순으로 변수를 입력.
function setWinRatingByElem(elemRatingId, elemNumId, heroId, periodId){
	$('#' + elemRatingId + ' > span').remove();
	
	if(!checkUpdate(heroId, periodId)){
		setWinRatingBarNull(elemRatingId); // Set Bar Average Data to zero
		setWinRatingNumNull(elemNumId); // Set Number Average Data to zero
	}  else {
		setWinRatingBar(elemRatingId, heroId, periodId);
		setWinRatingNum(elemNumId, heroId, periodId);
	}
}

function setTrendBurnedTime(preIndex, postIndex){
	
};
function setTrendKDA(preIndex, postIndex){
	
};



function setTrendPrefix(elementId, diffValue){
	if(diffValue==0){
		$("#" + elementId).css("color", "Chartreuse ").text("→");
	} else if(diffValue > 0) {
		$("#" + elementId).css("color", "red").text("▲");
	} else if(diffValue < 0){
		$("#" + elementId).css("color", "#668cff").text("▼");
	}
}

/* 즐겨찾기 ShortStats 데이터를 Ajax로 요청합니다. */
function updateFavoriteListShortStats(){
	/* 저장된 쿠키를 가져와서 ajax로 short stats을 요청합니다. */
	var strFavoriteIds = getCookie(window.cFavoriteName);
	var objFavoriteIds = JSON.parse(strFavoriteIds);
	
	$_target = $("#small-favorite-list");
	ajaxFetchShortStats(objFavoriteIds, 			/* Ids */
							updateShortStatsList,	/* Callback */
							$_target, 				/* target Elem */
							true,					/* true = key search*/
							true);					/* is Init Execute 최초 실행이면 alert를 실행하지 않습니다. */	
}

function updateSearchListShortStats(battleName){
	var objIds = {};
	objIds[battleName] = null;
	$_target = $("#friend-search-list");
	ajaxFetchShortStats(	objIds, 				/* Ids */
							updateShortStatsList,	/* Callback */
							$_target, 				/* target Elem */
							false,					/* false = battle Name search*/
							false);					/* is Init Execute 최초 실행이면 alert를 실행하지 않습니다. */	
	
}

/* Call back function, It excutes after response */
function updateShortStatsList(ajaxResponse, $_target, isInit){
	/* 서버로 부터 응답받은 ajaxResponse 입니다. */
	var shortStats = ajaxResponse;
	
	/* 해당 엘리먼트 Id의 디스플레이를 업데이트 합니다. */
	/* 친구비교탭에 있는 즐쳐찾기 항목을 업데이트 합니다. */
	updateShortStatsListDisplay($_target, shortStats, isInit);
	
	/* 리스트에 추가하는 이벤트 리스너를 독립적으로 추가하기 위해 index.js와 이 함수를 분리합니다. */
	addListener2ShortStatsList($_target);
	
	/* 업데이트가 완료되었으면 디스플레이 한다. */
	$("#js-dropdown-search-list").addClass("show");
}

function addListener2ShortStatsList($_target){
	
	$_target.find("li").click(function(){
		
		var battleId = $(this).attr("id");
		
		/* TODO:전역변수 말고 param으로 넘길것 */
		window.fTempBattleId = battleId;
		
		/* 검색한 친구 배틀네임을 셋팅 한다. */
		// TODO : GETSTATS에 배틀네임을 받으면 이부분은 삭제할것
		window.fBattleName = $(this).find("div:nth-child(3)").text();
		
		/* 로딩스크린을 불러온다. */
		friendLoaderScreen(true);
		
		/* ajax를 요청합니다. */
		ajaxFetchStats(battleId, refreshFriendStats, "fstats", null);
	});
}

/* ajaxFetchStats의 call back 함수 입니다. */
function refreshFriendStats(reference, responseText){
	
	/* responseText가 정상인지 확인합니다. */
	/* 오버워치에 등록된 사용자가 아니라면 */
	if(responseText == ""){	// ""(공백 문자열)이 오게 된다.
		friendLoaderScreen(false);
		alert("검색된 사용자가 없습니다.");
		return;
	}
	
	var responseObj = JSON.parse(responseText);
	
	if(typeof responseObj['dataResult'] == 'undefined'){
		alert("죄송합니다. 사용자 데이터가 존재하지 않습니다. ");
		friendLoaderScreen(false);
		return;
	}
	
	/* 받아온 데이터를 set data 합니다 */
	window[reference] = responseObj['dataResult'];
	
	/* 디스플레이를 업데이트 합니다. */
	updateFriendDataDisplay();
}

function updateFriendDataDisplay(){
	/* 친구 배틀네임 가져와서 설정하기 */
	printFriendBattleName();
	
	/* TODO:전역변수 말고 param으로 넘길것 */
	window.fBattleId = window.fTempBattleId;
	
	/* 응답을 받은뒤에 친구의 게임수를 실행시켜 준다. */
	setfGameNumInFixedBtm();
	
	/* 버튼 액션 클릭해서 화면을 업데이트 하기 */
	executeAfterButton();
	
	/* 로딩화면을 off 합니다. */
	friendLoaderScreen(false);
}












