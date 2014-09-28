<?php
include_once '../Shaml.php';

$tpl = <<<EOF
ul>
	li
		a<
			i.icon-opn<
EOF;
$tpl = trim($tpl);
$shaml = new Shaml;
echo($shaml->compile($tpl));