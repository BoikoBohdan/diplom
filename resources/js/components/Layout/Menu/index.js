import React from 'react'
import { connect } from 'react-redux'
import { MenuItemLink, getResources, Responsive } from 'react-admin'
import AccountCircle from '@material-ui/icons/AccountCircle'
import { withRouter } from 'react-router-dom'
import SettingsIcon from '@material-ui/icons/Settings'
import DashboardIcon from '@material-ui/icons/Dashboard'
import GodsIcon from '@material-ui/icons/PinDrop'
import LocaleButton from '../../localeButton'
const token = localStorage.getItem('token')

const MyMenu = ({ resources, onMenuClick, logout, lang }) => {
  console.log(resources, 'resources')
  return (
    token && (
      <div>
        <MenuItemLink
          to='/profile'
          primaryText={lang.profileTitle}
          onClick={onMenuClick}
          leftIcon={<AccountCircle />}
        />
        {}
        {(localStorage.getItem('role') === 'master_admin' ||
          localStorage.getItem('role') === 'super_admin') && (
          <MenuItemLink
            to='/dashboard'
            primaryText={lang.dashboardTitle}
            onClick={onMenuClick}
            leftIcon={<DashboardIcon />}
          />
        )}
        {resources
          .filter(resource => resource.name !== 'api/admin/users')
          .map(
            (resource, index) =>
              resource.name.length > 0 && (
                <MenuItemLink
                  to={`/${resource.name}`}
                  primaryText={resource.options.label}
                  onClick={onMenuClick}
                  key={index}
                  leftIcon={resource.icon ? resource.icon : <SettingsIcon />}
                />
              )
          )}
        {/* <MenuItemLink
          to='/gods_view'
          primaryText={lang.godsView}
          onClick={onMenuClick}
          leftIcon={<GodsIcon />}
        /> */}
        <MenuItemLink
          to='/chat'
          primaryText={lang.chat}
          onClick={onMenuClick}
          leftIcon={<GodsIcon />}
        />
        <Responsive small={logout} medium={logout} />
        <LocaleButton />
      </div>
    )
  )
}

const mapStateToProps = state => ({
  resources: getResources(state),
  lang: state.i18n.messages
})

export default withRouter(connect(mapStateToProps)(MyMenu))
