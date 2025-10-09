@extends('backend.layouts.master')
@section('title', 'Add New Customer')
@section('main-content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-12">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <h4 class="mb-0">Create Customer</h4>
                    <a href="{{ route('manage-customer.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-arrow-left"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <form id="customerForm" action="{{ route('manage-customer.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label class="form-label">Firm Name <span class="text-danger">*</span></label>
                                <input type="text" name="firm_name" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label">Contact Person</label>
                                <input type="text" name="contact_person" class="form-control">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone_number" class="form-control" maxlength="10">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label">GST No.</label>
                                <input type="text" name="gst_no" class="form-control">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label">Profile Image</label>
                                <input type="file" name="profile_img" class="form-control">
                            </div>
                        </div>
                        <div class="border-top pt-2 mt-2 mb-2">
                            <h5 class="text-primary mb-2">Address Information</h5>

                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label class="form-label">Search Address <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="autocomplete-input" class="form-control" placeholder="Enter complete address and click search...">
                                        <button type="button" id="searchAddressBtn" class="btn btn-primary">
                                            <i class="ti ti-search me-1"></i> Search Address
                                        </button>
                                    </div>
                                    <small class="form-text text-info">Enter address and click search, or click directly on the map below</small>
                                </div>
                            </div>
                            <div id="map" style="height: 300px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #ddd;"></div>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Country</label>
                                    <input type="text" name="country" id="country" class="form-control" readonly>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">State</label>
                                    <input type="text" name="state" id="state" class="form-control" readonly>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" id="city" class="form-control" readonly>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Pin Code</label>
                                    <input type="text" name="pin_code" id="postal_code" class="form-control" readonly>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" class="form-control" readonly>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" checked>
                                    <label class="form-check-label" for="status">Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-2">
                            <button type="submit" id="submitBtn" class="btn btn-success px-5" disabled>
                                <i class="ti ti-user-plus me-2"></i> Create Customer
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
    #searchAddressBtn {
        transition: all 0.3s ease;
    }
    #searchAddressBtn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    .input-group {
        max-width: 600px;
    }
</style>
@endpush

@push('scripts')
<script>
    let map, marker;
    function initMap() {
        console.log('Google Maps initialized successfully!');
        document.getElementById('map').innerHTML = `
        <div class="map-loading">
            <div class="text-center">
                <div class="spinner-border text-primary mb-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted">Loading Map...</p>
            </div>
        </div>`;
        setTimeout(() => {
            try {
                map = new google.maps.Map(document.getElementById("map"), {
                    center: {
                        lat: 20.5937,
                        lng: 78.9629
                    },
                    zoom: 5,
                    streetViewControl: false,
                    fullscreenControl: true,
                    zoomControl: true,
                    mapTypeControl: false
                });
                marker = new google.maps.Marker({
                    map: map,
                    position: {
                        lat: 20.5937,
                        lng: 78.9629
                    },
                    draggable: true,
                    title: "Drag me to set location"
                });
                map.addListener("click", (event) => {
                    handleMapClick(event.latLng);
                });
                marker.addListener("dragend", function() {
                    const pos = marker.getPosition();
                    handleLocationUpdate(pos);
                });
                showAlert('Map loaded successfully! Click on the map or search for an address.', 'success');
            } catch (error) {
                console.error('Error initializing map:', error);
                showAlert('Error loading map. Please refresh the page.', 'danger');
            }
        }, 100);
    }
    function handleMapClick(latLng) {
        marker.setPosition(latLng);
        handleLocationUpdate(latLng);
    }
    function handleLocationUpdate(location) {
        updateCoordinates(location.lat(), location.lng());
        reverseGeocode(location);
    }
    function searchAddress() {
        const addressInput = document.getElementById('autocomplete-input');
        const address = addressInput.value.trim();
        if (!address) {
            showAlert('Please enter an address to search.', 'warning');
            return;
        }
        const searchBtn = document.getElementById('searchAddressBtn');
        searchBtn.disabled = true;
        searchBtn.innerHTML = '<i class="ti ti-loader ti-spin me-1"></i> Searching...';
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({
            address: address
        }, (results, status) => {
            searchBtn.disabled = false;
            searchBtn.innerHTML = '<i class="ti ti-search me-1"></i> Search Address';
            if (status === "OK" && results[0]) {
                const location = results[0].geometry.location;
                map.setCenter(location);
                map.setZoom(15);
                marker.setPosition(location);
                updateCoordinates(location.lat(), location.lng());
                fillAddressDetails(results[0]);
                autoEnableSubmit();
                showAlert('Address found and location set automatically!', 'success');
            } else {
                showAlert('Address not found. Please try a different address or click on the map.', 'warning');
            }
        });
    }
    function reverseGeocode(location) {
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({
            location: location
        }, (results, status) => {
            if (status === "OK" && results[0]) {
                fillAddressDetails(results[0]);
                autoEnableSubmit();
                showAlert('Location detected automatically!', 'success');
            } else {
                autoEnableSubmit();
                showAlert('Coordinates set! Address details may be approximate.', 'info');
            }
        });
    }
    function fillAddressDetails(geocodeResult) {
        const components = geocodeResult.address_components || [];
        let country = '',
            state = '',
            city = '',
            postal_code = '';
        components.forEach(c => {
            const types = c.types;
            if (types.includes('country')) country = c.long_name;
            if (types.includes('administrative_area_level_1')) state = c.long_name;
            if (types.includes('locality')) {
                city = c.long_name;
            } else if (types.includes('administrative_area_level_2') && !city) {
                city = c.long_name;
            } else if (types.includes('postal_town') && !city) {
                city = c.long_name;
            }
            if (types.includes('postal_code')) postal_code = c.long_name;
        });
        document.getElementById('country').value = country;
        document.getElementById('state').value = state;
        document.getElementById('city').value = city;
        document.getElementById('postal_code').value = postal_code;
        if (geocodeResult.formatted_address) {
            document.getElementById('autocomplete-input').value = geocodeResult.formatted_address;
        }
    }
    function updateCoordinates(lat, lng) {
        document.getElementById("latitude").value = lat.toFixed(6);
        document.getElementById("longitude").value = lng.toFixed(6);
    }
    function autoEnableSubmit() {
        const latitude = document.getElementById('latitude').value;
        const longitude = document.getElementById('longitude').value;

        if (latitude && longitude) {
            document.getElementById('submitBtn').disabled = false;
        }
    }

    function showAlert(message, type) {
        const existingAlerts = document.querySelectorAll('.alert-container .alert');
        existingAlerts.forEach(alert => alert.remove());

        let alertContainer = document.querySelector('.alert-container');
        if (!alertContainer) {
            alertContainer = document.createElement('div');
            alertContainer.className = 'alert-container';
            document.body.appendChild(alertContainer);
        }

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="ti ti-${getAlertIcon(type)} me-2"></i>
                <div>${message}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        alertContainer.appendChild(alertDiv);
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 4000);
    }
    function getAlertIcon(type) {
        const icons = {
            'success': 'circle-check',
            'warning': 'alert-triangle',
            'danger': 'circle-x',
            'info': 'info-circle'
        };
        return icons[type] || 'info-circle';
    }
    document.addEventListener('DOMContentLoaded', function() {
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyBmvGbMx-92VviaYr2IvzjCyC4-DVEzQCU&callback=initMap`;
        script.async = true;
        script.defer = true;
        script.onerror = function() {
            showAlert('Failed to load Google Maps. Please check your internet connection.', 'danger');
            document.getElementById('map').innerHTML = `
                <div class="map-loading">
                    <div class="text-center text-danger">
                        <i class="ti ti-map-off fs-1 mb-2"></i>
                        <p>Failed to load map</p>
                        <small class="text-muted">Please check your API key and internet connection</small>
                    </div>
                </div>
            `;
        };
        document.head.appendChild(script);
        document.getElementById('searchAddressBtn').addEventListener('click', searchAddress);
        document.getElementById('autocomplete-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchAddress();
            }
        });
        document.getElementById('customerForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn.disabled) {
                e.preventDefault();
                showAlert('Please set a location on the map before submitting.', 'warning');
                return false;
            }
            const firmName = document.querySelector('input[name="firm_name"]').value;
            if (!firmName.trim()) {
                e.preventDefault();
                showAlert('Please fill in the Firm Name field.', 'warning');
                return false;
            }
            submitBtn.innerHTML = '<i class="ti ti-loader ti-spin me-2"></i> Creating...';
            submitBtn.disabled = true;
        });
    });
    window.gm_authFailure = function() {
        showAlert('Google Maps authentication failed. Please check your API key configuration.', 'danger');
    };
</script>
@endpush