/*global google*/
import React, { Component } from 'react'
const { compose, withProps } = require("recompose");
const {
  withScriptjs,
  withGoogleMap,
  GoogleMap,
} = require("react-google-maps");
const { DrawingManager } = require("react-google-maps/lib/components/drawing/DrawingManager");

export const MapWithADrawingManager = compose(
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
      {!props.status &&
        <DrawingManager
          defaultDrawingMode={google.maps.drawing.OverlayType.POLYGON}
          defaultOptions={{
            drawingControl: true,
            drawingControlOptions: {
              position: google.maps.ControlPosition.TOP_CENTER,
              drawingModes: [
                google.maps.drawing.OverlayType.POLYGON,
              ],
            },
            polygonOptions: {
              clickable: true,
              editable: true,
              zIndex: 1,
            },
          }}
          onPolygonComplete={(poly) => {
            const polyArray = poly.getPath().getArray();
            let paths = [];
            polyArray.forEach(function (path) {
              paths.push({ latitude: path.lat(), longitude: path.lng() });
            });
            props.draw(paths)
          }}
        />
      }
    </GoogleMap>
  )
}
);
