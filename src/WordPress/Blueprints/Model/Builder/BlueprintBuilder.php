<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace WordPress\Blueprints\Model\Builder;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
use WordPress\Blueprints\Model\DataClass\Blueprint;
use Swaggest\JsonSchema\Structure\ClassStructureContract;


/**
 * Built from #/definitions/Blueprint
 */
class BlueprintBuilder extends Blueprint implements ClassStructureContract
{
    use \Swaggest\JsonSchema\Structure\ClassStructureTrait;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->landingPage = Schema::string();
        $properties->landingPage->description = "The URL to navigate to after the blueprint has been run.";
        $properties->description = Schema::string();
        $properties->description->description = "Optional description. It doesn't do anything but is exposed as a courtesy to developers who may want to document which blueprint file does what.";
        $properties->preferredVersions = BlueprintPreferredVersionsBuilder::schema();
        $properties->features = BlueprintFeaturesBuilder::schema();
        $properties->constants = Schema::object();
        $properties->constants->additionalProperties = Schema::string();
        $properties->constants->description = "PHP Constants to define on every request";
        $properties->plugins = Schema::arr();
        $properties->plugins->items = new Schema();
        $properties->plugins->items->anyOf[0] = Schema::string();
        $propertiesPluginsItemsAnyOf1 = new Schema();
        $propertiesPluginsItemsAnyOf1->anyOf[0] = Schema::string();
        $propertiesPluginsItemsAnyOf1->anyOf[1] = VFSReferenceBuilder::schema();
        $propertiesPluginsItemsAnyOf1->anyOf[2] = LiteralReferenceBuilder::schema();
        $propertiesPluginsItemsAnyOf1->anyOf[3] = CoreThemeReferenceBuilder::schema();
        $propertiesPluginsItemsAnyOf1->anyOf[4] = CorePluginReferenceBuilder::schema();
        $propertiesPluginsItemsAnyOf1->anyOf[5] = UrlReferenceBuilder::schema();
        $propertiesPluginsItemsAnyOf1->setFromRef('#/definitions/FileReference');
        $properties->plugins->items->anyOf[1] = $propertiesPluginsItemsAnyOf1;
        $properties->plugins->description = "WordPress plugins to install and activate";
        $properties->siteOptions = BlueprintSiteOptionsBuilder::schema();
        $properties->login = new Schema();
        $properties->login->anyOf[0] = Schema::boolean();
        $properties->login->anyOf[1] = LoginDetailsBuilder::schema();
        $properties->login->description = "User to log in as. If true, logs the user in as admin/password.";
        $properties->phpExtensionBundles = Schema::arr();
        $properties->phpExtensionBundles->items = Schema::string();
        $properties->phpExtensionBundles->items->const = "kitchen-sink";
        $properties->phpExtensionBundles->items->setFromRef('#/definitions/SupportedPHPExtensionBundle');
        $properties->phpExtensionBundles->description = "The PHP extensions to use.";
        $properties->steps = Schema::arr();
        $properties->steps->items = new Schema();
        $propertiesStepsItemsAnyOf0 = Schema::object();
        $propertiesStepsItemsAnyOf0->oneOf[0] = ActivatePluginStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[1] = ActivateThemeStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[2] = CpStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[3] = DefineWpConfigConstsStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[4] = DefineSiteUrlStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[5] = EnableMultisiteStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[6] = ImportFileStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[7] = ImportWordPressFilesStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[8] = InstallPluginStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[9] = InstallThemeStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[10] = LoginStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[11] = MkdirStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[12] = MvStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[13] = PHPRequestStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[14] = RmStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[15] = RmDirStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[16] = RunPHPStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[17] = RunPHPWithOptionsStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[18] = RunWordPressInstallerStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[19] = RunSQLStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[20] = SetPHPIniEntryStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[21] = SetSiteOptionsStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[22] = UnzipStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[23] = UpdateUserMetaStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[24] = WriteFileStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->oneOf[25] = WPCLIStepBuilder::schema();
        $propertiesStepsItemsAnyOf0->required = array(
            self::names()->step,
        );
        $propertiesStepsItemsAnyOf0->setFromRef('#/definitions/StepDefinition');
        $properties->steps->items->anyOf[0] = $propertiesStepsItemsAnyOf0;
        $properties->steps->items->anyOf[1] = Schema::string();
        $propertiesStepsItemsAnyOf2 = new Schema();
        $propertiesStepsItemsAnyOf2->not = new Schema();
        $properties->steps->items->anyOf[2] = $propertiesStepsItemsAnyOf2;
        $propertiesStepsItemsAnyOf3 = Schema::boolean();
        $propertiesStepsItemsAnyOf3->const = false;
        $properties->steps->items->anyOf[3] = $propertiesStepsItemsAnyOf3;
        $properties->steps->items->anyOf[4] = Schema::null();
        $properties->steps->description = "The steps to run after every other operation in this Blueprint was executed.";
        $properties->schema = Schema::string();
        $ownerSchema->addPropertyMapping('$schema', self::names()->schema);
        $ownerSchema->type = Schema::OBJECT;
        $ownerSchema->additionalProperties = false;
        $ownerSchema->setFromRef('#/definitions/Blueprint');
    }

    /**
     * @param string $landingPage The URL to navigate to after the blueprint has been run.
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setLandingPage($landingPage)
    {
        $this->landingPage = $landingPage;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string $description Optional description. It doesn't do anything but is exposed as a courtesy to developers who may want to document which blueprint file does what.
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param BlueprintPreferredVersionsBuilder $preferredVersions The preferred PHP and WordPress versions to use.
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setPreferredVersions(BlueprintPreferredVersionsBuilder $preferredVersions)
    {
        $this->preferredVersions = $preferredVersions;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param BlueprintFeaturesBuilder $features
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setFeatures(BlueprintFeaturesBuilder $features)
    {
        $this->features = $features;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string[] $constants PHP Constants to define on every request
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setConstants($constants)
    {
        $this->constants = $constants;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string[]|string[]|VFSReferenceBuilder[]|LiteralReferenceBuilder[]|CoreThemeReferenceBuilder[]|CorePluginReferenceBuilder[]|UrlReferenceBuilder[]|array $plugins WordPress plugins to install and activate
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setPlugins($plugins)
    {
        $this->plugins = $plugins;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param BlueprintSiteOptionsBuilder|string[] $siteOptions WordPress site options to define
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setSiteOptions($siteOptions)
    {
        $this->siteOptions = $siteOptions;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param bool|LoginDetailsBuilder $login User to log in as. If true, logs the user in as admin/password.
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string[]|array $phpExtensionBundles The PHP extensions to use.
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setPhpExtensionBundles($phpExtensionBundles)
    {
        $this->phpExtensionBundles = $phpExtensionBundles;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param ActivatePluginStepBuilder[]|ActivateThemeStepBuilder[]|CpStepBuilder[]|DefineWpConfigConstsStepBuilder[]|DefineSiteUrlStepBuilder[]|EnableMultisiteStepBuilder[]|ImportFileStepBuilder[]|ImportWordPressFilesStepBuilder[]|InstallPluginStepBuilder[]|InstallThemeStepBuilder[]|LoginStepBuilder[]|MkdirStepBuilder[]|MvStepBuilder[]|PHPRequestStepBuilder[]|RmStepBuilder[]|RmDirStepBuilder[]|RunPHPStepBuilder[]|RunPHPWithOptionsStepBuilder[]|RunWordPressInstallerStepBuilder[]|RunSQLStepBuilder[]|SetPHPIniEntryStepBuilder[]|SetSiteOptionsStepBuilder[]|UnzipStepBuilder[]|UpdateUserMetaStepBuilder[]|WriteFileStepBuilder[]|WPCLIStepBuilder[]|string[]|mixed[]|bool[]|null[]|array $steps The steps to run after every other operation in this Blueprint was executed.
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setSteps($steps)
    {
        $this->steps = $steps;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string $schema
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    function toDataObject()
    {
        $dataObject = new Blueprint();
        $dataObject->landingPage = $this->recursiveJsonSerialize($this->landingPage);
        $dataObject->description = $this->recursiveJsonSerialize($this->description);
        $dataObject->preferredVersions = $this->recursiveJsonSerialize($this->preferredVersions);
        $dataObject->features = $this->recursiveJsonSerialize($this->features);
        $dataObject->constants = $this->recursiveJsonSerialize($this->constants);
        $dataObject->plugins = $this->recursiveJsonSerialize($this->plugins);
        $dataObject->siteOptions = $this->recursiveJsonSerialize($this->siteOptions);
        $dataObject->login = $this->recursiveJsonSerialize($this->login);
        $dataObject->phpExtensionBundles = $this->recursiveJsonSerialize($this->phpExtensionBundles);
        $dataObject->steps = $this->recursiveJsonSerialize($this->steps);
        $dataObject->schema = $this->recursiveJsonSerialize($this->schema);
        return $dataObject;
    }

    /**
     * @param mixed $objectMaybe
     */
    private function recursiveJsonSerialize($objectMaybe)
    {
        if ( is_array( $objectMaybe ) ) {
        	return array_map([$this, 'recursiveJsonSerialize'], $objectMaybe);
        } elseif ( $objectMaybe instanceof \Swaggest\JsonSchema\Structure\ClassStructureContract ) {
        	return $objectMaybe->toDataObject();
        } else {
        	return $objectMaybe;
        }
    }
}