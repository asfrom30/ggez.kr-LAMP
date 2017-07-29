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
	<link rel="stylesheet" href="/css/common.css?v=<?=time();?>" type="text/css">
	
	<link rel="stylesheet" href="/css/hero/hero.css?v=<?=time();?>" type="text/css">
	<link rel="stylesheet" href="/css/hero/layout.css?v=<?=time();?>" type="text/css">
	
	<link rel="stylesheet" href="/css/hero/style/data-selector.css?v=<?=time();?>" type="text/css">
	<link rel="stylesheet" href="/css/hero/style/total-content.css?v=<?=time();?>" type="text/css">
	
	
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
				<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-1722366924586940" data-ad-slot="5878328514" data-ad-format="auto"></ins>
			    <script>
			    	$(document).ready(function(){(adsbygoogle = window.adsbygoogle || []).push({})});
			    </script>
			</div>
		</div>
	</div>
		
	<!-- Tab buttons -->
	<div class="main-tab-wrapper h-30-px m-t-10 m-b-5">
		<div class="button-group flex h-100-pct" id="js-main-tab-buttons">
			<button id="js-total-tab-button" class="tablink">전체요약</button>
			<button id="js-hero-tab-button" class="tablink active">영웅상세</button>
			<button id="js-favorite-tab-button" class="tablink">친구비교</button>
		</div>
	</div>

	<!-- Main Contents -->	
	<!-- Tab Content -->
	<div class="main-tab-contents">
<!-- 		<div id="build-adsense-3"> -->
<!-- 		</div> -->
		<!-- ovLoading Image -->
		<div class="tab-loading fade-0.2s">
			<div class="flex f-align-w-center">
				<div>
					<iframe src="./resources/ovLoading.html" height="300" width="300" frameBorder="0"></iframe>
				</div>
			</div>
			<div class="loading-label">전적을 분석하고 있습니다. 잠시만 기다려주세요...</div>
			<div class="loading-label">무한 로딩이 발생하면 Ctrl+5로 새로고침하거나, 인터넷 사용기록을 삭제해주세요</div>
		</div> <!-- End of ovLoading Image -->

		<!-- Tab Content Start -->	
		<div class="tab-content flex fade-0.2s fade-out">
			<!-- Data Select and Status Bar -->
			<?php 
				$tab_compare_friend = file_get_contents("./section/data-selector.html");
				echo $tab_compare_friend;
			?>
			
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
			</div>
			
			<!-- Total Content("전체요약") Tab Pane -->
			<?php 
				$tab_total_content = file_get_contents("./section/total-content.html");
				echo $tab_total_content;
			?>
			
			<!-- Hero Content("영웅상세")  Tab Pane -->
			<?php 
				$tab_hero_detail = file_get_contents("./section/hero-detail.html");
				echo $tab_hero_detail;
			?>

			<!-- Compare Freind("친구비교") Tab Pane -->
			<?php 
				$tab_compare_friend = file_get_contents("./section/compare-friend.html");
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
	<script src="../js/hero/controller.js?<?php echo round(microtime(true));?>"></script>
	<script src="../js/hero/view.js?<?php echo round(microtime(true));?>"></script>
	<script src="../js/hero/model.js?<?php echo round(microtime(true));?>"></script>
	
	<script src="../js/common.utility.js?<?php echo round(microtime(true));?>"></script>
	<script src="../js/common.utility.view.js?<?php echo round(microtime(true));?>"></script>
	
	<script src="../js/ggezkr.init.radar.js?<?php echo round(microtime(true));?>"></script>
    
    <script src="../js/ggezkr.utility.js?<?php echo round(microtime(true));?>"></script>
    <script src="../js/ggezkr.utility.label.js?<?php echo round(microtime(true));?>"></script>
</body>



