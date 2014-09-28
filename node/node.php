<?php

/**
 * �ڵ�
 * @author lukeqin
 */
class Node{
	#Shaml����
	public $shaml;
	/*
	 *�ڵ��ǩ��
	 */
	public $tag;

	//����
	public $attr=array();

	//��������
	public $conditionAttr=array();

	#��������
	public $indentCnt;
	
	public $indentInc=0;

	#�����ı�
	public $indentText;

	#�Ƿ�����ǰһ��Ԫ��
	public $preIndent =false;

	#�Ƿ�������һ��Ԫ��
	public $suffIndent = false;

	#��ǰ�к���ı�
	public $inlineText = false;

	#�����ı�
	public $content;

	#���������
	public $context = true;

	#���ô���Ĳ���
	public $arg =array();
	
	#��Ҫɨ������ԣ���
	public $scan = array(
		'id','class','PreIndex','SuffIndex','Attr','InlineText'
	);
	
	#��ǰ�ı�������
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
	 * ���������ַ���
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

