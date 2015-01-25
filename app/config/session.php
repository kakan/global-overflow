<?php
/**
 * Config-file for sessions. 
 */
return array(
	// Session name
	'name' => preg_replace('/[^a-z\d]/i', '', __DIR__)
);
?>