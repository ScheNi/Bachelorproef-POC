<?php
function autoLoader($className) {
	$directories = array (
		'.',
		'interfaces/',
		'classes/',
        'packages/'
	);

	$fileNameFormats = array (
		'%s.php'
	);

	foreach($directories as $directory) {
		foreach($fileNameFormats as $fileNameFormat) {
			$path = $directory.sprintf($fileNameFormat, $className);
			if(file_exists($path)) {
				include_once $path;
				return;
			}
		}
	}
}

spl_autoload_register('autoLoader');
