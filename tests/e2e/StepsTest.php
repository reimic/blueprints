<?php

namespace e2e;

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
use WordPress\Blueprints\Model\DataClass\MkdirStep;
use WordPress\Blueprints\Model\DataClass\RmStep;
use function WordPress\Blueprints\run_blueprint;

class StepsTest extends E2ETestCase {
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
		$this->document_root = Path::makeAbsolute( 'test', sys_get_temp_dir() );

		$this->subscriber = new TestResourceClassSimpleSubscriber();
	}

	/**
	 * @after
	 */
	public function after() {
		( new Filesystem() )->remove( $this->document_root );
	}

	/**
	 * @dataProvider blueprint_with_mkdir_rm_steps
	 * @param string|stdClass|Blueprint $raw_blueprint
	 */
	public function testRunningBlueprintWithMkdirAndRmSteps( $raw_blueprint ) {
		$results = run_blueprint(
			$raw_blueprint,
			array(
				'environment'        => ContainerBuilder::ENVIRONMENT_NATIVE,
				'documentRoot'       => $this->document_root,
				'progressSubscriber' => $this->subscriber,
			)
		);

		$expected = array(
			0 => new StepSuccess( ( new MkdirStep() )->setPath( 'dir1' ), null ),
			1 => new StepSuccess( ( new RmStep() )->setPath( 'dir1' ), null ),
			2 => new StepSuccess( ( new MkdirStep() )->setPath( 'dir2' ), null ),
		);

		self::assertDirectoryDoesNotExist( Path::makeAbsolute( 'dir1', $this->document_root ) );
		self::assertDirectoryExists( Path::makeAbsolute( 'dir2', $this->document_root ) );
		self::assertEquals( $expected, $results );
	}

	/**
	 * Data provider for {@see self::testRunningBlueprintWithMkdirAndRmSteps()}.
	 *
	 * @return array
	 */
	public function blueprint_with_mkdir_rm_steps() {
		$json_string = '{"steps":[{"step":"mkdir","path":"dir1"},{"step": "rm","path": "dir1"},{"step":"mkdir","path":"dir2"}]}';

		$json_std_class = json_decode( $json_string );

		$php_blueprint = BlueprintBuilder::create()
			->addStep( ( new MkdirStep() )->setPath( 'dir1' ) )
			->addStep( ( new RmStep() )->setPath( 'dir1' ) )
			->addStep( ( new MkdirStep() )->setPath( 'dir2' ) )
			->toBlueprint();

		return array(
			'JSON as string'                  => array( $json_string ),
			'JSON as stdClass'                => array( $json_std_class ),
			'Blueprint class instance'        => array( $php_blueprint )
		);
	}
}
