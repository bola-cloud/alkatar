(function($) {
    "use strict";
    $(document).ready(function () {
        $('#BlogTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: $('#table-url').data("url"),
            columns: [
                {
                    data: 'state_id',
                    name: 'state_id'
                },
                {
                    data: 'city_id',
                    name: 'city_id'
                },
                {
                    data: 'charge',
                    name: 'charge'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ]
        });

     
    });
})(jQuery)