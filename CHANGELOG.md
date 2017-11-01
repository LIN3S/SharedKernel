# CHANGELOG

This changelog references the relevant changes done between versions.

To get the diff for a specific change, go to https://github.com/LIN3S/SharedKernel/commit/XXX where XXX is the change hash 
To get the diff between two versions, go to https://github.com/LIN3S/SharedKernel/compare/v0.4.0...v0.5.0

* 0.6.0
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
