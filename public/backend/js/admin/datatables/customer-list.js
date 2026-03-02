(function ($) {
    "use strict";
    var table = $("#BlogTable").DataTable({
        pageLength: 25,
        serverSide: true,
        processing: true,
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
        },
        ajax: $('#table-url').data("url"),
        columns: [
            {
                data: 'name',
                name: 'name',
                orderable: true,
                searchable: true
            },
            {
                data: 'Number',
                name: 'Number',
                orderable: true,
                searchable: true
            },
            {
                data: 'email',
                name: 'email',
                orderable: true,
                searchable: true
            },
            {
                data: 'is_subscribed',
                name: 'is_subscribed',
                orderable: false,
                searchable: false
            },
            {
                data: 'offer_types',
                name: 'offer_types',
                orderable: false,
                searchable: false
            },
            {
                data: 'orders',
                name: 'orders',
                orderable: false,
                searchable: false
            },
            {
                data: 'registered_at',
                name: 'created_at',
                orderable: true,
                searchable: false
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ]
    });
})(jQuery);
