let map, marker, autocomplete;
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
            /* Initialize autocomplete */
            initializeAutocomplete();
            /* Map click event */
            map.addListener("click", (event) => {
                handleMapClick(event.latLng);
            });
            /* Marker drag event */
            marker.addListener("dragend", function () {
                const pos = marker.getPosition();
                handleLocationUpdate(pos);
            });
            showAlert('Map loaded successfully! Start typing address or click on the map.', 'success');
        } catch (error) {
            console.error('Error initializing map:', error);
            showAlert('Error loading map. Please refresh the page.', 'danger');
        }
    }, 100);
}
function initializeAutocomplete() {
    const autocompleteInput = document.getElementById('autocomplete-input');
    autocomplete = new google.maps.places.Autocomplete(autocompleteInput, {
        /* Removed 'types' to allow both business names and addresses */
        componentRestrictions: {
            country: 'in'
        }
    });
    autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace();
        if (!place.geometry) {
            showAlert('No details available for this location. Please try another one.', 'warning');
            return;
        }
        map.setCenter(place.geometry.location);
        map.setZoom(15);
        marker.setPosition(place.geometry.location);
        updateCoordinates(place.geometry.location.lat(), place.geometry.location.lng());
        fillAddressDetails(place);
        autoEnableSubmit();
        showAlert('Address selected! Location has been set on the map.', 'success');
    });
    autocompleteInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') e.preventDefault();
    });
}
function handleMapClick(latLng) {
    marker.setPosition(latLng);
    handleLocationUpdate(latLng);
}
function handleLocationUpdate(location) {
    updateCoordinates(location.lat(), location.lng());
    reverseGeocode(location);
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
    console.log('All address components:', components);
    components.forEach(c => {
        const types = c.types;
        console.log('Component:', c.long_name, 'Types:', types);
        if (types.includes('country')) country = c.long_name;
        if (types.includes('administrative_area_level_1')) state = c.long_name;
        /* City detection with priority */
        if (types.includes('locality')) {
            city = c.long_name;
        } else if (types.includes('administrative_area_level_2') && !city) {
            city = c.long_name;
        } else if (types.includes('postal_town') && !city) {
            city = c.long_name;
        }
        /* Postal code detection */
        if (types.includes('postal_code')) {
            postal_code = c.long_name;
        }
    });
    /* If postal code not found, try alternative methods */
    if (!postal_code && geocodeResult.formatted_address) {
        const postalCodeRegex = /\b\d{5,6}\b/;
        const match = geocodeResult.formatted_address.match(postalCodeRegex);
        if (match) {
            postal_code = match[0];
            console.log('Found postal code via regex:', postal_code);
        }
    }
    document.getElementById('country').value = country;
    document.getElementById('state').value = state;
    document.getElementById('city').value = city;
    document.getElementById('postal_code').value = postal_code;
    /* Update the autocomplete input with formatted address */
    if (geocodeResult.formatted_address) {
        document.getElementById('autocomplete-input').value = geocodeResult.formatted_address;
    }
    /* Make postal code editable if not found */
    if (!postal_code) {
        const postalInput = document.getElementById('postal_code');
        postalInput.readOnly = false;
        postalInput.placeholder = "Enter pin code manually";
        showAlert('Pin code not found automatically. Please enter it manually.', 'warning');
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
document.addEventListener('DOMContentLoaded', function () {
    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyBmvGbMx-92VviaYr2IvzjCyC4-DVEzQCU&callback=initMap&loading=async&libraries=places`;
    script.async = true;
    script.defer = true;
    script.onerror = function () {
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
    document.getElementById('customerForm').addEventListener('submit', function (e) {
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
window.gm_authFailure = function () {
    showAlert('Google Maps authentication failed. Please check your API key configuration.', 'danger');
};