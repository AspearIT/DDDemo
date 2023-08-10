# Domain Driven Demo

This repo is a simplified example of an event sourced DDD application.

## The DDD example

In this case you can see how an orderline is added to an order. It's more code than how a regular traditional application would be written to do the same. But the architeture has a lot of advantages when it comes to:
 - write unit tests for this code
 - write domain specific validations / business rules
 - self-explaining code due to the ubiquitous language

## Event driven
In this case the domain is event driven. This means that every change made in the domain will cause an event. This will tackle
 - Separating side effects like confirmation mails, logging, etc. from the domain itself
 - Implementing a CQRS architecture. The read structure can keep itself updated by listening to the events.
 - The need for a unit-of-work (see the traditional order repository how you can implement a repository based on events)

## Event sourcing

The domain contains two repositories. One of them is meant for DDD with a traditional database, the other is meant for an event sourced storage.

With event sourcing you never loose data. Every change in the domain is stored as an event. This means that you can always recreate the state of the domain by replaying the events. This is very useful when you want to implement a CQRS architecture. The read model can be recreated by replaying the events.

The beauty of this architecture is that the domain itself doesn't know how it is stored, so both storage implementations can be used without disturbing the domain itself.

