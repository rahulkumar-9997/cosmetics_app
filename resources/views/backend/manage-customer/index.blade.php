@extends('backend.layouts.master')
@section('title','Customer List')
@section('main-content')
@push('styles')
@endpush
<!-- Start Container Fluid -->

<div class="container-fluid">
   <div class="row">
      <div class="col-xl-12">

         <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center gap-1">
               <h4 class="card-title flex-grow-1">
                  All Customer List
               </h4>
               <a href="{{route('manage-customer.create')}}" data-bs-toggle="tooltip" class="btn btn-sm btn-purple" data-bs-original-title=" Add new Customer">
                  Add new Customer
               </a>            
               
            </div>
            <div class="card-body">
               @if (isset($data['customer_list']) && $data['customer_list']->count() > 0)

               <div class="table-responsive" id="customer-list-container">
                  @include('backend.manage-customer.partials.customer-list', ['data' => $data])
               </div>
               @endif
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
<script src="{{asset('backend/assets/js/pages/cosmetics-js/customer-status.js')}}" type="text/javascript"></script>
@endpush