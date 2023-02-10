import { Controller } from '@hotwired/stimulus';
import DataTable from "datatables.net";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static values = {
        view: Object
    }

    connect() {
        if (!(this.element instanceof HTMLTableElement)) {
            throw new Error('Invalid element');
        }

        //consult EntityDatatable::createView() result
        const { path, options } = this.viewValue;
        let datatableId = '#'+this.element.id;

        this._dispatchEvent('datatable:before-connect', { path, options , datatableId });

        //check if datatable already exists
        if(DataTable.isDataTable(datatableId)){
            this.table = new DataTable(datatableId);
        }
        //configuration the table
        else{
            this.table = new DataTable(datatableId, options);
        }

        // destroy table when return by cache (in ux-turbo)
        document.addEventListener("turbo:before-cache", ()=> {

            this._dispatchEvent('datatable:before-cache', { table: this.table , datatableId });

            if(document.querySelectorAll(datatableId+'_wrapper').length === 1){
                this.table.destroy()
            }
        })

        this._dispatchEvent('datatable:connect', { table: this.table, options, path });
    }

    _dispatchEvent(name, payload) {
        this.element.dispatchEvent(new CustomEvent(name, { detail: payload }));
    }
}
