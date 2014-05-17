<?php

/*
 * This file is part of the Yosymfony\ConfigurationServiceProvider.
 *
 * (c) YoSymfony <http://github.com/yosymfony>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yosymfony\Silex\ConfigServiceProvider\Loaders;

use Symfony\Component\Yaml\Yaml;
use Yosymfony\Silex\ConfigServiceProvider\ConfigFileLoader;
use Yosymfony\Silex\ConfigServiceProvider\ConfigRepository;

/**
 * Yaml file loader
 * 
 * @author Victor Puertas <vpgugr@gmail.com>
 */
class YamlLoader extends ConfigFileLoader
{
    public function load($resource, $type = null)
    {
        if(null === $type)
        {
            $resourceName = $this->getLocator()->locate($resource, null, true);
            $resource = $this->getResolvedName($resourceName);
        }
        
        $data = Yaml::parse($resource);
        $repository = new ConfigRepository();
        $repository->load($data ? $data : array());
        
        return $repository;
    }
    
    public function supports($resource, $type = null)
    {
        return 'yaml' === $type || is_string($resource) && 'yml' === pathinfo(
            $resource,
            PATHINFO_EXTENSION
        );
    }
}