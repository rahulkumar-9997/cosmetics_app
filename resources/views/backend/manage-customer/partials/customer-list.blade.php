@if ($data['customer_list']->count() > 0)
<table class="table align-middle mb-0 table-hover table-centered">
    <thead class="bg-light-subtle">
        <tr>
            <th>Sr. No.</th>
            <th style="width: 15%;">Firm Name</th>
            <th>Email / Phone</th>
            <th>Status</th>
            <th>Approval Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['customer_list'] as $index => $customer)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>
                {{ $customer->firm_name }}
                <br><span class="text-success">{{ $customer->created_at->format('d F Y') }}</span>
            </td>
            <td>
                {{ $customer->email ?? '-' }}
                <br>
                <strong>Phone No.</strong> {{ $customer->phone_number ?? '-' }}
            </td>
            <td>
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input status-toggle"
                        data-id="{{ $customer->id }}"
                        data-url="{{ route('manage-customer.status', $customer->id) }}"
                        {{ $customer->status ? 'checked' : '' }}>
                </div>
            </td>
            <td>
                @if(auth()->check() && auth()->user()->hasAnyRole(['Super Admin (Wizards)', 'Main Admin (Owner)']))
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input approval-toggle"
                            data-id="{{ $customer->id }}"
                            data-url="{{ route('manage-customer.approval', $customer->id) }}"
                            {{ $customer->approval_status ? 'checked' : '' }}>
                    </div>
                @else
                    @if($customer->approval_status)
                        <span class="badge bg-success">Approved</span>
                    @else
                        <span class="badge bg-warning">Pending</span>
                    @endif
                @endif
                <!-- @if(auth()->check() && auth()->user()->hasRole('Super Admin (Wizards)'))
                    User is Super Admin
                @endif -->
            </td>
            <td>
                <div class="d-flex gap-1">
                    <a href="{{ route('manage-customer.edit', $customer->id) }}" class="btn btn-soft-success btn-sm">
                        <i class="ti ti-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('manage-customer.destroy', $customer->id) }}">
                        @csrf
                        @method('DELETE')
                        <a href="javascript:void(0);" class="show_confirm_customer btn btn-soft-danger btn-sm" data-name="{{ $customer->firm_name }}">
                            <i class="ti ti-trash"></i>
                        </a>
                    </form>
                </div>
            </td>

        </tr>
        @endforeach
    </tbody>
</table>

<div class="my-pagination" id="pagination-links-customer">
    {{ $data['customer_list']->links('vendor.pagination.bootstrap-4') }}
</div>
@else
<p class="text-center">No customers found.</p>
@endif