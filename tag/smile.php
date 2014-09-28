<?php
return array(
	'include' => array(
		'node'=>'selfClose',
		'2attr'=>array(
				array('f'),
		),
	),
	'blockto'	=>	array(
		'node'=>'selfClose',
		'2attr'=>array(
				array('name'),
		),
	),
	'block'	=>	array(
		'node'=>'Tag',
		'2attr'=>array(
				array('to'),
		),
	),
);