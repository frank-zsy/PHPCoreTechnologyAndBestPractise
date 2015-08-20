<?php

class Test {
	public static $name = "Test";
	public $publicAttr;
	private $privateAttr;

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

// Reflection test
$obj = new ReflectionClass('Test');	// Will not have newAttr
$className = $obj->getName();
$methods = $properties = $traits = array();
foreach ($obj->getProperties() as $v) {
	$properties[$v->getName()] = $v;
}
foreach ($obj->getMethods() as $m) {
	$methods[$m->getName()] = $m;
}
foreach ($obj->getTraits() as $m) {
	$traits[$m->getName()] = $m;
}
echo "Class {$className} {" . PHP_EOL;
echo PHP_EOL;
foreach ($properties as $key => $value) {
	echo "    ";
	echo $value->isPublic() ? "public " : ($value->isPrivate() ? "private " : ($value->isProtected() ? "protected " : ""));
	echo $key . ";". PHP_EOL;
}
echo PHP_EOL;
foreach ($methods as $key => $value) {
	echo "    ";
	echo $value->isPublic() ? "public " : ($value->isPrivate() ? "private " : ($value->isProtected() ? "protected " : ""));
	echo "function {$key}(){}" . PHP_EOL;
}
echo PHP_EOL;
foreach ($traits as $key => $value) {
	echo "    trait {$key} : {$value}" . PHP_EOL;
}
echo "}" . PHP_EOL;

