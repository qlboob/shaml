<?php
include_once 'TagNode.php';
/**
 * php�﷨�еĿ��ƽڵ��磺if else while��
 * @author lukeqin
 *
 */
class ControlNode extends TagNode{
	public $dumpTpl='<?php %s%s{?>';//������ģ��
	public $endDumpTpl = '<?php }?>';
	public $scan = array(
		'PreIndex','SuffIndex','InlineText'
	);
	
	function getinlineText() {
		return '';
	}
	
	function dump (){
		$ret = sprintf($this->dumpTpl,$this->tag,$this->inlineText?"({$this->inlineText})":"{$this->inlineText}");
		return $ret;
	}
	
	function endDump (){
		return $this->endDumpTpl;
	}
}
/* switch ($$GLOBALS) {
	case value:
	;
	break;
	
	default:
		;
	break;
} */