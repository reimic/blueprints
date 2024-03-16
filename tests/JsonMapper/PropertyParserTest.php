<?php

namespace JsonMapper;

use ReflectionClass;
use stdClass;
use WordPress\JsonMapper\PropertyParser;
use PHPUnit\Framework\TestCase;
use WordPress\JsonMapper\Property;

// phpcs:disable Squiz.Commenting
// phpcs:disable Generic.Commenting
class PropertyParserTest extends TestCase {

	public function testMapsPropertiesForScalars() {
		$class = new class() {
			/**
			 * @var string
			 */
			private $string;
		};

		$result   = PropertyParser::compute_property_map( new ReflectionClass( $class ) );
		$expected = array(
			'string' => new Property( 'string', 'private', array( 'string' ) ),
		);
		$this->assertEquals( $expected, $result );
	}

	public function testMapsPropertiesForArraysOfScalars() {
		$class = new class() {
			/**
			 * @var string
			 */
			private $string;

			/**
			 * @var string[]
			 */
			private $string_array;

			/**
			 * @var string[][]
			 */
			private $string_deep_array;
		};

		$result   = PropertyParser::compute_property_map( new ReflectionClass( $class ) );
		$expected = array(
			'string'            => new Property( 'string', 'private', array( 'string' ) ),
			'string_array'      => new Property( 'string_array', 'private', array( 'string[]' ) ),
			'string_deep_array' => new Property( 'string_deep_array', 'private', array( 'string[][]' ) ),
		);
		$this->assertEquals( $expected, $result );
	}
	public function testMapsPropertiesForArrays() {
		$class = new class() {
			/**
			 * @var string|array
			 */
			private $string_or_array;
		};

		$result   = PropertyParser::compute_property_map( new ReflectionClass( $class ) );
		$expected = array(
			'string_or_array' => new Property( 'string_or_array', 'private', array( 'string', 'array' ) ),
		);
		$this->assertEquals( $expected, $result );
	}

	public function testMapsPropertiesWithNoDocBlocks() {
		$class = new class() {
			private $no_docblock;
		};

		$result   = PropertyParser::compute_property_map( new ReflectionClass( $class ) );
		$expected = array(
			'no_docblock' => new Property( 'no_docblock', 'private', array() ),
		);
		$this->assertEquals( $expected, $result );
	}

	//test visibility parsing
	public function testMapsPropertiesForPublic() {
		$class = new class() {
			/**
			 * @var string
			 */
			public $string;
		};

		$result   = PropertyParser::compute_property_map( new ReflectionClass( $class ) );
		$expected = array(
			'string' => new Property( 'string', 'public', array( 'string' ) ),
		);
		$this->assertEquals( $expected, $result );
	}
	public function testMapsPropertiesForProtected() {
		$class = new class() {
			/**
			 * @var string
			 */
			protected $string;
		};

		$result   = PropertyParser::compute_property_map( new ReflectionClass( $class ) );
		$expected = array(
			'string' => new Property( 'string', 'protected', array( 'string' ) ),
		);
		$this->assertEquals( $expected, $result );
	}

	public function testMapsPropertiesForPrivate() {
		$class = new class() {
			/**
			 * @var string
			 */
			private $string;
		};

		$result   = PropertyParser::compute_property_map( new ReflectionClass( $class ) );
		$expected = array(
			'string' => new Property( 'string', 'private', array( 'string' ) ),
		);
		$this->assertEquals( $expected, $result );
	}

	public function testMapsPropertiesForUnionDocBlock() {
		$class = new class() {
			/**
			 * @var string|array|bool
			 */
			private $string_or_array_or_bool;
		};

		$result   = PropertyParser::compute_property_map( new ReflectionClass( $class ) );
		$expected = array(
			'string_or_array_or_bool' => new Property( 'string_or_array_or_bool', 'private', array( 'string', 'array', 'bool' ) ),
		);
		$this->assertEquals( $expected, $result );
	}

	/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
	public function testMapsPropertiesWhenTypeWithGlobalNamespacePrefix() {
		$class = new class() {
			/**
			 * @var \stdClass
			 */
			private $global_stdclass;

			/**
			 * @var stdClass
			 */
			private $local_stdclass;
		};

		$result   = PropertyParser::compute_property_map( new ReflectionClass( $class ) );
		$expected = array(
			'global_stdclass' => new Property( 'global_stdclass', 'private', array( 'stdClass' ) ),
			'local_stdclass'  => new Property( 'local_stdclass', 'private', array( 'stdClass' ) ),
		);
		$this->assertEquals( $expected, $result );
	}

	public function testMapsPropertiesWhenTypeNullable() {
		$class = new class() {
			/**
			 * @var null|string
			 */
			private $nullable_string;
		};

		$result   = PropertyParser::compute_property_map( new ReflectionClass( $class ) );
		$expected = array(
			'nullable_string' => new Property( 'nullable_string', 'private', array( 'string' ) ),
		);
		$this->assertEquals( $expected, $result );
	}
}
