import React from 'react';
import { Show, SimpleShowLayout, TextField, BooleanField, EditButton, RichTextField } from 'react-admin';

export const UserShow = (props) => (
  <Show title="Restarunt Information" {...props}>
    <SimpleShowLayout>
      <TextField label="First Name" source="first_name" />
      <TextField label="Last Name" source="last_name" />
      <TextField label="Email" type="email" source="email" />
      <TextField type="password" source="password" />
      <BooleanField label="Status" source="status" />
    </SimpleShowLayout>
  </Show>
);