<?php

namespace Zeek\PHP_Util;


class RecursiveRemoveDirectoryTest extends \Codeception\Test\Unit {

	/**
	 * @var \UnitTester
	 */
	protected $tester;

	private $base_dir;
	private $rrmdir;

	private $test_file = 'file.txt';

	protected function _before() {
		$this->base_dir = codecept_data_dir();

		$this->rrmdir = $this->base_dir . 'rrmdir_test';
		$this->test_file = $this->rrmdir . '/test/file.txt';

		rrmdir( $this->rrmdir );

		// Create directory
		mkdir( $this->rrmdir );
		mkdir( $this->rrmdir . '/test' );

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