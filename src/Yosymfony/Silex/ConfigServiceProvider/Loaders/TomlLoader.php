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
use Yosymfony\Toml\Toml;

/**
 * Toml file loader
 * 
 * @author Victor Puertas <vpgugr@gmail.com>
 */
class TomlLoader extends FileLoader
{
    public function load($resource, $type = null)
    {
        if(null === $type)
        {
            $resource = $this->getLocator()->locate($resource, null, true);
        }
        
        $data = Toml::parse($resource);
        $repository = new ConfigRepository();
        $repository->load($data ? $data : array());
        
        return $repository;
    }
    
    public function supports($resource, $type = null)
    {
        return 'toml' === $type || is_string($resource) && 'toml' === pathinfo(
            $resource,
            PATHINFO_EXTENSION
        );
    }
}