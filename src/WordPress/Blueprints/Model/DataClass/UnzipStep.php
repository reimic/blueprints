<?php
/**
 * @file AUTOGENERATED FILE – DO NOT CHANGE MANUALLY
 * All your changes will get overridden. See the README for more details.
 */

namespace WordPress\Blueprints\Model\DataClass;

use WordPress\Blueprints\Model\Builder\CorePluginResourceBuilder;
use WordPress\Blueprints\Model\Builder\CoreThemeResourceBuilder;
use WordPress\Blueprints\Model\Builder\FilesystemResourceBuilder;
use WordPress\Blueprints\Model\Builder\InlineResourceBuilder;
use WordPress\Blueprints\Model\Builder\ProgressBuilder;
use WordPress\Blueprints\Model\Builder\UrlResourceBuilder;


class UnzipStep implements StepInterface
{
    const SLUG = 'unzip';

    /** @var ProgressBuilder */
    public $progress;

    /** @var bool */
    public $continueOnError = false;

    /** @var string */
    public $step = 'unzip';

    /** @var string|FilesystemResourceBuilder|InlineResourceBuilder|CoreThemeResourceBuilder|CorePluginResourceBuilder|UrlResourceBuilder */
    public $zipFile;

    /** @var string The path to extract the zip file to */
    public $extractToPath;
}