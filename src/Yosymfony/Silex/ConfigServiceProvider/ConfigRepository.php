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

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Simple implementation of a configuration repository
 * 
 * @author Victor Puertas <vpgugr@gmail.com>
 */
class ConfigRepository implements ConfigRepositoryInterface
{    
    protected $repository = array();
    
    /**
     * Load configuration data from an array
     * 
     * @param mixed $configuration
     */
    public function load($configuration)
    {
        if(!is_array($configuration))
        {
            throw new \InvalidArgumentException('This repository only accept configuration from arrays');
        }
        
        $this->repository = $configuration;
    }
    
    /**
     * Get value from the key
     * 
     * @param string $key Key name
     * @param mixed $default Default value
     * 
     * @return mixed The value in the $key or default
     */
    public function get($key, $default = null)
    {
        return isset($this->repository[$key]) ? $this->repository[$key] : $default;
    }
    
    /**
     * Set value to a key
     * 
     * @param string $key The key name
     * @param mixed $value The value
     */
    public function set($key, $value)
    {
        $this->offsetSet($key, $value);
    }
    
    /**
     * Delete a key
     * 
     * @param string $key Key name
     */
    public function del($key)
    {
        if(array_key_exists($key, $this->repository))
        {
            unset($this->repository[$key]);
        }
    }
    
    /**
     * Merge the repository with $repository. The values of $repository have
     * less precedence.
     * 
     * @deprecated Deprecated since 1.2.0. It's replaced by union method.
     * 
     * @param ConfigRepositoryInterface $repository
     * 
     * @return ConfigRepositoryInterface A new repository
     */
    public function mergeWith(ConfigRepositoryInterface $repository)
    {
        return $this->union($repository);
    }
    
    /**
     * Union the repository with $repository. The values of $repository have
     * less precedence
     * 
     * @param ConfigRepositoryInterface $repository
     * 
     * @return ConfigRepositoryInterface A new repository
     */
    public function union(ConfigRepositoryInterface $repository)
    {
        $union = function($main, $second)
        {
            foreach($second as $key => $value)
            {
                if(!isset($main[$key]))
                {
                    $main[$key] = $value;
                }
            }
            
            return $main;
        };
        
        return $union($this, $repository);
    }
    
    /**
     * Intersection the repository with $repository. The values of $repository have
     * less precedence
     * 
     * @param ConfigRepositoryInterface $repository
     * 
     * @return ConfigRepositoryInterface A new repository
     */
    public function intersection(ConfigRepositoryInterface $repository)
    {
        $interception = function($main, $second)
        {
            $result = [];
            $keysMain = array_keys($main);
            $keysSecond = array_keys($second);
            $keys = array_intersect($keyMain, $keySecond);
            
            foreach($keys as $key)
            {
                $result[$key] = $main[$main];
            }
            
            return $result;
        };
        
        return $interception($this, $repository);
    }
    
    /**
     * Validate the repository's values
     * 
     * @param ConfigurationInterface $definition The definition rules
     * 
     * @throws Exception If any value is not of the expected type, is mandatory and yet undefined, or could not be validated in some other way
     */
    public function validateWith(ConfigurationInterface $definition)
    {
        $processor = new Processor();
        
        $processor->processConfiguration($definition, array($this->getArray()));
    }
    
    /**
     * Get an array representation
     * 
     * @return array
     */
    public function getArray()
    {
        return $this->repository;
    }
    
    /**
     * Get the repository's raw representation
     * 
     * @return mixed
     */
    public function getRaw()
    {
        return $this->repository;
    }
    
    /**
     * Set a new key (From ArrayAccess interface)
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->repository[] = $value;
        } else {
            $this->repository[$offset] = $value;
        }
    }
    
    /**
     * Check if a key exists (from ArrayAccess interface)
     */
    public function offsetExists($offset)
    {
        return isset($this->repository[$offset]);
    }
    
    /**
     * Delete a key (from ArrayAccess interface)
     */
    public function offsetUnset($offset)
    {
        unset($this->repository[$offset]);
    }
    
    /**
     * Retrueve a key (from ArrayAccess interface)
     */
    public function offsetGet($offset)
    {
        return isset($this->repository[$offset]) ? $this->repository[$offset] : null;
    }
    
    /**
     * Count of element of a repository (from Countable interface)
     */
    public function count()
    {
        return count($this->repository);
    }
    
    /**
     * Set the pointer to the first element (from Iterator interface)
     */
    public function rewind() 
    {
        return reset($this->repository);
    }
    
    /**
     * Get the current element (from Iterator interface)
     */
    public function current() 
    {
        return current($this->repository);
    }
    
    /**
     * Get the current position (from Iterator interface)
     */
    public function key() 
    {
        return key($this->repository);
    }
    
    /**
     * Set the pointer to the next element (from Iterator interface)
     */
    public function next() 
    {
        return next($this->repository);
    }
    
    /**
     * Checks if the current position is valid (from Iterator interface)
     */
    public function valid() 
    {
        return key($this->repository) !== null;
    }
}