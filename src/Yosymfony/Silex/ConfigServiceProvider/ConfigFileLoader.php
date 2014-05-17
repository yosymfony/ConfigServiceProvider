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

use Symfony\Component\Config\Loader\FileLoader;

/**
 * Abstract class used by built-in loaders
 * 
 * @author Victor Puertas <vpgugr@gmail.com>
 */
abstract class ConfigFileLoader extends FileLoader
{
    /**
     * Get the resolver name of a file resource follow the next hierachy:
     *    1. filename.ext
     *    2. filename.ext.dist (if filename.ext not exists)
     * 
     *    or
     * 
     *    filename.ext.dist if the .dist is included in resource.
     * 
     * @param string $resource Filename path
     * 
     * @return string
     */
    public function getResolvedName($resource)
    {
        if(false === $this->isDistExtension($resource))
        {
            $resource = file_exists($resource) ? $resource : $resource . '.dist';
        }
        
        return $resource;
    }
    
    /**
     * The file resource have .dist extension?
     * 
     * @param string $resource
     * 
     * @return bool
     */
    public function isDistExtension($resource)
    {
        return 'dist' === pathinfo($resource, PATHINFO_EXTENSION);
    }
}