<?php

trait PrintSomething {
	private static function printOut() {
		echo "This is a print function." . PHP_EOL;
	}
}

class Test {
	public static $name = "Test";
	public $publicAttr;
	private $privateAttr;

	use PrintSomething;

	public function __construct() {
		$this->publicAttr = "Public attr";
		$this->privateAttr = "Private attr";
	}

	public function __get($name) {	// declare as private will get a warning but still work
		echo "In __get function" . PHP_EOL;
		if (!isset($this->$name)) {
			$this->$name = "New attr";
		}
		return $this->$name;
	}

	public static function __callStatic($method, $args) {	// declare as non-static will get a warning but still work
		$kclass = get_called_class();
		$cls_methods = get_class_methods($kclass);
		if (in_array($method, $cls_methods)) {
			call_user_func_array(array($kclass, $method), $args);
		}
	}

	public static function printMethods() {
		$kclass = get_called_class();
		$cls_methods = get_class_methods($kclass);
		var_dump($cls_methods);
	}

	private static function printOut() {
		echo "This is a print function in Test." . PHP_EOL;	// Override the printOut function in trait PrintSomething
	}

}

// Magic functions test
$test = new Test;	// new Test equals new Test()
echo $test->publicAttr . PHP_EOL;	// public variables do not trigger __get function
echo $test->privateAttr . PHP_EOL;
echo $test->newAttr . PHP_EOL;	// __get will be triggered by private or undefined variables

// Static variables test
echo Test::$name . PHP_EOL;
echo $test::$name . PHP_EOL;	// $test->name will cause an error but this is OK

Test::printMethods();

// Trait test
Test::printOut();
