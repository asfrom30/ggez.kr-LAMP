<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/../resources/config.php");

require_once (INIT_PATH . "/HeroInfoList.php");

class DataComputeHandler{
	
	private $errorLogFileName;
	private $statMap;

	
	function __construct(){
		//TODO : mysqli가 Null 일때 핸들링할것
		$this -> statMap = getStatMap();
	}
	
	function __destruct(){
		
	}
	
	function getMost3($data){
		return array("Soldier76", "Bastion", "McCree");
	}
	
	/* 첫번째 분류 Tag를 삭제 합니다. */
	function removeCtg($data){	// 깊은 복사로 새로운 object를 리턴한다.
		if($data == null){
			return null;
		}
	
		$result= array();
		$heros = array_keys($data);
	
		foreach ($heros as $hero){
			$result[$hero] = array();
			$heroDatas = $data[$hero];
	
			foreach ($heroDatas as $key => $value){
				foreach($value as $key => $value){
					$result[$hero][$key] = $value;
				}
			}
		}
		return $result;
	}
	
	function getBattleName(){
		
	}
}