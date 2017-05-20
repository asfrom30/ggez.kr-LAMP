/** Utility Function **/
/* Count pattern number in text */
function countIncludeStr(text, pattern){
	return (text.match(new RegExp(pattern, "g")) || []).length;
}

/* Open New Window */ 
function openNewWindow(url, toolbar, scrollbars, resizable, posX, posY, width, height, resizable){
	var toolbar = true2yes(toolbar);
	var scrollbars = true2yes(scrollbars);
	var resizable = true2yes(resizable);
	var option = "toolbar=" + toolbar + ", scrollbars=" + scrollbars + ", resizable=" + resizable +
					 ", top=" + posY  + ", left=" + posX + ", width=" + width + ", height=" + height;

	window.open(url, "_blank", option);
}

/** Post Form Class **/
/* Note : urlPath should be endeed with "/" */ 
function PostForm(urlPath){
	this.form = document.createElement("form");
	$(this.form).attr("action", urlPath)
    .attr("method", "post");
	this.urlPath = urlPath;
}

PostForm.prototype = {
		constructor : PostForm,
		addElem : function(name, value) {
			var element = document.createElement("INPUT");  
			element.name= name;
			element.value = value;
			element.type = 'hidden';
			this.form.appendChild(element);
		},
		submit : function() {
			document.body.appendChild(this.form);
			this.form.submit();
			document.body.removeChild(this.form);
		},
}

/** Location **/
//window.location.replace("ggez.kr");			// window.location.href보다 좋다. replace는 기존페이지를 보관하지 않는다.

/** Array Explorer **/
/* 배열에서 찾고자 하는 key가 정의되지 않았을 경우 점검하여 null을 return 하는 역할을 하는 함수입니다. */
/* ex) getStat('All', 'cptPt', 'avg'); */
function getStat(){
	
	/* note : 자바스크립트는 오버로딩을 지원하지 않으므로 arguments를 배열로 이용하여 사용합니다. */
	var args = arguments;
	var array = window.stats;
	
	for(var index in args){
		var key = args[index];
		if(array[key] == undefined){
			console.log("args : " + args + ", 에서 '" + key +"'값이 undefined입니다.");
			return null;
		}
		array = array[key];
	}
	return array;
}

/** CHECK global declaration and undefined **/
/* Just check declaration */
	//"theFu" in window; // true
	//"theFoo" in window; // false
/* Just check undefined(has value or not) */
	//if (typeof myVar != 'undefined')

/** JSON Utility **/

//JSON.parse();			// encode;
//JSON.stringify();		// decode;


/** Difference between object and array **/
/* Declare */
var array = [];
var object = {};;

/* Deffrence */
// Javascript array don't have key and easy to loop(different from PHP)
// Javascript object can have key and value, and It can't be dulplicated.

/** Compare Object and Array Structure **/
/* Object */
//var object = {"a":"C", "b":"B};

//console.log(object)
	//	Object {a: "C", b: "B"}
	//		a: "C"
	//		b: "B"
	//		__proto__: Object

//console.log(json)
	// {"a":"C","b":"B"}

/* Array */
//var array = ["a", "b", "c"];

//console.log(array)
	//	["a", "b", "c"]
	//	0: "a"
	//	1: "b"
	//	2: "c"
	//	length: 3
	//	__proto__: Array(0)

//console.log(json)
	// ["a","b","c"]

/** You can use Array which has key and value **/
/** It can't be possible, array has key and value when declaration **/
/** Only the way which decalre first array  **/
//	var array = [];
//	array["a"] = "A";
//	array["b"] = "B";
//	array["a"] = "C";

//	console : [a: "C", b: "B"]



/** Get Key Object and Array **/
/* Array Case */
//	var arr = ['a', 'b', 'c'];
//	console.log(Object.keys(arr)); // console: ['0', '1', '2']
	
/* Object Case */
//	var obj = { 0: 'a', 1: 'b', 2: 'c' };
//	console.log(Object.keys(obj)); // console: ['0', '1', '2']

/** Array and Object Loop **/
/* Array use (for ... length ) */
//	var a = []; // Create a new empty array.
//	a[5] = 5;   // Perfectly legal JavaScript that resizes the array.
//	
//	for (var i = 0; i < a.length; i++) {
//	    // Iterate over numeric indexes from 0 to 5, as everyone expects.
//	    console.log(a[i]);
//	}
//
//	/* Will display:
//	   undefined
//	   undefined
//	   undefined
//	   undefined
//	   5
//	*/

/* Array can't use (for .. in), Why? */
//	var a = [];
//	a[5] = 5;
//	for (var x in a) {
//	    // Shows only the explicitly set index of "5", and ignores 0-4
//	    console.log(x);
//	}
//	
//	/* Will display:
//	   5
//	*/

/** Object Utility **/
/* Delete Property */
//	delete object.property		
//	delete object['property']
			//NOTE : property로는 변수를 지정할 수 없다. property 자체가 이름이 되기 때문이다.
			//예를들어 아래 코드는 작동하지 않는다. index안에 있는 값이 아니라 index 자체로 찾으려고 하기 때문이다.
			//	var array = {"a" : null ,"b": null };
			//	var index = "a";
			//	delete array.index;

/* Has Key */
//	myObj.hasOwnProperty('key')

/** Array Utility **/
/* Find item in the Array */
//	var index = array.indexOf(item);

/* Splice method will delete legth of array from index */
//	array.splice(index, length);

/* Find and remove example */
//	var index = array.indexOf(item);
//	array.splice(index, 1);

/** Json Utility **/
function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

/** Cookie Utility **/
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = encodeURI(cname) + "=" + encodeURI(cvalue) + ";" + expires + ";path=/";
//    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
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

function checkCookie(cname) {
    var cvalue = getCookie(cname);
    if (cvalue != "") {
    	return true;
    } else {
    	return false;
    }
}

/** Wrap Element **/
function wrapDiv(str){
	return "<div>" + str + "</div>";
}

function wrapSpan(str){
	return "<span>" + str + "</span>";
}

/** Jquery and Javascript **/
/* Get Element */
//	document.getElementById('contents'); //returns a HTML DOM Object
//	var contents = $('#contents');  //returns a jQuery Object
//	var contents = $('#contents')[0]; //returns a HTML DOM Object
	
/** Test Function(not used) **/

/** Exmaple **/
function movePage(urlPath){
	var form = document.createElement("form");
	
    $(form).attr("action", urlPath)
    .attr("method", "post");
    
    $(form).html('<input type="hidden" name="btg" value="' + btg + '" />' +
            '<input type="hidden" name="loc" value="' + loc + '" />' +
            '<input type="hidden" name="device" value="' + device + '" />' +
            '<input type="hidden" name="sample" value="' + isSample + '" />');
    
    /* If you want to add element, Use below code */
//    var element1 = document.createElement("INPUT");         
//    element1.name="un"
//    element1.value = un;
//    element1.type = 'hidden'
//    form.appendChild(element1);
    
//    var element2 = document.createElement("INPUT");         
//    element2.name="pw"
//    element2.value = pw;
//    element2.type = 'hidden'
//    form.appendChild(element2);
    	
	document.body.appendChild(form);
	$(form).submit();
	document.body.removeChild(form);
}



/** Object Prototype **/
/* get Object Name */
// TODO: HOW CAN OVERRIDE
//Object.prototype.getName = function() { 
//	   var funcNameRegex = /function (.{1,})\(/;
//	   var results = (funcNameRegex).exec((this).constructor.toString());
//	   return (results && results.length > 1) ? results[1] : "";
//	};

/** TODO: Object 클래스 살펴보기  **/
//	Object.defineProperty(Employee, 'name', {configurable: false});