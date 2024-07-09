(function($) {
    "use strict";
    $(document).ready(function () {
        $('.dataTableHover').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: $('#table-url').data("url"),
            },
            columns: [
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'name_ar',
                    name: 'name_ar'
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
