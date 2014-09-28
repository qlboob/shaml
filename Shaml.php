<?php
/**
 * 类ruby slim风格的php模板引擎
 * make U smile HTML abstraction markup language
 * @author lukeqin
 */
class Shaml {
	/**
	* 节点类型
	*/ 
	private $nodes = array();

	/**
	 * 所有的行数据
	 */
	private $lines;

	/**
	 * 当前处理的行号，从0开始
	 */
	private $curLineNo=0;
	#当前行数据
	private $line;

	#已经存在的开始标签
	private $stash = array();
	
	/**
	 * @var Node 上一次输出的元素
	 */
	private $lastNode;
	
	/**
	 * @var array 当前行输出的节点集合
	 */
	private $lineNodes=array();

	#当前行信息
	private $curLineInfo = array();
	/**
	 * 对外输出内容
	 */
	private $output;
	function __construct (){
		#加载节点;
		$nodesFiles = glob(dirname(__FILE__).'/node/*Node.php');
		foreach($nodesFiles as $f){
			include_once $f;
			$clsName = basename($f,'.php');
			$this->nodes[] = $clsName;
		}
		#加载配置
		$configFiles = glob(dirname(__FILE__).'/config/*.php');
		foreach($configFiles as $f){
				self::config(include $f);
		}
		#加载tag
		$tagFiles = glob(dirname(__FILE__).'/tag/*.php');
		foreach($tagFiles as $f){
				self::tag(include $f);
		}
	}
	
	/**
	 * get/set config
	 * @param mixed $name
	 * @param mixed $value
	 */
	static function _config($group,$name=NULL,$value=NULL) {
		static $_config	=	array();
		isset($_config[$group]) || $_config[$group]=array();
		$groupConfig = &$_config[$group];
		if (is_null($name)) {
			return $groupConfig;
		}
		if (is_string($name)) {
			$name	=	strtolower($name);
			if (! strpos ( $name, '.' )) {
				if (is_null($value))
					return isset($groupConfig[$name])?$groupConfig[$name]:null;
				$groupConfig[$name]	=	$value;
				return;
			}
			$name	=	explode('.', $name);
			$config	=	&$groupConfig;
			$confKey=	array_pop($name);
			foreach ($name as $k) {
				if (!isset($config[$k])) {
					$config[$k]	=	array();
				}
				$config	=	&$config[$k];
			}
			if (is_null ( $value ))
				return isset($config[$confKey])?$config[$confKey]:NULL;
			$config[$confKey]	=	$value;
			return;
		}
		if (is_array($name)) {
			return $groupConfig = array_merge ( $groupConfig, array_change_key_case ( $name ) );
		}
		return NULL;
	}
	
	static function config ($name=null,$value=null){
		return Shaml::_config('config',$name,$value);
	}

	static function tag ($name=null,$value=null){
		return self::_config('tag',$name,$value);
	}
	
	

	/**
	 * 编译
	 * @param string $content 被编译的模板
	 * @return string 编译好的模板
	 */
	function compile ($content){
		$content = preg_replace('/\r\n|\r/',"\n",$content);
		$this->lines	=	explode("\n",$content);
		$this->output = '';
		$this->curLineNo = 0;
		
		$cnt = count($this->lines);
		while($this->curLineNo<$cnt){
			$this->curLineInfo = array();
			$this->line = $this->lines[$this->curLineNo];
			$this->curLineInfo['line'] = $this->line;
			$this->scanIndent();
			$this->scanTag();
			if (empty($this->curLineInfo['tag'])) {
				$this->scanSpecialTag() || $this->scanIdClassTag();
			}
			$node = $this->createNode();
			$this->dumpOne($node);
			++$this->curLineNo;
		}
		$this->dumpOld(0);
		return $this->output;
	}

	/**
	 * 导出一个节点
	 * @param Node $node
	 */
	function dumpOne ($node){
		$closeTag = $node->endDump();
		$openTag = $node->dump();
		if($node->getcontext()){
			$this->dumpOld($node->getindentCnt());
		}
		#如果不向前缩进，输出缩进文本
		if (!$node->getpreIndent() && (!$this->lastNode || !$this->lastNode->getsuffIndent())) {
			//当前节点不向上一节点靠拢
			//前一节点不要求下一节点靠拢
			empty($this->output) || $this->output .= "\n";
			$this->output .=$node->getindentText();
			$this->lineNodes = array($node);
		}else {
			$this->lineNodes[] = $node;
		}
		$this->output .= $openTag;
		if($closeTag){
			$this->stash[] = $node;
		}
		$this->lastNode = $node;
	}
	
	/**
	 * 导出以前压入的节点
	 * @param integer $indentCnt 导出以前压入的节点缩进数量
	 */
	function dumpOld($indentCnt) {
		if($this->stash){
			$pop=end($this->stash);
			while($pop && $pop->getindentCnt()>=$indentCnt){
				if (!$pop->getinlineText() && $this->lastNode !== $pop && !in_array($pop, $this->lineNodes)){
					//如果存在当前行的文本 或者没有包含子元素的标签
					//	不需要输换行和缩进文本
					$this->output .= "\n".$pop->getindentText();
				}
				$this->output .= $pop->endDump();
				array_pop($this->stash);
				$pop=end($this->stash);
			}
		}
	}
	

	/**
	 * 为本行创建一个节点
	 * @return Node
	 */
	function createNode (){
		if(!empty($this->curLineInfo['tag']) && $tagConf=self::tag($this->curLineInfo['tag'])){
			$clsName = ucfirst($tagConf['node'].'Node');
			$node = new $clsName;
			if(!empty($tagConf['arg'])){
				$node->config($tagConf['arg']);
			}
			foreach ($node->getscan() as $v){
				$method = 'scan'.$v;
				$this->$method();
			}
			#转换成属性
			if (isset($tagConf['2attr'])) {
				$this->inlineText2Attr($tagConf['2attr']);
			}
			
		}else {
			//原样输出
			$node = new TextNode();
		}
		$node->config($this->curLineInfo);
		$node->setshaml($this);
		return $node;
	}
	

	/**
	 * 扫描缩进
	 */
	function scanIndent (){
		$cnt = $this->indentCnt($this->line);
		if($cnt){
			$text = $this->indentText($cnt);
			$this->curLineInfo['indentText'] = $text;
			$this->consumeInput($text);
		}
		$this->curLineInfo['indentCnt'] = $cnt;
	}

	/**
	 * 计算缩进的数量
	 */
	private function indentCnt ($str){
		$indentCnt = self::config('indentCnt');
		$pattern = str_pad('',$indentCnt,self::config('indent'));
		$pattern = "#^($pattern)+#";
		if(preg_match($pattern,$str,$match)){
				$len = strlen($match[0]);
				return $len/$indentCnt;
		}else{
			return 0;
		}
	}
	
	/**
	 * 缩进的文本
	 */
	private function indentText ($cnt){
		return str_pad('',$cnt,self::config('indent'));
	}
	
	

	function scanTag (){
		$pattern = '/^((:?)\w[:-\w]*)/';
		return $this->scanInput($pattern,'tag');
	}
	
	/**
	 * 扫描特殊字符组成的tag
	 */
	function scanSpecialTag() {
		$specialTag = self::config('specialTag');
		//保证长的特殊字符先查找
		usort($specialTag, create_function('$a,$b', '$al=strlen($a);$bl=strlen($b);return -($al-$bl)%2;'));
		foreach ($specialTag as $p){
			if ($p === $this->line || 0===strpos($this->line, $p.' ') ) {
				$this->curLineInfo['tag'] = $p;
				$this->consumeInput($p);
				return true;
			}
		}
		return false;
	}
	
	function scanIdClassTag() {
		$ps	=	array(
			//这是可能存在多个class的情况
			'!^#[\w-]+(\.[\w-]+?)*?<?>?( |$)!',
			'!^(\.[\w-]+)+?<?>?( |$)!'
		);
		foreach ($ps as $p){
			if (preg_match($p, $this->line)) {
				$this->curLineInfo['tag'] = 'div';
				return true;
			}
		}
		return false;
	}

	function scanId (){
			return $this->scanInput('/^#([\w-]+)/', 'id','attr');
	}

	function scanClass()
    {
		while( $this->scanInput('/^\.([\w-]+)/', 'class','attr')){
		}
    }

	function scanPreIndex (){
		if('<' == $this->line[0]){
			$this->consumeInput(1);
			$this->curLineInfo['preIndent'] = true;
		}
	}
	
	function scanSuffIndex (){
		if('>' == $this->line[0]){
			$this->consumeInput(1);
			$this->curLineInfo['suffIndent'] = true;
		}
	}

	function scanAttr (){
			$pattern = array(
					'#^\s+(([^ ]+?)\?)?([-\w]+)="([^"]*?)"#',#双引号的情况
					"#^\s+(([^ ]+?)\?)?([-\w]+)='([^']*?)'#",#单引号的情况
					'#^\s+(([^ ]+?)\?)?([-\w]+)=([^ ]+)#',#等号后直接写属性值的情况
			);
			while(true){
				$br = true;
				foreach($pattern as $v){
					if(preg_match($v,$this->line,$match)){
						$br = false;
						$this->consumeInput($match[0]);
						if($match[2]){
							isset($this->curLineInfo['conditionAttr']) || $this->curLineInfo['conditionAttr'] = array();
							$this->curLineInfo['conditionAttr'][$match[3]] = array(
								'condition' => $match[2],
								'val' => $match[4],
							);
						}else{
							isset($this->curLineInfo['attr']) || $this->curLineInfo['attr'] = array();
							$this->curLineInfo['attr'][$match[3]] = $match[4];
						}
						break;
					}
				}
				if($br){
					break;
				}
			}
	}

	function scanInlineText (){
			if($this->line && ' '==$this->line[0]){
					$this->curLineInfo['inlineText'] = substr($this->line, 1);
					$this->consumeInput($this->line);
			};
	}
	
	/**
	 * 把inlineText转换成属性
	 * @param array $attrNames
	 */
	function inlineText2Attr($attrNames) {
		if (empty($this->curLineInfo['attr']) && !empty($this->curLineInfo['inlineText'])) {
			usort($attrNames, create_function('$a,$b', '$al=count($a);$bl=count($b);return -($al-$bl)%2;'));
			$attrValues = explode(' ', $this->curLineInfo['inlineText']);
			$attrValCnt = count($attrValues);
			foreach ($attrNames as $attrs){
				if (count($attrs)<=$attrValCnt) {
					empty($this->curLineInfo['attr']) && $this->curLineInfo['attr']=array();
					foreach ($attrs as $attr){
						$this->curLineInfo['attr'][$attr] = array_shift($attrValues);
					}
					$this->curLineInfo['inlineText'] = implode(' ', $attrValues);
					break;
				}
			}
		}
		
	}

	function scanContent (){
		$lineNum = count($this->lines);
		$newLineCnt = 1;
		$ret = '';
		$newLineNo = $this->curLineNo+$newLineCnt;
		while( $newLineNo< $lineNum){
			$newLineStr = $this->lines[$newLineNo++];
			if($this->curLineInfo['indentCnt']<$this->indentCnt($newLineStr)){
				$ret .= "\n".$newLineStr;
				++$newLineCnt;
			}else{
				break;
			}
		}
		$this->curLineNo += --$newLineCnt;
		$this->curLineInfo['content'] =  $ret;
	}
	
	
	
	

	private function scanInput($regex,$type,$property=null){
		$matches = array();
        if (preg_match($regex, $this->line, $matches)) {
            $this->consumeInput($matches[0]);
            if ($property) {
            	isset($this->curLineInfo[$property]) || $this->curLineInfo[$property] = array();
				if(isset($this->curLineInfo[$property][$type])){
					$this->curLineInfo[$property][$type] .= ' ' . $matches[1];
				}else{
					$this->curLineInfo[$property][$type] = $matches[1];
				}
            }else{
				if(isset($this->curLineInfo[$type])){
					$this->curLineInfo[$type] .= ' ' . $matches[1];
				}else{
					$this->curLineInfo[$type] = $matches[1];
				}
            }
			return true;
        }
		return false;
		
	}

	private function consumeInput ($len){
		is_string($len) && $len = strlen($len);
		$this->line = substr($this->line,$len);
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
	
}



#

