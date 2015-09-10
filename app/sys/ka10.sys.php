#!/usr/bin/php
<?php

	/**
		Copyright 2015 Geoffrey L. Kuhl

	   	Licensed under the Apache License, Version 2.0 (the "License");
	   	you may not use this file except in compliance with the License.
	   	You may obtain a copy of the License at

	    	http://www.apache.org/licenses/LICENSE-2.0

	   	Unless required by applicable law or agreed to in writing, software
	   	distributed under the License is distributed on an "AS IS" BASIS,
	   	WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
	   	See the License for the specific language governing permissions and
	   	limitations under the License.
	*/


	/** ka10 Namespace **/
	namespace ka10;


	/**
	 * Header
	 * (Function) Prints CLI Header
	 */
	$header 		= function() {
		$msg 		=
			"ka10\n".
			"------------------------------\n".
			"All Rights Reserved (2015)\n".
			"\n"
		;

		print $msg;
	};


	/**
	 * Help
	 * (Function) Prints Help
	 */
	$help 			= function() {
		$msg 		= 
			"Usage \n".
			"------------------------------\n".
			"./ka10 <command> <args>\n".
			"\n"
		;

		exit($msg);
	};


	function loadHandlers($folder) {
		$folder 	= realpath($folder);
		if (!$folder) return false;

		$list 		= scandir($folder);

		foreach ($list as $item) {
			if ($item == '.' or $item == '..') continue;

			$path 	= "$folder/$item";

			if (is_dir($path)) {
				loadHandlers($path);
			} elseif (is_file($path)) {
				$parts 	= explode(".", $item);
				$ext 	= $parts[count($parts) - 1];

				include($path);
			}
		}
	};


	/**
	 * Standard Input Arguments
	 * Retrieve arguments passed from CLI
	 */
   	$stdIn 	= (isset($_SERVER['argv']))
   		? $_SERVER['argv']
   		: []
   	;


   	/** Print the Header **/
	$header();


	/** 
	 * If count of arguments is 0
	 * show help and exit 
	 */
   	if (count($stdIn) < 2) $help();


   	/** Get Command **/
   	$command 	= (isset($stdIn[1]))
   		? $stdIn[1]
   		: null
   	;


   	/** Handle Bad Input **/
   	if (!$command) {
   		print "Bad Syntax\n";
   		$help();
   	} 


   	/** Load Handlers **/
   	loadHandlers('sys/handlers');


   	/** Clean Parameters **/
   	$param 	= $stdIn;
   	if (isset($param[0])) unset($param[0]);
   	if (isset($param[1])) unset($param[1]);
   	$param 	= array_values($param);

   	/** Check for Param Keys **/
   	$buffer	= [];
   	$used 	= [];
   	foreach ($param as $index => $arg) {
   		if (strpos($arg, "=") === false) continue;

   		$parts 	= explode("=", $arg, 2);
   		$buffer[$parts[0]]	= (isset($parts[1])) ? $parts[1] : null;
   		$used[]	= $index;
   	}

   	foreach ($used as $index) {
   		unset($param[$index]);
   	}

   	if ((count($param) > 0) and $buffer) {
   		$param = array_merge($buffer, $param);
	} elseif ($buffer) {
   		$param = $buffer;
	}




   	/** Clean Command and set namespace **/
   	if (substr($command, 0, 1) != '\\') $command = "\\$command";
   	$command= str_replace(".", "\\", $command);


   	/** Make sure command exists **/
   	if (!function_exists($command)) {
   		print "Command not found\n";
   		exit();
   	}


   	/** Execute command and exit**/
   	exit($command($param) . "\n");

?>