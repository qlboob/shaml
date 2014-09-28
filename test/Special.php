<?php


/**
 * test case.
 */
class Special extends PHPUnit_Framework_TestCase {
	
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
	
	function testDelComment() {
		$tpl = <<<EOF
/ no
EOF;
		$this->compareTpl($tpl, '');
	}
	
	/**
	 * Ô­ÑùÊä³ö
	 */
	function testOrg() {
		$tpl = <<<OEF
| bbs
OEF;
		$this->compareTpl($tpl, 'bbs');
		$tpl = <<<OEF
|
	bbs
OEF;
		$this->compareTpl($tpl, '	bbs');
	}
	
	function testHtmlComment() {
		$tpl = <<<OEF
// comment
OEF;
		$this->compareTpl($tpl, '<!--comment-->');
		$tpl = <<<OEF
//
	bbs
OEF;
		$expect = <<<EOF
<!--
	bbs
-->
EOF;
		$this->compareTpl($tpl, $expect);
	}
}

