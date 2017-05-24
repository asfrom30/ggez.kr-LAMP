
/** Ajax Function **/
/* 서버에 stats 데이터를 Ajax로 요청합니다. */
//TODO: option으로 loc, device등을 요청할 수 있게 할 것
function ajaxFetchStats(battileId, callback, reference, optionObj){
	if (window.XMLHttpRequest) {
	    xmlhttp = new XMLHttpRequest();		    // code for IE7+, Firefox, Chrome, Opera, Safari
	} else {
	    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");	    // code for IE6, IE5
	}
	// StatDatas Onready State
	xmlhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	    	callback(reference, this.responseText); // xmlhttp.response type은 text와 xml만 가능하다.
	    }
	};
	
	// Send xmlHttp
	if(false /* is Sample */){
		var encode_uri = encodeURI("/pages/GetSampleStats.php?&btg=" + battileId + makeUrlQuery('loc', loc) + makeUrlQuery('device', device));
		xmlhttp.open("GET", encode_uri, true);
	} else {
		var encode_uri = encodeURI("../pages/GetStats.php?&btg=" + battileId);
		xmlhttp.open("GET", encode_uri, true);
	}
	xmlhttp.send();
}

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

/* ajaxFetchStats의 call back 함수 입니다. */
function updateMyStats(reference, responseText){
	
	/* 오버워치에 등록된 사용자가 아니라면 */
	if(responseText == "" || responseText == null){	// ""(공백 문자열)이 오게 된다.
		alert("검색된 사용자가 없습니다.");
		return;
	}
	
	var responseObj = {};
	
	try {
		responseObj = JSON.parse(responseText);
//		responseObj = $.parseJson(responseText);
		
	} catch (err) {
		console.log("Recieve Stats is not Json Type");
	}
	
	if(!validResponse(responseObj)) return;
		
	// 첫방문에는 안내메세지를 보내준다.
	if(responseObj['isFirstVisit'] == true){
		alert(getAlertMessage('notice_first_visit'));
	}

	/* */
	window.stats = responseObj['dataResult'];
	window.tiers = responseObj['tierResult'];
	window.most3Heros = getMost3Hero(responseObj['dataResult']);

	console.log(window.stats);
	console.log(window.tiers);
	window.updateStatsReady = true;
}



function getColIndexes(tableId){
	
	/* Initiate Variable */
	var shiftIndex = 0;
	var stdIndex = 2;
	var compIndex = 3;
	
	if(tableId == "#my-radar-table"){
		shiftIndex = window["resultShiftIndex"];
	} else if(tableId == "#friend-radar-table") {
		shiftIndex = window["resultfShiftIndex"];
	}
	
	
	if(shiftIndex == 0){
		stdIndex = 2;	// 내 평균
		compIndex = 3; // 내 오늘
	} else if(shiftIndex == 1){
		stdIndex = 2; // 티어평균
		compIndex = 4; // 내 평균
	} else if(shiftIndex == 2){
		stdIndex = 3;  // 티어평균
		compIndex = 4; // 내 오늘
	}
	
	return [stdIndex, compIndex];
}

function validResponse(response){
	
	if(response['status'] == undefined){
		alert(getAlertMessage('err_cant_recieve_data_2'));
		return false;
	}
	
	if(response['errFlag'] == true){	// 에러가 있는 경우...
		
		var errCode = response['error']['errCode'];
		var errMessage = response['error']['errMessage'];
		if(errCode == 2){
			alert(getAlertMessage('err_user_not_exists'));
			window.location="/"
		} else {
			alert('에러코드 [' + response['error']['errCode'] + ']가 발생했습니다. 관리자에게 문의해주세요.');
			window.location="/"
		}
		
		console.log(response);
		return false;
	}
	
	if(response['dataResult'] == undefined){
		alert(getAlertMessage('err_stats_undefined'));
		return false;
	}
	
	if(response['dataResult'] == null){
		alert(getAlertMessage('err_stats_null'));
		return false;
	} 
	
	return true;
}

function getAlertMessage(error_id){
	// error Map
	if(error_id == 'err_cant_recieve_data_1') return "서버에서 데이터를 가져오지 못했습니다. : 1";
	else if(error_id == 'err_cant_recieve_data_2') return "서버에서 데이터를 가져오지 못했습니다. : 2";
	else if(error_id == 'err_user_not_exists') return "오버워치에 존재하지 않는 배틀태그입니다. 영문은 대소문자를 구분합니다. 배틀태그를 확인하여 주세요";
	else if(error_id == 'err_stats_undefined') return "stats가 undefined입니다.";
	else if(error_id == 'err_stats_null') return "stats의 값이 null입니다.";
	
	// notice Map
	else if(error_id == 'notice_first_visit') return "환영합니다. 첫방문이시네요. 이시각 이후로 플레이한 게임이 분석에 반영됩니다.";
}

