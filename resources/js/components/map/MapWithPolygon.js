// /*global google*/
// import React, { Component } from 'react'
// const { compose, withProps } = require("recompose");
// const {
//   withScriptjs,
//   withGoogleMap,
//   GoogleMap,
//   Polygon
// } = require("react-google-maps");


// export const MapWithPolygon = compose(
//   withProps({
//     googleMapURL: "https://maps.googleapis.com/maps/api/js?key=AIzaSyBZx7tvnbWL5thE_11q5h1XlHHhhUA968c&v=3.exp&libraries=geometry,drawing,places",
//     loadingElement: <div style={{ height: `100%` }} />,
//     containerElement: <div style={{ height: `400px`, width: `400px` }} />,
//     mapElement: <div style={{ height: `100%` }} />,
//   }),
//   withScriptjs,
//   withGoogleMap
// )((props) => {
//   let bindref = (ref) => this.ref = ref
//   return (
//     <GoogleMap
//       defaultZoom={8}
//       defaultCenter={new google.maps.LatLng(-34.397, 150.644)}
//     >

//     </GoogleMap>
//   )
// }
// );



import React, { Component } from 'react'
import { withGoogleMap, GoogleMap, Polygon } from 'react-google-maps';

export class MapWithPolygon extends Component {
  shouldComponentUpdate() {
    return false
  }
  render() {
    const MyGoogleMap = withGoogleMap(() => (
      <GoogleMap
        defaultCenter={{ lat: -34.397, lng: 150.644 }}
        defaultZoom={8}>
        <Polygon
          ref={(ref) => this.polygon = ref}
          paths={this.props.points}
          options={{
            editable: true
          }}
          onMouseUp={() => {
            let poly = this.polygon
            const polyArray = poly.getPath().getArray();
            let paths = [];
            polyArray.forEach(function (path) {
              paths.push({ latitude: path.lat(), longitude: path.lng() });
            });
            this.props.draw(paths)
          }}
        />
      </GoogleMap>
    ));

    return (
      <div>
        <MyGoogleMap
          containerElement={<div style={{ height: `400px`, width: '400px' }} />}
          mapElement={<div style={{ height: `100%` }} />}
        />
      </div>
    );
  }
}
