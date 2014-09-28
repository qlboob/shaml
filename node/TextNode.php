<?php
class TextNode extends Node{
	public $scan = array();
	
	
	function getcontext() {
		$left = ltrim($this->line);
		if (empty($left)) {
			return false;
		}
		return parent::getcontext();
	}
	
	function dump() {
		return ltrim($this->line);
	}
}
