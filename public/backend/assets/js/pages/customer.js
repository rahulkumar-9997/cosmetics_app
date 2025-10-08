$(document).on('change', '.status-toggle', function () {
   let checkbox = $(this);
   let url = checkbox.data('url');
   $.ajax({
      url: url,
      type: 'POST',
      data: {
         _token: $('meta[name="csrf-token"]').attr('content')
      },
      success: function (response) {
         if (response.status === 'success') {
            Toastify({
               text: response.message,
               duration: 3000,
               gravity: "top",
               position: "right",
               className: "bg-success",
               close: true,
            }).showToast();
         } else {
            checkbox.prop('checked', !checkbox.prop('checked'));
            Toastify({
               text: response.message,
               duration: 3000,
               gravity: "top",
               position: "right",
               className: "bg-danger",
               close: true,
            }).showToast();
         }
      },
      error: function (xhr) {
         checkbox.prop('checked', !checkbox.prop('checked'));
         Toastify({
            text: 'Failed to update status',
            duration: 3000,
            gravity: "top",
            position: "right",
            className: "bg-danger",
            close: true,
         }).showToast();
      }
   });
});
$(document).on('change', '.approval-toggle', function () {
   let checkbox = $(this);
   let url = checkbox.data('url');
   $.ajax({
      url: url,
      type: 'POST',
      data: {
         _token: $('meta[name="csrf-token"]').attr('content')
      },
      success: function (response) {
         if (response.status === 'success') {
            Toastify({
               text: response.message,
               duration: 3000,
               gravity: "top",
               position: "right",
               className: "bg-success",
               close: true,
            }).showToast();
         } else {
            checkbox.prop('checked', !checkbox.prop('checked'));
            Toastify({
               text: response.message,
               duration: 3000,
               gravity: "top",
               position: "right",
               className: "bg-danger",
               close: true,
            }).showToast();
         }
      },
      error: function (xhr) {
         checkbox.prop('checked', !checkbox.prop('checked'));
         Toastify({
            text: 'Failed to update status',
            duration: 3000,
            gravity: "top",
            position: "right",
            className: "bg-danger",
            close: true,
         }).showToast();
      }
   });
});