# CHANGELOG

This changelog references the relevant changes done between versions.

To get the diff for a specific change, go to https://github.com/LIN3S/SharedKernel/commit/XXX where XXX is the change hash 
To get the diff between two versions, go to https://github.com/LIN3S/SharedKernel/compare/v0.8.0...v0.9.0

* 0.9.8
   * Made tactician event subscriber definitions public to avoid Symfony 4 issues
* 0.9.7
   * Allow to an event object to contain an array
* 0.9.6
   * Added `nationalPhone` in the Phone value object.
* 0.9.5
    * Allowed multiple tags with the same subscriber,
* 0.9.4
    * Fixed typo in the Doctrine custom type `PhoneType`.
* 0.9.3
    * Fixed return type error in the Doctrine custom types. 
* 0.9.2
    * Added comment support for Doctrine's custom types.
* 0.9.1
    * Fixed typo in the Doctrine's mappings in the standalone way.
* 0.9.0
    * Added Doctrine Mappings ready to use with Doctrine in standalone mode.
    * Added Iban and TaxIdNumber Doctrine custom types.
* 0.8.0
    * Added Iban value object.
* 0.7.1
    * Added accesses to the primitive value of the TaxIdNumber.
* 0.7.0
    * Added TaxIdNumber value object.
* 0.6.0
    * Added `update` method to the Pdo class.
    * Added utf8 charset as default option in the pdo.
    * Added missing return types to the Id VO.
    * Added `count` method to the Pdo.
    * Removed `isSubcribedTo` method from DomainEventSubscriber interface.
    * Improved event magic serialization/unserialization process.
    * Simplified the insert method of the `Pdo`.
    * Fixed the `ListEventsAction` type hints.
    * Fixed bug related with `serializeEvent` in the StoredEvent.
* 0.5.1
    * Fixed bug related with the wrong params in the phone VO.
* 0.5.0
    * Added `/events` endpoint to consume via REST the domain events.
    * Added stream version concept to the event store.
    * Removed datetime custom API extending the PHP native `DateTimeImmutable` class.
    * Made tactician services optional.
    * Replaced generic php-cs-fixer with lin3s/php-cs-fixer-config.
    * The minimum PHP version becomes 7.1.
    * Added timestamp methods to the DateTime wrapper.
    * Added PDO wrapper and sql connection factory.
    * Added DomainEventPublisher and added Tactician middleware to publish domain events after command execution.
    * Added support for Tactician command bus with PDO middleware.
* 0.4.0
    * Added locale value object.
    * Added zip code and phone value objects.
* 0.3.0
    * Fixed Value objects' mapping.
    * Added Lin3sSharedKernelBundle.
    * [BC BREAK] Added an interface to fix bug when the aggregate root is used with trait.
* 0.2.1
    * Implemented the generate method from Id to simplify the creation of Ids in our domains.
* 0.2.0
    * Added DateTime class that wraps php's native DateTimeImmutable.
    * [BC BREAK] Reordered all the namespaces of the package so, be careful upgrading from v0.1.0
    * Refactored all the basic exceptions and added DomainException and LogicException wrappers.
