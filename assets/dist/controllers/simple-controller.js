import { Controller } from '@hotwired/stimulus';
import DataTable from "datatables.net"

export default class extends Controller {
    static values = {
        view: Object
    }

    connect() {
        if (!(this.element instanceof HTMLTableElement)) {
            throw new Error('Invalid element');
        }

        const { options } = this.viewValue;

        this._dispatchEvent('datatable:pre-connect', { options });

        this.table = new DataTable(this.element, options);

        this._dispatchEvent('datatable:connect', { table: this.table, options });
    }

    _dispatchEvent(name, payload) {
        this.element.dispatchEvent(new CustomEvent(name, { detail: payload }));
    }
}
