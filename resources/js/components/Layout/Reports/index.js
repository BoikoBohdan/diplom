import React, { Component } from 'react'
import { MenuItemLink, getResources, Responsive } from 'react-admin'
import ReportIcon from '@material-ui/icons/Assessment'
import './style.css'

export default class Reports extends Component {

  state = {
    open: false
  }

  handlreChangeStatus = () => {
    let open = !this.state.open
    this.setState({ open })
  }

  render() {
    return (
      <>
        <MenuItemLink to="#" primaryText="Report" leftIcon={<ReportIcon />} onClick={this.handlreChangeStatus} />
        {
          this.state.open &&
          <div className="sub-menu__report">
            <MenuItemLink to="/" primaryText="Delivered" />
            <MenuItemLink to="/" primaryText="Cancelled" />
            <MenuItemLink to="/" primaryText="Pay driver" />
          </div>
        }
      </>
    )
  }
}
