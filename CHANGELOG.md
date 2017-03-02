#CHANGELOG

This changelog references the relevant changes done between versions.

To get the diff for a specific change, go to https://github.com/LIN3S/SharedKernel/commit/XXX where XXX is the change hash 
To get the diff between two versions, go to https://github.com/LIN3S/SharedKernel/compare/v0.2.0...v0.3.0

* 0.3.0
    * [BC BREAK] Added an interface to fix bug when the aggregate root is used with trait.
* 0.2.1
    * Implemented the generate method from Id to simplify the creation of Ids in our domains.
* 0.2.0
    * Added DateTime class that wraps php's native DateTimeImmutable.
    * [BC BREAK] Reordered all the namespaces of the package so, be careful upgrading from v0.1.0
    * Refactored all the basic exceptions and added DomainException and LogicException wrappers.
