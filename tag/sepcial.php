<?php
/**
 * �����ַ���ɵ�tag������
 */
return array(
	#ԭ���������
	'|'	=>	array(
		'node'	=>	'wrap',
		'arg'	=>	array(
			'scan'	=>	array('InlineText','content'),
			'preWrap'=>'',
		),
	),
	//����PHP����
	/* '-'	=>	array(
		'node' => 'wrap',
		'arg'=>	array(
			'scan'=>array('InlineText',),
			'preWrap' => '<?php ',
			'suffWrap'=> '?>',
		),
	), */
	//PHP����
	'-'	=>	array(
		'node' => 'wrap',
		'arg'=>	array(
			'scan'=>array('InlineText','content'),
			'preWrap' => '<?php ',
			'suffWrap'=> '?>',
		),
	),
	#����ע�� ������ʾ����
	'/'	=>	array(
		'node' => 'del',
		'arg'=>	array(
			'scan'=>array('InlineText','content'),
// 			'preWrap' => '',
// 			'suffWrap'=> '',
// 			'context' => false,
		),
	),
	#htmlע��
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