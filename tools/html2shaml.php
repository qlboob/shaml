<?php
include_once dirname(__FILE__).'/Htmlformate.php';
function replaceOpenTag($matches) {
	$tag = $matches[1];
	$attrStr = $matches[2];
	$attrArr = array();
	$idAndClass = '';
	$resultAttrStr = '';
	$pattern = array(
			'#^\s*([-\w]+)="([^"]*?)"#',#˫���ŵ����
			"#^\s*([-\w]+)='([^']*?)'#",#�����ŵ����
			'#^\s*([-\w]+)=([^ ]+)#',#�Ⱥź�ֱ��д����ֵ�����
	);
	if ($attrStr) {
		$attrLine = $attrStr;
		while(true){
			$br = true;
			foreach($pattern as $v){
				if(preg_match($v,$attrLine,$match)){
					$br = false;
					$attrLine = substr($attrLine, strlen($match[0]));
					$attrArr[$match[1]] = $match[2];
					break;
				}
			}
			if($br){
				break;
			}
		}
	}
	
	if (isset($attrArr['id'])) {
		$idAndClass .="#{$attrArr['id']}";
		unset($attrArr['id']);
	}
	if (isset($attrArr['class'])) {
		$attrArr['class'] = str_replace(' ', '.', $attrArr['class']);
		$idAndClass .=".{$attrArr['class']}";
		unset($attrArr['class']);
	}
	
	//�����������
	if ('div'==$tag && $idAndClass) {
		$tag = '';
	}elseif ('style'==$tag){
		$tag = ':style';
	}elseif ('script'==$tag and (empty($attrArr['type'])||'text/html'!=$attrArr['type'])){
		$tag = ':javascript';
		if(isset($attrArr['type'])){
			unset($attrArr['type']);
		}
	}
	//ƴ���Դ�
	foreach ($attrArr as $k=>$v) {
		if (FALSE!==strpos($v, '"')) {
			$v = sprintf("'%s'",$v);
		}elseif (FALSE!==strpos($v, "'")||FALSE!==strpos($v, " ")|| !$v){
			$v = sprintf('"%s"',$v);
		}
		$resultAttrStr .= " $k=$v";
	}
	return "$tag{$idAndClass}$resultAttrStr";
}
function html2shaml($html) {
	$html = Htmlformate::formate($html);
	//ȥ��������ǩ
	$ret = preg_replace('#</\w+>#', '', $html);
	//�滻��ʼ��ǩ
	$ret = preg_replace_callback ('#<(\w+) ?(.*?)/?>#', 'replaceOpenTag', $ret);
	//ȥ������Ŀ���
	$ret = preg_replace('/($\s*$)|(^\s*(\r|rn)\s*^)/m', '',$ret);
	return $ret;
}
if(!empty($argv) and $argv[1] and is_file($argv[1])){
	echo html2shaml(file_get_contents($argv[1]));
}
if(isset($_GET['testhtml2shaml'])){
	$str = <<<EOF
<div id="ssd">	<input class="x" value="100" />
</div><ul class="dropdown-menu" id="themes">
	<li>
		<a data-value="classic" href="#">
			<i class="icon-blank"></i>
			����
		</a>
	</li>
	<li>
		<a data-value="cerulean" href="#">
			<i class="icon-blank">
			</i>
			����ɫ
		</a>
	</li>
	<li>
		<a data-value="cyborg" href="#">
			<i class="icon-blank">
			</i>
			��ɫ
		</a>
	</li>
	<li>
		<a data-value="redy" href="#">
			<i class="icon-blank">
			</i>
			�ִ�
		</a>
	</li>
	<li>
		<a data-value="journal" href="#">
			<i class="icon-blank">
			</i>
			��ɫ
		</a>
	</li>
	<li>
		<a data-value="simplex" href="#">
			<i class="icon-blank">
			</i>
			��ɫ
		</a>
	</li>
	<li>
		<a data-value="slate" href="#">
			<i class="icon-blank">
			</i>
			������ɫ
		</a>
	</li>
	<li>
		<a data-value="spacelab" href="#">
			<i class="icon blank">
			</i>
			ʵ����
		</a>
	</li>
	<li>
		<a data-value="united" href="#">
			<i class="icon-blank">
			</i>
			��ɫ
		</a>
	</li>
</ul>
<style>
			#ssd{
			color:red;
		}
			</style>
<script type="text/javascript">alert(1);</script>
<script type="text/html" id="tpl">
			<ul><li>1</li></ul>
	</script>
EOF;
	echo html2shaml($str);
}

if (isset($_GET['testHtml'])){
	$str = <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> Ajax��ʾ </title>
  <meta charset="utf-8">
  <meta name="Generator" content="EditPlus">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
 </head>

 <body>
 <h2>�������ŵ�http�������ϲ������С�</h2>
  <a href="###" id="action">����ѧ���ɼ�</a>
  <div id="result"></div>
  <script type="text/html" id="tpl">
	<table border="1">
		<thead>
			<tr>
				<th>����</th>
				<th>����</th>
				<th>�÷�</th>
			</tr>
		</thead>
		<tbody>
			<%var i=0,l=obj.length,v;for(;i<l;i++){v=obj[i];%>
				<tr>
					<td><%=v.no%></td>
					<td><%=v.name%></td>
					<td><%=v.score%></td>
				</tr>
			<%}%>
		</tbody>
	</table>
  </script>
  <script>
function ajax(url, fnSucc, fnFaild)
{
	//1.����Ajax����
	if(window.XMLHttpRequest)
	{
		var oAjax=new XMLHttpRequest();
	}
	else
	{
		var oAjax=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	//2.���ӷ��������򿪺ͷ����������ӣ�
	oAjax.open('GET', url, true);
	
	
	//3.����
	oAjax.send();
	
	//4.����
	oAjax.onreadystatechange=function ()
	{
		if(oAjax.readyState==4)
		{
			if(oAjax.status==200)
			{
				//alert('�ɹ��ˣ�'+oAjax.responseText);
				fnSucc(oAjax.responseText);
			}
			else
			{
				//alert('ʧ����'+oAjax.status);
				if(fnFaild)
				{
					fnFaild(oAjax.status);
				}
			}
		}
	};
}
(function(){
	var _formatJson_cache = {};
	
	\$formatJson=function(str, data){
   	 	/* ģ���滻,str:ģ��id�������ݣ�data:��������
			\W��ƥ���κηǵ����ַ����ȼ��� '[^A-Za-z0-9_]'�� 
			�����id,����cache����ֵ��ֱ�ӷ��أ������ȡinnerHTML���ٴν�����
			�������id������������cache
		 */
		var fn = !/\W/.test(str)?
			_formatJson_cache[str]=_formatJson_cache[str] || \$formatJson(\$id(str).innerHTML) :
				new Function("obj",
					"var p=[],print=function(){p.push.apply(p,arguments);};" +
					"with(obj){p.push('" +str
					.replace(/[\r\t\n]/g, " ")
					.split("<%").join("\t")
					.replace(/((^|%>)[^\t]*)'/g, "\$1\r")
					.replace(/\t=(.*?)%>/g, "',\$1,'")
					.split("\t").join("');")
					.split("%>").join("p.push('")
					.split("\r").join("\\'") + "');}return p.join('');");
		return data ? fn( data ) : fn;
	}
})()
function \$id(id){
	return typeof(id)=="string"?document.getElementById(id):id;
}
document.getElementById('action').onclick=function(){
	ajax('result.js',function(data){
		var json = eval('('+data+')');
		var html = \$formatJson('tpl',json);
		\$id('result').innerHTML = html;
	});
	
}
  </script>
 </body>
</html>
EOF;
	echo html2shaml($str);
}