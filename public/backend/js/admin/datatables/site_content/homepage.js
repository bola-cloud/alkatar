(function ($) {
    "use strict";
    $(document).ready(function () {
        $('#homePageTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: $('#table-url').data("url"),
            createdRow: function(row, data, dataIndex){
                // make entire row clickable to open edit page via the action button link
                var $row = $(row);
                $row.css('cursor', 'pointer');
                $row.on('click', function(e){
                    // if clicked element is a button/link or inside action column, follow that action
                    var $target = $(e.target);
                    if ($target.closest('.action__buttons').length) {
                        return; // let the button handle it
                    }
                    var href = $row.find('.btn-action').attr('href');
                    if (href) {
                        window.location = href;
                    }
                });
            },
            columns: [
                {
                    data: 'Location',
                    name: 'Location'
                },
                {
                    data: 'Title',
                    name: 'Title'
                },
                {
                    data: 'Description_One',
                    name: 'Description_One'
                },
                {
                    data: 'Description_Two',
                    name: 'Description_Two'
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
