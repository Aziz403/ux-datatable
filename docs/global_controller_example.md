Example of using ``global_controller``
=========

In this is example we will use ``global_controller`` to render a custom theme.

### Step 1 : Create Stimulus Controller
Of course the first step is create the controller, and also we need to relate it with our main datatable controller, how ? its easy like that:

```
// myglobal-datatable_controller.js

import { Controller } from '@hotwired/stimulus';
import $ from 'jquery';
import "myglobal_datatable.css"; // Use your datatable custom style

export default class extends Controller {
    connect() {
        this.element.addEventListener('datatable:before-connect', this._onPreConnect);
        this.element.addEventListener('datatable:connect', this._onConnect);
    }

    disconnect() {
        // You should always remove listeners when the controller is disconnected to avoid side effects
        this.element.removeEventListener('datatable:before-connect', this._onPreConnect);
        this.element.removeEventListener('datatable:connect', this._onConnect);
    }

    _onPreConnect(event) {
        // The datatable is not yet created
        // You can access the datatable options using the event details
        event.detail.options['dom'] = "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'<'float-right destination-datatable-buttons-container'>>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";
    }

    _onConnect(event) {
        // The datatable was just created
        // use global search input
        let globalSearchBar = $('input.global-search-bar');
        globalSearchBar.keyup(function(){
            // You can access the datatable instance using the event details
            event.detail.table.search($(this).val()).draw();
        })
    }
}
```

### Step 2 : Use Stimulus Controller In Config
Set controller in config

```yaml
//config/packages/datatable.yaml
datatable:
  global_controller: 'myglobal-datatable'
```