import React from 'react';
import { List, Datagrid, TextField, EmailField, EditButton, ShowButton, DeleteButton, ExportButton, RefreshButton } from 'react-admin';
import { AdminStatus, EditDocuments } from '../fields'
import { CustomFilter } from '../action'

export const VehicleTypeList = props => (
  <List title='Vehicle Type' {...props} filters={<CustomFilter />}>
    <Datagrid rowClick="show">
      <TextField label="Type" source="type" />
      <TextField label="Localization" source="localization" />
      <TextField label="Delivery Charges Per Order For Completed Orders" source="completed" />
      <TextField label="Delivery Charges Per Order For Cancelled Orders" source="cancelled" />
      <TextField label="Delivery Radius " source="radius" />
      <EditButton label="Edit" />
      <ShowButton label="Show" />
      <DeleteButton label="Delete" />
    </Datagrid>
  </List>
);