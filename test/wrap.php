<?php 
include_once '../Shaml.php';


$tpl = <<<EOF
html5
html
	head
		title publish
	body
		// test
		#divDetailBar
			if PHP
				hello
			p h
		p go
		// #include virtual="/sinclude/jsi/pp.media.js.shtml"
EOF;

$tpl = trim($tpl);
$shaml = new Shaml;
echo($shaml->compile($tpl));
