import React, { Component } from 'react'
import { MenuItemLink, getResources, Responsive } from 'react-admin'
import ReportIcon from '@material-ui/icons/Assessment'
import './style.css'

export default class Orders extends Component {

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
        <MenuItemLink to="#" primaryText="Orders" leftIcon={<ReportIcon />} onClick={this.handlreChangeStatus} />
        {
          this.state.open &&
          <div className="sub-menu">
            <MenuItemLink to="/" primaryText="Not assigned" />
            <MenuItemLink to="/" primaryText="Assigned" />
            <MenuItemLink to="/" primaryText="On it's way to Restaurant" />
            <MenuItemLink to="/" primaryText="In Restaurant" />
            <MenuItemLink to="/" primaryText="On it's way to Customer" />
            <MenuItemLink to="/" primaryText="In Customer" />
            <MenuItemLink to="/" primaryText="Delivered" />
            <MenuItemLink to="/" primaryText="Cancelled" />
          </div>
        }
      </>
    )
  }
}
