import React from 'react';
import { Edit, SimpleForm, TextInput, SelectInput, required } from 'react-admin'
import { MapWithPolygon } from '../fields'
import './style.css'

let coords = [
  { "lat": -34.21680686718722, "lng": 150.42431140752137 },
  { "lat": -34.03491578385153, "lng": 150.81981922002137 },
  { "lat": -34.39830612728138, "lng": 150.81981922002137 }
]
export class GeoFenceEdit extends React.Component {

  state = {
    finishDraw: false,
    polygon: '',
  }

  handlerFinishDraw = (polygon) => {
    let coord = JSON.stringify(polygon);
    this.setState({ finishDraw: true, polygon: coord })
  }

  render() {
    return (
      <Edit title='Edit Geo Fence Location'  {...this.props}>
        <SimpleForm defaultValue={this.state.finishDraw}>
          <TextInput validate={required()} lable="Location Name" source="location_name" />
          <TextInput validate={required()} lable="Country" source="country" />
          <TextInput validate={[required()]} lable="Location For" source="location_for" />
          <MapWithPolygon points={coords} status={this.state.finishDraw} draw={this.handlerFinishDraw} />
          <TextInput validate={[required()]} defaultValue={this.state.polygon} lable='' source="coord" type="hidden" disabled />
        </SimpleForm>
      </Edit>
    )
  }
}