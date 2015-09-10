<?php

	namespace handler;

	function create(array $args = array()) {

		$name 	= (isset($args[0])) 
			? $args[0] 
			: ((isset($args['name']))
				? $args['name']
				: null
			)
		;

		if (!$name) return 
			"Handlers require a name\n".
			"./ka10 handler.create name=myHandler\n"
		;

		return "Handler '$name' Created.";
	}

?>