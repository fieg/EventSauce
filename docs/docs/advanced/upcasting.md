---
permalink: /docs/advanced/upcasting/
title: Upcasting
published_at: 2018-03-11
updated_at: 2018-03-12
---

Event sourcing is a great discovery tools, it's also provides a
solid basis to perform system introspection. By analysing streams
of events you can come to great new insights. Sometimes different
events are needed to better express what has happened. Instead of
handling two types of events and handling each of them separately
**upcasting** can be used to transform messages at runtime. This
prevents from having to alter previously persisted events at a minor
expense.

In EventSauce _upcasting_ is facilitated in the serialization
process. The `EventSauce\EventSourcing\Serialization\UpcastingMessageSerializer`
can be used to add one or more upcasting transformations.

Each upcaster must implement the `EventSauce\EventSourcing\Upcasting\Upcaster`
interface:

```php
<?php

namespace EventSauce\EventSourcing\Upcasting;

use Generator;

interface Upcaster
{
    public function canUpcast(string $type, array $message): bool;

    public function upcast(array $message): Generator;
}
```

Because of the use of PHP **generators** upcasting is simple but very
powerful. Generators allow you to iterate over a collection without
_holding_ the entire collection. A generator can choose to omit, to remove
irrelevant events from the stream, or break up events and emit multiple
more fine-grained ones.

## Using multiple upcasters

You can use multiple upcasters using the `DelegatingUpcaster`. The delegating
upcaster is itself an upcaster that takes one or more `DelegatableUpcaster`
instances, which are an extension of the regular upcaster but also expose
the type which they can upcast for speedy lookups.

```php
<?php

use EventSauce\EventSourcing\Upcasting\DelegatingUpcaster;
use EventSauce\EventSourcing\Serialization\UpcastingMessageSerializer;

$upcastingSerializer = new UpcastingMessageSerializer(
    $actualSerializer,
    new DelegatingUpcaster(new UpcasterOne(), new UpcasterTwo())
);
```
