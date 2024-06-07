<?php

namespace e2e;

use e2e\resources\TestConstants;
use e2e\resources\TestResourceClassSimpleSubscriber;
use E2ETestCase;
use stdClass;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use WordPress\Blueprints\Compile\StepSuccess;
use WordPress\Blueprints\ContainerBuilder;
use WordPress\Blueprints\Model\BlueprintBuilder;
use WordPress\Blueprints\Model\DataClass\Blueprint;
use function WordPress\Blueprints\run_blueprint;

class ShorthandsTest extends E2ETestCase {
	/**
	 * @var string
	 */
	private $document_root;

	/**
	 * @var EventSubscriberInterface
	 */
	private $subscriber;

	/**
	 * @before
	 */
	public function before() {
		$this->document_root = Path::makeAbsolute('test', sys_get_temp_dir());

		( new Filesystem() )->remove( $this->document_root );

		$this->subscriber = new TestResourceClassSimpleSubscriber();
	}

	/**
	 * @after
	 */
	public function after() {
		( new Filesystem() )->remove( $this->document_root );
	}

	/**
	 * @dataProvider blueprint_with_word_press_version
	 * @param string|stdClass|Blueprint $raw_blueprint
	 */
	public function testRunningBlueprintWithWordPressVersion(
		$raw_blueprint
	) {
		/** @var StepSuccess[] $results */
		$results = run_blueprint(
			$raw_blueprint,
			array(
				'environment'        => ContainerBuilder::ENVIRONMENT_NATIVE,
				'documentRoot'       => $this->document_root,
				'progressSubscriber' => $this->subscriber,
			)
		);

		$step_result = trim ( $results[3]->result ); // RunWordPressInstallerStep result trimmed
		$expected_result = 'Success: WordPress installed successfully.';
		// For PHP <=7.3 the success message is prefixed with: '#!/usr/bin/env php'
		self::assertStringContainsString( $expected_result, $step_result );

		$expected_steps = TestConstants::prepare_steps_from_shorthand_word_press_version();

		foreach ( $results as $key => $result ) {
			self::assertEquals( $result->step, $expected_steps[$key] );
		}
	}

	/**
	 * Data provider for {@see self::testRunningBlueprintWithWordPressVersion()}.
	 *
	 * @return array
	 */
	public function blueprint_with_word_press_version() {
		$json_string = '{"WordPressVersion":"https://wordpress.org/latest.zip"}';

		$json_std_class = json_decode( $json_string );

		$php_blueprint = BlueprintBuilder::create()
			->withWordPressVersion( 'https://wordpress.org/latest.zip' )
			->toBlueprint();

		return array(
			'JSON as string'                  => array( $json_string ),
			'JSON as stdClass'                => array( $json_std_class ),
			'Blueprint class instance'        => array( $php_blueprint )
		);
	}
}