@extends('backend.layouts.master')
@section('title', 'Edit Customer')
@section('main-content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-12">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <h4 class="mb-0">Edit Customer</h4>
                    <a href="{{ route('manage-customer.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-arrow-left"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <form id="customerForm" action="{{ route('manage-customer.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label class="form-label">Firm Name <span class="text-danger">*</span></label>
                                <input type="text" name="firm_name" class="form-control" value="{{ old('firm_name', $customer->firm_name) }}" required>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label">Contact Person</label>
                                <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $customer->contact_person) }}">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone_number" class="form-control" maxlength="10" value="{{ old('phone_number', $customer->phone_number) }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label">GST No.</label>
                                <input type="text" name="gst_no" class="form-control" value="{{ old('gst_no', $customer->gst_no) }}">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label">Profile Image</label>
                                <input type="file" name="profile_img" class="form-control">
                                @if($customer->profile_img)
                                    <div class="mt-2">
                                        <img src="{{ asset('images/customer/' . $customer->profile_img) }}" alt="Profile Image" class="img-thumbnail" style="max-height: 100px;">
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="remove_profile_img" id="remove_profile_img" value="1">
                                            <label class="form-check-label" for="remove_profile_img">
                                                Remove current image
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="border-top pt-2 mt-2 mb-2">
                            <h5 class="text-primary mb-2">Address Information</h5>
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label class="form-label">Search Address <span class="text-danger">*</span></label>
                                    <input type="text" id="autocomplete-input" name="full_addresses" class="form-control" placeholder="Start typing address..." value="{{ old('full_addresses', $customer->permanent_address) }}">
                                    <small class="form-text text-info">Type address and select from suggestions, or click directly on the map below</small>
                                </div>
                            </div>
                            <div id="map" style="height: 300px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #ddd;"></div>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Country</label>
                                    <input type="text" name="country" id="country" class="form-control" value="{{ old('country', $customer->country) }}" readonly>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">State</label>
                                    <input type="text" name="state" id="state" class="form-control" value="{{ old('state', $customer->state) }}" readonly>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $customer->city) }}" readonly>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Pin Code</label>
                                    <input type="text" name="pin_code" id="postal_code" class="form-control" value="{{ old('pin_code', $customer->pin_code) }}" readonly>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" class="form-control" value="{{ old('latitude', $customer->latitude) }}" readonly>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" class="form-control" value="{{ old('longitude', $customer->longitude) }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" {{ $customer->status ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-2">
                            <button type="submit" id="submitBtn" class="btn btn-success px-5">
                                <i class="ti ti-user-check me-2"></i> Update Customer
                            </button>
                            <a href="{{ route('manage-customer.index') }}" class="btn btn-outline-secondary px-5">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    #map {
        min-height: 300px;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
    }
    .alert-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1060;
        min-width: 300px;
    }
    .map-loading {
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 10px;
        border: 1px solid #dee2e6;
    }
    .pac-container {
        z-index: 1050 !important;
        border-radius: 0.375rem;
        border: 1px solid #dee2e6;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .pac-item {
        padding: 0.5rem 0.75rem;
        border-bottom: 1px solid #f8f9fa;
        cursor: pointer;
    }

    .pac-item:hover {
        background-color: #f8f9fa;
    }

    .pac-item-query {
        font-size: 1rem;
        color: #495057;
    }

    .pac-matched {
        font-weight: 600;
    }
</style>
@endpush
@push('scripts')
<script src="{{asset('backend/assets/js/pages/cosmetics-js/customer-location.js')}}"></script>
@endpush