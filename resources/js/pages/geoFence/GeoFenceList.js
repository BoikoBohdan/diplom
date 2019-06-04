import React from 'react';
import { List, Datagrid, TextField, EmailField, EditButton, ShowButton, DeleteButton, ExportButton, RefreshButton } from 'react-admin';
import { AdminStatus, EditDocuments } from '../fields'
import { CustomFilter } from '../action'

export const GeoFenceList = props => (
  <List title='Geo Fence Location List' {...props} filters={<CustomFilter />}>
    <Datagrid rowClick="show">
      <TextField labe="Location Name" source="location" />
      <TextField labe="Country" source="country" />
      <TextField labe="Location For" source="location_for" />
      <AdminStatus source="status" />
      <EditButton labe="Edit" />
      <ShowButton labe="Show" />
      <DeleteButton labe="Delete" />
    </Datagrid>
  </List>
);