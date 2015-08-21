<?php

$date = "2012-12-20";
if (ereg("([0-9]{4})-([0-9]{2})-([0-9]{2})", $date, $regs)) {	// Deprecated: ereg is a deprecated function
	echo "{$regs[3]}.{$regs[2]}.{$regs[1]}" . PHP_EOL;
} else {
	echo "Invalid date format: {$date}" . PHP_EOL;
}

if ($i > 5) {	// Notice: undefined varaible
	echo "{$i} has not been initialized!" . PHP_EOL;
}

$a = array("o" => 2, 4, 6, 8);
echo $a[o] . PHP_EOL;	// Notice: use undefined constant

$result = array_sum($a, 3);	// Warning: function parameter number invalid

echo fun() . PHP_EOL;	// Fatal Error: call undefined function
echo "After a fatal error!" . PHP_EOL;	// Will not reach here
