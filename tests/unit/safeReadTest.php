<?php

namespace Zeek\PHP_Util;

use Codeception\Test\Unit;

class SafeReadTest extends Unit {

	public function testSafeReadDefined() {

		$this->assertEquals( true, function_exists( 'Zeek\PHP_Util\safe_read' ) );
	}

	public function testSafeReadCanReadExistingIndice() {
		$array_to_test = [
			'index1' => 'somevalue',
		];

		$this->assertEquals( 'somevalue', safe_read( $array_to_test, 'index1' ) );
	}

	public function testSafeReadReturnsFalseOnInvalidKey() {
		$array_to_test = [
			'index1' => 'somevalue',
		];

		$this->assertEquals( false, safe_read( $array_to_test, 'index2' ) );
	}

	public function testSafeReadHandlesObject() {
		$object = new \stdClass();
		$object->index1 = 'somevalue';

		$this->assertEquals( 'somevalue', safe_read( $object, 'index1' ) );
	}

	public function testSafeReadObjectInvalidKey() {
		$object = new \stdClass();

		$this->assertEquals( false, safe_read( $object, 'index2' ) );
	}

	public function testSafeReadHandlesNull() {
		$this->assertEquals( false, safe_read( null, 'index1' ) );
	}

	public function testSafeReadHandlesEmptyString() {
		$this->assertEquals( false, safe_read( '', 'index1' ) );
	}

}