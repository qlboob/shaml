<?php
include_once 'ControlNode.php';
class ForeachNode extends ControlNode{
	function dump (){
		$attr = $this->attr;
		empty($attr['k']) && $attr['k'] = 'k';
		empty($attr['v']) && $attr['v'] = 'v';
		
		//如果是一个变量在前加上$符号
		if (preg_match('#^[a-z]\w+$#i', $attr['name'])) {
			$attr['name'] = '$'.$attr['name'];
		}
		
		$ret = "<?php foreach({$attr['name']} as \${$attr['k']}=>\${$attr['v']}){?>".$this->inlineText;
		return $ret;
	}
}