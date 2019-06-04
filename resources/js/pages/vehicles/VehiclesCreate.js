import React from 'react';
import { Create, SimpleForm, SelectInput, TextInput, BooleanInput, required } from 'react-admin'

export const VehiclesCreate = props => (
  <Create title='Add Driver Vehicles'  {...props}>
    <SimpleForm>
      <SelectInput validate={required()} label="Make" optionText="make" />
      <SelectInput validate={required()} label="Model" optionText="model" />
      <SelectInput validate={required()} label="Year" optionText="year" />
      <TextInput validate={required()} label="License Plate" optionText="license" />
      <SelectInput validate={required()} label="Driver" optionText="driver" />
      <TextInput label="Vehicle Color" optionText="color" />
      <BooleanInput label="Status" source="status" />
    </SimpleForm>
  </Create>
);