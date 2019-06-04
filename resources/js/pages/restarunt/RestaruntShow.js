import React from 'react';
import { Show, SimpleShowLayout, TextField, DateField, EditButton, RichTextField } from 'react-admin';

export const RestaruntShow = (props) => (
  <Show title="Restarunt Information" {...props}>
    <SimpleShowLayout>
      <TextField label="Email" source="email" />
      <TextField label="Restarunt Location" source="location" />
      <TextField label="Contact Person Name" source="name" />
      <TextField label="Phone Number" source="phone" />
      <TextField label="Language" optionText="group" />
    </SimpleShowLayout>
  </Show>
);