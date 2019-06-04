import React from 'react';
import { Show, SimpleShowLayout, TextField, EditButton, RichTextField } from 'react-admin';
import { MapWithShowPolygon } from '../fields'

let coords = [
  { "lat": -34.21680686718722, "lng": 150.42431140752137 },
  { "lat": -34.03491578385153, "lng": 150.81981922002137 },
  { "lat": -34.39830612728138, "lng": 150.81981922002137 }
]

export const GeoFenceShow = (props) => (
  <Show title="Geo Fence Location Information" {...props}>
    <SimpleShowLayout>

      <TextField lable="Location Name" source="location_name" />
      <TextField lable="Country" source="country" />
      <TextField lable="Location For" source="location_for" />
      <MapWithShowPolygon points={coords} source="coords" />
    </SimpleShowLayout>
  </Show>
);