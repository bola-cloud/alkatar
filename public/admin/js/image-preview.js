(function ($) {
    "use strict";
    $('.putImage1').on('change', function () {
        var src = this;
        var target = document.getElementById('target1');
        target.style.width = '120px';
        target.style.height = '80px';
        target.style.marginTop = '10px';
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#target1').attr('src', e.target.result);
        }
        reader.readAsDataURL(src.files[0]);
    });
    $('.putImage2').on('change', function () {
        var src = this;
        var target = document.getElementById('target2');
        target.style.width = '120px';
        target.style.height = '80px';
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#target2').attr('src', e.target.result);
        }
        reader.readAsDataURL(src.files[0]);
    });
    $('.putImage3').on('change', function () {
        var src = this;
        var target = document.getElementById('target3');
        target.style.width = '120px';
        target.style.height = '80px';
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#target3').attr('src', e.target.result);
        }
        reader.readAsDataURL(src.files[0]);
    });
    $('.putImage4').on('change', function () {
        var src = this;
        var target = document.getElementById('target4');
        target.style.width = '120px';
        target.style.height = '80px';
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#target4').attr('src', e.target.result);
        }
        reader.readAsDataURL(src.files[0]);
    });

    $('.putImage5').on('change', function () {
        var src = this;
        var target = document.getElementById('target5');
        target.style.width = '120px';
        target.style.height = '80px';
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#target5').attr('src', e.target.result);
        }
        reader.readAsDataURL(src.files[0]);
    });
    $('.putImage6').on('change', function () {
        var src = this;
        var target = document.getElementById('target6');
        target.style.width = '120px';
        target.style.height = '80px';
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#target6').attr('src', e.target.result);
        }
        reader.readAsDataURL(src.files[0]);
    });
    $('.putImage7').on('change', function () {
        var src = this;
        var target = document.getElementById('target7');
        target.style.width = '120px';
        target.style.height = '80px';
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#target7').attr('src', e.target.result);
        }
        reader.readAsDataURL(src.files[0]);
    });
    $('.putImage8').on('change', function () {
        var src = this;
        var target = document.getElementById('target8');
        target.style.width = '240px';
        target.style.height = '160px';
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#target8').attr('src', e.target.result);
        }
        reader.readAsDataURL(src.files[0]);
    });
    $('.putImage9').on('change', function () {
        var src = this;
        var target = document.getElementById('target9');
        target.style.width = '120px';
        target.style.height = '80px';
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#target9').attr('src', e.target.result);
        }
        reader.readAsDataURL(src.files[0]);
    });
    $('.putImage10').on('change', function () {
        var src = this;
        var target = document.getElementById('target10');
        target.style.width = '120px';
        target.style.height = '80px';
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#target10').attr('src', e.target.result);
        }
        reader.readAsDataURL(src.files[0]);
    });
    $('.putImage11').on('change', function () {
        var src = this;
        var target = document.getElementById('target11');
        target.style.width = '120px';
        target.style.height = '80px';
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#target11').attr('src', e.target.result);
        }
        reader.readAsDataURL(src.files[0]);
    });
    $('.putImage12').on('change', function () {
        var src = this;
        var target = document.getElementById('target12');
        target.style.width = '120px';
        target.style.height = '80px';
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#target12').attr('src', e.target.result);
        }
        reader.readAsDataURL(src.files[0]);
    });
    $('.putImage13').on('change', function () {
        var src = this;
        var target = document.getElementById('target13');
        target.style.width = '120px';
        target.style.height = '80px';
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#target13').attr('src', e.target.result);
        }
        reader.readAsDataURL(src.files[0]);
    });
    $('.putImage14').on('change', function () {
        var src = this;
        var target = document.getElementById('target14');
        target.style.width = '120px';
        target.style.height = '80px';
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#target14').attr('src', e.target.result);
        }
        reader.readAsDataURL(src.files[0]);
    });

    $(document).on('click', '.delete', function (event) {
        event.preventDefault();
        const url = $(this).attr('href');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        })
    });

    $(document).on('click', '.send-to-whatsapp', function (event) {
        event.preventDefault();
        const url = $(this).attr('href');
        Swal.fire({
            title: 'Are you sure?',
            text: "You will send invoice and payment link to user whatsapp",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, send'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        })
    });
})(jQuery);
