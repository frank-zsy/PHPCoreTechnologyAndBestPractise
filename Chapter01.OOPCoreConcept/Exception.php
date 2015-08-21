<?php

class emailException extends Exception {
}

class pwdException extends Exception {
	function __toString() {
		return "Exception {$this->getCode()}:{$this->getMessage()} in File:{$this->getFile()} on line {$this->getLine()}";
	}
}

function reg($regInfo = null) {
	if (empty($regInfo) || !isset($regInfo)) {
		throw new Exception("Illegal parameter!");
	}
	if (empty($regInfo['email'])) {
		throw new emailException("Email is empty!", 601);
	}
	if ($regInfo['pwd'] != $regInfo['repwd']) {
		throw new pwdException("Password not identical twice!", 602);
	}
	echo "注册成功";
}

try {
	reg(array("email" => "fankzhao@gmail.com", "pwd" => "123", "repwd" => "1234"));
} catch(emailException $ee) {
	echo $ee->getMessage() . PHP_EOL;
} catch(pwdException $pe) {
	echo $pe . PHP_EOL;	// Will catch this exception and use __toString function to echo it.
} catch(Exception $e) {
	echo $e->getTraceAsString(). PHP_EOL;
}
