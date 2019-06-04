import React, { Component } from 'react'
import AccountForm from './Form'
import Card from '@material-ui/core/Card';
import './style.css'

export class NewAccount extends Component {
  render() {
    return (
      <div className='new-user__page'>
        <Card>
          <AccountForm />
        </Card>
      </div>
    )
  }
}