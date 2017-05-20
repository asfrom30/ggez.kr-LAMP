
$(document).ready(function(){
	
	/** Display Initialize **/
	/* Device and Location name update */
	updateDeviceName();
	updateLocationName();
	
	/** Init Button Map **/
	initButtonMap();
	
	/* Location Button Clicked */
	$(".location-list li").click(function(){

		$("#search-dropdown").addClass("open");
		
		$("#search-dropdown").removeClass("open");
		$("#search-dropdown").addClass("open");
		
		
//		document.body.appendChild(form1);
//		alert("죄송합니다. 현재는 PC-KR만 이용 가능합니다.");
//		$(this).addClass("active").siblings().removeClass("active");
//		updateLocationName();
	});
	
	$(".device-list li").click(function(){
		 document.body.appendChild(form3);
//		    $(form3).submit();
//		    document.body.removeChild(form3);
//		alert("죄송합니다. 현재는 PC-KR만 이용 가능합니다.");
// 			$(this).addClass("active").siblings().removeClass("active");
// 			updateDeviceName();
	});


	$("#notice").click(function(){
		console.log("hel");
		openNewWindow("/email", false, true, false, 0, 0, 400, 800, false);
	});


	// 등록하기 버튼..
	$("#checkin").click(function(){
		window.open("./register", "", "width=400, height=800, history=no, resizable=no, status=no, scrollbars=no");

	});

	// 탈퇴하기 버튼..
	$("#checkout").click(function(){
		window.open("./remove", "", "width=400, height=800, history=no, resizable=no, status=no, scrollbars=no");
	});

	//건의사항 및 버그신고 버튼
	$("#email").click(function(){
		var w = 600;
		var h = 460;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);

		window.open("./email", "_blank", "toolbar=yes,scrollbars=no,resizable=no,"
				+ "width= " + w + ", height=" + h + ", top=" + top + ", left=" + left);
	});

	/** Search Button Declaration **/



	/** Search Duplicate Table Button Declaration **/
	$("#duplicateBtgTableWrapper tr").click(function(){
		excuteTableSearch($(this));
	});
	
	/* TODO:동작 확인 조금더 할 것 https://goo.gl/mHS1hl*/
	$(document).click(function (event) {
	    var clickover = $(event.target);
	    var $navbar = $("#search-dropdown");               
	    var _opened = $navbar.hasClass("in");
	    if (_opened === true && !clickover.hasClass("navbar-toggle")) {      
	        $navbar.collapse('hide');
	    }
	});
});

function initButtonMap(){
	
	/** Button Action Declaration **/
	/* Search Button and Click pressed */
	$("#btgSearchBtn").click(function(){
		searchButClicked();
	});
	/* Enter Button pressed */
	$("#searchInput").on('keyup', function (e) {
		if (e.keyCode == 13) {
			searchButClicked();
		}
	});
	
}

function searchButClicked(){
	
	/* Variable Initiate */
	var loc = getAcitveLocation();
	var device = getActiveDevice();
	var isSample = false;
	
	/* 입력필드에서 값을 가져옵니다. */
	var searchValue = $("#searchInput").val();
	
	/* Count # Number */
	var countNumSign = countIncludeStr(searchValue, "#");
	
	/* #의 개수에 따라 처리하는 프로세스가 다릅니다. */
	if(countNumSign == 1){	// #이 1개일때는 정상적으로 입력했다고 생각합니다.
		var battleTag = searchValue.replace("#","-");
		moveHeroPage(battleTag, loc, device, isSample);
	} else if(countNumSign == 0) {	// #이 없을때는 배틀태그가 아닌 배틀네임만 입력한 경우로 봅니다.
		var objIds = {};
		objIds[searchValue] = null;
		$_target = $("#search-list");
		ajaxFetchShortStats(	objIds, 		/* Ids */
								updateShortStatsList,/* Callback */
								$_target, 		/* target Elem */
								false,			/* false = battle Name search*/
								false);			/* is Init Execute 최초 실행이면 alert를 실행하지 않습니다. */					
	} else {
		alert("배틀태그를 정확히 입력해 주세요.");	// #이 1개 초과일때는 배틀태그를 잘못 입력한 것으로 간주합니다.
		return;
	}

	/* 테이블을 닫았다가 다시 엽니다. */
	if($("#search-dropdown").hasClass("in")){
		$("#search-dropdown").collapse("hide");
		$("#search-dropdown").on('hidden.bs.collapse', function(){
			$("#search-dropdown").collapse("show");
	    });
	} else if($("#search-dropdown").hasClass("collapsing")){
		// collapsing 일때는 아무 동작도 하지 않도록 방지합니다.
	} else {
		$("#search-dropdown").collapse("show");
	}
}

//TODO: hero.model에 있는것과 같은형태 객체 지향형태로 클래스로 만들어 상속해볼것..
/* Parameter는 Array 형태가 아닌 Object 형태로 전달한다. */
function ajaxFetchShortStats(objIds, callback, $_target, boolSearchType /* KeySearch=true, battleNameSearch=false */, isInit){
	
	/* Create xmlhttp object */
	if (window.XMLHttpRequest) {
	    xmlhttp = new XMLHttpRequest();		    // code for IE7+, Firefox, Chrome, Opera, Safari
	} else {
	    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");	    // code for IE6, IE5
	}
	
	/* Before send, declare recieve process first */
	xmlhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {

	    	/* JSON Parse */
	    	var response = JSON.parse(this.responseText);
	    	/* Validate Check */
	    	var errorCode = response['error']['errorCode'];
	    	if(errorCode == null){	// errorCode가 없으면 반환한다.
	    		callback(response['result'], $_target, isInit);	// update display, update 
	    	}
	    }
	};
	
	/* Declare Send parameter */
	var url = "/pages/GetShortStats.php";
	var param = "?playerId=";
	
	/* object {btg:null} 형태이면 된다. */
	for(var key in objIds){
		param += key + "+";
	}
	param = param.substring(0, param.length-1);
	param += "&searchType=" + boolSearchType;
	
	/* Make Uri and xml http */
	var encode_uri = encodeURI(url + param);
	xmlhttp.open("GET", encode_uri, true);
	//	TODO:Username, password 사용해서 보안을 강화	
	//	xmlhttp.open(method, url, async, username, password);
	
	/* Send */
	xmlhttp.send();
}

//TODO: hero.model에 있는것과 같은형태 객체 지향형태로 클래스로 만들어 상속해볼것..
/* 리스트에 추가하는 이벤트 리스너를 독립적으로 추가하기 위해 hero.js와 이 함수를 분리합니다.(동일한 함수명이 존재합니다.) */
function updateShortStatsList(ajaxResponse, $_target, isInit){
	
	/* 서버로 부터 응답받은 ajaxResponse 입니다. */
	var shortStats = ajaxResponse;
	
	/* 해당 엘리먼트 Id의 디스플레이를 업데이트 합니다. */
	/* 친구비교탭에 있는 즐쳐찾기 항목을 업데이트 합니다. */
	updateShortStatsListDisplay($_target, shortStats, isInit);

	/* Add Listener */
	/* 사용자 정보를 하나 클릭하면 친구비교 탭의 데이터를 이제 업데이트 한다. */
	$_target.find("li").click(function(){
		var strId = $(this).attr("id");
		moveHeroPage(strId, 'kr', 'pc', false);
	});
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

function updateLocationName(){
	var activeLocationId = getAcitveLocation();
	$("#locationName").text(activeLocationId.toUpperCase());
}

function getAcitveLocation(){
	var result;
	$(".location-list li").siblings().each(function(){
		if($(this).hasClass('active')){
			result = $(this).attr('id');
			return;
		}
	});
	return result;
}

function updateDeviceName(){
	var activeDeviceId = getActiveDevice();
	$("#deviceName").text(activeDeviceId.toUpperCase());
}

function getActiveDevice(){
	var result;
	$(".device-list li").siblings().each(function(){
		if($(this).hasClass('active')){
			result = $(this).attr('id');
			return;
		}
	});
	return result;
}

function sendBtg(btg){
	document.getElementById("demo").innerHTML = btg;
	// 정상적으로 등록 되었습니다 or 탈퇴 되었습니다.
	// 			register Class Activate 해제하기..
}


function makeUrlQuery(urlKey, urlValue){
	return "&" + urlKey + "=" + urlValue;
};

/* Move Hero Page */
function moveHeroPage(btg, loc, device, isSample){
	/* Add post Form */
	var postForm = new PostForm("./hero/");
	postForm.addElem("btg", btg);
	postForm.addElem("loc", loc);
	postForm.addElem("device", device);
	postForm.addElem("sample", isSample);
	
	/* Action */
	postForm.submit();
}

/** Display Function Declaration **/

function excuteTableSearch(target){
	var btg = target.find('td:nth-child(1)').text();
	var loc = target.find('td:nth-child(2)').text()
	var device = target.find('td:nth-child(3)').text();
	moveHeroPage(target.attr('id'), loc, device, false);
}

function createDuplicateTableRow(btg, hiddenBtg, cptPt, iconUrl){
	var iconUrl = "url('" + iconUrl +  "')";
	$("#duplicateBtgTableWrapper tbody").append('<tr id="'+ btg + '"><td><a href="#">' + hiddenBtg + '</a></td><td>' + cptPt +'</td><td><div style="background-image:' + iconUrl + '"></div></td></tr>');
}
