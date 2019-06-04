import React from 'react';
import { Create, SimpleForm, SelectInput, TextInput, BooleanInput, required } from 'react-admin'

export const VehicleTypeCreate = props => (
  <Create title='Add Vehicle Type'  {...props}>
    <SimpleForm>
      <TextInput validate={required()} label="Vehicle Type(English)" source='vehicle_type' />
      <SelectInput validate={required()} label="Select Location" optionText="location" />
      <TextInput label="Delivery Charges Per Order For Completed Orders (In USD)" optionText="completed" />
      <TextInput label="Delivery Charges Per Order For Cancelled Orders (In USD)" optionText="cancelled" />
      <TextInput label="Delivery Radius " source="status" />
    </SimpleForm>
  </Create>
);