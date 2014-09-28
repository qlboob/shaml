<?php

/**
 * 节点
 * @author lukeqin
 */
class Node{
	#Shaml对象
	public $shaml;
	/*
	 *节点标签名
	 */
	public $tag;

	//属性
	public $attr=array();

	//条件属性
	public $conditionAttr=array();

	#缩进数量
	public $indentCnt;
	
	public $indentInc=0;

	#缩进文本
	public $indentText;

	#是否贴近前一个元素
	public $preIndent =false;

	#是否贴近后一个元素
	public $suffIndent = false;

	#当前行后的文本
	public $inlineText = false;

	#内容文本
	public $content;

	#上下文相关
	public $context = true;

	#配置传入的参数
	public $arg =array();
	
	#需要扫描的属性，等
	public $scan = array(
		'id','class','PreIndex','SuffIndex','Attr','InlineText'
	);
	
	#当前文本行内容
	public $line;

	function config($arg=array()){
		foreach($arg as $k=>$v){
			$method = 'set'.$k;
			$this->$method( $v);
		}
		$this->arg = $arg;
	}
	
	function __isset($att)
	{
		$props = get_object_vars($this);
		return array_key_exists($att, $props);
	}

	function setTag($tag) {
		if (empty($this->tag)) {
			$this->tag = $tag;
		};
	}
	function __call ($method,$arg){
		$property	=	substr($method, 4);
		if(isset($method[3])){
				$property = strtolower($method[3]) . $property;
		}
		switch (substr($method, 0, 3)) {
			case 'get' :
	            $flag = isset($this->$property);
            	return $flag?$this->$property:NULL;
            case 'set':
            	$this->$property	=	$arg[0];
            	break;
		}
	}

	function dump (){
		
	}
	function endDump(){}
	
	

	/**
	 * 构建属性字符串
	 */
	protected function buildAttr (){
		$ret = '';
		$attrKey = array_keys($this->attr);
		$conditionAttrKey = array_keys($this->conditionAttr);
		$insectionKey = array_intersect($attrKey,$conditionAttrKey);
		if($insectionKey){
			foreach($insectionKey as $v){
				$ret .= sprintf(' %s="%s<?php if(%s){ ?> %s<?php } ?>"',$v,$this->attr[$v],$this->conditionAttr[$v]['condition'],$this->conditionAttr[$v]['val']);
				unset($this->attr[$v],$this->conditionAttr[$v]);
			}
		}

		foreach($this->attr as $k =>$v){
			$ret .= sprintf(' %s="%s"',$k,$v);
		}
		foreach($this->conditionAttr as $k=>$v){
				$ret .= sprintf('<?php if(%s){ ?> %s="%s"<?php } ?>',$v['condition'],$k,$v['val']);
		}
		return $ret;
	}
	
	

	function getIndentCnt() {
		return $this->indentCnt + $this->indentInc;
	}
	
}

