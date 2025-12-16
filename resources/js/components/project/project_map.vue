<template>
    <div>
        <input class="form-control" ref="searchBox" type="text" placeholder="Search for a location" :value="initialAddress">
        <div style="height: 400px; width: 100%;" id="map">
        </div><br>
    </div>
</template>
<script>
import { Loader } from '@googlemaps/js-api-loader';

export default {
    props: {
        initialAddress: String // Prop to receive the initial address
    },
    data(){
        return {
            markerPosition: { lat: -34.397, lng: 150.644 },
            lat: "",
            long: "",
            selectedAddress: "" // Added selectedAddress data property
        }
    },

    mounted() {
        const loader = new Loader({
            apiKey: 'AIzaSyC35SHRVQ0JebXbbRKgx85RTjZXDsDQH70',
            version: 'weekly',
            libraries:["places"]
        });

        loader.load().then(() => {
            const map = new google.maps.Map(document.getElementById('map'), {
                center: this.markerPosition,
                zoom: 8,
                draggable: true, // make the marker draggable
            });

            const searchBox = new google.maps.places.SearchBox(
                this.$refs.searchBox
            );

            if (this.initialAddress) {
                this.geocodeAddress(this.initialAddress, map);
            }

            searchBox.addListener('places_changed', () => {
                const places = searchBox.getPlaces();

                if (places.length === 0) {
                    return;
                }

                const place = places[0];

                // Update selectedLocation with the lat and lng of the selected place
                this.selectedLocation = {
                    lat: place.geometry.location.lat(),
                    lng: place.geometry.location.lng(),
                };

                // Update the marker position to the selected location
                const marker = new google.maps.Marker({
                    position: place.geometry.location,
                    map: map,
                    draggable: true,
                });

                // When the marker is dragged, update the selectedLocation with the new position
                marker.addListener('dragend', () => {
                    this.selectedLocation = {
                        lat: marker.getPosition().lat(),
                        lng: marker.getPosition().lng(),
                    };
                });

                // Center the map on the selected location and zoom in
                map.setCenter(place.geometry.location);
                map.setZoom(12);

                this.selectedAddress = place.formatted_address; // Set selected address

                this.$emit('locationSelected', {
                    lat: this.selectedLocation.lat,
                    long: this.selectedLocation.lng,
                    address: this.selectedAddress // Emit selected address
                });
            });
        });
    },
}
</script>
