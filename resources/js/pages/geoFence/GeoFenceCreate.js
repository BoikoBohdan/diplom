import React from 'react';
import { Create, SimpleForm, TextInput, SelectInput, required } from 'react-admin'
import { MapWithADrawingManager } from '../fields'
import './style.css'
export class GeoFenceCreate extends React.Component {

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
      <Create title='Add Geo Fence Location'  {...this.props}>
        <SimpleForm defaultValue={this.state.finishDraw}>
          <TextInput validate={required()} lable="Location Name" source="location_name" />
          <TextInput validate={required()} lable="Country" source="country" />
          <TextInput validate={[required()]} lable="Location For" source="location_for" />
          <MapWithADrawingManager validate={this.state.finishDraw} status={this.state.finishDraw} draw={this.handlerFinishDraw} />
          <TextInput validate={[required()]} defaultValue={this.state.polygon} lable='' source="coord" type="hidden" disabled />
        </SimpleForm>
      </Create>
    )
  }
}