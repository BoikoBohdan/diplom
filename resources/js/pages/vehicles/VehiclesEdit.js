import React from 'react';
import { Edit, SimpleForm, TextInput, required, SelectInput, BooleanInput } from 'react-admin';

export const VehiclesEdit = props => (
  <Edit title='Edit Driver Vehicles' {...props}>
    <SimpleForm>
      <SelectInput validate={required()} label="Make" optionText="make" />
      <SelectInput validate={required()} label="Model" optionText="model" />
      <SelectInput validate={required()} label="Year" optionText="year" />
      <TextInput validate={required()} label="License Plate" optionText="license" />
      <SelectInput validate={required()} label="Driver" optionText="driver" />
      <TextInput label="Vehicle Color" optionText="color" />
      <BooleanInput label="Status" source="status" />
    </SimpleForm>
  </Edit>
);