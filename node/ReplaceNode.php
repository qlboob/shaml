<?php
include_once 'Node.php';
/**
 * �滻�ڵ�
 * @author lukeqin
 *
 */
class ReplaceNode extends Node{
	protected $replace;
	
	function dump (){
		return $this->replace;
	}
}