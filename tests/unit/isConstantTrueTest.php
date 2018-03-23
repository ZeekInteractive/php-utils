<?php

namespace Zeek\PHP_Util;

use Codeception\Test\Unit;

class IsConstantTrueTest extends Unit {

	public function testIsConstantTrueDefined() {

		$this->assertEquals( true, function_exists( 'Zeek\PHP_Util\is_constant_true' ) );
	}

	public function testCheckDefinedConstantTrue() {

		define( 'CONSTANT_TEST_1', true );

		$this->assertEquals( true, is_constant_true( 'CONSTANT_TEST_1' ) );
	}

	public function testCheckDefinedConstantNotTrue() {

		define( 'CONSTANT_TEST_2', 'some_other_value' );

		$this->assertEquals( false, is_constant_true( 'CONSTANT_TEST_2' ) );
	}

	public function testCheckUndefinedConstantIsFalse() {

		$this->assertEquals( false, is_constant_true( 'CONSTANT_TEST_3' ) );
	}
}