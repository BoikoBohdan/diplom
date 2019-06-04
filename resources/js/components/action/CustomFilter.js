import React from 'react';
import { Filter, TextInput, SelectInput } from 'react-admin'

export const CustomFilter = (props) => {

  return (
    <Filter {...props}>
      <TextInput label="Search" source="q" alwaysOn />
      <SelectInput label="Search By" source="name" alwaysOn choices={props.fields}
        translateChoice={false} />
    </Filter>
  )
}
