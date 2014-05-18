CHANGELOG
=========

1.0.0 (2013-08-04)
------------------

* Initial release.

1.1.0 (2013-09-16)
------------------
* [New] Added new method getRaw() in ConfigRepositoryInterface #1.

1.2.0 (2014-05-18)
------------------
* [New] *ConfigRepositoryOperationInterface*: Define the operations with repositories.
* [New] Operations with repositories: *union* and *intersection* method was added to repository.
* [New] Support to .dist files.
* [New] Support to Toml 0.2.0.
* [Deprecated] Method *mergeWith* of repository is deprecated and replaced by *union* method.