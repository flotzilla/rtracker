
$(document).ready(function(){
    $("#future_table").tablesorter();
    $("#rutracker_result").tablesorter({
        theme : 'metro-dark',
        widgets : ["zebra"]
    });

    $("#rutor_result").tablesorter({
        theme : 'metro-dark',
        widgets : ["zebra"]
    });
});