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

use Symfony\Component\Config\Loader\FileLoader;
use Yosymfony\Silex\ConfigServiceProvider\ConfigRepository;
use Symfony\Component\Yaml\Yaml;

/**
 * Yaml file loader
 * 
 * @author Victor Puertas <vpgugr@gmail.com>
 */
class YamlLoader extends FileLoader
{
    public function load($resource, $type = null)
    {
        if(!class_exists('Symfony\\Component\\Yaml\\Yaml'))
        {
            throw new \RuntimeException('Unable to read yaml string because symfony\Yaml Parser is not installed.');
            
        }
        
        if(null === $type)
        {
            $resource = $this->getLocator()->locate($resource, null, true);
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