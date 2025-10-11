@extends('backend.layouts.master')
@section('title','Add Products')
@section('main-content')
@push('styles')
<!-- <link href="{{asset('backend/assets/plugins/select2/select2.css')}}" rel="stylesheet" type="text/css" media="screen" />
<link href="{{asset('backend/assets/plugins/multi-select/css/multi-select.css')}}" rel="stylesheet" type="text/css" media="screen" /> -->
@endpush
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="card-title flex-grow-1">Visit Customer List</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="" accept-charset="UTF-8" id="selectCutstomer" enctype="multipart/form-data">
                        @csrf
                        <div class="row">                            
                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <label for="customer" class="form-label">Select Customer *</label>
                                    <select class="form-control" id="product_categories" data-choices data-choices-groups data-placeholder="Select Customers" name="customer" required="required">
                                        <option value="">Choose a Customers</option>
                                        @if ($customers && $customers->isNotEmpty())
                                        @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ request('customer') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->firm_name }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @if($errors->has('customer'))
                                    <div class="text-danger">{{ $errors->first('customer') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">    
                            <div class="col-lg-3 mt-1">
                                <button type="submit" class="btn btn-info w-100">Proceed to Order &raquo;</button>
                            </div>
                            <div class="col-lg-3 mt-1">
                                <button type="reset" class="btn btn-success w-100">Only Visit</button>
                            </div>
                            <div class="col-lg-3 mt-1">
                                <button type="button" class="btn btn-primary w-100" onclick="window.location.href='{{ url()->previous() }}'">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Container Fluid -->
<!-- Modal -->
@include('backend.layouts.common-modal-form')
<!-- modal--->
@endsection
@push('scripts')
<!-- <script src="{{asset('backend/assets/js/components/form-quilljs.js')}}"></script>
<script src="{{asset('backend/assets/plugins/select2/select2.min.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/assets/plugins/multi-select/js/jquery.multi-select.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/assets/plugins/multi-select/js/jquery.quicksearch.js')}}" type="text/javascript"></script> -->


@endpush