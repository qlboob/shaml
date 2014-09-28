<?php
include_once 'TagNode.php';
/**
 * php语法中的控制节点如：if else while等
 * @author lukeqin
 *
 */
class ControlNode extends TagNode{
	public $dumpTpl='<?php %s%s{?>';//导出的模板
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