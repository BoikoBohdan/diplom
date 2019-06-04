import React from 'react';
import { List, Datagrid, TextField, EmailField, EditButton } from 'react-admin';
// import { AdminStatus } from '../fields'
// import { CustomFilter } from '../action'
import RemoveButton from '../../components/action/RemoveButton/RemoveButton'
import Exporter from '../../components/action/Exporter'
import ToggleButton from '../../components/action/ToggleButton';
import "@material/react-switch/dist/switch.css";

const fields = [
  { id: 'all', name: 'All' },
  { id: 'first_name', name: 'Admin Name' },
  { id: 'last_name', name: 'Admin Surename' },
  { id: 'email', name: 'E-mail' },
]

export const UserList = ({ permissions, ...props }) => {
  localStorage.setItem('file_name', props.options.label);
  return (
    <List title='Admin' {...props} filters={<CustomFilter fields={fields} />} exporter={Exporter}>
      <Datagrid>
        <TextField label="Admin Name" source="first_name" />
        <TextField label="Admin Surename" source="last_name" />
        <EmailField label="Email" source="email" />
        {permissions != 1 &&
          <AdminStatus label="Status" source="status" />
        }
        {permissions == 1 &&
          <ToggleButton label='Change status' data={props} />
        }
        {permissions == 1 &&
          <EditButton label="Edit" />
        }
        {permissions == 1 &&
          <RemoveButton label='Delete' />
        }
      </Datagrid>
    </List >
  )
}
