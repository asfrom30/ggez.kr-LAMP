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

	<!-- 기간별, 티어별 전적조회 -->

	<!-- JQuery -->
	<script
		src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

	<!-- Bootstrap -->
	<link rel="stylesheet"
		href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script
		src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<!-- Ggez CSS -->
	<link rel="stylesheet" href="/css/ggez.css">

	<!-- Icon -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

</head>
<body>
<?php include_once("analyticstracking.php") ?>
<!--	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>-->
<!--	<script>-->
<!--	  (adsbygoogle = window.adsbygoogle || []).push({-->
<!--	    google_ad_client: "ca-pub-7621832521510628",-->
<!--	    enable_page_level_ads: true-->
<!--	  });-->
<!--	</script>-->

	<!-- Header -->
		<div class="defWarpper">
			<nav class="navbar navbar-inverse">
			  <div class="container-fluid">
			    <div class="navbar-header">
			      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			      </button>
			      <a class="navbar-brand" href="#" id="notice">NOTICE</a>
			    </div>
			   </div>
			</nav>
			<div class="mainBackgroundImage" ></div>
			<div align="center">
				<div class="mainWrapper">
					<div id="mainLogo"></div>
					<div id="mainInput" class="input-group">
						<div class="input-group-btn" style="height: 20px;">
							<div class="btn-group">
						      	<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"><span id="locationName"></span><span class="caret"></span></button>
							    <ul class="dropdown-menu location-list" role="menu">
							    	<li class="active" id='kr'><a href="#">KR</a></li>
							    	<li id='us' class="disabled"><a href="#">US</a></li>
							    	<li id='eu' class="disabled"><a href="#">EU</a></li>
							    	<li id='cn' class="disabled"><a href="#">CN</a></li>
							    </ul>
						    </div>
						    <div class="btn-group">
							    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"><span id="deviceName"></span><span class="caret"></span></button>
							    <ul class="dropdown-menu device-list" role="menu">
							      <li class="active" id="pc"><a href="#">pc</a></li>
							      <li id="ps4" class="disabled"><a href="#">ps4</a></li>
							      <li id="xbox" class="disabled"><a href="#">xbox</a></li>
							    </ul>
							</div>
						</div>
					    <input id="btgTextInput" name="btg" type="text" class="form-control" placeholder="배틀태그를 입력하세요">
					    <div class="input-group-btn">
							<button class="btn btn-secondary" type="submit" id="btgSearchBtn"><i class="glyphicon glyphicon-search"></i></button>
						</div>
				    </div>
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
							</tbody>
						</table>
					</div>
					<div id="mainLabel">
						<div>
							<div><span class="label label-danger">첫방문이세요?</span><span> 배틀태그 영문 '대소문자'를 반드시 구분해서 기입해주세요</span></div>
							<div style="margin-top: 5px"><span class="label label-success">이후 방문부터는</span><span> '대소문자' 그리고 '숫자'없이 간편하게 조회가 가능합니다.</span></div>
						</div>
<!--            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>-->
<!--            <!-- GGEZ-INDEX -->
<!--            <ins class="adsbygoogle"-->
<!--                 style="display:block"-->
<!--                 data-ad-client="ca-pub-1722366924586940"-->
<!--                 data-ad-slot="4401595312"-->
<!--                 data-ad-format="auto"></ins>-->
<!--            <script>-->
<!--              (adsbygoogle = window.adsbygoogle || []).push({});-->
<!--            </script>-->
					</div>
				</div>
			</div>
		</div>
	<script type="text/javascript">

	</script>
</body>
</html>
