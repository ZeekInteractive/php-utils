<?php

namespace Zeek\PHP_Util;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class GetFileTest extends TestCase {

	/**
	 * @var vfsStream
	 */
	private $file_system;

	function setUp() {
		$directory = [
			'json' => [
				'valid.json'   => '{"VALID_KEY":123}',
				'invalid.json' => '{"test":123',
			]
		];

		$this->file_system = vfsStream::setup( 'root', 444, $directory );
	}

	function testInvalidFileTypes() {

		$this->expectException( \Exception::class );
		get_file( '', 'csv' );

		$this->expectException( \Exception::class );
		get_file( '', 'exe' );

		$this->expectException( \Exception::class );
		get_file( '', 'dmg' );

		$this->expectException( \Exception::class );
		get_file( '', 'png' );

		$this->expectException( \Exception::class );
		get_file( '', 'jpg' );
	}

	function testNonExistentFile() {

		$this->assertEquals( false, get_file( 'non-existent-file', 'txt' ) );
	}

	function testRetrieveRealFile() {
		$example_file = $this->file_system->url( 'json' ) . '/json/valid';

		$this->assertEquals( '{"VALID_KEY":123}', get_file( $example_file, 'json' ) );
	}

	function testRetrieveRealFileWrongExtension() {
		$example_file = $this->file_system->url( 'json' ) . '/json/valid';

		$this->assertEquals( false, get_file( $example_file, 'txt' ) );
	}
}