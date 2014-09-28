<?php
include_once 'Node.php';
/**
 * Ìæ»»½Úµã
 * @author lukeqin
 *
 */
class ReplaceNode extends Node{
	protected $replace;
	
	function dump (){
		return $this->replace;
	}
}