<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace WordPress\Blueprints\Model\DataClass;



class CorePluginReference implements FileReferenceInterface
{
    /** @var string Identifies the file resource as a WordPress Core plugin */
    public $resource = 'wordpress.org/plugins';

    /** @var string The slug of the WordPress Core plugin */
    public $slug;
}