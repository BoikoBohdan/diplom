import React from 'react';
import { List, Datagrid, TextField, EmailField, EditButton, ShowButton, DeleteButton, ExportButton, RefreshButton } from 'react-admin';
import { AdminStatus, EditDocuments } from '../fields'
import { CustomFilter } from '../action'

export const VehiclesList = props => (
  <List title='Driver Vehicles' {...props} filters={<CustomFilter />}>
    <Datagrid rowClick="show">
      <TextField label="Taxis" source="taxis" />
      <EmailField label="Driver" source="driver" />
      <EditDocuments label="View/Edit Document(s)" source="docs" />
      <AdminStatus label="Status" source="status" />
      <EditButton label="Edit" />
      <ShowButton label="Show" />
      <DeleteButton label="Delete" />
    </Datagrid>
  </List>
);