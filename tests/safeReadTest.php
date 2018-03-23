<?php

namespace Zeek\PHP_Util;

class UtilsTest extends \PHPUnit_Framework_TestCase {

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
//
//	/**
//	 * @expectedException \Error
//	 */
//	public function testSafeReadDoesntHandleObjects() {
//		$object_to_test = new \stdClass();
//		$object_to_test->index1 = 'somevalue';
//
//		safe_read( $object_to_test, 'index1' );
//	}

	public function testSafeReadHandlesNull() {
		$this->assertEquals( '', safe_read( null, 'index1' ) );
	}

	public function testSafeReadHandlesEmptyString() {
		$this->assertEquals( '', safe_read( '', 'index1' ) );
	}

}