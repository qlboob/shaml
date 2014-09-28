<?php
include_once 'Node.php';
/**
 * ÓĞÄÚÈİµÄTag
 * @author lukeqin
 */
class TagNode extends Node{

	function dump (){
		$ret = '<'.$this->tag;
		$ret .= $this->buildAttr();
		$ret .= '>'.$this->inlineText;
		$this->content && $ret .= $this->content;
		return $ret;
	}

	function endDump (){
		return "</$this->tag>";
	}
	
	
	
}

