<?php

namespace Zeek\PHP_Util;


class RecursiveRemoveDirectoryTest extends \Codeception\Test\Unit {

	/**
	 * @var \UnitTester
	 */
	protected $tester;

	private $rrmdir;

	private $test_file = 'file.txt';

	protected function _before() {
		$this->rrmdir = codecept_data_dir( 'rrmdir' );
		$this->test_file = codecept_data_dir( 'rrmdir/file.txt' );

		// Create directory
		mkdir( $this->rrmdir, 0777, true );

		// Create file
		fopen( $this->test_file, 'w' );
	}

	// tests
	public function testFileExists() {
		$this->assertFileExists( $this->test_file );

		rrmdir( $this->rrmdir );

		$this->assertFileNotExists( $this->test_file );
	}
}