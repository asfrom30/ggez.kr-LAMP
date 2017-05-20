<?php

// 세션사용법.
// http://navism.tistory.com/entry/%EC%84%B8%EC%85%98-sessionstart
// http://php.net/manual/kr/session.examples.basic.php
// 변수 등록이 안되나?? 커뮤니티에 물어보자...

// function getHeroList(){
// 	$herolist = array(
// 			"Soldier76","Reinhardt"
// 	);
// 	return $herolist;
// }


function getHeroList(){
	$herolist = array(
			"User", "Soldier76","Reinhardt","Bastion","Symmetra","McCree","Pharah","Reaper","Widowmaker","Tracer","Hanzo","Mercy","Roadhog","Zenyatta",
			"Winston","Genji","Torbjoern","Junkrat","Zarya","DVa","Mei","Ana","Lucio","Sombra"
	);
	return $herolist;
}

function getHeroMap(){
	$heroMap = array(
			"All" => array("crawlKey" => "0x02E00000FFFFFFFF", "dpName" => "", ),
			
			"Reaper" => array("crawlKey" => "0x02E0000000000002", "dpName" => ""),
			"Tracer" => array("crawlKey" => "0x02E0000000000003", "dpName" => ""),
			"Hanzo" => array("crawlKey" => "0x02E0000000000005", "dpName" => ""),
			"Mercy" => array("crawlKey" => "0x02E0000000000004", "dpName" => ""),
			"Torbjoern" => array("crawlKey" => "0x02E0000000000006", "dpName" => ""),
			"Reinhardt" => array("crawlKey" => "0x02E0000000000007", "dpName" => ""),
			"Pharah" => array("crawlKey" => "0x02E0000000000008", "dpName" => ""),
			"Winston" => array("crawlKey" => "0x02E0000000000009", "dpName" => ""),
			"Widowmaker" => array("crawlKey" => "0x02E000000000000A", "dpName" => ""),
			
			"Bastion" => array("crawlKey" => "0x02E0000000000015", "dpName" => "" ),
			"Symmetra" => array("crawlKey" => "0x02E0000000000016", "dpName" => ""),
			
			"Zenyatta" => array("crawlKey" => "0x02E0000000000020", "dpName" => ""),
			"Genji" => array("crawlKey" => "0x02E0000000000029", "dpName" => ""),
			
			"Roadhog" => array("crawlKey" => "0x02E0000000000040", "dpName" => ""),
			"McCree" => array("crawlKey" => "0x02E0000000000042", "dpName" => ""),
			
			"Junkrat" => array("crawlKey" => "0x02E0000000000065", "dpName" => ""),
			"Zarya" => array("crawlKey" => "0x02E0000000000068", "dpName" => ""),
			"Soldier76" => array("crawlKey" => "0x02E000000000006E", "dpName" => "" ),
			
			"Lucio" => array("crawlKey" => "0x02E0000000000079", "dpName" => ""),
			"DVa" => array("crawlKey" => "0x02E000000000007A", "dpName" => ""),
			
			"Mei" => array("crawlKey" => "0x02E00000000000DD", "dpName" => ""),
			"Sombra" => array("crawlKey" => "0x02E000000000012E", "dpName" => ""),
			"Ana" => array("crawlKey" => "0x02E000000000013B", "dpName" => "")
	);
	return $heroMap;
}

// HTTP RESPONSE 형식
function statMap(){
	$statMap = array(
			"statLabels" => $statLabels,
			// 		"dpNames" => $dpNames,			//필요에 따라서 할지 말지..
			"btg1" => $statDatas, // Player의 모든 영웅의 점수...
			"btg2" => $statDatas,
	);
	return $statMap;
}
// label들..
$statLabels = array(
		"Soldier76" => array("전술조준경처치", "평균딜량", "죽음", "K/D", "ETC"),
);

// Player의 모든 영웅의 점수...
$statDatas = array(
		"Soldier76" => array(20, 40, 50, 10, 10),
		"Reinhardt" => array(50, 60, 30, 20, 10),
);

function getGrades(){
	$grades=array(
			'kda' => array(
				'dealer' => array(0, 0, 0, 0, 5.0),
				'offense' => array(),
				'defense' => array(),
				'support' => array(0, 0, 0, 0, 3.0)
			),
			'deal' => array(
				'dealer' => array(500, 1100, 1400, 1700, 2000),
				'offense' => array(800, 1100, 1400, 1700, 2000),
				'defense' => array(800, 1100, 1400, 1700, 2000),
				'support' => array(800, 1100, 1400, 1700, 2000)
			),
			// 명중율
			'target' => array(
				'dealer' => array(0, 30, 40, 50, 70),
				'offense' => array(0, 30, 40, 50, 70),
				'defense' => array(),
				'support' => array()
			),
			// 헤드샷
			'head' => array(
				'dealer' => array(0, 4, 6, 8, 10),
				'offense' => array(0, 4, 6, 8, 7),
				'defense' => array(),
				'support' => array()
			),
			
			// 치명타 / 마무리
			'finish' => array(
				'dealer' => array(0, 4, 6, 8, 10),
				'offense' => array(0, 4, 6, 8, 1),
				'defense' => array(),
				'support' => array()
			),
	);
	
	return $grades;
}

function getDescription($label){
	switch ($label){
		case '명중율'		: return '명중/발사된 탄환';
		case '치명타'		: return '치명타(헤드샷)/명중된 탄환';
		case '결정타'		: return '결정타(마무리)/명중된 탄환';
		
		case '피해량'		: return '한 목숨으로 적에게 가한 피해';
		case 'KDA'		: return "한 목숨으로 적을 처치한 회수";
		
		
		//바스티온
		case '자가치유량' 	: return "게임당 자가치유량";
		case '경계처치'	: return "게임당 경계모드로 처치한 횟수";
		case '궁극활용'	: return "게임당 궁극(전차모드)로 처치한 횟수";
		
		//맥크리
		case '피스키퍼'	: return "게임당 피스키퍼로 처치한 횟수";
		case '석양'	: return "게임당 황야의 무법자로 처치한 횟수";
	}
}

// case ''	: return "";

function getStatMap(){
	
	$grades = getGrades();
	$statMap =	array(
			
			
			//type1 Data 존재, type2 Data 존재x
			"All" => array(
					array( "label" => "금메달", "unit" => "", "factor" => array(2, "메달 - 금", "치른 게임"), "grade"=> array(0,0,0,0,2)), // (명중/발사))
					array( "label" => "은메달", "unit" => "", "factor" => array(2, "메달 - 은", "치른 게임"), "grade"=> array(0, 4, 6, 8, 2)),
					array( "label" => "동메달", "unit" => "", "factor" => array(2, "메달 - 동", "치른 게임"), "grade"=> array(0, 4, 6, 8, 2)), // (마무리/명중)
						
					array( "label" => "딜량(%)", "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(800, 1100, 1400, 1700, 2000)), // (준 피해/목숨당)
					array( "label" => "KDA(%)", "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(1.0, 3.5, 4.0, 4.5, 5.0)), // (준 피해/목숨당)
					
					// 폭주시간
					// 임무기여시간
			),
			//솔져76
			"Soldier76" => array(								// 분자, 분모, 기준데이터, (단위 (보정수치랑100, 뒤에 라벨팩터%)팩터 넣는게 좋겠다.) label/factor를 따로 만들기.. 자꾸 index num이 바뀌네.
					array( "label" => "명중율", "description" => getDescription("명중율"), "unit" => "%", "factor" => array(1, "명중", "발사", "명중률"), "grade"=> array(0,20,30,40,50)), // (명중/발사))
					array( "label" => "치명타", "description" => getDescription('치명타'), "unit" => "%", "factor" => array(1, "치명타", "명중", "치명타 명중률"), "grade"=> array(0, 4, 6, 8, 10)),
					array( "label" => "결정타", "description" => getDescription('결정타'), "unit" => "%", "factor" => array(2, "결정타","명중"), "grade"=> array(0, 2, 3, 4, 5)), // (마무리/명중)
					
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0, 1000, 2000, 3000, 3000)), // (준 피해/목숨당)
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0, 3.5, 4.0, 4.5, 5.0)), // (준 피해/목숨당)
					
					array( "label" => "생체장", "description" =>"게임당 치유량", "unit" => "", "factor" => array(2, "생체장으로 치유","치른 게임"), "grade"=> array(0, 1500, 2000, 2500,3000)), // 
					array( "label" => "나선로켓", "description" =>"게임당 나선로켓으로 처치 횟수", "unit" => "", "factor" => array(1, "나선 로켓으로 처치", "치른 게임", "나선 로켓으로 처치 - 평균"), "grade"=> array(0, 6, 8, 10, 10)), // 
					array( "label" => "궁극", "description" =>"게임당 전술조준경으로 처치 횟수", "unit" => "", "factor" => array(1, "전술 조준경으로 처치", "치른 게임", "전술 조준경으로 처치 - 평균"), "grade"=> array(0, 4, 6, 8, 10)) //
			),
			//바스티온(NED)
			"Bastion" => array(
					array( "label" => "명중율", "description" => getDescription("명중율"), "unit" => "%", "factor" => array(1, "명중", "발사", "명중률"), "grade"=> array(0,25,30,35,50)), // (명중/발사))
					array( "label" => "치명타", "description" => getDescription('치명타'), "unit" => "%", "factor" => array(1, "치명타", "명중", "치명타 명중률"), "grade"=> array(0, 4, 6, 8, 10)),
					array( "label" => "결정타", "description" => getDescription('결정타'), "unit" => "%", "factor" => array(2, "결정타","명중"), "grade"=> array(0, 1, 2, 3, 5)), // (마무리/명중)
			
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0, 1100, 1400, 1700, 4000)), // (준 피해/목숨당)
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0, 3.5, 4.0, 4.5, 5)), // (준 피해/목숨당)
			
					array( "label" => "자가치유량", "description" => getDescription('자가치유량'), "unit" => "", "factor" => array(2, "자가 치유","치른 게임"), "grade"=> array(0, 700, 900, 1100, 1300)), //
					array( "label" => "경계처치", "description" =>getDescription('경계처치'), "unit" => "", "factor" => array(1, "경계 모드로 처치", "치른 게임", "경계 모드로 처치 - 평균"), "grade"=> array(0, 5, 10, 15, 20)), //
					array( "label" => "궁극활용", "description" =>getDescription('궁극활용'), "unit" => "", "factor" => array(1, "전차 모드로 처치", "치른 게임", "전차 모드로 처치 - 평균"), "grade"=> array(0, 2, 4, 6, 8)) //
			),
			//
			"McCree" => array(
					array( "label" => "명중율", "description" => getDescription("명중율"), "unit" => "%", "factor" => array(1, "명중", "발사", "명중률"), "grade"=> array(0,20,30,40,60)), // (명중/발사))
					array( "label" => "치명타", "description" => getDescription('치명타'), "unit" => "%", "factor" => array(1, "치명타", "명중", "치명타 명중률"), "grade"=> array(0, 4, 6, 8, 10)),
					array( "label" => "결정타", "description" => getDescription('결정타'), "unit" => "%", "factor" => array(2, "결정타","명중"), "grade"=> array(0, 2, 3, 4, 10)), // (마무리/명중)
						
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"),"grade"=> array(0, 1000, 2000, 3000, 2000)),
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0, 3.5, 4.0, 4.5, 5)), // (준 피해/목숨당)
						
					array( "label" => "피스키퍼", "description" =>getDescription('피스키퍼'), "unit" => "", "factor" => array(1, "피스키퍼 난사로 처치","치른 게임","피스키퍼 난사로 처치 - 평균"), "grade"=> array(0, 4, 6, 8, 5)), //
					array( "label" => "석양", "description" =>getDescription('석양'), "unit" => "", "factor" => array(1, "황야의 무법자로 처치", "치른 게임", "황야의 무법자로 처치 - 평균"), "grade"=> array(0, 4, 6, 8, 5)) //
			),
			
			//리퍼
			"Reaper" => array(
					array( "label" => "명중율", "description" => getDescription("명중율"), "unit" => "%", "factor" => array(1, "명중", "발사", "명중률"), "grade"=> array(10,20,30,40,50)), // (명중/발사))
					array( "label" => "치명타", "description" => getDescription('치명타'), "unit" => "%", "factor" => array(1, "치명타", "명중", "치명타 명중률"), "grade"=> array(2, 4, 6, 8, 10)),
					array( "label" => "결정타", "description" => getDescription('결정타'), "unit" => "%", "round"=>"3", "factor" => array(2, "결정타","명중"), "grade"=> array(0, 2, 3, 4, 2)), // (마무리/명중)
					
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0, 1000, 2000, 3000, 4000)), // (준 피해/목숨당)
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0, 3.5, 4.0, 4.5, 5.0)), // (준 피해/목숨당)
					
					array( "label" => "거둔영혼", "description" => "한 게임당 거둔영혼", "unit" => "", "factor" => array(1, "거둔 영혼","치른 게임", "거둔 영혼 - 평균"), "grade"=> array(0, 4, 6, 8, 30)), //
					array( "label" => "궁극", "description" =>"한 게임당 죽음의 꽃으로 처치", "unit" => "", "factor" => array(1, "죽음의 꽃으로 처치", "치른 게임", "죽음의 꽃으로 처치 - 평균"), "grade"=> array(0, 4, 6, 8, 10)) //
			),
			"Pharah" => array(
					array( "label" => "명중", "description" =>"피해가 들어간 횟수/로켓 발사 횟수", "unit" => "%", "factor" => array(1, "명중", "발사", "명중률"), "grade"=> array(0, 1000, 2000, 3000, 70)), // (명중/발사))
					array( "label" => "결정타", "description" =>"결정타(마무리)/피해가 들어간 횟수", "unit" => "%", "factor" => array(2, "결정타","명중"), "grade"=> array(0, 2, 3, 4, 14)), // (마무리/명중)
			
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0, 1000, 2000, 3000, 3000)), // (준 피해/목숨당)
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(1.0, 3.5, 4.0, 4.5, 5.0)), // (준 피해/목숨당)
			
					array( "label" => "낙사", "description" =>"한 게임당 환경 요소로 처치", "unit" => "", "factor" => array(2, "환경 요소로 처치","치른 게임"), "grade"=> array(0, 0.2, 0.4, 0.6, 3)), //
					array( "label" => "직격", "description" =>"한 게임당 로켓을 직격으로 맞춘 횟수", "unit" => "", "factor" => array(1, "로켓 명중","치른 게임","로켓 명중 - 평균"), "grade"=> array(0, 4, 6, 30, 100)), //
					array( "label" => "궁극", "description" =>"한 게임당 포화로 처치", "unit" => "", "factor" => array(1, "포화로 처치", "치른 게임", "포화로 처치 - 평균"), "grade"=> array(0, 4, 6, 8, 7)) //
			),
			
			//라인하르트
			"Reinhardt" =>  array(
					array( "label"=> "유지력", "description" => "죽음당 막은 피해", "unit" => "", "factor" => array(2, "막은 피해", "죽음"), "grade"=> array(0,2500,3000,3500,5000)),
					array( "label"=> "막은피해", "description" => "한 게임당 막은 피해", "unit" => "", "factor" => array(1, "막은 피해", "치른 게임", "막은 피해 - 평균"), "grade"=> array(0,11000,14000,17000,30000)),		// 게임당 막은피해
					
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0, 1100, 1400, 1700, 1000)), // (준 피해/목숨당)
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0, 3.5, 4.0, 4.5, 3.0)), // (준 피해/목숨당)

					array( "label" => "돌진", "description" => "한 게임당 돌진으로 처치", "unit" => "", "factor" => array(1, "돌진으로 처치", "치른 게임", "돌진으로 처치 - 평균"), "grade"=> array(1.0, 3.0, 3.5, 4.0, 4.5)), //
					array( "label" => "화염", "description" => "한 게임당 화염강타로 처치", "unit" => "", "factor" => array(1, "화염 강타로 처치", "치른 게임", "화염 강타로 처치 - 평균"), "grade"=> array(1.0, 6.5, 7.0, 7.5, 8.0)), //
					array( "label" => "궁극", "description" => "한 게임당 대지분쇄로 처치", "unit" => "", "factor" => array(1, "대지분쇄로 처치", "치른 게임", "대지분쇄로 처치 - 평균"), "grade"=> array(1.0, 4.5, 5.0, 5.5, 6.0)), //
			),
			
			// 	teamJOKER-31226
			"Roadhog" => array(
					array( "label" => "명중율", "description" => getDescription("명중율"), "unit" => "%", "factor" => array(1, "명중", "발사", "명중률"), "grade"=> array(0, 3.5, 4.0, 4.5, 50)), // (명중/발사))
					array( "label" => "치명타", "description" => getDescription('치명타'), "unit" => "%", "factor" => array(1, "치명타", "명중", "치명타 명중률"), "grade"=> array(0, 3.5, 4.0, 4.5, 20)),
					array( "label" => "결정타", "description" => getDescription('결정타'), "unit" => "%", "round" => "4", "factor" => array(2, "결정타","명중"), "grade"=> array(0, 3.5, 4.0, 4.5, 2)), // (마무리/명중)
			
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0, 3.5, 4.0, 4.5, 3000)),
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0, 3.5, 4.0, 4.5, 5)), // (준 피해/목숨당)
			
					array( "label" => "갈고리", "description" => "갈고리 성공/갈고리 총 시도", "unit" => "%", "factor" => array(1, "갈고리로 끌어오기","갈고리 시도","갈고리 명중률"), "grade"=> array(0, 4, 6, 8, 50)), //
					array( "label" => "궁극", "description" => "한 게임당 돼재앙으로 처치", "unit" => "", "factor" => array(1, "돼재앙으로 처치", "치른 게임", "돼재앙으로 처치 - 평균"), "grade"=> array(0, 4, 6, 8, 5))
			),
			//
			"Winston" => array(
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0, 3.5, 4.0, 4.5, 1200)),
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0, 3.5, 4.0, 4.5, 5)), // (준 피해/목숨당)
			
					array( "label" => "막은피해", "description" =>"한 게임당 막은피해", "unit" => "", "factor" => array(1, "막은 피해", "치른 게임", "막은 피해 - 평균"), "grade"=> array(4000, 4, 6, 8, 12000)), //
					array( "label" => "점프팩처치", "description" => "한 게임당 점프팩으로 처치", "unit" => "", "factor" => array(1, "점프 팩으로 처치","치른 게임","점프 팩으로 처치 - 평균"), "grade"=> array(0, 4, 6, 8, 10)), //
					array( "label" => "궁극", "description" => "한 게임당 원시의 분노로 처치", "unit" => "", "factor" => array(1, "원시의 분노로 처치", "치른 게임", "원시의 분노로 처치 - 평균"), "grade"=> array(0, 4, 6, 8, 5)) //
			),
			
			//PiKq-11189
			"DVa" => array(
					array( "label" => "명중율", "description" => getDescription("명중율"), "unit" => "%", "factor" => array(1, "명중", "발사", "명중률"), "grade"=> array(0, 3.5, 4.0, 4.5, 50)), // (명중/발사))
					array( "label" => "치명타", "description" => getDescription('치명타'), "unit" => "%", "factor" => array(1, "치명타", "명중", "치명타 명중률"), "grade"=> array(2, 4, 6, 8, 10)),
					array( "label" => "결정타", "description" => getDescription('결정타'), "unit" => "%", "round" => "4", "factor" => array(2, "결정타", "명중"), "grade"=> array(0, 2, 3, 4, 1)), // (마무리/명중)
			
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0, 1000, 2000, 3000, 3000)),
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0, 3.5, 4.0, 4.5, 6.0)), // (준 피해/목숨당)
			
					array( "label" => "막은피해 ", "description" =>"한 게임당 막은 피해", "unit" => "", "factor" => array(1, "막은 피해","치른 게임","막은 피해 - 평균"), "grade"=> array(0, 4, 6, 8, 12000)), //
					array( "label" => "로봇호출 ", "description" =>"한 게임당 호출한 로봇", "unit" => "", "factor" => array(1, "호출한 로봇","치른 게임","호출한 로봇 - 평균"), "grade"=> array(0, 4, 6, 8, 10)), //
					array( "label" => "자폭", "description" =>"한 게임당 자폭으로 처치", "unit" => "", "factor" => array(1, "자폭으로 처치", "치른 게임", "자폭으로 처치 - 평균"), "grade"=> array(0, 3, 4, 5, 5))
			),
			// Fireweed-3726
			"Symmetra" => array(
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0, 1000, 2000, 3000, 1500)),
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0, 1000, 2000, 3000, 5)), // (준 피해/목숨당)
					
					array( "label" => "감시포탑", "description" => "한 게임당 감시포탑으로 처치", "unit" => "", "factor" => array(1, "감시 포탑으로 처치","치른 게임","감시 포탑으로 처치 - 평균"), "grade"=> array(2, 4, 6, 8, 10)), //
					array( "label" => "순간이동 시간", "description" => "한 게임당 순간이동기 가동 시간",  "unit" => "", "factor" => array(2, "순간이동기 가동 시간", "치른 게임"), "grade"=> array(0, 4, 6, 8, 250)), //
					array( "label" => "순간이동 횟수", "description" => "한 게임당 순간이동 사용 플레이어",  "unit" => "", "factor" => array(1, "순간이동한 플레이어", "치른 게임", "순간이동한 플레이어 - 평균"), "grade"=> array(0, 4, 6, 8, 10)) //
			),
			
			"Widowmaker" => array(
					array( "label" => "명중율", "description" => getDescription("명중율"), "unit" => "%", "factor" => array(1, "명중", "발사", "명중률"), "grade"=> array(0,20,30,40,50)), // (명중/발사))
					array( "label" => "저격명중", "description" => "저격명중/저격발사", "unit" => "%", "factor" => array(1, "저격 명중", "저격 발사", "저격 명중률"), "grade"=> array(0, 4, 6, 8, 70)), // (명중/발사))
					array( "label" => "결정타", "description" => getDescription('결정타'), "unit" => "%", "factor" => array(2, "결정타","명중"), "grade"=> array(0, 2, 3, 4, 15)), // (마무리/명중)
						
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0, 1000, 2000, 3000, 2000)), // (준 피해/목숨당)
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0, 3.5, 4.0, 4.5, 5.0)), // (준 피해/목숨당)
					
			),
			"Tracer" => array(
					array( "label" => "명중율", "description" => getDescription("명중율"), "unit" => "%", "factor" => array(1, "명중", "발사", "명중률"), "grade"=> array(0,20,30,40,50)), // (명중/발사))
					array( "label" => "치명타", "description" => getDescription('치명타'), "unit" => "%", "factor" => array(1, "치명타", "명중", "치명타 명중률"), "grade"=> array(0, 4, 6, 8, 11)),
					array( "label" => "결정타", "description" => getDescription('결정타'), "round" => "3", "unit" => "%", "factor" => array(2, "결정타","명중"), "grade"=> array(0, 2, 3, 4, 2)), // (마무리/명중)
					
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0, 1000, 2000, 3000, 3000)), // (준 피해/목숨당)
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0, 3.5, 4.0, 4.5, 5.0)), // (준 피해/목숨당)
					
					array( "label" => "펄스폭탄", "description" =>"게임당 펄스폭탄으로 처치 횟수", "unit" => "", "factor" => array(1, "펄스 폭탄으로 처치", "치른 게임", "펄스 폭탄으로 처치 - 평균"), "grade"=> array(0, 4, 6, 8, 7)) //
			),
			"Hanzo" => array(
					array( "label" => "명중율", "description" => getDescription("명중율"), "unit" => "%", "factor" => array(1, "명중", "발사", "명중률"), "grade"=> array(0,20,30,40,50)), // (명중/발사))
					array( "label" => "치명타", "description" => getDescription('치명타'), "unit" => "%", "factor" => array(1, "치명타", "명중", "치명타 명중률"), "grade"=> array(0, 4, 6, 8, 10)),
					array( "label" => "결정타", "description" => getDescription('결정타'), "unit" => "%", "factor" => array(2, "결정타","명중"), "grade"=> array(0, 2, 3, 4, 15)), // (마무리/명중)
				
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0, 1000, 2000, 3000, 1500)), // (준 피해/목숨당)
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0, 3.5, 4.0, 4.5, 5.0)), // (준 피해/목숨당)
						
					array( "label" => "갈래 화살", "description" =>"게임당 갈래화살로 처치","unit" => "", "factor" => array(1, "갈래 화살로 처치","치른 게임","갈래 화살로 처치 - 평균"), "grade"=> array(0, 4, 6, 8, 10)), //
					array( "label" => "궁극", "description" =>"게임당 용의 일격으로 처치", "unit" => "", "factor" => array(1, "용의 일격으로 처치", "치른 게임", "용의 일격으로 처치 - 평균"), "grade"=> array(0, 4, 6, 8, 5)) //
			),
// 			Lime#32473
			"Mercy" => array(
					array( "label" => "명중율", "description" => getDescription("명중율"), "unit" => "%", "factor" => array(1, "명중", "발사", "명중률"), "grade"=> $grades['target']['dealer']), // (명중/발사))
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0, 4, 6, 8, 0.5)), // (준 피해/목숨당)
					
					array( "label" => "공격버프", "description" =>"한 게임당 공격형 도움", "unit" => "", "factor" => array(1, "공격형 도움","치른 게임","공격형 도움 - 평균"), "grade"=> array(0, 4, 6, 8, 10)), //
					
					array( "label" => "치유지속", "description" =>"한 목숨당 치유량", "unit" => "", "factor" => array(2, "치유","죽음"), "grade"=> array(0, 4, 6, 8, 1600)), //
					array( "label" => "치유량", "description" =>"한 게임당 치유량", "unit" => "", "factor" => array(1, "치유","치른 게임","치유 - 평균"), "grade"=> array(0, 4, 6, 8, 15000)), //
					
					array( "label" => "부활", "description" => "한 게임당 부활한 플레이어", "unit" => "", "factor" => array(1, "부활한 플레이어", "치른 게임", "부활한 플레이어 - 평균"), "grade"=> array(0, 4, 6, 8, 10)) //
			),
			
// 			Yesclip-1562
			"Zenyatta" => array(
					array( "label" => "명중율", "description" => getDescription("명중율"), "unit" => "%", "factor" => array(1, "명중", "발사", "명중률"), "grade"=> array(0,20,30,40,40)), // (명중/발사))
					array( "label" => "치명타", "description" => getDescription('치명타'), "unit" => "%", "factor" => array(1, "치명타", "명중", "치명타 명중률"), "grade"=> array(0, 4, 6, 8, 10)),
					array( "label" => "결정타", "description" => getDescription('결정타'), "unit" => "%", "factor" => array(2, "결정타","명중"), "grade"=> array(0, 2, 3, 4, 5)), // (마무리/명중)
					
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0, 1000, 2000, 3000, 2000)),
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0, 3.5, 4.0, 4.5, 3)), // (준 피해/목숨당)
					
					array( "label" => "부조화 도움", "description" =>"한 게임당 부조화로 도움", "unit" => "", "factor" => array(1, "공격형 도움", "치른 게임", "공격형 도움 - 평균"), "grade"=> array(0, 4, 6, 8, 25)), //
					array( "label" => "조화 도움", "description" =>"한 게임당 조화로 도움", "unit" => "", "factor" => array(1, "방어형 도움","치른 게임","방어형 도움 - 평균"), "grade"=> array(0, 4, 6, 8, 25)), //
					array( "label" => "초월치유", "description" =>"한 게임당 초월로 치유", "unit" => "", "factor" => array(2, "초월로 치유", "치른 게임"), "grade"=> array(0, 4, 6, 8, 3000)) //
			),
			"Genji" => array(
					array( "label" => "명중율", "description" => getDescription("명중율"), "unit" => "%", "factor" => array(1, "명중", "발사", "명중률"), "grade"=> array(0,20,30,40,50)), // (명중/발사))
					array( "label" => "치명타", "description" => getDescription('치명타'), "unit" => "%", "factor" => array(1, "치명타", "명중", "치명타 명중률"), "grade"=> array(0, 4, 6, 8, 10)),
					array( "label" => "결정타", "description" => getDescription('결정타'), "unit" => "%", "factor" => array(2, "결정타","명중"), "grade"=> array(0, 2, 3, 4, 7)), // (마무리/명중)
					
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0, 1000, 2000, 3000, 3000)), // (준 피해/목숨당)
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0, 3.5, 4.0, 4.5, 5.0)), // (준 피해/목숨당)
					
					array( "label" => "튕겨낸 피해", "description" =>"게임당 튕겨낸 피해", "unit" => "", "factor" => array(1, "튕겨낸 피해","치른 게임","튕겨낸 피해 - 평균"), "grade"=> array(0, 4, 6, 8, 2500)), //
					array( "label" => "용검", "description" =>"게임당 용검으로 처치", "unit" => "", "factor" => array(1, "용검으로 처치", "치른 게임", "용검으로 처치 - 평균"), "grade"=> array(0, 4, 6, 8, 10))
			),
// 			swisrolish-1126
			"Torbjoern" => array(
					array( "label" => "명중율", "description" => getDescription("명중율"), "unit" => "%", "factor" => array(1, "명중", "발사", "명중률"), "grade"=> array(0,20,30,40,50)), // (명중/발사))
					array( "label" => "치명타", "description" => getDescription('치명타'), "unit" => "%", "factor" => array(1, "치명타", "명중", "치명타 명중률"), "grade"=> array(0, 4, 6, 8, 10)),
					array( "label" => "결정타", "description" => getDescription('결정타'), "unit" => "%", "factor" => array(2, "결정타","명중"), "grade"=> array(0, 2, 3, 4, 5)), // (마무리/명중)
	
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0, 1000, 2000, 3000, 3000)), // (준 피해/목숨당)
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0, 3.5, 4.0, 4.5, 5.0)), // (준 피해/목숨당)
	
					array( "label" => "직접처치", "description" =>"게임당 토르비욘이 직접처치", "unit" => "", "factor" => array(1, "토르비욘이 직접 처치","치른 게임","토르비욘이 직접 처치 - 평균"), "grade"=> array(0, 4, 6, 8, 10)), //
					array( "label" => "포탑처치", "description" =>"게임당 토르비욘이 포탑처치", "unit" => "", "factor" => array(1, "포탑으로 처치","치른 게임","포탑으로 처치 - 평균"), "grade"=> array(0, 4, 6, 8, 20)), //
					array( "label" => "궁극", "description" =>"게임당 초고열 용광로로 처치", "unit" => "", "factor" => array(1, "초고열 용광로로 처치", "치른 게임", "초고열 용광로로 처치 - 평균"), "grade"=> array(0, 4, 6, 8, 5))
			),
// 			swisrolish-1126
			// Meijin-11555
			"Junkrat" => array(
					array( "label" => "명중율", "description" => getDescription("명중율"), "unit" => "%", "factor" => array(1, "명중", "발사", "명중률"), "grade"=> array(0,20,30,40,50)), // (명중/발사))
					array( "label" => "결정타", "description" => getDescription('결정타'), "unit" => "%", "factor" => array(2, "결정타","명중"), "grade"=> array(0, 2, 3, 4, 20)), // (마무리/명중)
						
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0, 1000, 2000, 3000, 3000)), // (준 피해/목숨당)
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0, 3.5, 4.0, 4.5, 5.0)), // (준 피해/목숨당)
						
					array( "label" => "덫", "description" =>"게임당 덫에 걸린 적", "unit" => "", "factor" => array(2, "덫에 걸린 적","치른 게임"), "grade"=> array(0, 4, 6, 8, 15)), //
					array( "label" => "궁극", "description" =>"게임당 죽이는 타이어로 처치", "unit" => "", "factor" => array(1, "죽이는 타이어로 처치", "치른 게임", "죽이는 타이어로 처치 - 평균"), "grade"=> array(2, 4, 6, 8, 10))
			),
			"Zarya" => array(
					array( "label" => "명중율", "description" => getDescription("명중율"), "unit" => "%", "factor" => array(1, "명중", "발사", "명중률"), "grade"=> array(0,20,30,40,50)), // (명중/발사))
					array( "label" => "결정타", "description" => getDescription('결정타'), "unit" => "%", "factor" => array(2, "결정타","명중"), "grade"=> array(0,20,30,40,15)), // (마무리/명중)
					
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0,20,30,40,2500)),
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0,20,30,40,5)), // (준 피해/목숨당)
					
					array( "label" => "막은 피해", "description" =>"게임당 막은 피해", "unit" => "", "factor" => array(1, "막은 피해", "치른 게임","막은 피해 - 평균"), "grade"=> array(0, 4, 6, 8, 8000)), //
					array( "label" => "고에너지", "description" =>"게임당 고에너지로 처치", "unit" => "", "factor" => array(1, "고에너지로 처치", "치른 게임","고에너지로 처치 - 평균"), "grade"=> array(0, 4, 6, 8, 20)), //
					array( "label" => "중력자탄", "description" =>"게임당 중력자탄으로 처치", "unit" => "", "factor" => array(1, "중력탄으로 처치 누적기록", "치른 게임", "중력탄으로 처치 - 평균"), "grade"=> array(0, 4, 6, 8, 10))
			),
			//Momoco-4718
			"Mei" => array(
					array( "label" => "명중율", "description" => getDescription("명중율"), "unit" => "%", "factor" => array(1, "명중", "발사", "명중률"), "grade"=> array(0,20,30,40,50)), // (명중/발사))
					array( "label" => "치명타", "description" => getDescription('치명타'), "unit" => "%", "factor" => array(1, "치명타", "명중", "치명타 명중률"), "grade"=> array(0, 4, 6, 8, 20)),
					array( "label" => "결정타", "description" => getDescription('결정타'), "unit" => "%", "factor" => array(2, "결정타","명중"), "grade"=> array(0, 2, 3, 4, 20)), // (마무리/명중)
						
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0, 1000, 2000, 3000, 1500)), // (준 피해/목숨당)
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0, 3.5, 4.0, 4.5, 5)), // (준 피해/목숨당)
					
					array( "label" => "얼린 적 ", "description" => "한 게임당 얼린 적", "unit" => "", "factor" => array(1, "얼린 적","치른 게임","얼린 적 - 평균"), "grade"=> array(0, 4, 6, 8, 20)), //
					array( "label" => "막은 피해 ", "description" => "한 게임당 막은 피해", "unit" => "", "factor" => array(1, "막은 피해","치른 게임","막은 피해 - 평균"), "grade"=> array(0, 4, 6, 8, 10000)), //
					array( "label" => "궁극",  "description" => "한 게임당 눈보라로 처치", "unit" => "", "factor" => array(1, "눈보라로 처치", "치른 게임", "눈보라로 처치 - 평균"), "grade"=> array(2, 3, 4, 5, 6))
			),
			
			//G0LFK4RT-3243
			"Ana" => array(
					array( "label" => "명중율", "description" => getDescription("명중율"), "unit" => "%", "factor" => array(1, "명중", "발사", "명중률"), "grade"=> array(0, 4, 6, 8, 20)), // (명중/발사))
					array( "label" => "저격 명중", "description" => "저격명중/저격발사", "unit" => "%", "factor" => array(1, "저격 명중", "저격 발사", "저격 명중률"), "grade"=> array(0, 4, 6, 8, 70)), // (명중/발사))
					array( "label" => "결정타", "description" => getDescription('결정타'), "unit" => "%", "factor" => array(2, "결정타","명중"), "grade"=> array(0, 4, 6, 8, 10)), // (마무리/명중)
					
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0, 1000, 2000, 3000, 1000)),
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0, 3.5, 4.0, 4.5, 3.0)), // (준 피해/목숨당)
					
					array( "label" => "치유량", "description" =>"게임당 치유량", "unit" => "", "factor" => array(1, "치유","치른 게임","치유 - 평균"), "grade"=> array(0, 4, 6, 8, 11000)), //
					array( "label" => "강화제", "description" =>"게임당 나노 강화제로 도움", "unit" => "", "factor" => array(1, "나노 강화제 도움", "치른 게임", "나노 강화제 도움 - 평균"), "grade"=> array(0, 4, 6, 8, 7))
			),

			"Lucio" => array(
					array( "label" => "명중율", "description" => getDescription("명중율"), "unit" => "%", "factor" => array(1, "명중", "발사", "명중률"), "grade"=> array(0,20,30,40,40)), // (명중/발사))
						
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0,20,30,40,800)),
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0,20,30,40,3)), // (준 피해/목숨당)
						
					array( "label" => "치유지속", "description" =>"한 목숨당 치유량", "unit" => "", "factor" => array(2, "치유","죽음"), "grade"=> array(0, 4, 6, 8, 1600)), //
					array( "label" => "치유량", "description" =>"게임당 치유량", "unit" => "", "factor" => array(1, "치유","치른 게임","치유 - 평균"), "grade"=> array(2, 4, 6, 8, 15000)),
					
					array( "label" => "환경요소", "description" =>"게임당 환경 요소로 처치", "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0,20,30,40,2.5)), // (준 피해/목숨당)
					array( "label" => "소리방벽", "description" =>"게임당 소리방벽 제공", "unit" => "", "factor" => array(1, "소리 방벽 제공", "치른 게임", "소리 방벽 제공 - 평균"), "grade"=> array(2, 4, 6, 8, 20))
			),
// 			ChapSsalDduk-3497
// 			NED
			"Sombra" => array(
					array( "label" => "명중율", "description" => getDescription("명중율"), "unit" => "%", "factor" => array(1, "명중", "발사", "명중률"), "grade"=> array(0,20,30,40,50)), // (명중/발사))
					array( "label" => "치명타", "description" => getDescription('치명타'), "unit" => "%", "factor" => array(1, "치명타", "명중", "치명타 명중률"), "grade"=> array(0, 4, 6, 8, 10)),
					array( "label" => "결정타", "description" => getDescription('결정타'), "unit" => "%", "factor" => array(2, "결정타","명중"), "grade"=> array(0, 2, 3, 4, 5)), // (마무리/명중)
				
					array( "label" => "피해량", "description" =>getDescription('피해량'), "unit" => "", "factor" => array(2, "준 피해","죽음"), "grade"=> array(0, 1000, 2000, 3000, 3000)), // (준 피해/목숨당)
					array( "label" => "KDA", "description" =>getDescription('KDA'), "unit" => "", "factor" => array(2, "처치","죽음"), "grade"=> array(0, 3.5, 4.0, 4.5, 5.0)), // (준 피해/목숨당)
								
					array( "label" => "해킹", "description" =>"게임당 해킹한 적", "unit" => "", "factor" => array(1, "해킹한 적", "치른 게임", "해킹한 적 - 평균"), "grade"=> array(0, 4, 6, 8, 25)), //
					array( "label" => "궁극", "description" =>"게임당 EMP 맞힌 적", "unit" => "", "factor" => array(1, "EMP 맞힌 적", "치른 게임", "EMP 맞힌 적 - 평균"), "grade"=> array(0, 4, 6, 8, 18)) //
			)
	);

	return $statMap;
}

//type1 Data 존재, type2 Data 존재x

function getCrawlIds(){
	$heroMap = getHeroMap();
	$arrayKey = array_keys($heroMap);
	$result = array();
	foreach ($arrayKey as $hero){
		$result[$hero] = $heroMap[$hero]["crawlKey"];
	}
	return $result;
}



