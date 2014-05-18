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

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Config\FileLocator;

/**
 * Configuration ServiceProvider
 * 
 * @author Victor Puertas <vpgugr@gmail.com>
 */
class ConfigServiceProvider implements ServiceProviderInterface
{
    private $configDirectories;
    private $customLoaders;
    
    /**
     * Constructor
     * 
     * @param array $configDirectories A collection of locations where it should look for files.
     * @param array $customLoaders Custom loaders
     */
    public function __construct(array $configDirectories = null, array $customLoaders = null)
    {   
        $this->customLoaders = $customLoaders ?: array();
        $this->configDirectories = $configDirectories ?: array();
    }
    
    public function register(Application $app)
    {
        $app['configuration.custom_loaders'] = $this->customLoaders;
        $app['configuration.directories'] = $this->configDirectories;
        
        $app['configuration'] = $app->share(function($app){
            
            $locator = new FileLocator($app['configuration.directories']);
            $loaders = count($app['configuration.custom_loaders']) > 0 ? $app['configuration.custom_loaders'] : array(
                new Loaders\TomlLoader($locator), 
                new Loaders\YamlLoader($locator),
                new Loaders\JsonLoader($locator),
            );
            
            return new Config($loaders);
        });
    }
    
    public function boot(Application $app)
    {
        // do nothing
    }
}