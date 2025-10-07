$(document).off('submit', '#enquiryForm').on('submit', '#enquiryForm', function (event) {
    event.preventDefault();
    var form = $(this);
    var submitButton = form.find('button[type="submit"]');
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...');

    var formData = new FormData(this);

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            submitButton.prop('disabled', false).html('Submit');
            if (response.status === 'success') {
                showToast('success', response.message);
                form[0].reset();
            }
           
        },
        error: function(xhr) {
            submitButton.prop('disabled', false).html('Submit');
            var errors = xhr.responseJSON?.errors;
            if (errors) {
                $.each(errors, function(key, value) {
                    var inputField = $('#' + key);
                    inputField.addClass('is-invalid');
                    inputField.after('<div class="invalid-feedback">' + value[0] + '</div>');
                });
            }
            else if (xhr.responseJSON?.message) {
                showToast('danger', xhr.responseJSON.message);
            } else {
                showToast('danger', "An error occurred! Please try again.");
            }
        }
    });
});


$(document).off('submit', '#customerCare').on('submit', '#customerCare', function (event) {
    event.preventDefault();
    var form = $(this);
    var submitButton = form.find('button[type="submit"]');
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...');

    var formData = new FormData(this);

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            submitButton.prop('disabled', false).html('Submit');
            if (response.status === 'success') {
                showToast('success', response.message);
                form[0].reset();
            }
           
        },
        error: function(xhr) {
            submitButton.prop('disabled', false).html('Submit');
            var errors = xhr.responseJSON?.errors;
            if (errors) {
                $.each(errors, function(key, value) {
                    var inputField = $('#' + key);
                    inputField.addClass('is-invalid');
                    inputField.after('<div class="invalid-feedback">' + value[0] + '</div>');
                });
            }
            else if (xhr.responseJSON?.message) {
                showToast('danger', xhr.responseJSON.message);
            } else {
                showToast('danger', "An error occurred! Please try again.");
            }
        }
    });
});

/**Product enquiry model  */
$(document).on('click', '.product-enquiry', function(e) {
    var button = $(this);
    var originalButtonText = button.html();
    button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading please wait...');
    var title = $(this).data('title');
    var product_id = $(this).data('pid');
    var product_title = $(this).data('ptitle');
    var product_image_path = $(this).data('pimg');
    var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
    var url = $(this).data('url');
    var data = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        product_id: product_id,
        product_title: product_title,
        product_image_path: product_image_path
    };
    $("#commonModel .modal-title").html(title);
    $("#commonModel .modal-dialog").addClass('modal-' + size);
    
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            $('#commonModel .render-data').html(data.form);
            $("#commonModel").modal('show');
            button.prop('disabled', false).html(originalButtonText);
        },
        error: function (data) {
            data = data.responseJSON;
            button.prop('disabled', false).html(originalButtonText);
        }
    });
});

/**Product enquiry model  */
$(document).off('submit', '#productEnquiryForm').on('submit', '#productEnquiryForm', function (event) {
    event.preventDefault();
    var form = $(this);
    var submitButton = form.find('button[type="submit"]');
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...');

    var formData = new FormData(this);

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            submitButton.prop('disabled', false).html('Submit');
            if (response.status === 'success') {
                showToast('success', response.message);
                form[0].reset();
                $("#commonModel").modal('hide');
            }
           
        },
        error: function(xhr) {
            submitButton.prop('disabled', false).html('Submit');
            var errors = xhr.responseJSON?.errors;
            console.log(errors);  // Log errors to check if 'name' exists
            if (errors) {
                $.each(errors, function(key, value) {
                    var inputField = $('#' + key);
                    inputField.addClass('is-invalid');
                    if(inputField.next('.invalid-feedback').length === 0) {
                        inputField.after('<div class="invalid-feedback">' + value[0] + '</div>');
                    } else {
                        inputField.next('.invalid-feedback').html(value[0]);
                    }
                });
            }
            else if (xhr.responseJSON?.message) {
                showToast('danger', xhr.responseJSON.message);
            } else {
                showToast('danger', "An error occurred! Please try again.");
            }
        }
    });
});

function showToast(type = 'success', message = 'Success!') {
    const toastEl = $('#liveToast');
    const toastBody = $('#toast-body');
    let bgClass = 'bg-success';
    if (type === 'danger') {
        bgClass = 'bg-danger';
    } else if (type === 'warning') {
        bgClass = 'bg-warning text-dark';
    } else if (type === 'info') {
        bgClass = 'bg-info text-dark';
    }

    toastEl.removeClass('bg-success bg-danger bg-warning bg-info text-dark').addClass(bgClass);
    toastBody.html(message);
    const toast = new bootstrap.Toast(toastEl[0]);
    toast.show();
}