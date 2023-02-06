## Configuration

If you're using ``symfony/flex`` can you found this is tour default config:

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

can you customize the config to be compatible with what you're wants.

The main parts of datatable configuration:
* Default Datatables [Language](/docs/languages.md)
* Default Datatables [Theme](/docs/themes.md)
* Default Datatables Options: supports the Datatables.net options
* Template Parameters: style and className
* Global Controller: use the ``global_controller`` in your ``datatable`` config