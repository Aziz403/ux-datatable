Configuration
=========

If you're using ``symfony/flex`` can you found this is your default config:

```yaml
//config/packages/datatable.yaml
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

Can you customize the config to be compatible with what you're wants.
But first remember! This is shoud be global configuration and can you change it in your `Datatable`.

This the Datatable options:

+-------------------+------------------------------------------------------------------------------------------+--------------+
| Option            | Description                                                                              | Value        |
+===================+==========================================================================================+==============+
| language_from_cdn | Load Datatable language from ``dataTables.net`` CDN or locally. [See More](/docs/languages_and_translation.md#locally-datatable-translation)   | ``boolean``  |
+-------------------+------------------------------------------------------------------------------------------+--------------+
| language          | Language will used in Datatable, Also supports locale language base on request. [See More](/docs/languages_and_translation.md#available-language-options) | ``string``   |
+-------------------+------------------------------------------------------------------------------------------+--------------+
| options           | To set default options to load into ``dataTables.net``, [See More](https://datatables.net/reference/option)  | ``array``    |
+-------------------+------------------------------------------------------------------------------------------+--------------+
| template_parameters | Style parameters has two options: ``style``: One of ``dataTables.net`` themes. ``className`` class attribute of your table. [See More](/docs/themes.md)   | ``array``    |
+-------------------+------------------------------------------------------------------------------------------+--------------+
| global_controller | Create and use a custom controller to use custom datatable style,functions,plugins,extensions... [Example](/docs/global_controller_example.md)   | ``boolean``  |
+-------------------+------------------------------------------------------------------------------------------+--------------+