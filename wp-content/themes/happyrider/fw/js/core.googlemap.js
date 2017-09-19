function happyrider_googlemap_init(dom_obj, coords) {
	"use strict";
	if (typeof HAPPYRIDER_GLOBALS['googlemap_init_obj'] == 'undefined') happyrider_googlemap_init_styles();
	HAPPYRIDER_GLOBALS['googlemap_init_obj'].geocoder = '';
	try {
		var id = dom_obj.id;
		HAPPYRIDER_GLOBALS['googlemap_init_obj'][id] = {
			dom: dom_obj,
			markers: coords.markers,
			geocoder_request: false,
			opt: {
				zoom: coords.zoom,
				center: null,
				scrollwheel: false,
				scaleControl: false,
				disableDefaultUI: false,
				panControl: true,
				zoomControl: true, //zoom
				mapTypeControl: false,
				streetViewControl: false,
				overviewMapControl: false,
				styles: HAPPYRIDER_GLOBALS['googlemap_styles'][coords.style ? coords.style : 'default'],
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
		};
		
		happyrider_googlemap_create(id);

	} catch (e) {
		
		dcl(HAPPYRIDER_GLOBALS['strings']['googlemap_not_avail']);

	};
}

function happyrider_googlemap_create(id) {
	"use strict";

	// Create map
	HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].map = new google.maps.Map(HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].dom, HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].opt);

	// Add markers
	for (var i in HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers)
		HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[i].inited = false;
	happyrider_googlemap_add_markers(id);
	
	// Add resize listener
	jQuery(window).resize(function() {
		if (HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].map)
			HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].map.setCenter(HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].opt.center);
	});
}

function happyrider_googlemap_add_markers(id) {
	"use strict";
	for (var i in HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers) {
		
		if (HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[i].inited) continue;
		
		if (HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[i].latlng == '') {
			
			if (HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].geocoder_request!==false) continue;
			
			if (HAPPYRIDER_GLOBALS['googlemap_init_obj'].geocoder == '') HAPPYRIDER_GLOBALS['googlemap_init_obj'].geocoder = new google.maps.Geocoder();
			HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].geocoder_request = i;
			HAPPYRIDER_GLOBALS['googlemap_init_obj'].geocoder.geocode({address: HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[i].address}, function(results, status) {
				"use strict";
				if (status == google.maps.GeocoderStatus.OK) {
					var idx = HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].geocoder_request;
					if (results[0].geometry.location.lat && results[0].geometry.location.lng) {
						HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[idx].latlng = '' + results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();
					} else {
						HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[idx].latlng = results[0].geometry.location.toString().replace(/\(\)/g, '');
					}
					HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].geocoder_request = false;
					happyrider_googlemap_add_markers(id);
				} else
					dcl(HAPPYRIDER_GLOBALS['strings']['geocode_error'] + ' ' + status);
			});
		
		} else {
			
			// Prepare marker object
			var latlngStr = HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[i].latlng.split(',');
			var markerInit = {
				map: HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].map,
				position: new google.maps.LatLng(latlngStr[0], latlngStr[1]),
				clickable: HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[i].description!=''
			};
			if (HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[i].point) markerInit.icon = HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[i].point;
			if (HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[i].title) markerInit.title = HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[i].title;
			HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[i].marker = new google.maps.Marker(markerInit);
			
			// Set Map center
			if (HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].opt.center == null) {
				HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].opt.center = markerInit.position;
				HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].map.setCenter(HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].opt.center);
			}
			
			// Add description window
			if (HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[i].description!='') {
				HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[i].infowindow = new google.maps.InfoWindow({
					content: HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[i].description
				});
				google.maps.event.addListener(HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[i].marker, "click", function(e) {
					var latlng = e.latLng.toString().replace("(", '').replace(")", "").replace(" ", "");
					for (var i in HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers) {
						if (latlng == HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[i].latlng) {
							HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[i].infowindow.open(
								HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].map,
								HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[i].marker
							);
							break;
						}
					}
				});
			}
			
			HAPPYRIDER_GLOBALS['googlemap_init_obj'][id].markers[i].inited = true;
		}
	}
}

function happyrider_googlemap_refresh() {
	"use strict";
	for (id in HAPPYRIDER_GLOBALS['googlemap_init_obj']) {
		happyrider_googlemap_create(id);
	}
}

function happyrider_googlemap_init_styles() {
	// Init Google map
	HAPPYRIDER_GLOBALS['googlemap_init_obj'] = {};
	HAPPYRIDER_GLOBALS['googlemap_styles'] = {
		'default': [],
		'invert': [ { "stylers": [ { "invert_lightness": true }, { "visibility": "on" } ] } ],
		'dark': [{"featureType":"landscape","stylers":[{ "invert_lightness": true },{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}],
		'simple': [
				{
				  stylers: [
					{ hue: "#00ffe6" },
					{ saturation: -20 }
				  ]
				},{
				  featureType: "road",
				  elementType: "geometry",
				  stylers: [
					{ lightness: 100 },
					{ visibility: "simplified" }
				  ]
				},{
				  featureType: "road",
				  elementType: "labels",
				  stylers: [
					{ visibility: "off" }
				  ]
				}
			  ],
	'greyscale': [
					{
						"stylers": [
							{ "saturation": -100 }
						]
					}
				],
	'greyscale2': [
				{
				 "featureType": "landscape",
				 "stylers": [
				  { "hue": "#FF0300" },
				  { "saturation": -100 },
				  { "lightness": 20.4705882352941 },
				  { "gamma": 1 }
				 ]
				},
				{
				 "featureType": "road.highway",
				 "stylers": [
				  { "hue": "#FF0300" },
				  { "saturation": -100 },
				  { "lightness": 25.59999999999998 },
				  { "gamma": 1 }
				 ]
				},
				{
				 "featureType": "road.arterial",
				 "stylers": [
				  { "hue": "#FF0300" },
				  { "saturation": -100 },
				  { "lightness": -22 },
				  { "gamma": 1 }
				 ]
				},
				{
				 "featureType": "road.local",
				 "stylers": [
				  { "hue": "#FF0300" },
				  { "saturation": -100 },
				  { "lightness": 21.411764705882348 },
				  { "gamma": 1 }
				 ]
				},
				{
				 "featureType": "water",
				 "stylers": [
				  { "hue": "#FF0300" },
				  { "saturation": -100 },
				  { "lightness": 21.411764705882348 },
				  { "gamma": 1 }
				 ]
				},
				{
				 "featureType": "poi",
				 "stylers": [
				  { "hue": "#FF0300" },
				  { "saturation": -100 },
				  { "lightness": 4.941176470588232 },
				  { "gamma": 1 }
				 ]
				}
			   ],
	'style1': [
    {
        "featureType": "administrative",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#ebe9e7"
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#737373"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#ebe9e7"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#ebe9e7"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#696969"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#ebe9e7"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#b3b3b3"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#ebe9e7"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#d6d6d6"
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#ebe9e7"
            },
            {
                "weight": 1.8
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#d7d7d7"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "all",
        "stylers": [
            {
                "color": "#808080"
            },
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#dad5d2"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    }
],
	'style2': [
		 {
		  "featureType": "landscape",
		  "stylers": [
		   {
		    "hue": "#007FFF"
		   },
		   {
		    "saturation": 100
		   },
		   {
		    "lightness": 156
		   },
		   {
		    "gamma": 1
		   }
		  ]
		 },
		 {
		  "featureType": "road.highway",
		  "stylers": [
		   {
		    "hue": "#FF7000"
		   },
		   {
		    "saturation": -83.6
		   },
		   {
		    "lightness": 48.80000000000001
		   },
		   {
		    "gamma": 1
		   }
		  ]
		 },
		 {
		  "featureType": "road.arterial",
		  "stylers": [
		   {
		    "hue": "#FF7000"
		   },
		   {
		    "saturation": -81.08108108108107
		   },
		   {
		    "lightness": -6.8392156862745
		   },
		   {
		    "gamma": 1
		   }
		  ]
		 },
		 {
		  "featureType": "road.local",
		  "stylers": [
		   {
		    "hue": "#FF9A00"
		   },
		   {
		    "saturation": 7.692307692307736
		   },
		   {
		    "lightness": 21.411764705882348
		   },
		   {
		    "gamma": 1
		   }
		  ]
		 },
		 {
		  "featureType": "water",
		  "stylers": [
		   {
		    "hue": "#0093FF"
		   },
		   {
		    "saturation": 16.39999999999999
		   },
		   {
		    "lightness": -6.400000000000006
		   },
		   {
		    "gamma": 1
		   }
		  ]
		 },
		 {
		  "featureType": "poi",
		  "stylers": [
		   {
		    "hue": "#00FF60"
		   },
		   {
		    "saturation": 17
		   },
		   {
		    "lightness": 44.599999999999994
		   },
		   {
		    "gamma": 1
		   }
		  ]
		 }
	],
	'style3':  [
 {
  "featureType": "landscape",
  "stylers": [
   {
    "hue": "#FFA800"
   },
   {
    "saturation": 17.799999999999997
   },
   {
    "lightness": 152.20000000000002
   },
   {
    "gamma": 1
   }
  ]
 },
 {
  "featureType": "road.highway",
  "stylers": [
   {
    "hue": "#007FFF"
   },
   {
    "saturation": -77.41935483870967
   },
   {
    "lightness": 47.19999999999999
   },
   {
    "gamma": 1
   }
  ]
 },
 {
  "featureType": "road.arterial",
  "stylers": [
   {
    "hue": "#FBFF00"
   },
   {
    "saturation": -78
   },
   {
    "lightness": 39.19999999999999
   },
   {
    "gamma": 1
   }
  ]
 },
 {
  "featureType": "road.local",
  "stylers": [
   {
    "hue": "#00FFFD"
   },
   {
    "saturation": 0
   },
   {
    "lightness": 0
   },
   {
    "gamma": 1
   }
  ]
 },
 {
  "featureType": "water",
  "stylers": [
   {
    "hue": "#007FFF"
   },
   {
    "saturation": -77.41935483870967
   },
   {
    "lightness": -14.599999999999994
   },
   {
    "gamma": 1
   }
  ]
 },
 {
  "featureType": "poi",
  "stylers": [
   {
    "hue": "#007FFF"
   },
   {
    "saturation": -77.41935483870967
   },
   {
    "lightness": 42.79999999999998
   },
   {
    "gamma": 1
   }
  ]
 }
]
}
}