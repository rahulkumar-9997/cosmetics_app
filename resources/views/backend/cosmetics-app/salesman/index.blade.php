@extends('backend.layouts.master')
@section('title','Mange Salesman')
@section('main-content')
@push('styles')

@endpush
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="card-title flex-grow-1">Salesman List</h4>
                    <a href="javascript:void(0)"
                        data-addsalesman-popup="true"
                        data-size="lg"
                        data-title="Add Salesman"
                        data-url="{{ route('salesman.create') }}"
                        data-bs-toggle="tooltip"
                        title="Add Salesman"
                        class="btn btn-sm btn-primary">
                        <i class="ti ti-plus"></i> Add Salesman
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="salesman_list">
                            @if(isset($salesmen) && $salesmen->count() > 0)
                                @include('backend.cosmetics-app.salesman.partials.salesman-list', ['salesmen' => $salesmen])
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('backend.layouts.common-modal-form')
@endsection
@push('scripts')
<script src="{{asset('backend/assets/js/pages/cosmetics-js/salesman.js')}}" type="text/javascript"></script>
@endpush