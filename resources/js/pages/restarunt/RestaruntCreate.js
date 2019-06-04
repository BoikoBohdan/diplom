import React from 'react';
import { Create, SimpleForm, TextInput, SelectInput, required } from 'react-admin'

export const RestaruntCreate = props => (
  <Create title='Add Restarunt'  {...props}>
    <SimpleForm>
      <TextInput validate={required()} label="Restarunt Name" source="name" />
      <TextInput validate={required()} label="Email" source="email" type='email' />
      <TextInput validate={required()} label="Restarunt Location" source="location" />
      <TextInput validate={required()} label="Contact Person Name" source="name" />
      <TextInput validate={required()} label="Phone Number" source="phone" />
      <SelectInput validate={required()} label="Languge" optionText="group" />
    </SimpleForm>
  </Create>
);