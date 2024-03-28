<?php

namespace unit\zip;

use PHPUnitTestCase;
use WordPress\Zip\ZipException;
use function WordPress\Zip\zip_extract_to;

class ZipFunctionsTest extends PHPUnitTestCase {
	public function testThrowsExceptionWhenZipContainsFilesWithRelativePaths() {
		// zipped file named: "../../../../../../../../tmp/zip-slip-test.txt"
		$zip = __DIR__ . '/resources/zip-slip-test.zip';

		self::expectException( ZipException::class );
		self::expectExceptionMessage( "Relative paths in zips are not allowed." );
		zip_extract_to( fopen( $zip, 'rb' ), dirname( $zip ) );
	}

	public function testThrowsExceptionWhenZipContainsFilesWithSymlinks1() {
		// zipped semantic link
		$zip = __DIR__ . '/resources/test.zip';

		self::expectException( ZipException::class );
		self::expectExceptionMessage( "Relative paths in zips are not allowed." );
		zip_extract_to( fopen( $zip, 'rb' ), dirname( $zip ) );
	}

	public function testThrowsExceptionWhenZipContainsFilesWithSymlinks2() {
		// zipped semantic link
		$zip = __DIR__ . '/resources/zip-symlink-test.zip';

		self::expectException( ZipException::class );
		self::expectExceptionMessage( "Relative paths in zips are not allowed." );
		zip_extract_to( fopen( $zip, 'rb' ), dirname( $zip ) );
	}

	public function testThrowsExceptionWhenZipContainsFilesWithSymlinks3() {
		// zipped semantic link
		$zip = __DIR__ . '/resources/zip-symlinks-test.zip';

		self::expectException( ZipException::class );
		self::expectExceptionMessage( "Relative paths in zips are not allowed." );
		zip_extract_to( fopen( $zip, 'rb' ), dirname( $zip ) );
	}
}
