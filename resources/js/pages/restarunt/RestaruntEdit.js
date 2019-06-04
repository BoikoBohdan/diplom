import React from 'react';
import { Edit, SimpleForm, TextInput, required, SelectInput } from 'react-admin';

export const RestaruntEdit = props => (
  <Edit title='Edit Restarunt' {...props}>
    <SimpleForm>
      <TextInput validate={required()} label="Restarunt Name" source="name" />
      <TextInput validate={required()} label="Email" source="email" />
      <TextInput validate={required()} label="Restarunt Location" source="location" />
      <TextInput validate={required()} label="Contact Person Name" source="name" />
      <TextInput validate={required()} label="Phone Number" source="phone" />
      <SelectInput validate={required()} label="Language" optionText="group" />
    </SimpleForm>
  </Edit>
);