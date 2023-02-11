Events
=========

UxDatatable Supports Events!
This image explain how events runs:


The following is a list of events you can listen to:

| Event name | Event constant | Event argement | Trigger point |
|------------|----------------|----------------|--------------|
| `datatable.pre_query` | `Events::PRE_QUERY` | `RenderQueryEvent::class` | before create query to get records |
| `datatable.search_query` | `Events::SEARCH_QUERY` | `RenderSearchQueryEvent::class` | after create query builder to get records |
| `datatable.pre_data` | `Events::PRE_DATA` | `RenderDataEvent::class` | before create convert query result to view data |

### Example 1
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

### Example 2

We also have another way to set event easy ,but this is technique works just with `datatable.search_query` event:

```php
$datatable = $builder->createDatatableFromEntity(Product::class);
$datatable
    ->addColumn(new TextColumn('name'))
    ->addColumn(new TextColumn('description','description',false))
    ->addColumn(new TextColumn('price'))
    ->addColumn(new BooleanColumn('isEnabled'))
    ->addColumn(new EntityColumn('category','name'))
    ->addFilter(function(RenderSearchQueryEvent $event){
        $q = $event->getQuery();
        $q->andWhere('entity.name = :name')
            ->setParameter('name',"Product 3");
    })
;

$datatable->handleRequest($request);

if($datatable->isSubmitted()){
    return $datatable->getResponse();
}

return $this->render('simple_datatable.html.twig', [
    'datatable' => $datatable
]);
```

### Different Betwenn ``addCriteria`` and ``addFilter``

To explain the different, lets see examples:

### Without ``addCriteria`` or ``addFilter``

The Result looks like:
```
[
    'data' => [...],
    'recordsTotal', => 50,
    'recordsFiltered' => 50
]
```

#### Using ``addCriteria``

The Criteria applyed for all records, filtered or not!
This custimize data will be viewed in datatable

```
[
    'data' => [...],
    'recordsTotal', => 40,
    'recordsFiltered' => 40
]
```

#### Using ``addFilter``

The Filter applyed jusft to the filtered records.
And can you use it to add a custom search or filter.

```
[
    'data' => [...],
    'recordsTotal', => 50,
    'recordsFiltered' => 40
]
```
