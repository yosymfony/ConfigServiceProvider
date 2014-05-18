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

use Yosymfony\Toml\Toml;
use Yosymfony\Silex\ConfigServiceProvider\ConfigFileLoader;
use Yosymfony\Silex\ConfigServiceProvider\ConfigRepository;

/**
 * TOML file loader
 * 
 * @author Victor Puertas <vpgugr@gmail.com>
 */
class TomlLoader extends ConfigFileLoader
{
    public function load($resource, $type = null)
    {
        if(null === $type)
        {
            $resource = $this->getLocation($resource);
        }
        
        $data = Toml::parse($resource);
        $repository = new ConfigRepository();
        $repository->load($data ? $data : array());
        
        return $repository;
    }
    
    public function supports($resource, $type = null)
    {
        return 'toml' === $type || (is_string($resource) && preg_match('#\.toml(\.dist)?$#', $resource));
    }
}