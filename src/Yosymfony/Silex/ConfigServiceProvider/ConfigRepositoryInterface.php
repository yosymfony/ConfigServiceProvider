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

use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Interface that must be implemented by configuration reader
 * 
 * @author Victor Puertas <vpgugr@gmail.com>
 */
interface ConfigRepositoryInterface extends \ArrayAccess, \Countable, \Iterator
{
    /**
     * Load data repository
     * 
     * @param mixed $data
     */
    public function load($data);
    
    /**
     * Get value from the key
     * 
     * @param string $key Key name
     * @param mixed $default Default value
     * 
     * @return mixed The value in the $key or default
     */
    public function get($key, $default);
    
    /**
     * Set value to a key
     * 
     * @param string $key The key name
     * @param mixed $value The value
     */
    public function set($key, $value);
    
    /**
     * Delete a key
     * 
     * @param string $key Key name
     */
    public function del($key);
    
    /**
     * Merge the repository with $repository. The values of $repository have
     * less precedence
     * 
     * @param ConfigRepositoryInterface $repository
     * 
     * @return ConfigRepositoryInterface A new repository
     */
    public function mergeWith(ConfigRepositoryInterface $repository);
    
    /**
     * Validate the configurations values
     * 
     * @param ConfigurationInterface $definition The rules
     */
    public function validateWith(ConfigurationInterface $definition);
    
    /**
     * Get the repository's raw representation
     * 
     * @return mixed
     */
    public function getRaw();
    
    /**
     * Get an array representation
     * 
     * @return array
     */
    public function getArray();
}