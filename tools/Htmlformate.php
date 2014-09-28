<?php
/**
 * 格式化|美化HTML代码。html代码必须书写正确
 * @author lukeqin
 *
 */
class Htmlformate {
	private $str;//格式化的字符
	
	private $currentModel = 'Text';//当前处理字符串的模式。Text找普通文本，Tag找标签，script找script的内容
	
	private $offset = 0;//当前处理到的位置
	
	private $out = '';//格式化后的输出
	
	private $indentStr = "\t";//缩进字符串
	
	private $currentIndentCnt = 0;//当前缩进的个数
	
	function __construct($str) {
		$this->str = $str;
	}
	
	
	private function multiLine($content){
		if ($content && $trimContent = trim($content)) {
			$lines = preg_split("#\r?\n#", $trimContent,-1,PREG_SPLIT_NO_EMPTY);
			$lines = array_map('trim', $lines);
			foreach ($lines as $line){
				$this->newLine($line);
			}
		}
	}
	
	function getText() {
		$pos = strpos($this->str,'<',$this->offset);
		//TODO 未考虑文字分行的情况
		if (FALSE===$pos) {
			$content = substr($this->str, $this->offset);
			$this->offset += 999999999;
		}else{
			$content = substr($this->str, $this->offset,$pos-$this->offset);
			$this->offset = $pos;
			$this->currentModel = 'Tag';
		}
		
		$this->multiLine($content);
	}
	
	
	
	function getTag() {
		$pos = strpos($this->str, '>',$this->offset);
		if (FALSE===$pos) {
			exit('error');
		}else {
			$content = substr($this->str, $this->offset,$pos-$this->offset+1);
			$this->offset = $pos+1;
			$this->currentModel = 'Text';
			if(0===strpos($content, '</')||0===strpos($content, '<!--')){ //结束标签 | 注释
				$this->currentIndentCnt--;
				$this->newLine($content);
			}elseif (preg_match('#^<([!\w]+)#', $content,$matches)) {
				$this->newLine($content);
				$tag = strtolower($matches[1]);
				if (!in_array($tag, explode(',', '!doctype,base,br,col,command,hr,input,img,keygen,link,meta,source'))) {
					$this->currentIndentCnt++;
					if('script'==$tag && FALSE===strpos($content, 'text/html')) {
						//是script;
						$this->currentModel = 'script';
					}elseif ('style'==$tag) {
						//是style;
						$this->currentModel = 'style';
					}
				}
				
			}
		}
	}
	
	function getScript() {
		$pos = strpos($this->str, '</script>',$this->offset);
		if (FALSE===$pos) {
			exit('no end script tag');
		}else {
			$content = substr($this->str, $this->offset,$pos-$this->offset);
			$this->offset = $pos;
			$this->multiLine($content);
			
			$this->currentModel = 'Tag';
		}
	}
	
	function getStyle() {
		$pos = strpos($this->str, '</style>',$this->offset);
		if (false === $pos) {
			exit('no end style tag');
		}else {
			$content = substr($this->str, $this->offset,$pos-$this->offset);
			$this->offset = $pos;
			$this->multiLine($content);
				
			$this->currentModel = 'Tag';
		}
	}
	
	/**
	 * 新加一行
	 * @param string $content
	 */
	private function newLine($content){
		if ($content) {
			if ($this->out) {
				$this->out	.=	"\n".str_pad('', $this->currentIndentCnt,$this->indentStr);
			}
			$this->out	.=	$content;
		}
	}

	function __toString() {
		$len = strlen($this->str);
		while ($this->offset<$len) {
			$method = 'get'.$this->currentModel;
			$this->$method();
		}
		return $this->out;
	}
	
	
	/**
	 * 公开格式化方法
	 * @param string $str 要格式化的字符串
	 * @return string
	 */
	static function formate($str) {
		$me = new Htmlformate($str);
		return ''.$me;
	}
}

if (isset($_GET['testhtmlformate'])) {
	echo Htmlformate::formate('2<b>1</b>');
}
