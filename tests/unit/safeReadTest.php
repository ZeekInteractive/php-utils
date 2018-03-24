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

	public function testSafeReadReturnsEmptyStringOnInvalidKey() {
		$array_to_test = [
			'index1' => 'somevalue',
		];

		$this->assertEquals( '', safe_read( $array_to_test, 'index2' ) );
	}

	public function testSafeReadHandlesObject() {
		$object = new \stdClass();
		$object->index1 = 'somevalue';

		$this->assertEquals( false, safe_read( $object, 'index1' ) );
	}

	public function testSafeReadHandlesNull() {
		$this->assertEquals( '', safe_read( null, 'index1' ) );
	}

	public function testSafeReadHandlesEmptyString() {
		$this->assertEquals( '', safe_read( '', 'index1' ) );
	}

}