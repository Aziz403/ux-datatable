Events
=========

UxDatatable Supports Events!
This image explain how events runs:


The following is a list of events you can listen to:

| Event name | Event constant | Event argement | Trigger point |
|------------|----------------|----------------|--------------|
| `datatable.pre_query` | `Events::PRE_QUERY` | `RenderQueryEvent::class` | before create query to get records |
| `datatable.pre_data` | `Events::PRE_DATA` | `RenderDataEvent::class` | before create convert query result to view data |

### Example
Create a listener class:

```php
<?php

namespace App\EventListener;

use Aziz403\UX\Datatable\RenderQueryEvent;

class CustomListener
{
    public function onPreCreateQuery(RenderQueryEvent $event)
    {
        $datatable = $event->getDatatable();
        $query = $event->getQuery();

        // do your stuff with $datatable and/or $query...
    }

}
```

Configure it in your configuration:

```yaml
# config/services.yaml or app/config/services.yml
services:
    App\EventListener\CustomListener:
        tags:
            - { name: kernel.event_listener, event: datatable.pre_query }
```