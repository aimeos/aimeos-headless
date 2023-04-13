<?php

return [

	'apc_enabled' => false, // enable for maximum performance if APCu is availalbe
	'apc_prefix' => 'aimeos:', // prefix for caching config and translation in APCu
	'pcntl_max' => 4, // maximum number of parallel command line processes when starting jobs
	'num_formatter' => 'Locale', // locale based number formatter (alternative: "Standard")

	'routes' => [
		// Docs: https://aimeos.org/docs/latest/laravel/extend/#custom-routes
		// Multi-sites: https://aimeos.org/docs/latest/laravel/customize/#multiple-shops
		// 'admin' => ['prefix' => 'admin', 'middleware' => ['web']],
		// 'jqadm' => ['prefix' => 'admin/{site}/jqadm', 'middleware' => ['web', 'auth']],
		// 'jsonadm' => ['prefix' => 'admin/{site}/jsonadm', 'middleware' => ['web', 'auth']],
		'jsonapi' => ['prefix' => 'jsonapi', 'middleware' => ['api', 'auth.api']],
		'confirm' => ['middleware' => ['web']],
		'account' => false,
		'default' => false,
		'supplier' => false,
		'page' => false,
		'update' => [],
	],

	'resource' => [
		'db' => [
			'adapter' => config( 'database.connections.' . config( 'database.default', 'mysql' ) . '.driver', 'mysql' ),
			'host' => config( 'database.connections.' . config( 'database.default', 'mysql' ) . '.host', '127.0.0.1' ),
			'port' => config( 'database.connections.' . config( 'database.default', 'mysql' ) . '.port', '3306' ),
			'socket' => config( 'database.connections.' . config( 'database.default', 'mysql' ) . '.unix_socket', '' ),
			'database' => config( 'database.connections.' . config( 'database.default', 'mysql' ) . '.database', 'forge' ),
			'username' => config( 'database.connections.' . config( 'database.default', 'mysql' ) . '.username', 'forge' ),
			'password' => config( 'database.connections.' . config( 'database.default', 'mysql' ) . '.password', '' ),
			'stmt' => config( 'database.default', 'mysql' ) === 'mysql' ? ["SET SESSION sort_buffer_size=2097144; SET NAMES 'utf8mb4'; SET SESSION sql_mode='ANSI'"] : [],
			'limit' => 3, // maximum number of concurrent database connections
			'defaultTableOptions' => [
				'charset' => config( 'database.connections.' . config( 'database.default', 'mysql' ) . '.charset' ),
				'collate' => config( 'database.connections.' . config( 'database.default', 'mysql' ) . '.collation' ),
			],
			'driverOptions' => config( 'database.connections.' . config( 'database.default', 'mysql' ) . '.options' ),
		],
		'fs' => [
			'adapter' => 'Standard',
			'tempdir' => storage_path( 'tmp' ),
			'basedir' => public_path( 'aimeos' ),
			'baseurl' => rtrim(env('ASSET_URL', PHP_SAPI == 'cli' ? env('APP_URL') : '', '/')) . '/aimeos',
		],
		'fs-media' => [
			'adapter' => 'Standard',
			'tempdir' => storage_path( 'tmp' ),
			'basedir' => public_path( 'aimeos' ),
			'baseurl' => rtrim(env('ASSET_URL', PHP_SAPI == 'cli' ? env('APP_URL') : '', '/')) . '/aimeos',
		],
		'fs-mimeicon' => [
			'adapter' => 'Standard',
			'tempdir' => storage_path( 'tmp' ),
			'basedir' => public_path( 'vendor/shop/mimeicons' ),
			'baseurl' => rtrim(env('ASSET_URL', PHP_SAPI == 'cli' ? env('APP_URL') : '', '/')) . '/vendor/shop/mimeicons',
		],
		'fs-theme' => [
			'adapter' => 'Standard',
			'tempdir' => storage_path( 'tmp' ),
			'basedir' => public_path( 'vendor/shop/themes' ),
			'baseurl' => rtrim(env('ASSET_URL', PHP_SAPI == 'cli' ? env('APP_URL') : '', '/')) . '/vendor/shop/themes',
		],
		'fs-admin' => [
			'adapter' => 'Standard',
			'tempdir' => storage_path( 'tmp' ),
			'basedir' => storage_path( 'admin' ),
		],
		'fs-export' => [
			'adapter' => 'Standard',
			'tempdir' => storage_path( 'tmp' ),
			'basedir' => storage_path( 'export' ),
		],
		'fs-import' => [
			'adapter' => 'Standard',
			'tempdir' => storage_path( 'tmp' ),
			'basedir' => storage_path( 'import' ),
		],
		'fs-secure' => [
			'adapter' => 'Standard',
			'tempdir' => storage_path( 'tmp' ),
			'basedir' => storage_path( 'secure' ),
		],
		'mq' => [
			'adapter' => 'Standard',
			'db' => 'db',
		],
		'email' => [
			'from-email' => config( 'mail.from.address' ),
			'from-name' => config( 'mail.from.name' ),
		],
	],

	'admin' => [],

	'client' => [
	],

	'controller' => [
		'frontend' => [
			'catalog' => [
				'levels-always' => 2
			]
		]
	],

	'i18n' => [
	],

	'madmin' => [
		'cache' => [
			'manager' => [
				// 'name' => 'None', // Disable caching for development
			],
		],
		'log' => [
			'manager' => [
				// 'loglevel' => 7, // Enable debug logging into madmin_log table
			],
		],
	],

	'mshop' => [
	],


	'command' => [
	],

	'frontend' => [
	],

	'backend' => [
	],

];
