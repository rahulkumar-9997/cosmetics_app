<table class="table datatable1">
    <thead class="thead-light">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Profile Photo</th>
            <th>Address</th>
            <th>Status</th>
            <th>User</th> 
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($salesmen) && $salesmen->count() > 0)
        @foreach($salesmen as $salesman)
        <tr>
            <td>{{ $salesman->name }}</td>
            <td>{{ $salesman->email ?? '-' }}</td>
            <td>{{ $salesman->phone ?? '-' }}</td>
            <td>
                @if($salesman->profile_photo)
                <img src="{{ asset('images/salesman/' . $salesman->profile_photo) }}" width="100" alt="Profile Photo">
                @else
                -
                @endif
            </td>
            <td>{{ $salesman->address ?? '-' }}</td>
            <td>
                <div class="form-check form-switch d-inline-block ms-2">
                    <input 
                        type="checkbox" 
                        class="form-check-input salesman-status-toggle" 
                        data-id="{{ $salesman->id }}" 
                        data-url="{{ route('salesman.status', $salesman->id) }}"
                        {{ $salesman->status ? 'checked' : '' }}>
                </div> 
            </td>
            <td>
                @if($salesman->user)
                    <span class="badge bg-dark border">{{ $salesman->user->user_id }}</span>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td>
                <div class="d-flex gap-2">
                    <a class="btn btn-sm btn-info"
                        href="javascript:;"
                        data-title="Edit Salesman"
                        data-size="lg"
                        data-id="{{ $salesman->id }}"
                        data-ajax-edit-salesman="true"
                        data-url="{{ route('salesman.edit', $salesman->id) }}"
                        title="Edit">
                        <i class="ti ti-edit"></i>
                    </a>
                    <form action="{{ route('salesman.destroy', $salesman->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger show_confirm" data-name="{{ $salesman->name }}" title="Delete">
                          <i class="ti ti-trash"></i>
                        </button>
                    </form>                    
                </div>
            </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="7" class="text-center">No salesmen found.</td>
        </tr>
        @endif
    </tbody>
</table>