<?php


/**
 * test case.
 */
class DocType extends PHPUnit_Framework_TestCase {
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		include_once dirname(__FILE__).'/../Shaml.php';
		$this->shaml = new Shaml();
		// TODO Auto-generated Test::setUp()
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated Special::tearDown()
		parent::tearDown ();
	}
	
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	function compareTpl($tpl,$expect) {
		$tpl = trim($tpl);
		$expect = trim($expect,"\r\n");
		$expect = str_replace("\r\n", "\n", $expect);
		$result = $this->shaml->compile($tpl);
		$trimResult = trim($result,"\r\n");
		file_put_contents('t.txt', $trimResult);
		$this->assertEquals($trimResult, $expect);
	}
	
	function testHtml5() {
		$tpl = <<<EOF
html5
EOF;
		$this->compareTpl($tpl, '<!DOCTYPE html>');
	}
	
	function testXhtml1_trans() {
		$tpl = <<<EOF
xhtml1-trans
EOF;
		$this->compareTpl($tpl, '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">');
	}
	
	
}

