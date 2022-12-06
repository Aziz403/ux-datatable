import { Controller } from '@hotwired/stimulus';
const $ = require('jquery');
import DataTable from "datatables.net";

import "datatables.net-bs4/js/dataTables.bootstrap4";
import "datatables.net-bs4/css/dataTables.bootstrap4.min.css";

import "datatables.net-colreorder/js/dataTables.colReorder";
import "datatables.net-colreorder-bs4/js/colReorder.bootstrap4";
import "datatables.net-colreorder-bs4/css/colReorder.bootstrap4.css";

import "datatables.net-buttons/js/dataTables.buttons";
import "datatables.net-buttons-bs4/js/buttons.bootstrap4";
import "datatables.net-buttons-bs4/css/buttons.bootstrap4.css";
import "datatables.net-buttons/js/buttons.colVis.min";

import ColumnsSearch from "../helpers/columns-search_helper";
import ButtonsHelper from "../helpers/buttons_helper";
import ColumnsFormat from "../helpers/columns-format_helper";
import LanguageHelper from "../helpers/language_helper";

export default class extends Controller {
    static values = {
        view: Object
    }

    connect() {
        if (!(this.element instanceof HTMLTableElement)) {
            throw new Error('Invalid element');
        }

        ColumnsSearch.controllerInstance = this;
        ButtonsHelper.controllerInstance = this;

        const { path, columns, locale, options } = this.viewValue;
        let datatableId = '#'+this.element.id;

        this._dispatchEvent('datatable:pre-connect', { options , datatableId });

        if(DataTable.isDataTable(datatableId)){
            this.table = new DataTable(datatableId);
        }
        else{
            let btnsConfig = ButtonsHelper.getBtnsConfig();
            // nezido search f columns
            ColumnsSearch.addSearchInColumn(datatableId)
            //configuration d table
            this.table = new DataTable(datatableId, {
                dom: "<'d-flex justify-content-between'Bl>t<'d-flex justify-content-between'ip>r",//position d dok plugins (buttons,search input,paginator..) f dom
                processing: true,//progress spinner
                serverSide: true,//requetes d search aykono f parti d server
                colReorder: true,
                ajax: path,
                columns: ColumnsFormat.changeColumnsFormat(columns),// negado format d affichage d cells
                buttons: btnsConfig,
                bStateSave: true,
                pageLength: 10,
                fnStateSaveParams: function (oSettings, oData) {//bax menin idir actualiser l page may3awdx idir search
                    oData.columns.forEach(item=>{
                        if(item.search.search){
                            item.search.search = "";
                        }
                    })
                },
                order: [[0, 'desc']],// by default order by id DESC
                initComplete: function (){// event bax isift requete d search l server mnin ikon search f columns
                    ColumnsSearch.searchInColumnEvents(this);
                },
                language: LanguageHelper.getLanguage(locale),// language 3ela 7esab language d app
                ...options
            });
        }

        // destory table when return by back button in navigator
        document.addEventListener("turbo:before-cache", ()=> {
            if($(datatableId+'_wrapper').length === 1){
                this.table.destroy()
            }
        })

        this._dispatchEvent('datatable:connect', { table: this.table, options });
    }

    toggleSearchColumnsVisibility(){
        let datatableId = "#"+this.element.id;
        $(datatableId+' thead tr').eq(0).toggleClass('d-none');
        const toggleBtn = $(datatableId+' thead tr').eq(1).find('button i');
        if(toggleBtn.hasClass('fa-eye')){
            toggleBtn.addClass('fa-eye-slash')
            toggleBtn.removeClass('fa-eye')
        }
        else{
            toggleBtn.removeClass('fa-eye-slash')
            toggleBtn.addClass('fa-eye')
        }
    }

    _dispatchEvent(name, payload) {
        this.element.dispatchEvent(new CustomEvent(name, { detail: payload }));
    }
}
