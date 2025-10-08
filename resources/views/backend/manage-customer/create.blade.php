@extends('backend.layouts.master')
@section('title','Add New Customer')
@section('main-content')

<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="mb-0">Create Customer</h4>
                    <a href="{{ route('manage-customer.index') }}" class="btn btn-light btn-sm">
                        <i class="ti ti-arrow-left"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('manage-customer.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Firm Name <span class="text-danger">*</span></label>
                                    <input type="text" name="firm_name" class="form-control @error('firm_name') is-invalid @enderror" value="{{ old('firm_name') }}">
                                    @error('firm_name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Contact Person</label>
                                    <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror" value="{{ old('contact_person') }}">
                                    @error('contact_person')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number') }}" maxlength="10">
                                    @error('phone_number')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                    @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">GST No.</label>
                                    <input type="text" name="gst_no" class="form-control @error('gst_no') is-invalid @enderror" value="{{ old('gst_no') }}">
                                    @error('gst_no')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Country</label>
                                    <input type="text" name="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country') }}">
                                    @error('country')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">State</label>
                                    <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state') }}">
                                    @error('state')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}">
                                    @error('city')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Pin Code</label>
                                    <input type="text" name="pin_code" class="form-control @error('pin_code') is-invalid @enderror" value="{{ old('pin_code') }}" maxlength="6">
                                    @error('pin_code')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Permanent Address</label>
                                    <textarea name="permanent_address" class="form-control @error('permanent_address') is-invalid @enderror" rows="3">{{ old('permanent_address') }}</textarea>
                                    @error('permanent_address')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Profile Image</label>
                                    <input type="file" name="profile_img" class="form-control @error('profile_img') is-invalid @enderror">
                                    @error('profile_img')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" {{ old('status') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">Status</label>
                                    </div>
                                    @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-success px-4">Create</button>
                            <a href="{{ route('manage-customer.index') }}" class="btn btn-secondary px-4">Cancel</a>
                        </div>
                        <!-- <div class="mb-3">
                        <label class="form-label">Permanent Address</label>
                        <textarea name="permanent_address" class="form-control" id="permanent_address" rows="3">{{ old('permanent_address') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Location on Map</label>
                        <input type="text" id="full_address" class="form-control" placeholder="Enter full address to show on map" value="{{ old('permanent_address') }}">
                        <div id="map" style="height: 300px; margin-top:10px;"></div>
                    </div>
                    <input type="text" name="latitude" id="latitude">
                    <input type="text" name="longitude" id="longitude"> -->
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmvGbMx-92VviaYr2IvzjCyC4-DVEzQCU&libraries=marker   "></script>
<script>
    let map;
    let marker;
    let geocoder;
    function initMap() {
        geocoder = new google.maps.Geocoder();
        const defaultLocation = {
            lat: 20.5937,
            lng: 78.9629
        };
        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 5,
            center: defaultLocation,
        });
        marker = new google.maps.Marker({
            position: defaultLocation,
            map: map,
            draggable: true,
        });
        document.getElementById("full_address").addEventListener("blur", function() {
            const address = this.value;
            geocodeAddress(address);
        });
        marker.addListener('dragend', function() {
            const position = marker.getPosition();
            document.getElementById('latitude').value = position.lat();
            document.getElementById('longitude').value = position.lng();

            geocoder.geocode({
                location: position
            }, function(results, status) {
                if (status === "OK" && results[0]) {
                    document.getElementById("full_address").value = results[0].formatted_address;
                }
            });
        });
    }
    function geocodeAddress(address) {
        geocoder.geocode({
            address: address
        }, function(results, status) {
            if (status === "OK") {
                const location = results[0].geometry.location;
                map.setCenter(location);
                map.setZoom(15);
                marker.setPosition(location);
                document.getElementById('latitude').value = location.lat();
                document.getElementById('longitude').value = location.lng();
            } else {
                alert("Address not found: " + status);
            }
        });
    }
    window.onload = initMap;
</script> -->


@endpush