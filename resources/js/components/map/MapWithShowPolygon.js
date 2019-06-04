/*global google*/
import React, { Component } from 'react'
const { compose, withProps } = require("recompose");
const {
  withScriptjs,
  withGoogleMap,
  GoogleMap,
  Polygon
} = require("react-google-maps");


export const MapWithShowPolygon = compose(
  withProps({
    googleMapURL: "https://maps.googleapis.com/maps/api/js?key=AIzaSyBZx7tvnbWL5thE_11q5h1XlHHhhUA968c&v=3.exp&libraries=geometry,drawing,places",
    loadingElement: <div style={{ height: `100%` }} />,
    containerElement: <div style={{ height: `400px`, width: `400px` }} />,
    mapElement: <div style={{ height: `100%` }} />,
  }),
  withScriptjs,
  withGoogleMap
)(props => {
  return (
    <GoogleMap
      defaultZoom={8}
      defaultCenter={new google.maps.LatLng(-34.397, 150.644)}
    >
      <Polygon
        paths={props.points}
        options={{
          editable: false
        }}
      />
    </GoogleMap>
  )
}
);
