<?php

/*
 * This file is part of the Yosymfony\ConfigurationServiceProvider.
 *
 * (c) YoSymfony <http://github.com/yosymfony>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Yosymfony\Silex\ConfigServiceProvider;

use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\DelegatingLoader;

/**
 * Load configurations and create repositories
 * 
 * @author Victor Puertas <vpgugr@gmail.com>
 */
class Config
{
    const TYPE_TOML = 'toml';
    const TYPE_YAML = 'yaml';
    
    private $loaders;
    private $loaderResolver;
    private $delegatingLoader;
    
    /**
     * Constructor
     * 
     * @param array $loaders
     */
    public function __construct(array $loaders)
    {
        if(null === $loaders || 0 == count($loaders))
        {
            throw new \InvalidArgumentException('Array of loaders is empty');
        }
        
        $this->loaders = $loaders;
        $this->loaderResolver = new LoaderResolver($this->loaders);
        $this->delegatingLoader = new DelegatingLoader($this->loaderResolver);
    }
    
    /**
     * Loads a resource like file or inline configuration
     *
     * @param mixed  $resource A resource
     * @param string $type     The resource type
     *
     * @return mixed
     *
     * @throws FileLoaderLoadException if no loader is found.
     */
    public function load($resource, $type = null)
    {
        $repository = $this->delegatingLoader->load($resource, $type);
        
        if(!$repository instanceof ConfigRepositoryInterface)
        {
            throw new \RuntimeException('The loader must be return a repository instance');
        }
        
        return $repository;
    }
}