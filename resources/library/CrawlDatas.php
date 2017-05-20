<?php

// Log도 자주 남기고,
// Data 알고리즘도 많이 개선해야할듯
// 이부분 개선 많이 필요..
// 정규식도 잘 모르니 ㅠㅠ


// \preg_replace("/\s+/", "", $string); // 공백제거하기..

// $heroDatas = array();
// foreach ($result[0] as $val) {
// 	echo "<pre>";
// 	print_r($val);
// }

// TODO : ID 루프로 돌려서 가져올것, 루프에 없으면 아직 이 영웅으로 플레이하지 않았습니다.. RETURN NULL
// 영웅 루프로 배열 돌릴것..
// TODO : <td> </td> 태그 가져올것.
// JOSN 으로 저장..

require_once '../../resources/config.php';

require_once (INIT_PATH . "/HeroInfoList.php");
require_once (LIBRARY_PATH  . "/Snoopy/Snoopy.class.php");


function getCrawlDatas($btg){
	$crawlIds = getCrawlIds();
	$crawlDatas = array();
	
	// Snoopy
	$snoopy = new Snoopy ();
	$btg = urlencode($btg);
	$URI = 'https://playoverwatch.com/ko-kr/career/pc/kr/' . $btg;
	$snoopy->fetch($URI);
	$o="";
	$content=$snoopy->results;
	$log = "";
	
	// 페이지를 찾을 수 없습니다....
	
	// 전체 영웅 정보를 가져온다....
	// toekn setup
	$regexCompitive=
	// 해당문자열 검색 : <div id="competitive" data-js="career-category" data-mode="competitive">
	'#<div\s+id="competitive"\s+data-js="career-category"\s+data-mode="competitive">'
			// .*?로 인해 사이에 문자가 있어도 다 찾아낸다.
	. '.*?'
					// 마지막 문자열검색 : <section id="achievements-section" class="content-box
	. '<section\s+id="achievements-section"\s+class="content-box#is';
	// preg_match_all($rex1, $content, $result1, PREG_SET_ORDER);
	
	$matchNum = preg_match($regexCompitive, $content, $coreContent);
	
	// TODO : Error Page 개선필요
	if($matchNum == 0){
		return null;
	}
	
	// coreContent가 전체 영웅에 대한 정보, 경쟁전을 하지 않았으면 하나도 없을 것이다.
	if(count($coreContent) != 1){
		$log = $log . "regetCompitive 이상발생 : " . "Match된 개수 : " . count($coreContent);
	}
	
	// 속도 측정해서 개선하기... LOOP를 한번에 돌면서 (.*?)로 매치하면 더 좋을텐데...
	// 계속 돌면서 일일히 대조함.. ㅜㅜ, 실력부족
	$arrayKey = array_keys($crawlIds);
	foreach ($arrayKey as $hero){
		$crawlId = $crawlIds[$hero];
		// Crawl Key를 이용해 핵심 자료만 뽑아낸다.
		$rawData = getHeroRawData($crawlId, $coreContent);
		
		// 추가사항 Div Category
		$ctgRawDatas = getCtgRawData($rawData);

		// div를 분석한다.
		foreach($ctgRawDatas as $ctgRawData){
			foreach ($ctgRawData as $ctg){
				$category = getCategory($ctg);
				$crawlData = analData($ctg);
				$crawlDatas[$hero][$category] = $crawlData;
			}
		}
	}

	// ETC Data
	$cptPt = getCptPt($content);
	$iconUrl = getIconUrl($content);
// 	$badge = 'bronze';
// 	$badge = getBadge($content);
	$crawlDatas['All']['etcData'] = array( 'cptPt' => $cptPt, 'iconUrl' => $iconUrl);
	
	return $crawlDatas;
}

// 1. Sub Match를 이용하여 개선하기... loop를 계속 돌아야 한다...
function getHeroRawData($crawlId, $coreContent){
	$regexHero=
	//<div data-group-id="stats" data-category-id="0x02E0000000000007" class="row gutter-18@md spacer-12 spacer-18@md js-stats toggle-display">
	'#<div\s+data-group-id="stats"\s+data-category-id="'. $crawlId . '"'
			.'.*?'
			.'</table>\s*</div>\s*</div>\s*</div>#is';

	// 해당 영웅의 data 가져오기.
	preg_match($regexHero, $coreContent[0], $data);

	if(count($data) == 1){
		return $data[0];
	} else {
		// log 남기기
		return null;
	}
}

function getCtgRawData($rawData){
	$regexCtg=
	'#<div\s+class="card-stat-block">'
	.'.*?'
	.'</table>\s*</div>\s*</div>#is';
	
	preg_match_all($regexCtg, $rawData, $data);
	
	return $data;
}

function getCategory($rawData){
	$regexCtg=
	'#<span\s+class="stat-title">'
	.'(.*?)'
	.'</span>#is';		
	
	preg_match($regexCtg, $rawData, $result);
	return $result[1];
}

// div 안쪽 분석하는중.
function analData($rawData){
	$crawlData = array();
	// <td> 가져오는거 말고 바깥쪽에 block을 먼저 가져와서 캡슐로 쌓아서 분석할것. => 1쌍이 되므로 데이터가 하나 비어도 괜찮을듯...
	// 지금 알고리즘에서는 td를 하나만 잘못가져와도 순서대로 정렬이 안되어버린다...
	// 짝수가 아니여도 문제가 된다.
	// ! 부정형이 아니라 정규식을 시작하는 표현
	$mpmatch = "!<td>(.*?)</td>!is";
	preg_match_all($mpmatch, $rawData, $tempData);


	// Array('label' => 'data') 형식
	for($i=0; $i < count($tempData[1]); $i+=2){
		// 		preg_replace("/\s+/", "", $tempData[1][$i]); // 공백제거하기. 안되는데 이상하다...
		$crawlData[$tempData[1][$i]] = $tempData[1][$i+1];
	}

	return $crawlData;

}

function getRawCrawl(){
	// Snoopy
	$snoopy = new Snoopy ();
	$btg = urldecode($btg);
	$URI = "https://playoverwatch.com/ko-kr/career/pc/kr/" . $btg;
	$snoopy->fetch($URI);
	$content=$snoopy->results;
	
	return $content;
}

function getCptPt($content){
	// CoreContent
	// 경쟁전 점수가 들어있는 구문을 먼저 빼고...
	//<div class="competitive-rank"><img src="https://blzgdapipro-a.akamaihd.net/game/rank-icons/season-2/rank-3.png"><div class="u-align-center h6">2118</div></div>
	$regexPoint='#<div\s+class="competitive-rank"><img src='
			    .'.*?'
				.'</div>\s*</div>#is';
	
				
	// 핵심 키 가져오기
	// 해당 영웅의 data 가져오기.
	preg_match($regexPoint, $content, $coreContent);
	
	$result = -1;
	if(count($coreContent) != 0){
		$mpmatch = '!<div class="u-align-center h6">(.*?)</div>!is';
		preg_match($mpmatch, $coreContent[0], $result);
		$result = $result[1];
	}
	
	return $result;
}

function getIconUrl($content){
	//<img src="https://blzgdapipro-a.akamaihd.net/game/unlocks/0x02500000000008F3.png" class="player-portrait">
	$regexPoint='#<img\s+src="https://blzgdapipro-a.akamaihd.net/game/unlocks/'
			.'.*?'
			.'class="player-portrait">#is';

	preg_match($regexPoint, $content, $coreContent);
	
	$result = null;	
	if(count($coreContent) != 0){
		$mpmatch = '#<img\s+src="(.*?)"\s+class="player-portrait">#is';
		preg_match($mpmatch, $coreContent[0], $result);
		$result = $result[1];
	}
	return $result;
}
