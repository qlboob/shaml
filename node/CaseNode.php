<?php
include_once 'ControlNode.php';
class CaseNode extends ControlNode{
	
	function dump (){
		$lastNode = $this->shaml->getlastNode();
		if ($lastNode && 'switch'==$lastNode->gettag()) {
			$this->dumpTpl = ' %s %s:?>';
		}else {
			$this->dumpTpl = '<?php %s %s:?>';
		}
		$ret = sprintf($this->dumpTpl,$this->tag,$this->inlineText);
		return $ret;
	}
}
