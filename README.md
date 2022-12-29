Symfony UX Datatables.net
===================

Symfony UX Datatables.net is a Symfony bundle integrating the
`Datatables.net` library in Symfony applications.

![Datatable Example](/docs/images/datatable-example.png?raw=true)

Installation
------------

Before you start, make sure you have `Symfony UX configured in your app`.

Then, install this bundle using Composer and Symfony Flex:

    $ composer require aziz403/ux-datatablejs

    # Don't forget to install the JavaScript dependencies as well and compile
    $ npm install --force
    $ npm run watch

    # or use yarn
    $ yarn install --force
    $ yarn watch

Also make sure you have at least version 3.0 of `@symfony/stimulus-bridge`
in your ``package.json`` file.

Documentation
-----

The documentation is rendered [here](https://github.com/Aziz403/ux-datatable/blob/1.x/docs/index.md).\
The source of the documentation is available in the /docs folder.