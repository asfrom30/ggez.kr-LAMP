


/** Html Element Id <-> Javascript Array Index <-> Display Label **/
/* Get Tier Label using Tier Index */
function getTierId2Index(tierId){
	
}

function getTierIndex2Label(tierIndex){
	switch (tierIndex) {
	case 'bronze':
		return '브론즈';
		break;
	case 'silver':
		return '실버';
		break;
	case 'gold':
		return '골드';
		break;
	case 'platinum':
		return '플레';
		break;
	case 'dia':
		return '다이아';
		break;
	case 'master':
		return '마스터';
		break;
	case 'grandMaster':
		return '그랜드 마스터';
		break;
	default:
		return '--';
		break;
	}
}

function getHeroIndex2Label(heroIndex){
	switch (heroIndex) {
	case "Genji": 	return "겐지";
	case "Reaper":	return "리퍼";
	case "McCree":	return "맥크리";
	case "Soldier76":return "솔져76";
	case "Sombra": 	return "솜브라";
	case "Tracer": 	return "트레이서";
	case "Pharah": 	return "파라";
	case "Mei": return "메이";
	case "Bastion": return "바스티온";
	case "Widowmaker": return "위도우메이커";
	case "Junkrat": return "정크랫";
	case "Torbjoern": return "토르비욘";
	case "Hanzo": return "한조";
	case "DVa": return "디바";
	case  "Reinhardt": return "라인하르트";
	case "Roadhog": return "로드호그";
	case "Winston": return "윈스턴";
	case  "Zarya": return "자리야";
	case "Lucio" : return  "루시우";
	case  "Mercy": return "메르시";
	case "Symmetra" : return  "시메트라";
	case  "Ana": return "아나";
	case "Zenyatta": return "젠야타"
	
	default:
		return "all";
	}
}

function getPeriodIndex2Label(periodIndex){
	var label;
	
	if(periodIndex == 'week'){
		label = "주간";
	} else if (periodIndex == 'ystday'){
		label = "어제";
	} else if (periodIndex == 'today'){
		label = "오늘";
	} else {
		label = "평균";
	}
	return label;
}