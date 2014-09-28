<?php
include_once '../Shaml.php';

$tpl = <<<EOF
html5
EOF;
$tpl = trim($tpl);
$shaml = new Shaml;
echo($shaml->compile($tpl));