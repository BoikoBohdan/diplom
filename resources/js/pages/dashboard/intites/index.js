import React, { Component } from 'react'
import ManagePartner from '../../../components/action/Popup/ManagePartner'
import InvitePopup from '../../../components/action/Popup/InvitePopup'
import './style.css'

export default class Invites extends Component {
  render () {
    const { lang } = this.props
    return (
      <ul className='dashboard-invite__list'>
        {localStorage.getItem('role') === 'master_admin' && (
          <InvitePopup
            lang={lang}
            label={lang.rolesMasterAdmin}
            role='master_admin'
          />
        )}
        {localStorage.getItem('role') === 'master_admin' ||
        localStorage.getItem('role') === 'super_admin' ? (
          <InvitePopup
              lang={lang}
              label={lang.rolesSuperAdmin}
              role='super_admin'
            />
          ) : null}
        <InvitePopup label='User' role='admin' />
        {localStorage.getItem('role') === 'master_admin' && (
          <ManagePartner lang={lang} label={'Manage team'} />
        )}
      </ul>
    )
  }
}
