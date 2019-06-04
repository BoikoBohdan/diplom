// import React from 'react';
// import { Show, SimpleShowLayout, TextField, BooleanInput, EditButton, RichTextField } from 'react-admin';

// export const VehiclesShow = (props) => (
//   <Show title="Driver Vehicles Information" {...props}>
//     <SimpleShowLayout>
//       <TextField label="Make" optionText="make" />
//       <TextField label="Model" optionText="model" />
//       <TextField label="Year" optionText="year" />
//       <TextField label="License Plate" optionText="license" />
//       <TextField label="Driver" optionText="driver" />
//       <TextField label="Vehicle Color" optionText="color" />
//       <BooleanInput disabled label="Status" source="status" />
//     </SimpleShowLayout>
//   </Show>
// );

import React from 'react';
import { Show, SimpleShowLayout, TextField, BooleanField, EditButton, RichTextField } from 'react-admin';
import { AdminStatus } from '../fields'

export const VehiclesShow = (props) => (
  <Show title="Admin Information" {...props}>
    <SimpleShowLayout>
      <TextField label="Make" optionText="make" />
      <TextField label="Model" optionText="model" />
      <TextField label="Year" optionText="year" />
      <TextField label="License Plate" optionText="license" />
      <TextField label="Driver" optionText="driver" />
      <TextField label="Vehicle Color" optionText="color" />
      <BooleanField label="Status" source="status" />
    </SimpleShowLayout>
  </Show>
);