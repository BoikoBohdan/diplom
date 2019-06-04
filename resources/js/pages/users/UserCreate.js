import React from 'react';
import { Create, SimpleForm, BooleanInput, TextInput, required, email, minLength } from 'react-admin'
export const UserCreate = props => (
  <Create title='Add Admin'  {...props}>
    <SimpleForm redirect={`/api/admin/users`}>
      <TextInput label="First Name" validate={[required(), minLength(3)]} source="first_name" />
      <TextInput label="Last Name" validate={[required(), minLength(3)]} source="last_name" />
      <TextInput label="Email" type="email" validate={[required(), email()]} source="email" />
      <TextInput type="password" validate={[required(), minLength(8)]} source="password" />
      <BooleanInput label="Status" source="status" />
    </SimpleForm>
  </Create>
);