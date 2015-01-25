<?php
/**
 * Config-file for Anax, theme related settings, return it all as array.
 */
return array(
	/**
	 * Settings for Which theme to use, theme directory is found by path and name.
	 *
	 * path: where is the base path to the theme directory, end with a slash.
	 * name: name of the theme is mapped to a directory right below the path.
	 */
	'settings' => array(
		'path' => ANAX_INSTALL_PATH . 'theme/',
		'name' => 'anax-base'
	),
	/** 
	 * Add default views.
	 */
	'views' => array(
		array(
			'region' => 'header',
			'template' => 'csgo/header',
			'data' => array(
				'siteTitle' => "Global-Overflow",
				'siteTagline' => "Your home for CSGO-Questions"
			),
			'sort' => -1
		),
		array(
			'region' => 'navbar',
			'template' => array(
				'callback' => function() {
					return $this->di->navbar->create();
				}
			),
			'data' => array(),
			'sort' => -1
		),
		array(
			'region' => 'footer',
			'template' => 'csgo/footer',
			'data' => array(),
			'sort' => -1
		)
	),
	/** 
	 * Data to extract and send as variables to the main template file.
	 */
	'data' => array(
		// Language for this page.
		'lang' => 'en',
		// Append this value to each <title>.
		'title_append' => ' | Global-Overflow',
		// Stylesheets.
		'stylesheets' => array(
			'css/style.css',
			'css/navbar.css',
			'css/standard.css',
			'css/font-awesome-4.2.0/css/font-awesome.css'
		),
		// Inline style.
		'style' => null,
		// Favicon.
		'favicon' => 'css/img/favicon.png',
		//script.js.
		'script' => 'js/script.js',
		// Path to modernizr or null to disable.
		'modernizr' => 'js/modernizr.js',
		// Path to jquery or null to disable.
		'jquery' => '//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js',
		// Array with javscript-files to include.
		'javascript_include' => array(),
		// Use google analytics for tracking, set key or null to disable.
		'google_analytics' => null
	)
);
?>