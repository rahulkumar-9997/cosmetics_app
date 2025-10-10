$(document).ready(function () {
    $(document).on('change', '.status-toggle', function () {
        const customer_id = $(this).data('id');
        const url = $(this).data('url');
        const $toggle = $(this);
        $toggle.prop('disabled', true);

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                Toastify({
                    text: response.message,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    className: response.status === 'success' ? "bg-success" : "bg-danger",
                    close: true
                }).showToast();
            },
            error: function (xhr) {
                $toggle.prop('checked', !$toggle.prop('checked'));
                Toastify({
                    text: 'Failed to update status!',
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    className: "bg-danger",
                    close: true
                }).showToast();
            },
            complete: function () {
                $toggle.prop('disabled', false);
            }
        });
    });
    $(document).on('change', '.approval-toggle', function () {
        const customer_id = $(this).data('id');
        const url = $(this).data('url');
        const $toggle = $(this);
        $toggle.prop('disabled', true);

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                Toastify({
                    text: response.message,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    className: response.status === 'success' ? "bg-success" : "bg-danger",
                    close: true
                }).showToast();
            },
            error: function (xhr) {
                $toggle.prop('checked', !$toggle.prop('checked'));
                let msg = 'Failed to update approval status!';
                if (xhr.status === 403) {
                    msg = 'Unauthorized: Only Super Admin or Main Admin can do this.';
                }
                Toastify({
                    text: msg,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    className: "bg-danger",
                    close: true
                }).showToast();
            },
            complete: function () {
                $toggle.prop('disabled', false);
            }
        });
    });


    $('.show_confirm_customer').click(function (event) {
        var form = $(this).closest("form");
        var name = $(this).data("name");
        event.preventDefault();
        Swal.fire({
            title: `Are you sure you want to delete this ${name}?`,
            text: "If you delete this, it will be gone forever.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
            dangerMode: true,
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});