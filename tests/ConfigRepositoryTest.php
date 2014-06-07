<?php

/*
 * This file is part of the Yosymfony\ConfigurationServiceProvider.
 *
 * (c) YoSymfony <http://github.com/yosymfony>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Yosymfony\Silex\ConfigServiceProvider\Tests;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Yosymfony\Silex\ConfigServiceProvider\Config;
use Yosymfony\Silex\ConfigServiceProvider\ConfigRepository;
use Yosymfony\Silex\ConfigServiceProvider\Loaders\TomlLoader;
use Yosymfony\Silex\ConfigServiceProvider\Loaders\YamlLoader;
use Yosymfony\Silex\ConfigServiceProvider\Loaders\JsonLoader;

class ConfigRepositoryTest extends \PHPUnit_Framework_TestCase
{
    protected $config;
    
    public function setUp()
    {
        $locator = new FileLocator(array(__dir__.'/Fixtures'));
        
        $this->config = new Config(array(
            new TomlLoader($locator),
            new YamlLoader($locator),
            new JsonLoader($locator),
        ));
    }
    
    public function testRepositoryAddKey()
    {
        $repository = new ConfigRepository();
        $repository['name'] = 'YoSymfony';
        
        $this->assertEquals($repository->get('name'), 'YoSymfony');
        $this->assertEquals($repository['name'], 'YoSymfony');
    }
    
    public function testRepositoryAddKeyWithSet()
    {
        $repository = new ConfigRepository();
        $repository->set('name', 'YoSymfony');
        
        $this->assertEquals($repository->get('name'), 'YoSymfony');
        $this->assertEquals($repository['name'], 'YoSymfony');
    }
    
    public function testRepositoryGetWithDefault()
    {
        $repository = new ConfigRepository();
        
        $this->assertEquals($repository->get('name', 'no-val'), 'no-val');
        $this->assertEquals($repository->get('name', true), true);
        $this->assertEquals($repository->get('name', false), false);
        $this->assertEquals($repository->get('name', 10), 10);
        $this->assertEquals($repository->get('name', 1.0), 1.0);
        $this->assertEquals($repository->get('name', null), null);
    }
    
    public function testRespositoryGetRaw()
    {
        $repository = new ConfigRepository();
        $this->assertTrue(is_array($repository->getRaw()));
        
        $repository['val'] = 'value';
        $this->assertCount(1, $repository->getRaw());
    }
    
    public function testRespositoryGetArray()
    {
        $repository = new ConfigRepository();
        $this->assertTrue(is_array($repository->getArray()));
        
        $repository['val'] = 'value';
        $this->assertCount(1, $repository->getArray());
    }
    
    public function testRepositoryUnsetKey()
    {
        $repository = new ConfigRepository();
        $repository['val'] = 'value';
        unset($repository['val']);
        
        $this->assertCount(0, $repository);
    }
    
    public function testRepositoryDeleteKey()
    {
        $repository = new ConfigRepository();
        $repository['val'] = 'value';
        $repository->del('val');
        
        $this->assertCount(0, $repository);
    }
    
    public function testRepositoryNullKey()
    {
        $repository = new ConfigRepository();
        $repository[null] = 1;
        
        $this->assertEquals($repository[0], 1);
    }
    
    public function testRepositorySetNullKey()
    {
        $repository = new ConfigRepository();
        $repository->set(null, 1);

        $this->assertEquals($repository[0], 1);
    }
    
    public function testRepositoryUnion()
    {
        $repositoryA = new ConfigRepository();
        $repositoryA['port'] = 25;
        $repositoryA['server'] = 'localhost';
        
        $repositoryB = new ConfigRepository();
        $repositoryB['port'] = 24;
        $repositoryB['server'] = 'mail.yourname.com';
        $repositoryB['secure'] = true;
        
        $union = $repositoryA->union($repositoryB);
        $this->assertInstanceOf('Yosymfony\Silex\ConfigServiceProvider\ConfigRepositoryInterface', $union);
        $this->assertCount(3, $union);
        $this->assertEquals($union['port'], 25);
        $this->assertEquals($union['server'], 'localhost');
        $this->assertEquals($union['secure'], true);
        
        $this->assertCount(2, $repositoryA);
    }
    
    public function testRepositoryUnionMainMinor()
    {
        $repositoryA = new ConfigRepository();
        $repositoryA['port'] = 25;
        
        $repositoryB = new ConfigRepository();
        $repositoryB['port'] = 24;
        $repositoryB['server'] = 'mail.yourname.com';
        $repositoryB['secure'] = true;
        
        $union = $repositoryA->union($repositoryB);
        $this->assertInstanceOf('Yosymfony\Silex\ConfigServiceProvider\ConfigRepositoryInterface', $union);
        $this->assertCount(3, $union);
        $this->assertEquals($union['port'], 25);
        $this->assertEquals($union['server'], 'mail.yourname.com');
        $this->assertEquals($union['secure'], true);   
    }
    
    public function testRepositoryIntersection()
    {
        $repositoryA = new ConfigRepository();
        $repositoryA['port'] = 25;
        $repositoryA['server'] = 'localhost';
        
        $repositoryB = new ConfigRepository();
        $repositoryB['port'] = 24;
        $repositoryB['server'] = 'mail.yourname.com';
        $repositoryB['secure'] = true;
        
        $intersection = $repositoryA->intersection($repositoryB);
        $this->assertInstanceOf('Yosymfony\Silex\ConfigServiceProvider\ConfigRepositoryInterface', $intersection);
        $this->assertCount(2, $intersection);
        $this->assertEquals($intersection['port'], 25);
        $this->assertEquals($intersection['server'], 'localhost');
        $this->assertArrayNotHasKey('secure', $intersection);
        
        $this->assertCount(2, $repositoryA);
    }
    
    public function testRepositoryDefinitions()
    {
        $repository = $this->config->load("port = 25\n server = \"localhost\"", Config::TYPE_TOML);
        $repository->validateWith(new ConfigDefinitions());
    }
    
    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidTypeException
     */
    public function testRepositoryFailDefinitions()
    {
        $repository = $this->config->load("port = \"25\"\n server = \"localhost\"", Config::TYPE_TOML);
        $repository->validateWith(new ConfigDefinitions());
    }
}

/**
 * Configuration Definitions rules example for test purpose
 */
class ConfigDefinitions implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root(0);
        
        $rootNode->children()
            ->integerNode('port')
                ->end()
            ->scalarNode('server')
                ->end()
        ->end();
        
        return $treeBuilder;
    }
}