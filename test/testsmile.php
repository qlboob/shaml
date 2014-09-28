<?php
include_once '../Shaml.php';

$tpl = <<<EOF
include xxxx/gg
blockto main
block main
	ggg
	fads
	gasd
EOF;
$tpl = trim($tpl);
$shaml = new Shaml;
echo($shaml->compile($tpl));