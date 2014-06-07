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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        return isset($this->repository[$key]) ? $this->repository[$key] : $default;
    }
    
    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $this->offsetSet($key, $value);
    }
    
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function union(ConfigRepositoryInterface $repository)
    {
        $union = function($main, $second)
        {
            $result = new ConfigRepository();
            
            foreach($second as $key => $value)
            {
                if(!isset($main[$key]))
                {
                    $result[$key] = $value;
                }
                else
                {
                    $result[$key] = $main[$key];
                }
            }
            
            return $result;
        };
        
        return $union($this, $repository);
    }
    
    /**
     * {@inheritdoc}
     */
    public function intersection(ConfigRepositoryInterface $repository)
    {
        $interception = function($main, $second)
        {
            $result = new ConfigRepository();
            $keysMain = array_keys($main->getArray());
            $keysSecond = array_keys($second->getArray());
            $keys = array_intersect($keysMain, $keysSecond);
            
            foreach($keys as $key)
            {
                $result[$key] = $main[$key];
            }
            
            return $result;
        };
        
        return $interception($this, $repository);
    }
    
    /**
     * {@inheritdoc }
     */
    public function validateWith(ConfigurationInterface $definition)
    {
        $processor = new Processor();
        
        $processor->processConfiguration($definition, array($this->getArray()));
    }
    
    /**
     * {@inheritdoc }
     */
    public function getArray()
    {
        return $this->repository;
    }
    
    /**
     * {@inheritdoc }
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