CHANGELOG
=========

1.2.1 (2014-06-07)
------------------
* [Fixed] Bug #2: Union operation of a repository change the repository itself.

1.2.0 (2014-05-18)
------------------
* [New] *ConfigRepositoryOperationInterface*: Define the operations with repositories.
* [New] Operations with repositories: *union* and *intersection* method was added to repository.
* [New] Support to .dist files.
* [New] Support to Toml 0.2.0.
* [New] Support to JSON files.
* [New] Added PHP 5.6 and HHVM to Travis CI.
* [Deprecated] Method *mergeWith* of repository is deprecated and replaced by *union* method.

1.1.0 (2013-09-16)
------------------
* [New] Added new method getRaw() in ConfigRepositoryInterface #1.

1.0.0 (2013-08-04)
------------------

* Initial release.
