Themes Configuration
=========

Change the global datatable theme by changing the ``style`` key in configuration:

```yaml
datatable:
  template_parameters:
    style: 'none'
```

The available style themes is the same of datatables.net [styling themes](https://datatables.net/manual/styling/):
| Theme     | Reference    |
| ---- | ---- |
| none | without using any theme |
| bootstrap3 | [Example](https://datatables.net/examples/styling/bootstrap.html) |
| bootstrap4 | [Example](https://datatables.net/examples/styling/bootstrap4.html) |
| bootstrap5 | [Example](https://datatables.net/examples/styling/bootstrap5.html) |
| foundation | [Example](https://datatables.net/manual/styling/foundation) |
| bulma | [Example](https://datatables.net/examples/styling/bulma.html) |
| jqueryui | [Example](https://datatables.net/manual/styling/jqueryui) |

or can you create your custom theme and use it in ``global_controller`` from [theme creator](https://datatables.net/manual/styling/theme-creator).[Example](/docs/global_controller_example.md)
