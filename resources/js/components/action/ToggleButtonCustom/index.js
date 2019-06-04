import React, { Component } from 'react'
import Switch from '@material-ui/core/Switch'
import { connect } from 'react-redux'
import axios from '../../DataProvider/request'
import './style.css'
import { showNotification } from 'react-admin'
import { stringify } from 'query-string'
require('dotenv').config()

class ToggleButtonCustom extends Component {
  handleChangeStatus = e => {
    const { showNotification } = this.props
    let status = e.target.checked
    let update = status ? 1 : 0
    axios
      .put(
        `${process.env.MIX_PUBLIC_URL}/${this.props.data.basePath}/${
          this.props.record.id
        }`,
        stringify({ status: update }),
        {
          headers: {
            'content-type': `application/x-www-form-urlencoded`,
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        }
      )
      .then(() => {
        showNotification('The Status changed successfully!', 'default')
        this.setState({ checked: status })
        this.props.refresh()
      })
      .catch(e => {
        this.props.refresh()
        showNotification('Status not changed', 'warning')
        window.location.replace('/#/login')
      })
  }
  render () {
    return (
      <>
        <Switch
          className='margin14'
          color='primary'
          checked={
            (this.props.record.status === 1 || this.props.record.status) ===
            true
          }
          onChange={this.handleChangeStatus}
        />
      </>
    )
  }
}

export default connect(
  null,
  {
    showNotification
  }
)(ToggleButtonCustom)
