

<!-- Example Cookie uses -->
<?php
if(!isset($_COOKIE[$cookie_name])) {	// Cookie가 있으면.
    echo "Cookie named '" . $cookie_name . "' is not set!";
} else {
    echo "Cookie '" . $cookie_name . "' is set!<br>";	// Cookie가 없으면.
    echo $_COOKIE["ggezkr1"];
}
?>



<!-- Setting new cookie -->
<!-- ============================= -->
<?php
setcookie("name","value",time()+$int);
/*name is your cookie's name
 value is cookie's value
 $int is time of cookie expires*/
?>

<!-- Getting Cookie -->
<!-- ============================= -->
<?php 
echo $_COOKIE["your cookie name"];
?>

<!-- Updating Cookie -->
<!-- ============================= -->
<?php 
setcookie("color","red");
echo $_COOKIE["color"];
/*color is red*/
/* your codes and functions*/
setcookie("color","blue");
echo $_COOKIE["color"];
/*new color is blue*/
?>

<!-- Deleting Cookie -->
<!-- ============================== -->
<?php 
unset($_COOKIE["yourcookie"]);
/*Or*/
setcookie("yourcookie","yourvalue",time()-1);
/*it expired so it's deleted*/
?>

<?php
/* Serialize data: */
setcookie('your_cookie_name', json_encode($info), time()+3600);
/* Then unserialize data: */
$data = json_decode($_COOKIE['your_cookie_name'], true);

/* Don't use this code, this has security holes */
/* setcookie('cookie', serialize($info), time()+3600);
$data = unserialize($_COOKIE['cookie']); */
?>