
export default class ButtonsHelper {
    static controllerInstance;

    static getBtnsConfig(){
        let btnsConfig = {
            buttons: [
                { extend:'colvis',className:'btn btn-sm btn-default' }
            ]
        };
        if (ButtonsHelper.controllerInstance.exportExcellPathValue || ButtonsHelper.controllerInstance.exportBonPathValue || ButtonsHelper.controllerInstance.exportPdfPathValue)
        {
            //nezido dropdown
            btnsConfig.buttons.push({ text:'Export',extend: 'collection', className:'btn btn-sm bg-purple' ,buttons:[],});
            //nezido items d dropdown
            if(ButtonsHelper.controllerInstance.exportPdfPathValue){
                btnsConfig.buttons[1].buttons.push({
                    text:'PDF',
                    action:function () {
                        let params = ButtonsHelper.controllerInstance.table.ajax.params();
                        let visibleCols = {};
                        ButtonsHelper.controllerInstance.table.context[0].aoColumns.forEach(col=> visibleCols[`${col.data}`] = Boolean(col.bVisible));
                        let data = {'columns':params.columns,'order':params.order,'visibility':visibleCols};
                        window.open(ButtonsHelper.controllerInstance.exportPdfPathValue+"?"+ButtonsHelper.serialize(data));
                    }
                });
            }
            if(ButtonsHelper.controllerInstance.exportBonPathValue){
                btnsConfig.buttons[1].buttons.push({
                    text:'BON',
                    action:function () {
                        let params = ButtonsHelper.controllerInstance.table.ajax.params();
                        let visibleCols = {};
                        ButtonsHelper.controllerInstance.table.context[0].aoColumns.forEach(col=> visibleCols[`${col.data}`] = Boolean(col.bVisible));
                        let data = {'columns':params.columns,'order':params.order,'visibility':visibleCols};
                        window.open(ButtonsHelper.controllerInstance.exportBonPathValue+"?"+ButtonsHelper.serialize(data));
                    }
                });
            }
            if(ButtonsHelper.controllerInstance.exportExcellPathValue) {
                btnsConfig.buttons[1].buttons.push({
                    text: 'Excel',
                    action: function () {
                        let params = ButtonsHelper.controllerInstance.table.ajax.params();
                        let visibleCols = {};
                        ButtonsHelper.controllerInstance.table.context[0].aoColumns.forEach(col=> visibleCols[`${col.data}`] = Boolean(col.bVisible));
                        let data = {'columns':params.columns,'order':params.order,'visibility':visibleCols};
                        window.open(ButtonsHelper.controllerInstance.exportExcellPathValue+"?"+ButtonsHelper.serialize(data));
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