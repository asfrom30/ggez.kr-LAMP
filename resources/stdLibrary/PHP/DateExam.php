<?php 

/*
 //오늘 날짜 출력 ex) 2013-04-10
 $today_date = date('Y-m-d');
 //오늘의 요일 출력 ex) 수요일 = 3
 $day_of_the_week = date('w');
 //오늘의 첫째주인 날짜 출력 ex) 2013-04-07 (일요일임)
 $a_week_ago = date('Y-m-d', strtotime($date." -".$day_of_the_week."days"));
 */

date("Y-m-d H:i:s", strtotime("-1 day")); // 어제
date("Y-m-d H:i:s", strtotime("now")); // 현재
date("Y-m-d H:i:s", strtotime("+1 day")); // 내일
date("Y-m-d H:i:s", strtotime("+1 week")); // 일주일 후
date("Y-m-d H:i:s", strtotime("-1 month")); // 한달 전
date("Y-m-d H:i:s", strtotime("+1 month")); // 다음달
date("Y-m-d H:i:s", strtotime("+1 week 2 days 3 hours 4 seconds")); // 1주 2일 3시간 4초 후
date("Y-m-d H:i:s", strtotime("next Thursday")); // 다음주 목요일
date("Y-m-d H:i:s", strtotime("last Monday")); // 지난 월요일
date("Y-m-d H:i:s", strtotime("10 September 2013")); // 2013년 9월 10일

?>