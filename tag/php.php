<?php
return array(
	'if'	=>array(
		'node' => 'control',
	),
	'elseif'	=>array(
		'node' => 'control',
		'arg'=>	array(
				'indentInc' => 1,
				'dumpTpl'	=> '<?php }%s%s{?>',
				'endDumpTpl' => '',
		),
	),
	'while'	=>array(
		'node' => 'control',
	),
	'switch'	=>array(
		'node' => 'control',
		'arg'=>	array(
				'dumpTpl'	=> '<?php %s%s{',
		),
	),
	'case'	=>array(
		'node' => 'case',
		'arg'=>	array(
				'dumpTpl'	=> ' %s %s:?>',
				'endDumpTpl' => '<?php break; ?>',
		),
	),
	'else'	=>array(
		'node' => 'replace',
		'arg'=>	array(
				'replace' => '<?php }else{ ?>',
				'indentInc' => 1,
		),
	),
	'foreach'=>	array(
		'node' => 'foreach',
		'2attr'=>array(
				array('name','k','v'),
				array('name','v'),
				array('name'),
		),
	),
);