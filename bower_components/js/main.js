
$(document).ready(function(){
    $("#future_table").tablesorter({
        theme : 'metro-dark',
        widgets : ["zebra"]
    });
    $("#rutracker_result").tablesorter({
        theme : 'metro-dark',
        widgets : ["zebra"]
    });

    $("#rutor_result").tablesorter({
        theme : 'metro-dark',
        widgets : ["zebra"]
    });

    get_new_items_count();

    buttons_handler();
});


function get_new_items_count(){
    var new_items = $('#new_items_counter').attr('data-count');
    $('.items_counter').each(function(){
       $(this).append('<span class="badge">' + new_items + '</span>');
    });
}

function buttons_handler(){
    document.getElementById('save-btn').addEventListener('click', send_new_items);
}

function send_new_items(){
    var items = [];
    $('tr').each(function(){
        if($(this).attr('data-item-type') == 'new'){
            var alink = $(this).find('td.data-item').children();
            var href = alink.attr('href');
            var torrent_name = alink.text().trim();
            items.push({
                "name": torrent_name,
                "link": href
            });
        }
    });

    items = JSON.stringify(items);
    console.log(items);

    var req = $.ajax({
        url: "classes/xmlhttpreq/list_action.php",
        type: 'json',
        method: 'post',
        data: {
            action: 'save-list',
            data: items
        },
        success: function(){
            console.log(req.responseText);
        },
        error: function(){
            console.log(this);
        }
    });

}