<?php

/**
 *  test case.
 */
class Test extends PHPUnit_Framework_TestCase {
	
	private $shaml;
	
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
		// TODO Auto-generated Test::tearDown()
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	function testHtml() {
		$tpl= <<<EOF
:javascript
	var x = 5
:php
	\$my = 'x';
EOF;
		$tpl = trim($tpl);
		$expect = <<<EOF
<script type="text/javascript">
	var x = 5</script>
<?php 
	\$my = 'x';
?>
EOF;
		$this->compareTpl($tpl, $expect);
		
	}
	
	function testJavascriptContent() {
		$tpl= <<<EOF
:javascript
	var x = 5;
EOF;
		$tpl = trim($tpl);
		$expect = <<<EOF
<script type="text/javascript">
	var x = 5;</script>
EOF;
		$this->compareTpl($tpl, $expect);
	}
	
	function compareTpl($tpl,$expect) {
		$tpl = trim($tpl);
		$expect = trim($expect);
		$expect = str_replace("\r\n", "\n", $expect);
		$result = $this->shaml->compile($tpl);
		$trimResult = trim($result);
		file_put_contents('t.txt', $trimResult);
		$this->assertEquals($trimResult, $expect);
	}
}

