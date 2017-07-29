
function setBackgroundImg($_target, iconUrl){
	var iconUrl = 'url("' + iconUrl + '")';
	$_target.css("background-image", iconUrl);
}

function displayBlock($_target, bool){
	if(bool){
		$_target.css("display", "block");
	} else {
		$_target.css("display", "none");
	}
}

function displayFlex($_target, bool){
	if(bool){
		$_target.addClass("flex");
	} else {
		$_target.removeClass("flex");
	}
}


