## Themes Configuration

Change the global datatable theme esay by changing the ``style`` key in configuration:

```yaml
datatable:
  template_parameters:
    style: 'none'
```

The available style themes is the same of datatables.net [styling themes](https://datatables.net/manual/styling/):
* none (without using any theme)
* bootstrap3
* bootstrap4
* bootstrap5
* foundation
* bulma
* jqueryui

or can you create your custom theme and use it in ``global_controller`` from [theme creator](https://datatables.net/manual/styling/theme-creator).
