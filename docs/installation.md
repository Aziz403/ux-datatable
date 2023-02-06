## Installation

### Step 1: Installation

For Symfony Flex installation you need to enable community recipes:

```sh
  composer config extra.symfony.allow-contrib true
```

Install

```sh
  composer require aziz403/ux-datatable
```

### Step 2: Enable the bundle (Optional)

Enable the bundle in the kernel (not needed with symfony flex):

```php
<?php
// config/bundles.php

return [
    // ...
    Aziz403\UX\Datatable\DatatableBundle::class => ['all' => true],
];
```

And add your global datatable configuration in 

```yaml
datatable:
  # Load i18n data from DataTables CDN or locally
  language_from_cdn: true
  # Language of the Datatables (if language_from_cdn true)
  language: 'en'
  # Default options to load into DataTables
  # options:
  #    stateSave: true
  # Default parameters to be passed to the template
  template_parameters:
    # Default Datatables style (one from none,bootstrap3,bootstrap4,bootstrap5,foundation,bulma,jqueryui)
    style: 'bootstrap5'
    # Default class attribute to apply to the root table elements (change it to be compatible with the style)
    className: 'table table-striped'
```

See [Docs](/docs/configuration.md) for more about configuration.