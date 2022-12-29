
console.log("start");
let theme = document.querySelector("[data-styling-choicer]").getAttribute("data-styling-choicer");
console.log("gere1",theme);
if(theme!=="none"){
    console.log("gere2",theme);
    import("../datatable-styling/js/dataTables."+theme);
    import("../datatable-styling/css/dataTables."+theme+".css");
}