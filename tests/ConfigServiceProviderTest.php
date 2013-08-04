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

use Silex\Application;
use Yosymfony\Silex\ConfigServiceProvider\ConfigServiceProvider;

class ConfigServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testRegister()
    {
        $app = new Application();
        $app->register(new ConfigServiceProvider());
        
        $this->assertInstanceOf('Yosymfony\Silex\ConfigServiceProvider\Config', $app['configuration']);
    }
}