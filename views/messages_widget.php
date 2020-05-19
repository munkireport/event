<div class="col-lg-8 col-md-6">

    <div class="panel panel-default" id="events-widget">

        <div class="panel-heading" data-container="body" data-i18n="[title]events.widget_title">

            <h3 class="panel-title"><i class="fa fa-bullhorn"></i>
                <span data-i18n="event_plural"></span>
                <list-link data-url="/show/listing/event/event"></list-link>
            </h3>

        </div>

        <div class="list-group scroll-box" style="max-height: 308px"></div>

    </div><!-- /panel -->

</div><!-- /col -->

<script src="<?php echo url('module/event/js/format_event_data')?>"></script>

<script>
$(document).on('appUpdate', function(){
    
    var list = $('#events-widget div.scroll-box'),
        icons = {
            danger:'fa-times-circle',
            warning: 'fa-warning',
            info: 'fa-info-circle',
            success: 'fa-check-circle'
        },
        update_time = function(){
            $( "time" ).each(function( index ) {
                var date = new Date($(this).attr('datetime') * 1000);
                $(this).text(moment(date).fromNow());
            });
        },
        get_module_item  = function(item){
            formatEventData(item);
            
            // Get appropriate icon
            var icon = '<i class="text-'+item.type+' fa '+icons[item.type]+'"></i> ',
                url = appUrl+'/clients/detail/'+item.serial_number+item.tab,
                date = new Date(item.timestamp * 1000);
            
            return $('<a class="list-group-item">')
                        .attr('href', url)
                        .append($('<span class="pull-right" style="padding-left: 10px">')
                            .text(moment(date).fromNow())
                            
                        )
                        .append(icon)
                        .append($('<span>').text(item.computer_name))
                        .append($('<span class="hidden-xs"> | </span>'))
                        .append($('<br class="visible-xs-inline">'))
                        .append($('<span>').text(item.module + ' '+item.msg))
            // return '<a class="list-group-item" href="'+url+'">'+
            //         '<span class="pull-right" style="padding-left: 10px">'++'</span>'+
            //         icon+item.computer_name+'<span class="hidden-xs"> | </span><br class="visible-xs-inline">'+
            //         item.module + ' '+item.msg+'</a>'
        };

    $.getJSON( appUrl + '/module/event/get/50') // TODO make this configurable
    .done(function( data ) {

        if(data.error)
        {
            alert(data.error)
            if(data.reload)
            {
                location.reload();
            }
        }
        list.empty();

        var arrayLength = data.items.length
        if (arrayLength)
        {
            for (var i = 0; i < arrayLength; i++) {
                list.append(get_module_item(data.items[i]));
            }

            update_time();
        }
        else
        {
            list.append('<span class="list-group-item">No messages</span>');
        }


    }).fail(function( jqxhr, textStatus, error ) {
        list.empty();
        var err = textStatus + ", " + error;
        list.append('<span class="list-group-item list-group-item-danger">'+
            "Request Failed: " + err+'</span>')
    });
});
</script>
