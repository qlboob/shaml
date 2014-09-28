<?php 
include_once '../Shaml.php';

$tpl = <<<EOF
html lang=en
	head
		title hello
	
	body#main.test.g
		#first
			.last
EOF;
$tpl = trim($tpl);
$shaml = new Shaml;
echo($shaml->compile($tpl));
