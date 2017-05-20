<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/../resources/config.php");
require_once(INIT_PATH . "/HeroDpList.php");

/** This code must be exctued before HTML Headder **/

/* Post Parameter Check */
if(!isset($_POST['btg']) && !isset($_POST['sample'])) {
	header('Location: /');
	exit ();
} else {

}
/* Cookie Check For Favorite Player */
global $cookie;


// $jsonFavoriteIds = null;
// if(!isset($_COOKIE[$cookieName])){
// 	setcookie($cookieName,"",time()+$expireTime);	// 초기값 ""입니다.
// }
// /* get Favorite Ids String */
// $strFavoriteIds = $_COOKIE[$cookieName];


/*
 * Code Start
 */

?>
<!DOCTYPE html>
<html>
<head>
	<title>GgEZ - 오버워치 기간별, 티어별 전적조회</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<meta name="subject" content="오버워치 기간별, 티어별 전적비교">
	<meta name="description" content="오버워치, 기간별 전적비교, 티어별 전적비교, 티어확인, 랭킹, 인벤, GGEZ.KR, 오버워치 전적비교">
	<meta name="keywords" content="오버워치 전적검색, 기간별 비교, 티어별 비교, 오버로그, 인벤, 오버워치 랭킹">
	<meta name="author" content="DOYOON KIM">

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
	
	<!-- Icon -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

	<!-- CSS Style Description -->
	<!-- Ggez CSS --><!-- 마지막에 로딩할 것. 누군가 background css를 가로챈다.. -->
	<link rel="stylesheet" href="../css/hero.css?v=<?=time();?>" type="text/css">
	<link rel="stylesheet" href="../css/hero/fixedBottom.css?v=<?=time();?>" type="text/css">
	<link rel="stylesheet" href="../css/hero/normalContent.css?v=<?=time();?>" type="text/css">
	<link rel="stylesheet" href="../css/lib/common.css?v=<?=time();?>" type="text/css">
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">		

</head>

<body>
<?php include_once("../analyticstracking.php") ?>
<!--	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>-->
<!--	<script>-->
<!--	  (adsbygoogle = window.adsbygoogle || []).push({-->
<!--	    google_ad_client: "ca-pub-7621832521510628",-->
<!--	    enable_page_level_ads: true-->
<!--	  });-->
<!--	</script>-->

<!-- navbar -->
<div class="navbar navbar-inverse">
	<div class="navbar-header">
		<a class="navbar-brand" href="/">GgEZ.kr</a>
	</div>
</div>

<div class="container">
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- GGEZ-HERO -->
	<div class="main-title">
		<div class="flex f-align-w-between f-align-h-center">
			<div class="flex f-align-h-center">
				<div class="js-battle-image battle-image" id="mainTitleImg"></div>
				<div class="js-my-battle-name battle-name"></div>
				<div class="iconBox">
					<ul class="flex-row-wrap noMargin noPadding">
						<i class="material-icons cursor-pointer" id="regFavorite">star_border</i>
	<!-- 					<i class="material-icons cursor-pointer">star</i> -->
	<!-- 					<i class="material-icons cursor-pointer">star_border</i> -->
					</ul>
				</div>
			</div>
			<div id="build-adsense-1">
			</div>
		</div>
	</div>
		
	<!-- Tab buttons -->
	<div class="main-tab-buttons m-b-4px" id="js-main-tab-buttons">
		<ul class="nav nav-tabs">
			<li class="w-33-pct " id="js-total-tab-button"><a data-toggle="tab" href="#html-total-summary">전체요약</a></li>
			<li class="w-33-pct active" id="js-hero-tab-button"><a data-toggle="tab" href="#html-hero-detail">영웅상세</a></li>
			<li class="w-33-pct " id="js-favorite-tab-button"><a data-toggle="tab" href="#html-friend-compare">친구비교</a></li>
		</ul>
	</div>
	
	<!-- Main Contents -->	
	<!-- Tab Content -->
	<div class="main-tab-contents">
		<div id="build-adsense-3">
			<script type="text/javascript">
			(function(cl,i,c,k,m,o,n){m=cl.location.protocol+c;o=cl.referrer;m+='&mon_rf='+encodeURIComponent(o);
			n='<'+i+' type="text/javascript" src="'+m+'"></'+i+'>';cl.writeln(n);
			})(document,'script','//tab2.clickmon.co.kr/pop/wp_ad_728_js.php?PopAd=CM_M_1003067%7C%5E%7CCM_A_1033884%7C%5E%7CAdver_M_1003115');
			</script>
		</div>
		<!-- ovLoading Image -->
		<div class="tab-loading fade-0.2s">
			<div class="flex f-align-w-center">
				<div>
					<iframe src="/hero/ovLoading.html" height="300" width="300" frameBorder="0"></iframe>
				</div>
			</div>
			<div class="loading-label">전적을 분석하고 있습니다. 잠시만 기다려주세요...</div>
			<div class="loading-label">무한 로딩이 발생하면 Ctrl+5로 새로고침하거나, 인터넷 사용기록을 삭제해주세요</div>
		</div> <!-- End of ovLoading Image -->

		<!-- Tab Content Start -->	
		<div class="tab-content flex fade-0.2s fade-out">
			<!-- Data Select and Status Bar -->
			<div class="data-selector navbar navbar-inverse" id="heroContent-selector">

				<!-- Fixed Bottom Hide-selector -->
				<div class="selector-hidden collapse" id="html-selector-hidden">
					<div class="subTitle"><span class="glyphicon glyphicon-ok-circle"></span> 내 데이터 선택하기</div>
					<div class="my-data-selector btn-group">
						<button class="heroPeriodBtn btn btn-labeled btn-default btn-xs w-25-pct" id="yesterDayBtn"><div>어제전체</div><div>-</div></button>
						<button class="heroPeriodBtn btn btn-labeled btn-default btn-xs w-25-pct" id="todayBtn"><div>오늘전체</div><div>-</div></button>
						<button class="heroPeriodBtn btn btn-labeled btn-default btn-xs w-25-pct" id="weekBtn"><div>주간전체</div><div>-</div></button>
						<button class="heroPeriodBtn btn btn-labeled btn-default btn-xs w-25-pct" id="avgBtn"><div>시즌 평균</div><div>-</div></button>
<!-- 						<button class="heroPeriodBtn btn btn-labeled btn-default btn-xs w-20-pct" id="bestBtn"><div>시즌 최고</div><div>-</div></button> -->
					</div>
					<div class="subTitle"><span class="glyphicon glyphicon-ok-circle"></span> 친구 데이터 선택하기</div>
					<div class="friend-data-selector btn-group">
						<button class="heroPeriodBtn btn btn-labeled btn-default btn-xs w-25-pct" id="friend-yesterDayBtn"><div>어제전체</div><div>-</div></button>
						<button class="heroPeriodBtn btn btn-labeled btn-default btn-xs w-25-pct" id="friend-todayBtn"><div>오늘전체</div><div>-</div></button>
						<button class="heroPeriodBtn btn btn-labeled btn-default btn-xs w-25-pct" id="friend-weekBtn"><div>주간전체</div><div>-</div></button>
						<button class="heroPeriodBtn btn btn-labeled btn-default btn-xs w-25-pct" id="friend-avgBtn"><div>시즌 평균</div><div>-</div></button>
<!-- 						<button class="heroPeriodBtn btn btn-labeled btn-default btn-xs w-20-pct" id="friend-bestBtn"><div>시즌 최고</div><div>-</div></button> -->
					</div>
					<div class="navbar-collapse collapse in" id="heroList" >
						<div class="heroList clearfix">
							<ul class="nav navbar-nav" style="width:100%">
								<?php
									$dpList = getDpList();
									$roleKeys = array_keys($dpList);

									foreach($roleKeys as $role){
										// role div 출력.
										if($role != "All"){
										echo '<li>';

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
												. ' style="background-image:' . $url . '"' . '>'
												. '<span class="update"></span>'
												. '<span class="fupdate"></span>'
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
					<div class="subTitle"><span class="glyphicon glyphicon-ok-circle"></span> 다른티어 평균 선택<span id="tierSubTitle"></span></div>
					<div class="tierSelectBtn-Group btn-group">
						<button class="tierSelectBtn btn btn-labeled btn-default btn-xs" id="bronzeBtn"><div>브론즈</div><div>-</div></button>
						<button class="tierSelectBtn btn btn-labeled btn-default btn-xs" id="silverBtn"><div>실버</div><div>-</div></button>
						<button class="tierSelectBtn btn btn-labeled btn-default btn-xs" id="goldBtn"><div>골드</div><div>-</div></button>
						<button class="tierSelectBtn btn btn-labeled btn-default btn-xs" id="platinumBtn"><div>플래티넘</div><div>-</div></button>
						<button class="tierSelectBtn btn btn-labeled btn-default btn-xs" id="diaBtn"><div>다이아</div><div>-</div></button>
						<button class="tierSelectBtn btn btn-labeled btn-default btn-xs" id="masterBtn"><div>마스터</div><div>-</div></button>
						<button class="tierSelectBtn btn btn-labeled btn-default btn-xs" id="grandMasterBtn"><div>그랜드<br>마스터</div><div>-</div></button>
					</div>
				</div> <!-- end of selectorWrapper Class -->

				<!-- Fixed Bottom Header -->
				<div class="selector-header" id="heroContent-fixed-header">
					<div class="flex f-align-w-between h-50-px">
						<div class="flex h-100-pct overflow-hidden">
							<div class="title-image h-100-pct" id="js-fixed-bottom-img"></div>
							<div class="h-100-pct">
								<div class="title-upper-text h-50-pct" id="js-fixed-bottom-title-up">All</div>
								<div class="h-50-pct flex f-align-h-center title-lower-text">
									<div id="js-fixed-bottom-title-bottom">오늘 경기 결과</div>
									<div class="versus display-none" id="js-fixed-bottom-title-bottom-versus"><span class="red js-my-battle-name"></span> vs <span class="blue" id="js-fixed-bottom-friend-name"></span></div>
								</div>
							</div>
						</div>
						<div class="flexAlignSelf-v-center">
							<button type="button" class="toggle" data-toggle="collapse" data-target="#html-selector-hidden" id="data-select-button">
								<span class="glyphicon glyphicon-th-list"></span> 데이터 선택
							</button>
						</div>
					</div>

					<!-- Adsense -->
					<div class="flex f-align-w-center">
						<div id="build-adsense-2">
							<script type="text/javascript">
							(function(cl,i,c,k,m,o,n){m=cl.location.protocol+c;o=cl.referrer;m+='&mon_rf='+encodeURIComponent(o);
							n='<'+i+' type="text/javascript" src="'+m+'"></'+i+'>';cl.writeln(n);
							})(document,'script','//mtab.clickmon.co.kr/pop/wp_m_320_100_js.php?PopAd=CM_M_1003067%7C%5E%7CCM_A_1033884%7C%5E%7CAdver_M_1003115');
							</script>
					    </div>
					</div>
				</div>	
			</div> <!-- end of heroContent-selector -->
			
			<!-- Friend Search Bar and Favorite Tab -->
			<div class="favorite-search w-adjust" id="js-favorite-search-group">
				<div class="collapse-parent">
					<div class="input-group" id="js-compare-search-group">
					    <input type="text" class="form-control" placeholder="친구 배틀태그를 입력하세요">
					    <div class="input-group-btn">
					      <button class="btn btn-default js-dropdown-button c-black">
					      	<i class="glyphicon glyphicon-search c-black" id="js-favorite-search-search-icon"></i>
					      </button>
					    </div>
				  	</div>
				  	<div class="dropdown-position">
				  		<div class="build-player-list js-dropdown-content scrollable-menu w-100-pct" id="js-dropdown-search-list">
							<ul id="friend-search-list">
						    </ul>
					    </div>
				  	</div>
				</div>
				
				<!-- Find Top 10 and Favorite Drop Down Button -->
				<div class="flex f-align-w-between h-50-pct">
					<!-- Find Top 10 -->
					<div class="w-49-pct m-t-2px">
						<button class="w-100-pct c-black" type="button" id="js-favorite-add-button">지금 친구를 추가</button>
					</div>
					<!-- Find Favorite -->		
					<div class="w-49-pct m-t-2px">
						<button class="js-dropdown-button w-100-pct c-black" id="js-favorite-list-button">즐겨찾기<span class="caret"></span></button>
					</div>
				</div>
				
				<div class="dropdown-position">
					<!-- Top 10 and Favorite List -->
					<div class="build-player-list js-dropdown-content w-100-pct" id="js-dropdown-favorite-list"> <!-- small-favorite-dropdown -->
						<ul class="playerList w-100-pct" id="small-favorite-list">
						</ul>
					</div>
				</div>
				
				<!-- Find Top 10 and Favorite -->
<!-- 				<div class="flex f-align-w-between h-100-pct"> -->
					<!-- Find Top 10 -->
<!-- 					<div class="w-49-pct m-t-2px"> -->
<!-- 						<button class="btn btn-secondary dropdown-toggle w-100-pct c-black" type="button" id="js-top10-list-button">-</button> -->
<!-- 					</div> -->
					<!-- Find Favorite -->		
<!-- 					<div class="w-49-pct m-t-2px"> -->
<!-- 						<button class="btn btn-secondary dropdown-toggle w-100-pct c-black" type="button" id="js-favorite-list-button">즐겨찾기<span class="caret"></span></button> -->
<!-- 					</div> -->
<!-- 				</div> -->
<!-- 				<div class="collapse-parent w-100-pct"> -->
					<!-- Top 10 and Favorite List -->
<!-- 					<div class="build-player-list collapse collapse-child" id="small-favorite-dropdown"> -->
<!-- 						<ul class="playerList w-100-pct" id="small-favorite-list"> -->
<!-- 						</ul> -->
<!-- 					</div> -->
<!-- 				</div> -->

			</div>
			
			<!-- Total Content("전체요약") Tab Pane -->
			<?php 
				$tab_total_content = file_get_contents("./hero.tab.total-content.html");
				echo $tab_total_content;
			?>
			
			<!-- Hero Content("영웅상세")  Tab Pane -->
			<?php 
				$tab_hero_detail = file_get_contents("./hero.tab.hero-detail.html");
				echo $tab_hero_detail;
			?>

			<!-- Compare Freind("친구비교") Tab Pane -->
			<?php 
				$tab_compare_friend = file_get_contents("./hero.tab.compare-friend.html");
				echo $tab_compare_friend;
			?>
			
		</div>  <!-- Not ovLoading(Each Tab Pane and Fixed Bottom -->
	</div> <!-- end of Tab Content -->
</div>



	<!--  Java Script -->
	<!-- Pass parameter php to javascript -->
	<script>
	var passParameter = '<?php echo null ?>';	// This is example
    var btg = '<?php echo $_POST['btg']; ?>';
    var isSample = '<?php echo $_POST['sample']; ?>' == 'true';
    var loc = '<?php echo isset($_POST['loc'])?$_POST['loc']:""; ?>';
    var device = '<?php echo isset($_POST['device'])?$_POST['device']:""; ?>';

    var cFavoriteName = '<?php echo $cookie["favoriteIds"]["cookieName"] ?>';
    var cFavoriteExp = '<?php echo $cookie["favoriteIds"]["expireTime"] ?>';

    var currentId = btg;
    var battleName = convertBtg2Btn(btg);

    /** Battle Tag 2 Battle Name(It will be deprecated later) **/
    function convertBtg2Btn(btg){
    	var btg = decodeURI(btg);
    	// btg = btg.replace("-", "#");
        btg = btg.split("-")[0];
        return btg;
    	
    }

    </script>
	
	<!-- Source Java script -->
	<script src="../js/ggezkr.hero.controller.js?<?php echo round(microtime(true));?>"></script>
	<script src="../js/ggezkr.hero.view.js?<?php echo round(microtime(true));?>"></script>
	<script src="../js/ggezkr.hero.model.js?<?php echo round(microtime(true));?>"></script>
	
	<script src="../js/common.utility.js?<?php echo round(microtime(true));?>"></script>
	<script src="../js/common.utility.view.js?<?php echo round(microtime(true));?>"></script>
	
	<script src="../js/ggezkr.init.radar.js?<?php echo round(microtime(true));?>"></script>
    
    <script src="../js/ggezkr.utility.js?<?php echo round(microtime(true));?>"></script>
    <script src="../js/ggezkr.utility.label.js?<?php echo round(microtime(true));?>"></script>
</body>



