var objFavoriteIds ={};
		
objFavoriteIds["냅둬라날-393413"] = "12343";

setCookie("testCookie2", JSON.stringify(objFavoriteIds), 365);
var getTest = getCookie("testCookie2");
alert(getTest);

function setCookie(cname, cvalue, exdays) {
	
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = encodeURI(cname) + "=" + encodeURI(cvalue) + ";" + expires + ";path=/";
}

function getCookie(cname) {
	
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    alert(decodedCookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') { // 공백을 제거 합니다.
            c = c.substring(1);	
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}