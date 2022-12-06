

export default class ColumnsFormat {
    static changeColumnsFormat(columns){
        const badgeFormat = (data)=>{
            if(data){
                return '<span class="badge badge-success p-2">Active</span>';
            }
            else{
                return '<span class="badge badge-warning p-2">Désactive</span>';
            }
        }
        const dateFormat = (data)=>{
            if(data){
                return data.substr(0,10);
            }
            return '';
        }
        const moneyFormat = (data)=>{
            return data+' DH';
        }
        const percentageFormat = (data)=>{
            return data+' %';
        }
        const arrayFormat = (data)=>{
            let html = "";
            for(let key in data){
                if(Array.isArray(data[key])){
                    if(data[key][0]) {
                        html += "<span class='btn btn-default text-sm p-1 mx-1'>"+ key + " = " + data[key][0] + " => " + data[key][1] + "</span>";
                    }
                    else{
                        html += "<span class='btn btn-default text-sm p-1 mx-1'>"+key+" = " + data[key][1] + "</span>";
                    }
                }
            }
            return html;
        }

        return columns.map((item)=>{
            return {
                ...item,
                render: function ( data, type, row, meta ) {
                    // nakhedo format d cell mn item
                    if(item){
                        if(item.format==="budge"){
                            return badgeFormat(data);
                        }
                        if(item.format==="date"){
                            return dateFormat(data);
                        }
                        if(item.format==="price"){ // TODO: devis
                            return moneyFormat(data);
                        }
                        if(item.format==="percentage"){
                            return percentageFormat(data);
                        }
                        if(item.format==="array"){
                            return arrayFormat(data);
                        }
                    }
                    // hena ila makantx 3endha format rah kateban kima jat men database
                    return data;
                }
            }
        })
    }
    static getColumnByData(columns,data){
        const cols = columns.filter(col=>data===col.data);
        if(cols.length===1){
            return cols[0];
        }
        return {data};
    }
}