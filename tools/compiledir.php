<?php
/**
* usage
* php -f __FILE__ {被编译的目录} {输出目录} [{被编译的文件}] [{输出文件的扩展名}]
 */
error_reporting(E_ALL & ~E_NOTICE);
$pwd = dirname ( __FILE__ );
require "$pwd/../Shaml.php";

$templateDir = '';
$outputDir = '';
$ext = '';

#test
//$argv = array("$pwd/../../xserp/1/App/TplSrc","d:/www/tmp",'php');
	//var_dump($argv);
if (!empty($argv)) {
	#命令行方式运行，把第一个参数当模板目录，第二个参数当输出目录
	list($file,$templateDir,$outputDir,$complieFile,$ext) = $argv;
}elseif(!empty($_REQUEST)){
	$templateDir = $_REQUEST['in'];
	$outputDir = $_REQUEST['out'];
	$ext = $_REQUEST['ext'];
}else {
	exit('input arg error');
}
if ( !empty($complieFile) ) {
	if ( !is_file($complieFile) ) {
		$ext = $complieFile;
	}
}
$ext or $ext = 'html';

if ( !$templateDir || !is_dir($templateDir)) {
	exit('template dir is error');
}elseif(!$outputDir or !is_dir($outputDir)){
	exit('output dir is error');
}else{
	$templateDir = realpath($templateDir);
	$outputDir = realpath($outputDir);
}


/**
 * make directories
 * @param string $dir directory name
 * @param string $mode mode
 */
if (!function_exists('mk_dir')) {
	function mk_dir($dir, $mode = 0755) {
		if (is_dir ( $dir ) || @mkdir ( $dir, $mode ))
			return true;
		if (! mk_dir ( dirname ( $dir ), $mode ))
			return false;
		return @mkdir ( $dir, $mode );
	}
}
/**
 * write file
 * @param $filePath file path
 * @param $content file content
 */
if (!function_exists('writeFile')) {
	function writeFile($filePath, $content) {
		$dir = dirname ( $filePath );
		mk_dir ( $dir );
		if (!is_file($filePath) or file_get_contents($filePath)!=$content) {
			file_put_contents ( $filePath, $content );
		}
	}
}


/**
 * 编译目录
 **/
function compileDir($templateDir,$outputDir,$ext){
	$smile = new Shaml();
	if ( $handle = opendir($templateDir) ) {
		#读取目录
		while ( $file=readdir($handle) ) {
			if ( in_array($file[0],array('.','_')) ) {
				continue;
			}
			$outName = preg_replace('#\.\w+$#',".$ext",$file);
			$templateFile = "$templateDir/$file";
			$outputFile = "$outputDir/$outName";
			if ( is_dir($templateFile) ) {
				#递归编辑子目录
				compileDir("$templateDir/$file","$outputDir/$file",$ext);
			}elseif(is_file($templateFile)) {
				if ( file_exists($outputFile) and (!is_writable($outputFile) or filemtime($outputFile)> filemtime($templateFile) )) {
					#跳过不能写的文件
					continue;
				}
				$fileContent = file_get_contents($templateFile);
				$compliedContent = $smile->compile($fileContent);
				writeFile($outputFile,$compliedContent);
				echo "$outputFile \n";
			}
		}
	}
}

/**
 * 编译一个文件
 */
function compileFile($templateDir,$outputDir,$templateFile,$ext){
	$templateFile = realpath($templateFile);
	$outName = preg_replace('#\.\w+$#',".$ext",$templateFile);
	$outputFile = str_ireplace($templateDir,$outputDir,$outName);
	if ( file_exists($outputFile) and (!is_writable($outputFile) or filemtime($outputFile)> filemtime($templateFile) )) {
		#跳过不能写的文件
		return ;
	}
	$fileContent = file_get_contents($templateFile);
	$smile = new Shaml();
	$compliedContent = $smile->compile($fileContent);
	writeFile($outputFile,$compliedContent);
	echo "one file \n $outputFile \n";
}

if ( empty($complieFile) or !is_file($complieFile) ) {
	compileDir($templateDir,$outputDir,$ext);
}else{
	compileFile($templateDir,$outputDir,$complieFile,$ext);
}
