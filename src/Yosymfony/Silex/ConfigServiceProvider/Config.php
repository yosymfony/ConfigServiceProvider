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
     * @return ConfigRepositoryInterface
     *
     * @throws FileLoaderLoadException if the loader not found.
     * @throws UnexpectedValueException if the loader not return a repository instance
     */
    public function load($resource, $type = null)
    {
        $repository = $this->delegatingLoader->load($resource, $type);
        
        if(!$repository instanceof ConfigRepositoryInterface)
        {
            throw new \UnexpectedValueException('The loader must return a repository instance');
        }
        
        return $repository;
    }
}