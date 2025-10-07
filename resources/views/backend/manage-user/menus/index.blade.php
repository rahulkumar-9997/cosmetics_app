@extends('backend.layouts.master')
@section('title','Menus List')
@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h3 class="card-title mb-0">Menu Management</h3>
                    <div class="float-end">
                        <a href="{{ route('menus.create') }}" class="btn btn-primary btn-sm">
                            <i class="ti ti-plus"></i> Add Menu
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="menusTable">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Menu Name</th>
                                    <th>Roles</th>
                                    <th>URL</th>
                                    <th>Order</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="sortable">
                                @foreach($menus as $menu)
                                <tr data-id="{{ $menu->id }}">
                                    <td>{{ $menus->firstItem() + $loop->index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($menu->icon)
                                            <iconify-icon icon="{{ $menu->icon }}" width="20" height="20" class="me-1"></iconify-icon>
                                            @endif
                                            <strong>{{ $menu->name }}</strong>
                                        </div>
                                        @if($menu->children->count())
                                        <ul class="list-unstyled ms-2 mt-1">
                                            @foreach($menu->children as $child)
                                            <li data-id="{{ $child->id }}">
                                                <iconify-icon icon="{{ $child->icon ?? 'mdi:circle-small' }}" width="16" height="16" class="me-1"></iconify-icon>
                                                {{ $child->name }}
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </td>
                                    <td>
                                        @forelse($menu->roles as $role)
                                        <span class="badge bg-success text-light mb-1">{{ $role->name }}</span>
                                        @empty
                                        <span class="text-muted">No Role</span>
                                        @endforelse
                                    </td>
                                    <td>{{ $menu->url }}</td>
                                    <td class="order-handle"><i class="ti ti-arrows-sort"></i> {{ $menu->order }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input type="checkbox"
                                                class="form-check-input status-toggle"
                                                data-id="{{ $menu->id }}"
                                                data-url="{{ route('menus.status', $menu) }}"
                                                {{ $menu->is_active ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('menus.edit', $menu) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form action="{{ route('menus.destroy', $menu) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger show_confirm" data-name="{{ $menu->name }}" title="Delete">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        {{ $menus->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
    $(document).ready(function() {
        // Delete confirmation
        $('.show_confirm').click(function(event) {
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

        // Status toggle
        $(document).on('change', '.status-toggle', function() {
            var menu_id = $(this).data('id');
            var url = $(this).data('url');
            var is_active = $(this).is(':checked') ? 1 : 0;
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    is_active: is_active
                },
                success: function(response) {
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        className: response.status ? "bg-success" : "bg-danger",
                        close: true
                    }).showToast();
                },
                error: function(xhr) {
                    Toastify({
                        text: 'Something went wrong!',
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        className: "bg-danger",
                        close: true
                    }).showToast();
                }
            });
        });

        // Drag & drop sortable
        $("#sortable").sortable({
            handle: ".order-handle",
            update: function(event, ui) {
                var order = $(this).sortable('toArray', {
                    attribute: 'data-id'
                });
                $.ajax({
                    url: "{{ route('menus.reorder') }}",
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        order: order
                    },
                    success: function(response) {
                        Toastify({
                            text: response.message,
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            className: "bg-success",
                            close: true
                        }).showToast();
                        // Optionally, reload page or update order numbers
                        location.reload();
                    },
                    error: function(xhr) {
                        Toastify({
                            text: 'Something went wrong!',
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            className: "bg-danger",
                            close: true
                        }).showToast();
                    }
                });
            }
        }).disableSelection();
    });
</script>
@endpush