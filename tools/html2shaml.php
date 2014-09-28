<?php
include_once dirname(__FILE__).'/Htmlformate.php';
function replaceOpenTag($matches) {
	$tag = $matches[1];
	$attrStr = $matches[2];
	$attrArr = array();
	$idAndClass = '';
	$resultAttrStr = '';
	$pattern = array(
			'#^\s*([-\w]+)="([^"]*?)"#',#双引号的情况
			"#^\s*([-\w]+)='([^']*?)'#",#单引号的情况
			'#^\s*([-\w]+)=([^ ]+)#',#等号后直接写属性值的情况
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
	
	//处理特殊情况
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
	//拼属性串
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
	//去掉结束标签
	$ret = preg_replace('#</\w+>#', '', $html);
	//替换开始标签
	$ret = preg_replace_callback ('#<(\w+) ?(.*?)/?>#', 'replaceOpenTag', $ret);
	//去掉多余的空行
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
			经典
		</a>
	</li>
	<li>
		<a data-value="cerulean" href="#">
			<i class="icon-blank">
			</i>
			天蓝色
		</a>
	</li>
	<li>
		<a data-value="cyborg" href="#">
			<i class="icon-blank">
			</i>
			黑色
		</a>
	</li>
	<li>
		<a data-value="redy" href="#">
			<i class="icon-blank">
			</i>
			现代
		</a>
	</li>
	<li>
		<a data-value="journal" href="#">
			<i class="icon-blank">
			</i>
			白色
		</a>
	</li>
	<li>
		<a data-value="simplex" href="#">
			<i class="icon-blank">
			</i>
			纯色
		</a>
	</li>
	<li>
		<a data-value="slate" href="#">
			<i class="icon-blank">
			</i>
			深蓝灰色
		</a>
	</li>
	<li>
		<a data-value="spacelab" href="#">
			<i class="icon blank">
			</i>
			实验室
		</a>
	</li>
	<li>
		<a data-value="united" href="#">
			<i class="icon-blank">
			</i>
			橙色
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
  <title> Ajax演示 </title>
  <meta charset="utf-8">
  <meta name="Generator" content="EditPlus">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
 </head>

 <body>
 <h2>代码必须放到http服务器上才能运行。</h2>
  <a href="###" id="action">载入学生成绩</a>
  <div id="result"></div>
  <script type="text/html" id="tpl">
	<table border="1">
		<thead>
			<tr>
				<th>名次</th>
				<th>姓名</th>
				<th>得分</th>
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
	//1.创建Ajax对象
	if(window.XMLHttpRequest)
	{
		var oAjax=new XMLHttpRequest();
	}
	else
	{
		var oAjax=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	//2.连接服务器（打开和服务器的连接）
	oAjax.open('GET', url, true);
	
	
	//3.发送
	oAjax.send();
	
	//4.接收
	oAjax.onreadystatechange=function ()
	{
		if(oAjax.readyState==4)
		{
			if(oAjax.status==200)
			{
				//alert('成功了：'+oAjax.responseText);
				fnSucc(oAjax.responseText);
			}
			else
			{
				//alert('失败了'+oAjax.status);
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
   	 	/* 模板替换,str:模板id或者内容，data:数据内容
			\W：匹配任何非单词字符。等价于 '[^A-Za-z0-9_]'。 
			如果是id,并且cache中有值，直接返回，否则获取innerHTML，再次解析；
			如果不是id，解析并存入cache
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