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
use WordPress\Blueprints\Model\DataClass\DownloadWordPressStep;
use WordPress\Blueprints\Model\DataClass\InstallPluginStep;
use WordPress\Blueprints\Model\DataClass\InstallThemeStep;
use WordPress\Blueprints\Model\DataClass\MkdirStep;
use WordPress\Blueprints\Model\DataClass\RmStep;
use WordPress\Blueprints\Model\DataClass\UrlResource;
use WordPress\Blueprints\Runner\Step\InstallThemeStepRunner;
use WordPress\Blueprints\Runtime\ProcessFailedException;
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
	 * @dataProvider blueprint_with_mkdir_rm_steps
	 * @param string|stdClass|Blueprint $raw_blueprint
	 */
	public function testRunningMkdirAndRmSteps( $raw_blueprint ) {
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
	 * Data provider for {@see self::testRunningMkdirAndRmSteps()}.
	 *
	 * @return array
	 */
	public static function blueprint_with_mkdir_rm_steps(): array {
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

	/**
	 * @dataProvider blueprint_with_downloads
	 * @param string|stdClass|Blueprint $raw_blueprint
	 */
	public function testRunningStepsRequiringDownloads( $raw_blueprint ) {
		$results = run_blueprint(
			$raw_blueprint,
			array(
				'environment'        => ContainerBuilder::ENVIRONMENT_NATIVE,
				'documentRoot'       => $this->document_root,
				'progressSubscriber' => $this->subscriber,
			)
		);

		$expected = array(
			0 => new StepSuccess( ( new InstallPluginStep() )
				->setPluginZipFile( 'https://downloads.wordpress.org/plugin/hello-dolly.zip' )
				->setActivate( false ), null ),
			1 => new StepSuccess( ( new DownloadWordPressStep() )
				->setWordPressZip( 'https://wordpress.org/latest.zip' ), null ),
			2 => new StepSuccess( ( new InstallThemeStep() )
				->setThemeZipFile( 'https://downloads.wordpress.org/theme/twentytwentyfour.zip' )
				->setActivate( false ), null )
		);

		self::assertEquals( $expected, $results );
	}

	/**
	 * Data provider for {@see self::testRunningStepsRequiringDownloads()}.
	 *
	 * @return array
	 */
	public static function blueprint_with_downloads(): array {
		$json_string = '{"steps":[
				{"step":"installPlugin","pluginZipFile":"https://downloads.wordpress.org/plugin/hello-dolly.zip", "activate":false},
				{"step":"downloadWordPress","wordPressZip":"https://wordpress.org/latest.zip"},
				{"step":"installTheme","themeZipFile":"https://downloads.wordpress.org/theme/twentytwentyfour.zip", "activate":false}
			]}';

		$json_std_class = json_decode( $json_string );

		$install_plugin = ( new InstallPluginStep() )
			->setPluginZipFile( 'https://downloads.wordpress.org/plugin/wordpress-importer.zip' )
			->setActivate( false );
		$download_wp = ( new DownloadWordPressStep() )
			->setWordPressZip( 'https://wordpress.org/latest.zip' );
		$install_asset = ( new InstallThemeStep() )
			->setThemeZipFile( 'https://downloads.wordpress.org/theme/twentytwentyfour.zip' )
			->setActivate( false );

		$php_blueprint = BlueprintBuilder::create()
			->addStep( $install_plugin )
			->addStep( $download_wp )
			->addStep( $install_asset )
			->toBlueprint();

		return array(
			'JSON as string'                  => array( $json_string ),
			'JSON as stdClass'                => array( $json_std_class ),
			'Blueprint class instance'        => array( $php_blueprint )
		);
	}

	/**
	 * @dataProvider blueprint_with_install_plugin_steps
	 * @param string|stdClass|Blueprint $raw_blueprint
	 */
	public function testRunningMultipleInstallPluginSteps( $raw_blueprint ) {
		$results = run_blueprint(
			$raw_blueprint,
			array(
				'environment'        => ContainerBuilder::ENVIRONMENT_NATIVE,
				'documentRoot'       => $this->document_root,
				'progressSubscriber' => $this->subscriber,
			)
		);

		$expected_steps = array(
			0 => new StepSuccess( ( new InstallPluginStep() )->setPluginZipFile('https://downloads.wordpress.org/plugin/wordpress-importer.zip')->setActivate( false ), null ),
			1 => null, // only type check
			2 => new StepSuccess( ( new InstallPluginStep() )->setPluginZipFile('https://downloads.wordpress.org/plugin/hello-dolly.zip')->setActivate( false ), null ),
			3 => new StepSuccess( ( new InstallPluginStep() )->setPluginZipFile('https://downloads.wordpress.org/plugin/gutenberg.17.7.0.zip')->setActivate( false ), null )
		);

		foreach ( $results as $key => $result ) {
			if ( $key === 1 ){
				// the ProcessFailedException for a failing step is a very complex object,
				// for now checking the type is good enough
				self::assertInstanceOf(ProcessFailedException::class, $result);
			} else {
				self::assertEquals( $expected_steps[$key], $result );
			}
		}
	}

	/**
	 * Data provider for {@see self::testRunningBlueprintRequireingDownloads()}.
	 *
	 * @return array
	 */
	public static function blueprint_with_install_plugin_steps(): array {
		$json_string = '{"steps":[
				{"step":"installPlugin","pluginZipFile":"https://downloads.wordpress.org/plugin/wordpress-importer.zip","activate":false},
				{"step":"installPlugin","pluginZipFile":"https://downloads.wordpress.org/plugin/intentionally-bad-url.zip","continueOnError":true,"activate":true},
				{"step":"installPlugin","pluginZipFile":"https://downloads.wordpress.org/plugin/hello-dolly.zip","activate":false},
				{"step":"installPlugin","pluginZipFile":"https://downloads.wordpress.org/plugin/gutenberg.17.7.0.zip","activate":false}
			]}';

		$json_std_class = json_decode( $json_string );

		$failing_step = ( new InstallPluginStep() )
			->setPluginZipFile( 'https://downloads.wordpress.org/plugin/intentionally-bad-url.zip' )
			->setActivate( true )
			->setContinueOnError( true );
		$php_blueprint = BlueprintBuilder::create()
			->addStep( ( new InstallPluginStep() )->setPluginZipFile('https://downloads.wordpress.org/plugin/wordpress-importer.zip')->setActivate( false ) )
			->addStep( $failing_step )
			->addStep( ( new InstallPluginStep() )->setPluginZipFile('https://downloads.wordpress.org/plugin/hello-dolly.zip')->setActivate( false ) )
			->addStep( ( new InstallPluginStep() )->setPluginZipFile('https://downloads.wordpress.org/plugin/gutenberg.17.7.0.zip')->setActivate( false ) )
			->toBlueprint();

		return array(
			'JSON as string'                  => array( $json_string ),
			'JSON as stdClass'                => array( $json_std_class ),
			'Blueprint class instance'        => array( $php_blueprint )
		);
	}
}
