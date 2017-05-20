<!DOCTYPE html>
<html>
<head>
	<title>GgEZ</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<!-- JQuery -->
	<script
		src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	
	<!-- Bootstrap -->
	<link rel="stylesheet"
		href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script
		src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
	<!-- Ggez CSS -->
	<link rel="stylesheet" href="./css/ggez.css">
	
	<!-- Icon -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>

<body>
	<!-- Header -->
	<!-- <a href="#" class="btn btn-default btn-lg"><i class="material-icons" style="font-size:24px">info_outline</i></span>정보</a> -->
	<div class="row">
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse"
						data-target="#mainNavBar">
						<span class="icon-bar"></span> <span class="icon-bar"></span> <span
							class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">일간·주간 오버워치 전적 조회 사이트 GgEZ.kr</a>
				</div>
				<div class="collapse navbar-collapse" id="mainNavBar">
					<!-- 
					<ul class="nav navbar-nav">
						<li id="checkin"><a><span class="glyphicon glyphicon-log-in"></span>
								등록하기</a></li>
						<li id="checkout"><a href="#"><span
								class="glyphicon glyphicon-remove-circle"></span> 탈퇴하기</a></li>
					</ul>
					 -->
					<ul class="nav navbar-nav navbar-right">
						<li><a id="email" href="#"><span class="glyphicon glyphicon-envelope"></span> 건의사항 및 버그신고</a></li>
						<li><a href="/hero/?hero=sample"><span class="glyphicon glyphicon-eye-open"></span> 샘플 전적 보기</a></li>
					</ul>
				</div>
			</div>
		</nav>
	</div>
	
	<div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
		<div>
			<div class="mainLogo"></div>
			<div class="input-group">
				<input id="btgTextInput" name="btg" type="text" class="form-control"
					placeholder="배틀태그를 입력하세요">
				<div class="input-group-btn">
					<button class="btn btn-default" type="submit" id="btgSearchBtn"><i class="glyphicon glyphicon-search"></i></button>
				</div>
			</div>
			<div class="input-group">
				<!-- <input id="btgTextInput" name="btg" type="text" class="form-control" placeholder="배틀태그를 입력하세요">  -->
				<div class="btn-group">
				    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span id="locationName"></span><span class="caret"></span></button>
				    <ul class="dropdown-menu location-list" role="menu">
				      <li class="active" id='kr'><a href="#">KR</a></li>
				      <li id='us' class="disabled"><a href="#">US</a></li>
				      <li id='eu' class="disabled"><a href="#">EU</a></li>
				      <li id='cn' class="disabled"><a href="#">CN</a></li>
				    </ul>
				</div>
				<div class="btn-group">
				    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span id="deviceName"></span><span class="caret"></span></button>
				    <ul class="dropdown-menu device-list" role="menu">
				      <li class="active" id="pc"><a href="#">pc</a></li>
				      <li id="ps4" class="disabled"><a href="#">ps4</a></li>
				      <li id="xbox" class="disabled"><a href="#">xbox</a></li>
				    </ul>
				</div>
				<div style="color:white"><span class="label label-danger">주의!</span><span> 첫 방문시에만 '대소문자'를 구분해 기입해주면 됩니다.</span></div>
				<div style="color:white"><span class="label label-danger">주의!</span><span> 첫 방문시에만 '숫자까지' 모두 기입해 주세요.</span></div>
				
				<div class="collapse in" id="duplicateBtgTableWrapper"> 
					<table class="table" style="table-layout:fixed;">
						<thead>
							<tr>
								<th></th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><a href="#">냅둬라날#3934</a></td>
								<td>pc</td>
								<td>us</td>
							</tr>
							<tr>
								<td><a href="#">반짝반짝#1224</a></td>
								<td>pc</td>
								<td>kr</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">

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

	$(document).ready(function(){

		updateDeviceName();
		updateLocationName();
		
		$(".location-list li").click(function(){
			$(this).addClass("active").siblings().removeClass("active");
			updateLocationName();
		});
		
		$(".device-list li").click(function(){
			$(this).addClass("active").siblings().removeClass("active");
			updateDeviceName();
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
		// on Click
		$("#btgSearchBtn").click(function(){
			excuteSearch();
		});
		
		// Enter pressed
		$("#btgTextInput").on('keyup', function (e) {
			if (e.keyCode == 13) {
				excuteSearch();
			}
		});

		/** Search Duplicate Table Button Declaration **/
		$("#duplicateBtgTableWrapper tr").click(function(){
			excuteTableSearch($(this));
		});
	});

	function excuteSearch(){
		var btg = $("#btgTextInput").val();
		var loc = getAcitveLocation();
		var device = getActiveDevice();
		
		if(completeBtg(btg)){
			moveHeroPage(btg, loc, device, false);	
		} else {
			checkDuplicate(btg, loc, device, false);
		}
	}

	function excuteTableSearch(target){
		var btg = target.find('td:nth-child(1)').text();
		var loc = target.find('td:nth-child(2)').text()
		var device = target.find('td:nth-child(3)').text()
		moveHeroPage(btg, loc, device, false);
	}
	
	function completeBtg(btg){
		if(btg.search("#") == -1){
			return false;
		} else {
			return true;
		}
	}

	function checkDuplicate(btg, loc, device, isSample){
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		// StatDatas Onready State
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var response = this.responseText;
				response = JSON.parse(response);
				duplicateProcess(response);
				console.log(response);
			}
		};
	
		xmlhttp.open("GET","/checkDuplicateBtg.php?" + makeUrlQuery('btg', btg) + makeUrlQuery('loc', loc) + makeUrlQuery('device', device) + makeUrlQuery('sample', isSample));
		xmlhttp.send();
	}

	function duplicateProcess(response){
		$("#duplicateBtgTableWrapper tbody").html("");	//initiate
		
		if(!chkValidResponse){
// 			alert();
			return;
		}
		var result = response['result'];

		if (result.length == 1){
			moveHeroPage(result[0]['btg'], result[0]['loc'], result[0]['device'], false);
			return;
		}

		$("#duplicateBtgTableWrapper").collapse('hide');

		if(result.length == 0){
			$("#duplicateBtgTableWrapper tbody").html("<tr><td>검색된 플레이어가 없습니다.</td></tr>");	//initiate
		} else {
			for(var i=0; i < result.length; i++){
				createDuplicateTableRow(result[i]['btg'], result[i]['loc'], result[i]['device']);
			}
			// Click Button Refresh(새 엘리먼트가 추가될때마다 리프레쉬를 해줘야 한다.)
			$("#duplicateBtgTableWrapper tr").click(function(){
				excuteTableSearch($(this));
			});
		}

		// bootstrap에서 hide를 하면 이 함수가 작동할때까지 show가 작동하지 않는다.
		// 따라서 딜레이를 쓸수 밖에 없었다.
		// 주의 show, hide의 transition보다 큰 값을 딜레이로 넣을것.
		setTimeout(function(){
			$("#duplicateBtgTableWrapper").collapse('show');	
		}, 500)
	}

	function chkValidResponse(response){
		return true;
	}

	function createDuplicateTableRow(btg, loc, device){
		$("#duplicateBtgTableWrapper tbody").append('<tr><td><a href="#">' + btg + '</a></td><td>' + loc +'</td><td>' + device + '</td></tr>');
	}
	
	// Using battle Net Oauth
	function moveHeroPage(btg, loc, device, isSample){
		var btg = btg.replace("#","-");
		console.log("./checkBtg.php?" + makeUrlQuery('btg', btg) + makeUrlQuery('loc', loc) + makeUrlQuery('device', device) + makeUrlQuery('sample', true));
		window.location="./hero?btg=" + btg + makeUrlQuery('loc', loc) + makeUrlQuery('device', device) + makeUrlQuery('sample', isSample);
	}



// deprecated	
// 	// Using battle Net Oauth
// 	function moveHeroPage(chkPlayerFlag){
// 		var orgBtg = $("#btgTextInput").val();
// 		var transBtg = orgBtg.replace("#","-");

// 		if(chkPlayerFlag){
// 			if (window.XMLHttpRequest) {
// 				// code for IE7+, Firefox, Chrome, Opera, Safari
// 				xmlhttp = new XMLHttpRequest();
// 			} else {
// 				// code for IE6, IE5
// 				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
// 			}
// 			// StatDatas Onready State
// 			xmlhttp.onreadystatechange = function() {
// 				if (this.readyState == 4 && this.status == 200) {
// 					var response = this.responseText;
// 					console.log(response);
// 					if(response){
// 						window.location="./hero?btg=" + transBtg;	// transBtg # -> - 바꾼것.
// 					} else {
// 						alert("죄송합니다. 등록되지 않은 사용자 입니다.");
// 					}
// 				}
// 			};
		
// 			// 	    transBtg를 이용해서 추적하기..
// // 			xmlhttp.open("GET","./checkBtg.php?&btg=" + transBtg, true);
// 			var loc = getAcitveLocation();
// 			var device = getActiveDevice();
// 			console.log("./checkBtg.php?" + makeUrlQuery('btg', transBtg) + makeUrlQuery('loc', loc) + makeUrlQuery('device', device) + makeUrlQuery('sample', true));
// // 			xmlhttp.open("GET","./checkBtg.php?" + makeUrlQuery('btg', transBtg) + makeUrlQuery('loc', loc) + makeUrlQuery('device', device) + makeUrlQuery('sample', true));
// 			xmlhttp.send();
// 		} else {
// 			var loc = getAcitveLocation();
// 			var device = getActiveDevice();
// 			console.log("./checkBtg.php?" + makeUrlQuery('btg', transBtg) + makeUrlQuery('loc', loc) + makeUrlQuery('device', device) + makeUrlQuery('sample', true));
// // 			window.location="./hero?btg=" + transBtg;	// transBtg # -> - 바꾼것.
// 		}
// 	}




	// similar behavior as an HTTP redirect(현재 페이지에서 덮어 씌우기)
	//        window.location.replace("http://stackoverflow.com");

	// similar behavior as clicking on a link(다른 주소로 이동 - 뒤로가기 가능)
	//        window.location.href = "http://stackoverflow.com";
	</script>
</body>

<!-- For Test  -->
<!-- 	<p id="demo">asdfasdf</p> -->
<!-- 	document.getElementById("demo").innerHTML = "ASDF"; -->

<!-- Jquery form to get -->
<!--   $('form').get(0).setAttribute('action', 'baz'); //this works -->
<!--  http://stackoverflow.com/questions/979024/changing-the-action-of-a-form-with-javascript-jquery -->

</html>