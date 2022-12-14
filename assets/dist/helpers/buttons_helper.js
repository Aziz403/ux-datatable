
export default class ButtonsHelper {
    static controllerInstance;

    static getBtnsConfig(pathExcel){
        let btnsConfig = {
            buttons: [
                { extend:'colvis',className:'btn btn-sm btn-default' }
            ]
        };
        if (pathExcel!=='')
        {
            //nezido dropdown
            btnsConfig.buttons.push({ text:'Export',extend: 'collection', className:'btn btn-sm bg-purple' ,buttons:[],});
            //nezido items d dropdown
            if(pathExcel!=='') {
                btnsConfig.buttons[1].buttons.push({
                    text: 'Excel',
                    action: function () {
                        let params = ButtonsHelper.controllerInstance.table.ajax.params();
                        let visibleCols = {};
                        ButtonsHelper.controllerInstance.table.context[0].aoColumns.forEach(col=> visibleCols[`${col.data}`] = Boolean(col.bVisible));
                        let data = {'columns':params.columns,'order':params.order,'visibility':visibleCols};
                        window.open(pathExcel+"?"+ButtonsHelper.serialize(data));
                    }
                });
            }
        }

        return btnsConfig;
    }

    static serialize(obj, prefix){
        var str = [];
        for (let p in obj) {
            if (obj.hasOwnProperty(p)) {
                var k = prefix ? prefix + "[" + p + "]" : p,
                    v = obj[p];
                str.push((v !== null && typeof v === "object") ?
                    ButtonsHelper.serialize(v, k) :
                    encodeURIComponent(k) + "=" + encodeURIComponent(v));
            }
        }
        return str.join("&");
    }
}