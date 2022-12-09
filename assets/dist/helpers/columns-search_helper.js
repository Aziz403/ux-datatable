import $ from "jquery";
import ColumnsFormat from "./columns-format_helper";


export default class ColumnsSearch {
    static controllerInstance;

    static addSearchInColumn(dataTableId){
        const { columns } = ColumnsSearch.controllerInstance.viewValue;
        //ila kna deja kaynin inputs d search(idan rah jay men cache ayeb9aw)
        if($(`${dataTableId} thead th.filtre input`).length){return;}
        const searchInput = (col)=>{
            const input = document.createElement('input');
            input.className = "form-control form-control-sm";
            input.name = col.data;
            input.placeholder = "Search "+col.data;
            input.style.minWidth = "20px";
            return input;
        }
        const searchDate = (col)=>{
            const input = document.createElement('input');
            input.className = "form-control form-control-sm";
            input.type = "date";
            input.name = col.data;
            input.placeholder = "Search "+col.data;
            input.style.minWidth = "20px";
            return input;
        }
        const searchSelectSimple = (col)=>{
            const select = document.createElement('select');
            select.className = "form-control form-control-sm";
            select.name = col.data;
            select.style.minWidth = "20px";
            const option = document.createElement('option');
            option.value = '';
            option.innerText = 'Select '+col.data;
            select.append(option);
            for(let val in col.values){
                const option = document.createElement('option');
                option.value = val;
                option.innerText = col.values[val];
                select.append(option);
            }
            return select;
        }
        const searchSelectApi = (col)=>{
            const select = document.createElement('select');
            select.className = "form-control form-control-sm";
            select.name = col.data;
            select.style.minWidth = "20px";
            const option = document.createElement('option');
            option.value = '';
            option.innerText = 'Select '+col.data;
            select.append(option);
            //jib data
            $.get(col.ajax)
                .then((res)=>{
                    for(let item in res.data){
                        const option = document.createElement('option');
                        option.value = res.data[item];
                        option.innerText = item;
                        select.append(option);
                    }
                });
            //hena nejibo selected index
            return select;
        }
        const searchFieldSimple = (col)=>{
            const input = document.createElement('input');
            input.className = "form-control form-control-sm";
            input.name = col.data;
            input.placeholder = "Search "+col.data;
            input.style.minWidth = "20px";
            return input;
        }
        const searchIntBetween = (col)=>{
            //container el
            const container = document.createElement('div');
            container.className = "d-flex";
            //min search input
            const minInput = document.createElement('input');
            minInput.className = "form-control form-control-sm";
            minInput.name = 'min-'+col.data;
            minInput.placeholder = "Min";
            minInput.style.minWidth = "10px";
            container.append(minInput);
            //max search input
            const maxInput = document.createElement('input');
            maxInput.className = "form-control form-control-sm";
            maxInput.name = 'max-'+col.data;
            maxInput.placeholder = "Max";
            maxInput.style.minWidth = "10px";
            container.append(maxInput);
            return container;
        }
        const searchDateBetween = (col)=>{
            //container el
            const container = document.createElement('div');
            container.className = "d-flex";
            //min search input
            const minInput = document.createElement('input');
            minInput.className = "form-control form-control-sm";
            minInput.type = "date";
            minInput.name = 'min-'+col.data;
            minInput.placeholder = "Min";
            minInput.style.width = "95px";
            container.append(minInput);
            //max search input
            const maxInput = document.createElement('input');
            maxInput.className = "form-control form-control-sm";
            maxInput.type = "date";
            maxInput.name = 'max-'+col.data;
            maxInput.placeholder = "Max";
            maxInput.style.width = "95px";
            container.append(maxInput);
            return container;
        }

        //deja zedt wa7ed th.filtre d view index.html.twig d dataTable bax hadok les text li fihom ayetbdlo b input d search d column
        $(`${dataTableId} thead th.filtre`).each(function () {
            var title = $(this).text();
            //nejibo column bax ne3erfo wax had column ayekon fih group by
            var col = ColumnsFormat.getColumnByData(columns,title);
            // nexofo wax static group by (options li f select maxi d database)
            if(col.rowgroup==="static"){
                $(this).html(searchSelectSimple(col));
            }
            // nexofo wax group by entity (options ayekono elements f database)
            else if(col.rowgroup==="api"){
                $(this).html(searchSelectApi(col));
            }
            // nexofo wax group by entity (f had l7ala ayekon search by field li kiban f dik entity [like designation f article])
            else if(col.rowgroup==="field"){
                $(this).html(searchFieldSimple(col));
            }
            // nexofo wax field fih search by wa7ed duree (min & max)
            else if(col.rowgroup==="int_between"){
                $(this).html(searchIntBetween(col));
            }
            // nexofo wax field fih search by wa7ed duree date (min & max)
            else if(col.rowgroup==="date_between"){
                $(this).html(searchDateBetween(col));
            }
            // nexofo wax field fih search b date
            else if(col.rowgroup==="date"){
                $(this).html(searchDate(col));
            }
            // input 3adiya
            else if (title !== 'action') {
                $(this).html(searchInput(col));
            }
            //ila kant action input dyalha teb9a khawya
            else {
                $(this).html('');
            }
        });
    }

    static searchInColumnEvents(table){
        //foreach 3ela columns
        table.api()
            .columns()
            .every(function () {
                //nakhedo this o ne7etoh f var 'that' 7it ane7etajoh menbe3d
                var that = this;
                //title d dak column bax nejob bih input
                var title = $(this.header()).attr('data-tr-name');
                if(title){
                    //nediro events f simple search (input ola select)
                    $(`[name=${title}]`).on('keyup change clear', function () {
                        //ila value d search tebedlat ne3elmo dataTable bax isift requete d api 'then ->' nediro draw (bax teban data jedida)
                        // check wax had value jaya men select ola input (7it katebedel method bax nediro read l data)
                        let value = (this.nodeName==="SELECT" && this.options.selectedIndex) ? this.options[this.options.selectedIndex].value : this.value;
                        if (that.search() !== value) {
                            that.search(value).draw();
                        }
                    });
                    //event search ila kan multi input (int_between search)
                    $(`[name=min-${title}]`).on('keyup change clear', function () {//TODO: JQuery Selector two items different
                        //f had situation ayekon nejibo data men 2 inputs
                        let value = $(`[name=min-${title}]`).val()+' - '+$(`[name=max-${title}]`).val();
                        if (that.search() !== value) {
                            that.search(value).draw();
                        }
                    });
                }
            });
    }
}