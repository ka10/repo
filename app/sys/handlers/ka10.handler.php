<?php

	namespace handler;

	function create(array $args = array()) {

		$name 		= (isset($args[0])) 
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

		$path 		= realpath('sys/handlers');
		if (!$path) return
			"No place to store handlers!\n"
		;

		$content 	= "<?php\n\tnamespace $name;\n?>";

		$file 		= "sys/handlers/ka10.$name.php";
		if (file_exists($file)) return
			"Handler already exists!"
		;

		$wrote 		= file_put_contents($file, $content);

		if (!$wrote) return
			"Could not create file $file"
		;

		return "Handler '$name' Created.";
	}

?>