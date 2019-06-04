import React from 'react'
import { connect } from 'react-redux';
import { showNotification } from 'react-admin';

const FullName = props => {
  return (
    <>
      {props.record.first_name} {props.record.last_name}
    </>
  )
}

export default FullName