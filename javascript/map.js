var latlong,
    marker;

function initialize() {
    var mapOptions = {
        center: new google.maps.LatLng(52.3667, 4.9000),
        zoom: 15,
        styles: [
            {
                "featureType": "road",
                "elementType": "labels",
                "stylers": [
                    {
                        "visibility": "on"
                    }
                ]
            },
            {
                "featureType": "poi",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "administrative",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "color": "#000000"
                    },
                    {
                        "weight": 1
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "geometry.stroke",
                "stylers": [
                    {
                        "color": "#000000"
                    },
                    {
                        "weight": 0.8
                    }
                ]
            },
            {
                "featureType": "landscape",
                "stylers": [
                    {
                        "color": "#ffffff"
                    }
                ]
            },
            {
                "featureType": "water",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "transit",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "elementType": "labels",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "elementType": "labels.text",
                "stylers": [
                    {
                        "visibility": "on"
                    }
                ]
            },
            {
                "elementType": "labels.text.stroke",
                "stylers": [
                    {
                        "color": "#ffffff"
                    }
                ]
            },
            {
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#000000"
                    }
                ]
            },
            {
                "elementType": "labels.icon",
                "stylers": [
                    {
                        "visibility": "on"
                    }
                ]
            }
        ]
    }

    var map = new google.maps.Map(document.getElementById("map-canvas"),
        mapOptions);

    var icon = {};
    icon.url = "./images/arrow_m.svg";
    icon.scaledSize = new google.maps.Size(91.8, 189);

    marker = new google.maps.Marker({
        map: map,
        title:"Kabouter",
        icon: icon
    });

    var x = document.getElementById("notification");

    function getLocation()
    {
        if (navigator.geolocation){
            navigator.geolocation.getCurrentPosition(showPosition,showError);
            setTimeout(getLocation, 1000);
        }else{
            x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    function showPosition(position)
    {
        latlong = {"lat" : position.coords.latitude, "long" : position.coords.longitude}
        marker.setPosition(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
        map.panTo(new google.maps.LatLng(position.coords.latitude, position.coords.longitude))
    }


    function showError(error)
    {
      switch(error.code) 
        {
        case error.PERMISSION_DENIED:
          x.innerHTML = "User denied the request for Geolocation."
          break;
        case error.POSITION_UNAVAILABLE:
          x.innerHTML = "Location information is unavailable."
          break;
        case error.TIMEOUT:
          x.innerHTML = "The request to get user location timed out."
          break;
        case error.UNKNOWN_ERROR:
          x.innerHTML = "An unknown error occurred."
          break;
        }
      }
    
    getLocation();

}

google.maps.event.addDomListener(window, 'load', initialize);
