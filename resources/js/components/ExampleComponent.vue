<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Map Component</div>

                    <div class="card-body" id="map" style="height: 500px; width: 100%;">
                        I'm an example component.
                    </div>
                    <input ref="searchBox" type="text" placeholder="Search for a location">
                    <p>Selected location: {{ selectedLocation }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Loader } from '@googlemaps/js-api-loader';
    export default {
      data() {
        return {
          markerPosition: { lat: -34.397, lng: 150.644 },
        };
      },
        mounted() {
            console.log('Component mounted.')
            const loader = new Loader({
            apiKey: 'AIzaSyC35SHRVQ0JebXbbRKgx85RTjZXDsDQH70&libraries=places',
            version: 'weekly',
            // other options here
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
     });




      });


  }
}
</script>
