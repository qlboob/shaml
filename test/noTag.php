<?php
include_once '../Shaml.php';
$shaml = new Shaml();
$tpl = <<<EOF
#header.head.nav<>
		yes
EOF;
$tpl = trim($tpl);
$result = $shaml->compile($tpl);
echo $result;