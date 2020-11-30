
function loadJQuery() {
    var waitForLoad = function () {
        var my = window.setTimeout(waitForLoad, 500);
        if (typeof jQuery != undefined) {
            console.log("jquery loaded.."); 
            window.clearTimeout(my);  
        } else {
            console.log("jquery not loaded..");
        }
    };
}

$(document).ready(function(){
    get_servers(); // init
    get_db(); // init
    setInterval( function() { console.clear(); get_servers(); get_db(); }, 5000);
})

// Load jQuery
window.onload = loadJQuery;