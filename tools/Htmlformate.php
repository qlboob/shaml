<?php
/**
 * ��ʽ��|����HTML���롣html���������д��ȷ
 * @author lukeqin
 *
 */
class Htmlformate {
	private $str;//��ʽ�����ַ�
	
	private $currentModel = 'Text';//��ǰ�����ַ�����ģʽ��Text����ͨ�ı���Tag�ұ�ǩ��script��script������
	
	private $offset = 0;//��ǰ������λ��
	
	private $out = '';//��ʽ��������
	
	private $indentStr = "\t";//�����ַ���
	
	private $currentIndentCnt = 0;//��ǰ�����ĸ���
	
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
		//TODO δ�������ַ��е����
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
			if(0===strpos($content, '</')||0===strpos($content, '<!--')){ //������ǩ | ע��
				$this->currentIndentCnt--;
				$this->newLine($content);
			}elseif (preg_match('#^<([!\w]+)#', $content,$matches)) {
				$this->newLine($content);
				$tag = strtolower($matches[1]);
				if (!in_array($tag, explode(',', '!doctype,base,br,col,command,hr,input,img,keygen,link,meta,source'))) {
					$this->currentIndentCnt++;
					if('script'==$tag && FALSE===strpos($content, 'text/html')) {
						//��script;
						$this->currentModel = 'script';
					}elseif ('style'==$tag) {
						//��style;
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
	 * �¼�һ��
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
	 * ������ʽ������
	 * @param string $str Ҫ��ʽ�����ַ���
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
