<?php

namespace e2e\resources;


use WordPress\Blueprints\Model\DataClass\DownloadWordPressStep;
use WordPress\Blueprints\Model\DataClass\InstallSqliteIntegrationStep;
use WordPress\Blueprints\Model\DataClass\RunWordPressInstallerStep;
use WordPress\Blueprints\Model\DataClass\StepDefinitionInterface;
use WordPress\Blueprints\Model\DataClass\UrlResource;
use WordPress\Blueprints\Model\DataClass\WordPressInstallationOptions;
use WordPress\Blueprints\Model\DataClass\WriteFileStep;

class TestConstants {

	/**
	 * @return StepDefinitionInterface[]
	 */
	public static function prepare_steps_from_shorthand_word_press_version() {
		$word_press_zip = ( new UrlResource() )
			->setResource( 'url' )
			->setUrl('https://wordpress.org/latest.zip');
		$download_word_press_step = ( new DownloadWordPressStep() )
			->setWordPressZip( $word_press_zip );

		$sqlite_plugin_zip = ( new UrlResource() )
			->setResource('url' )
			->setUrl( 'https://downloads.wordpress.org/plugin/sqlite-database-integration.zip' );
		$install_sqlite_integration_step = ( new InstallSqliteIntegrationStep() )
			->setSqlitePluginZip( $sqlite_plugin_zip );

		$wp_cli = ( new UrlResource() )
			->setResource('url' )
			->setUrl('https://playground.wordpress.net/wp-cli.phar' );
		$write_file_step = ( new WriteFileStep() )
			->setPath( 'wp-cli.phar' )
			->setData( $wp_cli );

		$options = ( new WordPressInstallationOptions() )
			->setAdminUsername( 'admin' )
			->setAdminPassword( 'admin' );
		$run_word_press_installer_step = ( new RunWordPressInstallerStep() )
			->setOptions($options);

		return array(
			$download_word_press_step,
			$install_sqlite_integration_step,
			$write_file_step,
			$run_word_press_installer_step
		);
	}
}