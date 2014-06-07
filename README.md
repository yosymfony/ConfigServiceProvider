Config ServiceProvider for Silex
================================

A configuration ServiceProvider for Silex based on 
[Symfony Config component](https://github.com/symfony/Config) with support for 
YAML, TOML (0.2.0) and JSON.

[![Build Status](https://travis-ci.org/yosymfony/ConfigServiceProvider.png?branch=master)](https://travis-ci.org/yosymfony/ConfigServiceProvider)
[![Latest Stable Version](https://poser.pugx.org/yosymfony/config-serviceprovider/v/stable.png)](https://packagist.org/packages/yosymfony/config-serviceprovider)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/yosymfony/ConfigServiceProvider/badges/quality-score.png?s=9354c788b66668332f215f5d7d1b7809c1ddaed0)](https://scrutinizer-ci.com/g/yosymfony/ConfigServiceProvider/)
[![Total Downloads](https://poser.pugx.org/yosymfony/config-serviceprovider/downloads.png)](https://packagist.org/packages/yosymfony/config-serviceprovider)

Installation
------------

Use [Composer](http://getcomposer.org/) to install Yosyfmony ConfigServiceProvider package:

Add the following to your `composer.json` and run `composer update`.

    "require": {
        "yosymfony/config-serviceprovider": "1.2-dev"
    }

More informations about the package on 
[Packagist](https://packagist.org/packages/yosymfony/config-serviceprovider).

Usage
-----
Register the ServiceProvider:

    $app->register(new ConfigServiceProvider());
    
You can set a collection of locations where it should look for files and 
reference it only with the file's name

    $app->register(new ConfigServiceProvider(array(
        __dir__.'/config',
        '/var/www/general-config'
    )));
    
### Load a configuration file:

    $repository = $app['configuration']->load('user.yml');
    
    // or load with absolute path:
    $repository = $app['configuration']->load('/var/config/user1.yml');
    
#### *.dist* files

This library have support to `.dist` files. The location of a file follow the next hierachy:

- filename.ext
- filename.ext.dist (if filename.ext not exists)

### Load inline configuration:

    use Yosymfony\Silex\ConfigServiceProvider\Config;
    
    $repository = $app['configuration']->load('server: "mail.yourname.com"', Config::TYPE_YAML);
    // or
    $repository = $app['configuration']->load('server = "mail.yourname.com"', Config::TYPE_TOML);
    
Repository
----------
The configuration file is loaded to a repository. A repository is a wrapper with 
array access interface that supply methods for validating configurations values 
and merge with other repositories.

    $repository->get('name', 'noname'); // If 'name' not exists return 'noname'
    $repository['name']; // Get the element in 'name' key

### Validating configurations
The values and the structure can be validated using a definition class from 
[Symfony Config component](http://symfony.com/doc/current/components/config/definition.html). 
For example, for this configuratión file:

    # Yaml file
    port: 25
    server: "mail.yourname.com"

you can create the below definition:

    use Symfony\Component\Config\Definition\ConfigurationInterface;
    use Symfony\Component\Config\Definition\Builder\TreeBuilder;
    
    class MyConfigDefinitions implements ConfigurationInterface
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

and check your repository: `$repository->validateWith(new MyConfigDefinitions());`
An exception will be thrown if any definition constraints are violated.

### Operations
The operation ***mergeWith* was deprecated since version 1.2.0 and replaced by
*union* method**.

#### Unions
You can get the union of repository A with other B with C as result: 
`$resultC = $repositoryA->union($repositoryB);`. 
The values of `$repositoryB` have less priority than `$repositoryA`.

#### Intersections
You can get the intersection of repository A with other B with C as result: 
`$resultC = $repositoryA->intersection($repositoryB);`. 
The values of `$repositoryB` have less priority than `$repositoryA`.

### Create a blank repository
Create a blank repository is too easy. You only need create a instance of 
`ConfigRepository` and use the array interface or set method to insert new values:

    use Yosymfony\Silex\ConfigServiceProvider\ConfigRepository;
    
    //...
    
    $repository = new ConfigRepository();
    $repository->set('key1', 'value1');
    // or
    $repository['key1'] = 'value1';
    
and the next, you merge with others or validate it.

Unit tests
----------

You can run the unit tests with the following command:

    $ cd your-path/vendor/yosymfony/config-serviceprovider
    $ composer.phar install --dev
    $ phpunit