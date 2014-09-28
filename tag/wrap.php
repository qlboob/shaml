<?php
return array(
	':javascript' =>	array(
		'node' => 'wrap',
		'arg'=>	array(
			'tag'=>'script',
			'attr'=>array(
				'type'=>'text/javascript',
			)
		),
	),
	':style' =>	array(
		'node' => 'wrap',
		'arg'=>	array(
			'tag'=>'style',
			'attr'=>array(
				'type'=>'text/css',
			)
		),
	),
	':php'	=>array(
		'node' => 'wrap',
		'arg'=>	array(
			'scan'=>array('PreIndex','SuffIndex','InlineText','content'),
			'preWrap' => '<?php ',
			'suffWrap'=> '?>',
		),
	),
);