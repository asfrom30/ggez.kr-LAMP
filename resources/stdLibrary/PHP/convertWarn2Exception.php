<?php


/* This idea is from 'https://goo.gl/2x6qKk' */


/* Turning errors into exceptions */
set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
	// error was suppressed with the @-operator
	if (0 === error_reporting()) {
		return false;
	}

	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
	dns_get_record();
} catch (ErrorException $e) {
	// ...
}

/* Restore */
restore_error_handler();