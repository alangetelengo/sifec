var simplemaps_countrymap_mapdata={
  main_settings: {
    //General settings
		width: 500, //or 'responsive'
    background_color: "#FFFFFF",
    background_transparent: "yes",
    border_color: "#ffffff",
    pop_ups: "detect",

		//State defaults
		state_description: "State description",
    state_color: "#88A4BC",
    state_hover_color: "#3B729F",
    state_url: "",
    border_size: 1.5,
    all_states_inactive: "no",
    all_states_zoomable: "yes",

    //Location defaults
    location_description: "Location description",
    location_url: "",
    location_color: "#FF0067",
    location_opacity: 0.8,
    location_hover_opacity: 1,
    location_size: 25,
    location_type: "square",
    location_image_source: "frog.png",
    location_border_color: "#FFFFFF",
    location_border: 2,
    location_hover_border: 2.5,
    all_locations_inactive: "no",
    all_locations_hidden: "no",

		//Label defaults
		label_color: "#ffffff",
    label_hover_color: "#ffffff",
    label_size: 16,
    label_font: "Arial",
    label_display: "auto",
    label_scale: "yes",
    hide_labels: "no",
    hide_eastern_labels: "no",

		//Zoom settings
		zoom: "no",
    manual_zoom: "yes",
    back_image: "no",
    initial_back: "no",
    initial_zoom: "-1",
    initial_zoom_solo: "no",
    region_opacity: 1,
    region_hover_opacity: 0.6,
    zoom_out_incrementally: "yes",
    zoom_percentage: 0.99,
    zoom_time: 0.5,

		//Popup settings
		popup_color: "white",
    popup_opacity: 0.9,
    popup_shadow: 1,
    popup_corners: 5,
    popup_font: "12px/1.5 Verdana, Arial, Helvetica, sans-serif",
    popup_nocss: "no",

		//Advanced settings
		div: "map",
    auto_load: "yes",
    url_new_tab: "no",
    images_directory: "default",
    fade_time: 0.1,
    link_text: "View Website"
  },
  state_specific: {
    CG11: {
      name: "Bouenza",
      description: "default",
      color: "default",
      hover_color: "default",
      url: "default"
    },
    CG12: {
      name: "Pool",
      description: "default",
      color: "default",
      hover_color: "default",
      url: "default"
    },
    CG13: {
      name: "Sangha",
      description: "default",
      color: "default",
      hover_color: "default",
      url: "default"
    },
    CG14: {
      name: "Plateaux",
      description: "default",
      color: "default",
      hover_color: "default",
      url: "default"
    },
    CG15: {
      name: "Cuvette-Ouest",
      description: "default",
      color: "default",
      hover_color: "default",
      url: "default"
    },
    CG16: {
      name: "Pointe Noire",
      description: "default",
      color: "default",
      hover_color: "default",
      url: "default"
    },
    CG2: {
      name: "Lékoumou",
      description: "default",
      color: "default",
      hover_color: "default",
      url: "default"
    },
    CG5: {
      name: "Kouilou",
      description: "default",
      color: "default",
      hover_color: "default",
      url: "default"
    },
    CG7: {
      name: "Likouala",
      description: "default",
      color: "default",
      hover_color: "default",
      url: "default"
    },
    CG8: {
      name: "Cuvette",
      description: "default",
      color: "default",
      hover_color: "default",
      url: "default"
    },
    CG9: {
      name: "Niari",
      description: "default",
      color: "default",
      hover_color: "default",
      url: "default"
    },
    CGBZV: {
      name: "Brazzaville",
      description: "default",
      color: "default",
      hover_color: "default",
      url: "default"
    }
  },
  locations: {
    "0": {
      name: "Brazzaville",
      lat: "-4.259167",
      lng: "15.284722"
    }
  },
  labels: {
    CG11: {
      name: "Bouenza",
      parent_id: "CG11"
    },
    CG12: {
      name: "Pool",
      parent_id: "CG12"
    },
    CG13: {
      name: "Sangha",
      parent_id: "CG13"
    },
    CG14: {
      name: "Plateaux",
      parent_id: "CG14"
    },
    CG15: {
      name: "Cuvette-Ouest",
      parent_id: "CG15"
    },
    CG16: {
      name: "Pointe Noire",
      parent_id: "CG16"
    },
    CG2: {
      name: "Lékoumou",
      parent_id: "CG2"
    },
    CG5: {
      name: "Kouilou",
      parent_id: "CG5"
    },
    CG7: {
      name: "Likouala",
      parent_id: "CG7"
    },
    CG8: {
      name: "Cuvette",
      parent_id: "CG8"
    },
    CG9: {
      name: "Niari",
      parent_id: "CG9"
    },
    CGBZV: {
      name: "Brazzaville",
      parent_id: "CGBZV"
    }
  }
};
