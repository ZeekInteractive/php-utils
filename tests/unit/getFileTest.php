<?php

namespace Zeek\PHP_Util;

use Codeception\Test\Unit;
use org\bovigo\vfs\vfsStream;

class GetFileTest extends Unit {

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

		$vfs = new vfsStream();
		$this->file_system = $vfs->setup( 'root', 444, $directory );
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