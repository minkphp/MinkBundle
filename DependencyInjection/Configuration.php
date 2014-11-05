<?php

/*
 * This file is part of the Behat MinkBundle
 *
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Behat\MinkBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * MinkBundle configuration manager.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        return $treeBuilder->root('mink')->
            children()->
                scalarNode('base_url')->
                    defaultNull()->
                end()->
                scalarNode('coverage_script_url')->
                    defaultNull()->
                end()->
                scalarNode('show_cmd')->
                    defaultNull()->
                end()->
                scalarNode('show_tmp_dir')->
                    defaultValue('%kernel.cache_dir%')->
                end()->
                scalarNode('default_session')->
                    defaultValue('symfony')->
                end()->
                scalarNode('javascript_session')->
                    defaultValue('sahi')->
                end()->
                scalarNode('browser_name')->
                    defaultValue('firefox')->
                end()->
                arrayNode('goutte')->
                    treatTrueLike(array('enabled' => true))->
                end()->
                arrayNode('sahi')->
                    children()->
                        scalarNode('sid')->
                            defaultNull()->
                        end()->
                        scalarNode('host')->
                            defaultValue('localhost')->
                        end()->
                        scalarNode('port')->
                            defaultValue(9999)->
                        end()->
                    end()->
                end()->
                arrayNode('zombie')->
                    children()->
                        scalarNode('host')->
                            defaultValue('127.0.0.1')->
                        end()->
                        scalarNode('port')->
                            defaultValue(8124)->
                        end()->
                        booleanNode('auto_server')->
                            defaultTrue()->
                        end()->
                        scalarNode('node_bin')->
                            defaultValue('node')->
                        end()->
                    end()->
                end()->
                arrayNode('selenium')->
                    children()->
                        scalarNode('browser')->
                            defaultValue('*%mink.browser_name%')->
                        end()->
                        scalarNode('host')->
                            defaultValue('127.0.0.1')->
                        end()->
                        scalarNode('port')->
                            defaultValue(4444)->
                        end()->
                        scalarNode('timeout')->
                            defaultValue(60)->
                        end()->
                    end()->
                end()->
                arrayNode('selenium2')->
                    children()->
                        scalarNode('browser')->
                            defaultValue('%mink.browser_name%')->
                        end()->
                        arrayNode('capabilities')->
                            addDefaultsIfNotSet()->
                            children()->
                                scalarNode('browserName')->
                                    defaultValue('%mink.browser_name%')->
                                end()->
                            end()->
                        end()->
                        scalarNode('wd_host')->
                            defaultValue('http://localhost:4444/wd/hub')->
                        end()->
                    end()->
                end()->
            end()->
        end();
    }
}
