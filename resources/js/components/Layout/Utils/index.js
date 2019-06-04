import React, { Component } from 'react'
import { MenuItemLink, getResources, Responsive } from 'react-admin'
import ReportIcon from '@material-ui/icons/Assessment'
import './style.css'

export default class Utils extends Component {

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
        <MenuItemLink to="#" primaryText="Utils" leftIcon={<ReportIcon />} onClick={this.handlreChangeStatus} />
        {
          this.state.open &&
          <div className="sub-menu__utils">
            <MenuItemLink to="/" primaryText="Make Make" />
            <MenuItemLink to="/" primaryText="Make Model" />
          </div>
        }
      </>
    )
  }
}
