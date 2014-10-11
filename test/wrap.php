<?php 
include_once '../Shaml.php';


$tpl = <<<EOF
/
	.ziying_pics_upload
		.task_edit_ulist
			ul#pic-list
				foreach $mainPicLists
					- if($k>9) continue;
					li
						- 
							$_imgsrc='';
							if(!empty($v->strLocalPicRelativePath)){
								$_imgsrc = image2url($v->strLocalPicRelativePath."/".$v->lPicIndex.'.'.imageType2ext($v->lPicType)).'?'.$v->lLastModifyTime;
							}
						img.uploadMainPicImg src="{$_imgsrc}" data-index={$k} width=60 height=60
						button.uploadMainPicBtn data-index={$k} ÉÏ´«Í¼Æ¬
						button.delMainPic data-index={$k} É¾³ýÍ¼Æ¬
EOF;

$tpl = trim($tpl);
$shaml = new Shaml;
echo($shaml->compile($tpl));
