<?php


/**
 * test case.
 */
class Php extends PHPUnit_Framework_TestCase {
	
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
	
	function testIf() {
		$tpl = <<<EOF
if 5>4
	5 is biger than 4
elseif 3<4
	3 is smaller than 4
EOF;
		$except	=	<<<EOF
<?php if(5>4){?>
	5 is biger than 4
<?php }elseif(3<4){?>
	3 is smaller than 4
<?php }?>
EOF;
		$this->compareTpl($tpl,$except);
	}
	
	function testForeach() {
		$tpl = <<<EOF
foreach lists
	1
foreach lists val
	2
foreach lists key val
	3
foreach lists key val 4
EOF;
		$except	=	<<<EOF
<?php foreach(\$lists as \$k=>\$v){?>
	1
<?php }?>
<?php foreach(\$lists as \$k=>\$val){?>
	2
<?php }?>
<?php foreach(\$lists as \$key=>\$val){?>
	3
<?php }?>
<?php foreach(\$lists as \$key=>\$val){?>4<?php }?>
EOF;
		$this->compareTpl($tpl,$except);
	}
	
	function testWhile() {
		$tpl = <<<EOF
while \$x > 5
	x is biger than 5
EOF;
		$except	=	<<<EOF
<?php while(\$x > 5){?>
	x is biger than 5
<?php }?>
EOF;
		$this->compareTpl($tpl,$except);
	}
	
	
	
}

