(function($) {
    "use strict";
    $(document).ready(function () {
        $('#AdvertiseTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: $('#table-url').data("url"),
            columns: [
                {
                    data: 'image',
                    name: 'image',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'subtitle',
                    name: 'subtitle'
                },
                {
                    data: 'location',
                    name: 'location'
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
