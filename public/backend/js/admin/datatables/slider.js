(function ($) {
    "use strict";
    $(document).ready(function () {
        $("#SliderTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: $("#table-url").data("url"),
            columns: [
                {
                    data: "Background_Image",
                    name: "Background_Image",
                },
                {
                    data: "Title",
                    name: "Title",
                },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                },
            ],
        });
    });
})(jQuery);
