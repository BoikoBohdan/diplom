import React, { Component } from 'react'
import Select from '@material-ui/core/Select'
import MenuItem from '@material-ui/core/MenuItem'
import dataProvider from '../../DataProvider/dataProvider'

export default class SelectRole extends Component {
  handleChange = event => {
    dataProvider('UPDATE', `${this.props.resource}`, {
      data: { role_id: event.target.value },
      id: this.props.id
    }).then(e => {
      this.props.refresh()
      this.props.showPopupM()
    })
  }

  render () {
    const { lang } = this.props
    return (
      <div>
        <Select
          value={this.props.role_id}
          onChange={this.handleChange}
          name='role'
        >
          {localStorage.getItem('role') === 'master_admin' && (
            <MenuItem value={1}>{lang.rolesMasterAdmin}</MenuItem>
          )}
          {localStorage.getItem('role') === 'master_admin' ||
          localStorage.getItem('role') === 'super_admin' ? (
            <MenuItem value={2}>{lang.rolesSuperAdmin}</MenuItem>
            ) : (
              ''
            )}
          <MenuItem value={3}>User</MenuItem>
        </Select>
      </div>
    )
  }
}
