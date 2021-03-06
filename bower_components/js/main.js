
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
    var new_items = $('#new_items_counter');
    var count = new_items.attr('data-count');
    $('.items_counter').each(function(){
       $(this).append('<span class="badge">' +  count + '</span>');
    });
    var $info = $("#info-block");
    $info.html($info.text() + '<br>' + new_items.text());
}

function buttons_handler(){
    var save_btn = document.getElementById('save-btn');
    if(save_btn != undefined){
        save_btn.addEventListener('click', grab_new_items);
    }

    $('.my-list-rm').on('click', function(){
        remove_from_my_list($(this));
    });
}


function grab_new_items(){
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
   ajax_list_action(items, 'save-to-list', 'save-all', post_grub_send_action)
}

function ajax_list_action(items, action_type, action_params, post_action, post_obj){
    var req = $.ajax({
        url: "classes/xmlhttpreq/list_action.php",
        type: 'json',
        method: 'post',
        data: {
            data: items,
            action: action_type,
            "action-type": action_params
        },
        success: function(){
            var resp = JSON.parse(req.responseText);
            if(post_action !== undefined){
                post_action(resp, post_obj);
            }
        },
        error: function(){
            console.log(this);
        }
    });
}

function post_grub_send_action(resp){
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
            $('#info-block').html(resp.error);
        }
    }
}


/*
    add/remove single item to/from future list
 */
function add_single_item(obj){
    var type = $(obj).attr('data-action-type');
    var item = $(obj).parent().find('a.item-data');
    var name = item.text().trim();
    var href = item.attr('href').trim();
    var items = [];

    items.push({
        "name": name,
        "link": href
    });

    items = JSON.stringify(items);

    if(type == "add"){
        ajax_list_action(items, 'save-to-list', 'single', post_new_item_action, obj);

    }else if(type == "remove"){
        ajax_list_action(items, 'remove-from-list', 'single', post_remove_item_action, obj);
    }
}

function post_new_item_action(resp, object){
    if(resp.status && resp.status == "saved"){
        var j_ob = $(object);
        j_ob.removeClass('glyphicon-ok-circle color-green');
        j_ob.addClass('glyphicon-remove-circle color-red');
        j_ob.attr('data-action-type', 'remove');
        j_ob.attr('title', 'Remove from future list');
    }else if(resp.error){
        //do something with it
        console.log(resp.error);
    }
}

function post_remove_item_action(resp, object){
    if(resp.status && resp.status == "ok"){
        var j_ob = $(object);
        j_ob.removeClass('glyphicon-remove-circle color-red');
        j_ob.addClass('glyphicon-ok-circle color-green');
        j_ob.attr('data-action-type', 'add');
        j_ob.attr('title', 'Add to future list');
    }else{
        console.log('ouuups. Cannot remove item from future list');
        console.log(resp.error);
    }
}

function remove_from_my_list(obj) {
    var parent = $(obj).closest('tr');
    var a = parent.find('td.data-item').find('a');
    var link = a.attr('href').trim();
    var text = a.text().trim();

    var items = [];
    items.push({
            name: text,
            link: link
        });

    items = JSON.stringify(items);

    var remove = function(){
      parent.remove();
    };
    ajax_list_action(items, 'remove-from-list', 'single', remove, obj);

}