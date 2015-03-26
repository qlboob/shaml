<?php
include_once '../Shaml.php';

$tpl = <<<EOF
if 1>2
	some
elseif true
	yes
else
	goo
while 3>4
	en
foreach lists
	g
foreach lists val
foreach lists key val something
EOF;
$tpl = trim($tpl);
$shaml = new Shaml;
echo($shaml->compile($tpl));
$tpl2 = <<<EOF
- array()
.row
	/ comment no display
yyyy
EOF;
echo($shaml->compile($tpl2));
