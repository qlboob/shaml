<?php
/**
 * 特殊字符组成的tag的配置
 */
return array(
	#原样输出内容
	'|'	=>	array(
		'node'	=>	'wrap',
		'arg'	=>	array(
			'scan'	=>	array('InlineText','content'),
			'preWrap'=>'',
		),
	),
	//单行PHP代码
	/* '-'	=>	array(
		'node' => 'wrap',
		'arg'=>	array(
			'scan'=>array('InlineText',),
			'preWrap' => '<?php ',
			'suffWrap'=> '?>',
		),
	), */
	//PHP代码
	'-'	=>	array(
		'node' => 'wrap',
		'arg'=>	array(
			'scan'=>array('InlineText','content'),
			'preWrap' => '<?php ',
			'suffWrap'=> '?>',
		),
	),
	#代码注释 不会显示出来
	'/'	=>	array(
		'node' => 'wrap',
		'arg'=>	array(
			'scan'=>array(),
			'preWrap' => '',
			'suffWrap'=> '',
			'context' => false,
		),
	),
	#html注释
	'//'	=>	array(
		'node' => 'wrap',
		'arg'=>	array(
			'scan'=>array('InlineText','content'),
			'preWrap' => '<!--',
			'suffWrap'=> '-->',
			'context' => false,
		),
	),
	
);