import React, { Component } from 'react'
import Icon from '@material-ui/core/Icon';
import users from '../../../../../images/dashboard/users.png';
import driver from '../../../../../images/dashboard/driver.png';
import building from '../../../../../images/dashboard/building.png';
import dollar from '../../../../../images/dashboard/dollar.png';
import AccessTime from '@material-ui/icons/AccessTime'
import { MuiThemeProvider, createMuiTheme } from '@material-ui/core/styles';

import './style.css'

const whiteTheme = createMuiTheme({
  typography: {
    useNextVariants: true,
  },
  palette: { primary: {main: '#fff' } }
})

export default class Icons extends Component {
  render() {
    return (
      <div className='dashboard-statistick__icon-wrapper'>
        <MuiThemeProvider theme={whiteTheme} >
          <AccessTime color="primary"></AccessTime>
        </MuiThemeProvider>
      </div>
    )
  }
}
