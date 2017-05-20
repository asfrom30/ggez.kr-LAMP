<?php

function getDpList(){
	$dpList = array(
			"All" => array("All"=>"요약"),
			"offense" => array("Genji"=>"겐지", "Reaper"=>"리퍼", "McCree"=>"맥크리", "Soldier76"=>"솔져76", "Sombra"=>"솜브라", "Tracer"=>"트레이서", "Pharah"=>"파라"),
			"defense" => array("Mei"=>"메이", "Bastion"=>"바스티온", "Widowmaker"=>"위도우메이커","Junkrat"=>"정크랫","Torbjoern"=>"토르비욘","Hanzo"=>"한조"),
			"tank" => array("DVa"=>"디바", "Reinhardt"=>"라인하르트","Roadhog"=>"로드호그","Winston"=>"윈스턴", "Zarya"=>"자리야"),
			"support" => array("Lucio" => "루시우", "Mercy"=>"메르시","Symmetra" => "시메트라", "Ana"=>"아나","Zenyatta"=>"젠야타"));
	return $dpList;
}