<?php 
include_once 'Node.php';
/**
 * �Ապϱ�ǩ
 * @author lukeqin
 */
class SelfCloseNode extends Node {
	
	function dump (){
		return "<$this->tag".$this->buildAttr().' />'.$this->inlineText;
	}
	
	
}

