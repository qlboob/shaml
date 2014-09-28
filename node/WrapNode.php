<?php
include_once 'TagNode.php';
/**
 * �п������ݵĽڵ��磺php�����
 * @author lukeqin
 *
 */
class WrapNode extends TagNode{
	public $preWrap;
	public $suffWrap;
	public $scan = array(
			'id','class','PreIndex','SuffIndex','Attr','InlineText','content'
	);
	
	function dump() {
		if (null !== $this->getpreWrap()) {
			$ret = $this->getpreWrap();
			$end = $this->getsuffWrap();
			if ($this->getinlineText()) {
				$ret .= $this->getinlineText();
			}else {
				$end = "\n" .$this->indentText.$end;
			}
			$ret .= $this->content;
			$ret .= $end;
		}else {
			$ret =  parent::dump();
		}
		return $ret;
	}
	
	function endDump(){
		if (null !== $this->getpreWrap()) {
			return ;
		}else {
			return parent::endDump();
		}
	}
}