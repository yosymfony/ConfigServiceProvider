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

/**
 * Interface of operations with repositories
 * 
 * @author Victor Puertas <vpgugr@gmail.com>
 */
interface ConfigRepositoryOperationInterface
{
    /**
     * Union the repository with $repository. The values of $repository have
     * less precedence
     * 
     * @param ConfigRepositoryInterface $repository
     * 
     * @return ConfigRepositoryInterface A new repository
     */
    public function union(ConfigRepositoryInterface $repository);
    
    /**
     * Intersection the repository with $repository. The values of $repository have
     * less precedence
     * 
     * @param ConfigRepositoryInterface $repository
     * 
     * @return ConfigRepositoryInterface A new repository
     */
    public function intersection(ConfigRepositoryInterface $repository);
}