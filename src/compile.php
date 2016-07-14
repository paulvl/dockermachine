<?php

$sites = require('sites.php');

$servers = '';

foreach ($sites as $serverName => $path) {
	$servers .= 'server {' . PHP_EOL .
		'listen 80;' . PHP_EOL .
		'server_name ' . $serverName . ';' . PHP_EOL .
		'client_max_body_size 108M;' . PHP_EOL .
		'access_log /var/log/nginx/nginx-php-fpm.access.log;' . PHP_EOL .
		'root ' . $path . ';' . PHP_EOL .
		'index index.php index.html index.htm;' . PHP_EOL .
		'if (!-e $request_filename) {' . PHP_EOL .
			'rewrite ^.*$ /index.php last;' . PHP_EOL .
		'}' . PHP_EOL .
		'location ~ \.php$ {' . PHP_EOL .
			'fastcgi_pass php-fpm:9000;' . PHP_EOL .
			'fastcgi_index index.php;' . PHP_EOL .
			'fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;' . PHP_EOL .
			'fastcgi_param PHP_VALUE "error_log=/var/log/nginx/nginx-fpm_php_errors.log";' . PHP_EOL .
			'fastcgi_buffers 16 16k;' . PHP_EOL .
			'fastcgi_buffer_size 32k;' . PHP_EOL .
			'include fastcgi_params;' . PHP_EOL .
		'}' . PHP_EOL .
	'}' . PHP_EOL . PHP_EOL;
}

$serversConf = fopen("/home/nginx/servers.conf", "w") or die("Unable to open file!");
fwrite($serversConf, $servers);
fclose($serversConf);