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
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
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
	
	<!-- Header 선언 
	<nav class="navbar navbar-inverse nopadding" data-spy="affix" data-offset-top="0">
		<div class="container-fluid ">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<div style="display: inline-block">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</div>
					<div style="display: inline-block">
						영웅변경
					</div>
				</button>
				<a class="navbar-brand" href="/">Ggez.kr</a>
			</div>
		</div>
	</nav>
	-->
	
	<!-- <p><a>Welcome your Hero Data</a></p>  -->
<div class="navbar navbar-inverse">
	<div class="navbar-header">
		<a class="navbar-brand" href="/">GgEZ.kr</a>
	</div>
</div>						
<div class="flexWrapper">
		<!-- Header -->
		<div class="flexItem">
			<nav class="navbar navbar-inverse navbar-fixed-bottom">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#heroList">
						<div style="display: inline-block">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						</div>
						<div style="display: inline-block">영웅변경</div>
					</button>
				</div>
				<div class="navbar navbar-collapse collapse" id="heroList">
					<div class="heroList">
						<ul class="nav navbar-nav">
							<?php
							$dpList = getDpList();
							$roleKeys = array_keys($dpList);
							
							foreach($roleKeys as $role){
								// role div 출력.
								if($role != "All"){
								echo '<li style="display:flex; align-items:center; margin-top:0.5rem;">';
								
								// Javascript Sibling Div가 1개일 때는 동작을 안하네.. 
// 								if($role == "All"){
// 									echo '<div class="heroContainer" style="background-image: url(\'../img/' . $role . '-icon.png\');"></div>';
// 								} else {
									echo '<div class="roleContainer" style="background-image: url(\'../img/' . $role . '-icon.png\');"></div>';
// 								}
								$heroIds = array_keys($dpList[$role]);
								foreach ($heroIds as $heroId){
									$hero = $heroId;
									$imgId = strtolower($hero);
									$label = $dpList[$role][$heroId];
									
									$url = "url('../img/icon-" . $imgId . ".png');";
									echo '<div class="heroContainer img-rounded"'
										. 'id="' . $hero . '"'	
										. ' style="background-image:' . $url . '"'
										. '><span class="update">new</span>'
										. '<span class="heroNameTag badge" style="">'
										. $label .'</span></div>';
								}
								echo '</li>';
								} // End of if($role != "All")
							}
							?>
						</ul>
					</div>
				</div>
			</nav>
		</div>
		<div class="flexItem container-fluid">
			<!-- 1 BOX : BattleTag-->
			<div class="main">
				<div class="row title" style="display: flex; justify-content: center">
					<div style="height:100%;">
						<div style="height:50%; border-bottom:2px solid orange"></div>
					</div>
					<div id="mainTitleImg"></div>
					<div>
						<span id="battleTag"></span>
					</div>
					<div style="height:100%;">
						<div style="height:50%; border-bottom:2px solid orange"></div>			
					</div>
				</div>
				<div class="row content">
					<div>
						<div class="subTitle">경쟁전 점수</div>
						<div class="subContent"><span id="main-1">-</span></div>
					</div>
					<div class="divider">
					</div>
					<div>
						<div class="subTitle">승률</div>
						<div class="subContent"><span id="main-2">-</span><span>%</span></div>
					</div>
					<div class="divider">
					</div>
					<div>
						<div class="subTitle">K/D</div>
						<div class="subContent"><span id="main-3">-</span></div>
					</div>
				</div>
				<div class="row content">
					<div>
						<div class="subTitle">어제 게임수</div>
						<div class="subContent"><span id="main-4">-</span></div>
					</div>
					<div class="divider">
					</div>
					<div>
						<div class="subTitle">오늘 게임수</div>
						<div class="subContent"><span id="main-5">-</span></div>
					</div>
					<div class="divider">
					</div>
					<div>
						<div class="subTitle">주간 게임수</div>
						<div class="subContent"><span id="main-6">-</span></div>
					</div>
				</div>
			</div>
			<!-- 2 BOX : MOST 3-->
			<div class="most3Wrapper">
				<div class="row title" style="display: flex; justify-content: center">
					<div style="height:100%;">
						<div style="height:50%; border-bottom:2px solid orange"></div>
					</div>
					<!-- https://codepen.io/FelixRilling/pen/qzfoc -->
					<div></div>
					<div>
						<span>MOST 3 </span>
					</div>
					<div style="height:100%;">
						<div style="height:50%; border-bottom:2px solid orange"></div>			
					</div>
				</div>
				<div class="row content">
					<div class="mostHead">
						<div></div>
						<div>게임수<span></span></div>
						<div>승률</div>
						<div>평점</div>
					</div>
					<div id="most1" class="mostBody">
						<div>
							<div></div>
							<div>--</div>
						</div>
						<div><span>-</span><span></span></div>
						<div><span>-</span><span>%</span></div>
						<div><span>-</span><span>pt</span></div>
					</div>
					<div id="most2" class="mostBody">
						<div>
							<div></div>
							<div>--</div>
						</div>
						<div><span>-</span><span></span></div>
						<div><span>-</span><span>%</span></div>
						<div><span>-</span><span>pt</span></div>
					</div>
					<div id="most3" class="mostBody">
						<div>
							<div></div>
							<div>--</div>
						</div>
						<div><span>-</span><span></span></div>
						<div><span>-</span><span>%</span></div>
						<div><span>-</span><span>pt</span></div>
					</div>
				</div>
			</div>
		</div>
		<div class="flexItem container-fluid">
		<!-- 3 BOX -->
		<!-- Title Area -->
			<div class="chartTitle">
				<div class="row detailTitle" style="display: flex; justify-content: center">
					<div style="height:100%;">
						<div style="height:50%; border-bottom:1px solid orange"></div>
					</div>
					<div id="titleHeroImg"></div>
					<div>
						<div class="upperText">All</div>
						<div class="lowerText">오늘 경기 결과</div>
					</div>
					<div>
						<!-- <button id="updateBtn" class="btn btn-default"><span class="glyphicon glyphicon-repeat"></span></button>  -->
					</div>
					<div style="height:100%;">
						<div style="height:50%; border-bottom:1px solid orange"></div>			
					</div>
				</div>
			</div>
			<!-- Chart Area -->
			<div class="chartArea">
				<div class="subFlexWrapper">
					<div class="subFlexItem">
						<div class="radarContainer">
							<div class="subTitle"><span class="glyphicon glyphicon-object-align-left"></span> 영웅 성적</div>
							<div class="radarContainer" style="position:relative">
								<canvas id="radarCanvas" class="fade-out" style="background-color:rgba(255,255,255,0);"></canvas>
								<!-- <iframe id="radarLoading" class="fade-out" src="./ovLoading.html" frameBorder="0" style="position: relative; width:100%; height:100%; position:absolute; left:0; top:0; z-index:-1;"></iframe>  -->
							</div>
						</div>
					</div>
					<div class="subFlexItem">
						<div>
							<div class="subTitle"><span class="glyphicon glyphicon-check"></span> 데이터 선택</div>
							<div class="btnContainer">
								<button class="dataSelector btn btn-default btn-xs" id="yesterDayBtn">어제경기</button>
								<button class="dataSelector btn btn-default btn-xs" id="todayBtn">오늘경기</button>
								<button class="dataSelector btn btn-default btn-xs" id="weekBtn">주간요약</button>
							</div>
						</div>
						<!-- 
						<div>
							<div class="subTitle">다른티어평균</div>
							<div class="btnContainer">
								<button class="dataSelector btn btn-default btn-xs">브론즈</button>
								<button class="dataSelector btn btn-default btn-xs">실버</button>
								<button class="dataSelector btn btn-default btn-xs">골드</button>
								<button class="dataSelector btn btn-default btn-xs">플래티넘</button>
								<button class="dataSelector btn btn-default btn-xs">다이아</button>
								<button class="dataSelector btn btn-default btn-xs">마스터</button>
								<button class="dataSelector btn btn-default btn-xs">그랜드마스터</button>
							</div>
						</div>
						 -->
						<div>
							<div class="subTitle"><span class="glyphicon glyphicon-sort"></span> 경쟁전 점수</div>
							<div class="subContent" id="cptPoint">
								<div><span id="cptPtArrowLabel">-</span><span id="diffCptPt" class="number">---</span></div>
								<div>
									<div><span id="prdAdvLabel">--</span></div>
									<div><span id="prdCptPt" class="number">---</span></div>
								</div>
								<div>→</div>
								<div>
									<div><span id="todayAdvLabel">--</span></div>
									<div><span id="todayCptPt" class="number">---</span></div>
								</div>
							</div>
						</div>
						<div>
							<div class="subTitle"><span class="glyphicon glyphicon-signal"></span> 평점</div>
							<div class="subContent" id="ptRating">
								<div>
						 			<div>시즌3</div>
									<div class="fiveStar" id="avgPtRatingStar" ></div>
								</div>
								<div class="labelNumber"><span id="avgPtRatingNum"></span></div>
							</div>
							<div class="subContent" id="ptRating">
								<div>
						 			<div class="prdLabel">--</div>
									<div class="fiveStar" id="prdPtRatingStar" >--</div>
								</div>
								<div class="labelNumber"><span id="prdPtRatingNum"></span></div>
							</div>
						</div>
					
						<div>
							<div class="subTitle"><span class="glyphicon glyphicon-tasks"></span> 승률</div>
							<div class="subContent" id="winRating">
					 			<div style="width:70%;">
						 			<div>시즌3</div>
					 				<div id="avgWinRating" class="progress">
										<div id="success" class="progress-bar progress-bar-success" role="progressbar"></div>
										<div id="danger" class="progress-bar progress-bar-danger" role="progressbar"></div>
									</div>
								</div>
								<div class="labelNumber"><span id="avgWinRatingNum"></span><span>%</span></div>
							</div>
							<div class="subContent" id="winRating">
					 			<div style="width:50%;">
						 			<div class="prdLabel">--</div>
					 				<div id="prdWinRating" class="progress">
										<div id="success" class="progress-bar progress-bar-success" role="progressbar"></div>
										<div id="danger" class="progress-bar progress-bar-danger" role="progressbar"></div>
									</div>
								</div>
								<div class="labelNumber"><span id="prdWinRatingNum"></span><span>%</span></div>
							</div>
						</div>
						<div>
							<div class="subTitle" data-toggle="collapse" data-target="#tableData">
								<span class="glyphicon glyphicon-th-list"></span> 상세보기
								<i class="indicator glyphicon glyphicon-chevron-up pull-right"></i>
							</div>
							<div id="tableData" class="tableWrapper collapse"></div>
						</div>
					</div> <!-- Sub Flex Item -->
				</div> <!-- Sub Flex Wrapper -->
			</div>
		</div>
	</div>
	<!--  Java Script -->
	<script src="../js/hero.js"></script>
	<script type="text/javascript">
		var data = <?php createDefChrt()?>;	// Default Data
	</script>
</body>

<!-- Radar Area 
<div class="container-fluid" style="position: relative">
	<div class="border radarContainer">
		<canvas id="radarCanvas" class="fade-out" style="background-color:rgba(255,255,255,0);"></canvas>
		<iframe id="radarLoading" class="fade-out" src="./ovLoading.html" frameBorder="0" style="position: relative; width:100%; height:100%; position:absolute; left:0; top:0; z-index:-1;"></iframe>
	</div>
</div>
-->

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
  
  
  