<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/../resources/config.php");
require_once(INIT_PATH . "/ChartInit.php");
require_once(INIT_PATH . "/HeroDpList.php");

// url parameter에 btg 확인
if(!isset($_GET['btg']) && !isset($_GET['sample'])) {
	header('Location: /');
	exit ();
} else {
	
}
// 구분자로 btg 배열에 저장 // Javascript에서 대응
// $param = $_GET['btg'];
// $btgs = explode(", ", $_GET['btg']);


/*
 * Code Start
 */

?>
<!DOCTYPE html>
<html>
<head>
	<title>GgEZ</title>
	<meta charset="UTF-8">
	<meta name="description" content="Free Web tutorials">
	<meta name="keywords" content="HTML,CSS,XML,JavaScript">
	<meta name="author" content="Hege Refsnes">
	
	<!-- be responsive to mobile devices -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- Chart.js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	
	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<!-- http://rateyo.fundoocode.ninja/ -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.2.0/jquery.rateyo.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.2.0/jquery.rateyo.min.js"></script>
	
	<!-- Animate Number -->
	<script src="../js/animateNumber/demo/jquery.color.min.js"></script>
	<script src="../js/animateNumber/jquery.animateNumber.min.js"></script>
	
	<!-- CSS Style Description -->
	<!-- Ggez CSS --><!-- 마지막에 로딩할 것. 누군가 background css를 가로챈다.. -->
	<link rel="stylesheet" href="../css/hero.css" type="text/css">
	
</head>

<body>
	<!-- Menu Bar 참고하기 -->
	<!-- http://www.w3schools.com/bootstrap/tryit.asp?filename=trybs_temp_analytics&stacked=h -->
	<!-- display: inline-block; inline 차이점이 무엇일까.. -->
	
	<!-- Header 선언 -->
	<nav class="navbar navbar-inverse nopadding">
		<div class="container-fluid ">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/">Ggez.kr</a>
			</div>
			<div class="collapse navbar-collapse " id="myNavbar">
				<ul class="nav navbar-nav">
					<li class="active"><a href="#">버그신고</a></li>
					<li><a href="#">홈으로</a></li>
				</ul>
			</div>
		</div>
	</nav>
	
	<!-- Title Area -->	
	<div class="container-fluid">
		<div class="titleContainer col-xs-12 col-sm-10 col-sm-offset-1">
			<div>
				<span id="battleTag"></span>
			</div>
			<!--
			<div class="border" id="badge-icon"></div>
			<div class="border">
				<span id="cptPointNum"></span>
			</div>
			-->
		</div>
	</div>
	
	<!-- UpperArea -->
	<div class="container-fluid">
		<div id="upperArea" class="row" align="center">
			
			
			<!-- Radar Chart Area -->
			<div class="col-xs-12 col-sm-5 col-sm-offset-2" align="center" style="height:450px; min-height:450px; max-height:450px;">
				<div style="position:absolute; left:0; bottom:0; z-index:+2" class="btn btn-default" id="updateBtn">갱신하기</div>
				<!-- Radar Chart -->
				<canvas id="radarCanvas" class="fade-out" style="background-color:rgba(255,255,255,0); position:absolute;z-index:+1;"></canvas>
				<iframe id="radarLoading" class="fade-out" src="./ovLoading.html" frameBorder="0" style="position: relative; width:100%; height:100%; position:absolute; left:0; top:0; z-index:-1;"></iframe>
			</div>
	
	<!-- Table BackUp -->
 			<div class="tableWrapper col-xs-12 col-sm-3">
 			</div>
 		</div>
 	</div>
	
	<!-- <button type="button" class="glyphicon glyphicon-plus" data-toggle="collapse" data-target=".demo"></button> -->	
	<div id="middleArea" class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2" style="padding:0">
		<!-- Point Display -->
		<!-- Group 1 --> <!-- 버튼 -->
		<div id="prdButton" class="group">
			<div id="title"><span class="glyphicon glyphicon-log-in"></span> 데이터</div>
			<div>
				<div class="dataSelector" id="yesterDayBtn" style="padding-top:5px;">어제경기</div >
				<div class="dataSelector" id="todayBtn" style="padding-top:5px;">오늘경기</div >
				<div class="dataSelector" id="weekBtn">주간요약</div>
			</div>
		</div>
		<!-- Group 2 --> <!--평점 --> <!--  PX로 하면 되겠따...... px은 절대치 %는 상대치 이므로... -->
		<div id="ptRating" class="group">
			<div id="title"><span class="glyphicon glyphicon-log-in"></span> 평점</div>
			
			<div class="clearfix" style="width:100% height:40%; position:relative;">
		 		<div style="float:left;">
		 			<div class="subTitle"> 시즌3 </div>
					<div id="avgPtRatingStar" class="fiveStar" id="avgPtRating" style="padding-left: 15px"></div>
				</div>
				<div id="avgPtRatingNum" style="position:relative; bottom:-5px; float:left; line-height: 40px; font-family: beantown; color:white; font-size: 40px; display:inline-block"><span id="value">--</span></div>
			</div>
			<div  class="clearfix" style="position:relative; width:100% height:40%;">
				<div style="float:left;">
					<div class="subTitle" id="prdLabel"><span></span></div>
					<div id="prdPtRatingStar" class="fiveStar" id="prdPtRating" style="padding-left: 15px"></div>
				</div>
				<div id="prdPtRatingNum" style="position:relative; bottom:-5px; float:left; line-height: 40px; font-family: beantown; color:white; font-size: 40px; display:inline-block"><span id="value">--</span></div>
			</div> 
		</div>
		
		<!-- Group 3 -->
		<div id="winRating" class="group">
			 <!-- Label : 승률 --> 
			<div id="title" style="height:20%"><span class="glyphicon glyphicon-log-in"></span> 승률</div>
			
			<div style="float:left;  width:70%; height:40%;">
				<div class="subTitle">시즌3</div>
				<div id="avgWinRating" class="progress" style="margin:0; margin-left: 15px;">
				  <div id="success" class="progress-bar progress-bar-success" role="progressbar"></div>
				  <div id="danger" class="progress-bar progress-bar-danger" role="progressbar"></div>
				</div>
			</div>
			<div id="avgWinRatingNum" style="float:left; width:30%; height:40%;">
				<div style="margin-left:3px; font-family: beantown; color:white; font-size: 40px; display:inline-block"><span id="value">--</span><span style="font-size: 15px;">%</span></div>
			</div>
			
			<div style="float:left;  width:50%; height:40%; display:inline-block;">
				<div class="subTitle" id="prdLabel"><span></span></div>
				<div id="prdWinRating" class="progress" style="margin:0; margin-left: 15px;">
				  <div id="success" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"></div>
				  <div id="danger" class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar"></div>
				</div>
			</div>
			<div id="prdWinRatingNum" style="float:left; width:30%; height:40%; display:inline-block;">
				<div style="margin-left:3px; font-family: beantown; color:white; font-size: 40px; display:inline-block"><span id="value">--</span><span style="font-size: 15px;">%</span></div>
			</div>
		</div>
		
		<!-- Group 3 --> <!-- 경쟁전 점수 --> 
		<div id="cptPt" class="group" style="position:relative">
			<div id="title"><span class="glyphicon glyphicon-log-in"></span> 경쟁전 점수</div>
			<div class="cptPtContainer" style="width:90%">
				<div style="vertical-align: bottom; display:inline-block; font-family: beantown; font-size: 30px;"><span id="cptPtArrowLabel" style="color:white;">-</span></div>
				<div id="diffCptPt" style="vertical-align: bottom; display:inline-block; font-family: beantown; font-size: 60px; line-height: 40px;  color:white;"><span id="value">---</span></div>
				<div style="vertical-align: bottom; display:inline-block;" >
					<div style="float:left;">
						<div class="subTitle nopaddingAll" style="text-align:center; font-size: 15px"><span id="prdAdvLabel">--</span></div>
						<div id="prdCptPt" style="font-family: beantown; font-size: 20px; line-height: 20px;  color:white;"><span id="value">----</span></div>
					</div>
					<div class="subTitle nopaddingAll" style="float:left; font-size: 15px; margin-left:2px; margin-right:2px"><span>→</span></div>
					<div style="float:left;">
						<div class="subTitle nopaddingAll" style="text-align:center; font-size: 15px"><span id="todayAdvLabel">--</span></div>
						<div id="todayCptPt" style="font-family: beantown; font-size: 20px; line-height: 20px;  color:white;"><span id="value">----</span></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--  Hero Area HTML -->
	<div id="lowerArea" class="container-fluid"> 
		<div class="row" align="center">
			<div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2" >
				<?php
				$dpList = getDpList();
				$roleKeys = array_keys($dpList);
				
				foreach($roleKeys as $role){
					// role div 출력.
					if($role == "All"){
						
					} else {
						echo '<div class="roleContainer" style="background-image: url(\'../img/' . $role . '-icon.png\');"></div>';
					}
					$heroIds = array_keys($dpList[$role]);
					foreach ($heroIds as $heroId){
						$hero = $heroId;
						$imgId = strtolower($hero);
						$label = $dpList[$role][$heroId];
						
						$url = "url('../img/icon-" . $imgId . ".png');";
						echo '<div class="heroContainer"'
							. 'id="' . $hero . '"'	
							. ' style="background-image:' . $url . '"'
							. '><span class="update label label-danger"></span>'
							. '<span class="heroNameTag badge" style="">'
							. $label .'</span></div>';
					}
				}
				?>
			</div>
		</div>
	</div>
	
	
	
	<!--  Java Script -->
	<script src="../js/hero.js"></script>
	<script type="text/javascript">
		var data = <?php createDefChrt()?>;	// Default Data
	</script>
</body>

	<!-- Side Menu 
  <div class="panel-group col-sm-2 col-sm-offset-1 nopadding" style=" height:100%; overflow-x: hidden;">
    <div class="row panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" href="#collapse1"><span class="caret"></span>다른 티어</a>
        </h4>
      </div>
      <div id="collapse1" class="panel-collapse collapse">
      	<div class="btn-group-vertical">
      		<button id="bronzeBadge" type="button" class="btn btn-default btn-block activate"><div id="badge" ></div>브론즈</button>
      		<button type="button" class="btn btn-default btn-block"><span class="glyphicon glyphicon glyphicon-search"></span> 실버</button>
      		<button type="button" class="btn btn-default btn-block">Button 1</button>
      		<button id="bronzeBadge" type="button" class="btn btn-default btn-block activate"><div id="badge" ></div>브론즈</button>
      		<button type="button" class="btn btn-default btn-block"><span class="glyphicon glyphicon glyphicon-search"></span> 실버</button>
      		<button type="button" class="btn btn-default btn-block">Button 1</button>
      		<button id="bronzeBadge" type="button" class="btn btn-default btn-block activate"><div id="badge" ></div>브론즈</button>
      		<button type="button" class="btn btn-default btn-block"><span class="glyphicon glyphicon glyphicon-search"></span> 실버</button>
      		<button type="button" class="btn btn-default btn-block">Button 1</button>
      	</div>
      </div>
    </div>
  </div> -->

<?php 
// 직접 접속 방지
// 이 코드가 있으면 $_GET에 실어보내면 안먹히네... 왜그러지??
// if (! defined ( 'ABSPATH' )) {
// 	header('Location: http://localhost/ggez/');
// 	exit ();
// }

// 에러발생시 어떻게 할 것인지 처리, 간헐적 필요
// require './function/ConnectDB.php'; // 에러 발생시 중지
// include 'peer2db.php'; //에러 발생시 이후 코드 진행

/*
 * 공부한 것 정리..
 */
// foreach ($btgs as $btg){
// 	$dataMap[$btg] = ""; // hashMap으로 사용
// 	array_push($btgs, 'hello'); // 배열로 사용
// }

// 한번에 선언하기.
// $hashMap = array('mykey'=>"value1", 'mykey2' => "value2");


/*
 * Improve Things to Do
 */

// REQUEST에서 요청한 배틀태그 파싱하기

// 배틀태그 DB에 있는지 조회
	// 없으면 LOOT BOX에서 배틀태그 조회
	// DB에 DATA 넣기
	// DB가공해서 2차데이터에 넣기
	

// 2차데이터 가져오기.	
// 데이터 차트 표시

// 주소복사하기 밑에 따로 두기.

// Hash Map : $hashMap = array('mykey'=>"value1", 'mykey2' => "value2");

// http://localhost/ggez/hero?btg=냅둬라날-3934&KTX-21869

// mesure json data parsing speed
// http://stackoverflow.com/questions/9944567/how-to-measure-time-of-parsing-json-string-into-javascript-object
?>
  
  