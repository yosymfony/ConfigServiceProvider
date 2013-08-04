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

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    protected $config;
    
    public function setUp()
    {
        $locator = new FileLocator(array(__dir__.'/Fixtures'));
        
        $this->config = new Config(array(
            new TomlLoader($locator),
            new YamlLoader($locator),
        ));
    }
    
    public function testTomlInlineConfig()
    {    
        $repository = $this->config->load('var = "my value"', Config::TYPE_TOML);
        $this->assertNotNull($repository);
        $this->assertEquals($repository->get('var'), 'my value');
        $this->assertEquals($repository['var'], 'my value');
        $this->assertEquals($repository->get('key_not_exist', 'default'), 'default');
        $this->assertTrue(is_array($repository->getRaw()));
    }
    
    public function testYamlInlineConfig()
    {        
        $repository = $this->config->load('var: "my value"', Config::TYPE_YAML);
        $this->assertNotNull($repository);
        $this->assertEquals($repository->get('var'), 'my value');
        $this->assertEquals($repository['var'], 'my value');
        $this->assertEquals($repository->get('key_not_exist', 'default'), 'default');
        $this->assertTrue(is_array($repository->getRaw()));
    }
    
    public function testTomlFileConfig()
    {
        $repository = $this->config->load('config.toml');
        $this->assertNotNull($repository);
        $this->assertEquals($repository->get('port'), 25);
        $this->assertEquals($repository['port'], 25);
        $this->assertEquals($repository->get('server'), 'mail.yourname.com');
        $this->assertEquals($repository['server'], 'mail.yourname.com');
    }
    
    public function testYamlFileConfig()
    {   
        $repository = $this->config->load('config.yml');
        $this->assertNotNull($repository);
        $this->assertEquals($repository->get('port'), 25);
        $this->assertEquals($repository['port'], 25);
        $this->assertEquals($repository->get('server'), 'mail.yourname.com');
        $this->assertEquals($repository['server'], 'mail.yourname.com');
    }

    /**
     * @expectedException Yosymfony\Toml\Exception\ParseException
     */
    public function testTomlInlineFailConfig()
    {    
        $repository = $this->config->load('var = "my value', Config::TYPE_TOML);
    }
    
    /**
     * @expectedException Symfony\Component\Yaml\Exception\ParseException
     */
    public function testYamlInlineFailConfig()
    {    
        $repository = $this->config->load('var : [ elemnt', Config::TYPE_YAML);
    }
    
    /**
     * @expectedException Yosymfony\Toml\Exception\ParseException
     */
    public function testTomlFileFailConfig()
    {    
        $repository = $this->config->load('configFail.toml');
    }
    
    /**
     * @expectedException Symfony\Component\Yaml\Exception\ParseException
     */
    public function testYamlFileFailConfig()
    {    
        $repository = $this->config->load('configFail.yml');
    }

}
