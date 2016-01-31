
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

    if(new_items == 0){
        $('#save-btn').attr('disabled', 'disabled');
    }
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

    if(items.length == 0){
        $('#info-block').text("Nothing to save");
        return;
    }

    items = JSON.stringify(items);
    var req = $.ajax({
        url: "classes/xmlhttpreq/list_action.php",
        type: 'json',
        method: 'post',
        data: {
            action: 'save-list',
            data: items
        },
        success: function(){
            var resp = JSON.parse(req.responseText);
            post_save_list_action(resp);
        },
        error: function(){
            console.log(this);
        }
    });

}

function post_save_list_action(resp){
    if(resp != false){
        if(resp.status && resp.status == "saved"){
            $('#info-block').text("Successfully saved to file");
            $('#new_items_counter').attr('data-count', 0);

            $('.items_counter').each(function(){
                $(this).remove();
            });

            $('tr').each(function () {
                if ($(this).attr('data-item-type') == 'new') {
                    $(this).attr('data-item-type', 'old');
                    var span = $(this).find("span.glyphicon-flash");

                    span.removeClass('glyphicon-flash');
                    span.removeClass('color-green');
                    span.addClass('color-orange glyphicon-asterisk');
                }
            });

        }else if(resp.error){
            $('#info-block').text(resp.error);
        }
    }
}