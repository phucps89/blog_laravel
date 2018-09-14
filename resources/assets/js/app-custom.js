// require('jquery-ui/ui/widgets/autocomplete');

import * as $ from 'jquery';
import 'datatables';

export default (function () {
    $('.ajax-dataTable').each(function(){
        var callbackAjaxComplete = $(this).data('ajax-complete');

        $(this).DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url":$(this).data('url'),
                "dataType": "json",
            },
            "columnDefs": $(this).data('column-defs'),
            "fnDrawCallback": function(){
                if(window[callbackAjaxComplete] !== undefined) {
                    window[callbackAjaxComplete]()
                }
            }
        } );
    });
}());
