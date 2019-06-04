import React, { Component } from 'react'
import Button from '@material-ui/core/Button'
import CustomTable from '../../../components/Table'

const user_fields = [
  { name: 'full_name', label: 'Full Name', filter: true },
  { name: 'email', label: 'E-mail', filter: true },
  { name: 'role', label: 'User Type', filter: true },
  { name: 'company', label: 'Team Relation', filter: true },
  { name: 'phone', label: 'Phone Number', filter: true },
  { name: 'status', label: 'Status' },
  { name: 'edit', label: 'Edit' },
  { name: 'delete', label: 'Delete' }
]

export default class MemberList extends Component {
  state = {
    status: false
  }

  handlerSwitchButton = () => {
    let status = !this.state.status
    this.setState({ status })
  }

  render () {
    const { lang } = this.props
    return (
      <>
        <div className='dashboard-member__button-show'>
          <Button
            onClick={this.handlerSwitchButton}
            variant='contained'
            color='primary'
          >
            {!this.state.status ? lang.btnShow : lang.btnHide}
          </Button>
        </div>
        {this.state.status && (
          <CustomTable
            title='admins'
            resource='api/admin/users'
            create={false}
            show={false}
            edit
            lang={lang}
            fields={[
              { name: 'full_name', label: lang.tableFullName, filter: true },
              { name: 'email', label: lang.tableEmail, filter: true },
              { name: 'role', label: lang.tableUserType, filter: true },
              {
                name: 'company',
                label: 'Team Relation',
                filter: true
              },
              { name: 'phone', label: lang.tablePhoneNumber, filter: true },
              { name: 'status', label: lang.tableStatus },
              { name: 'edit', label: lang.btnEdit },
              { name: 'delete', label: lang.btnDelete }
            ]}
            bulk
            searchEnabled
          />
        )}
      </>
    )
  }
}
