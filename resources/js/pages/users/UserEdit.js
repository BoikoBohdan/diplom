import Card from '@material-ui/core/Card'
import React, { Component } from 'react'
import { Title } from 'react-admin'
import { connect } from 'react-redux'
import DropzoneComponent from '../../components/action/Dropzone'
import Address from '../../components/AutoSaveFields/address'
import Email from '../../components/AutoSaveFields/email'
import Phone from '../../components/AutoSaveFields/phone'
import Company from '../../components/AutoSaveFields/company'
import StringValid from '../../components/AutoSaveFields/string_valid'
import dataProvider from '../../components/DataProvider/dataProvider'
import './style.css'

class UserEditComponent extends Component {
  state = {
    data: {}
  }

  componentDidMount () {
    dataProvider('GET_ONE', `${this.props.resource}`, {
      id: this.props.id
    }).then(res => {
      this.setState({ data: res.data }, () => console.log(this.state, 'log'))
    })
  }

  loadedFile = file => {
    let data = {}
    data.image = file
    dataProvider('UPDATE', `${this.props.resource}`, {
      data,
      id: this.props.id
    })
  }

  render () {
    const { lang } = this.props
    return (
      <Card className='driver__cards'>
        <Title title={lang.adminEdit} />
        <div className='simple-autosubmit__list'>
          <h2>{lang.adminGeneralInfoTitle}</h2>
          <div className='simple-autosubmit__row'>
            {this.state.data.hasOwnProperty('first_name') && (
              <StringValid
                resource='api/admin/users'
                id={this.props.id}
                field='first_name'
                label={`${lang.tableFirstName} *`}
                success='First Name saved successfully'
                secondError='First Name is invalid'
                defaultValue={this.state.data.first_name}
              />
            )}
            <div className='simple-autosubmit__spacer' />
            {this.state.data.hasOwnProperty('last_name') && (
              <StringValid
                resource='api/admin/users'
                id={this.props.id}
                field='last_name'
                label={`${lang.tableLastName} *`}
                success='Last Name saved successfully'
                secondError='Last Name is invalid'
                defaultValue={this.state.data.last_name}
              />
            )}
            <div className='simple-autosubmit__spacer' />
            {this.state.data.hasOwnProperty('company') && (
              <div className='driver-edit__no-space'>
                <Company
                  resource='api/admin/users'
                  lang={lang}
                  id={this.props.id}
                  field='company_id'
                  label={`${lang.profileCompany} *`}
                  secondError={lang['Company is invalid']}
                  success={lang['Company saved successfully']}
                  defaultValue={this.state.data.company}
                />
              </div>
            )}
          </div>
          <div className='simple-autosubmit__row'>
            {this.state.data.hasOwnProperty('email') && (
              <Email
                resource='api/admin/users'
                id={this.props.id}
                field='email'
                label={`${lang.tableEmail} *`}
                success='E-mail saved successfully'
                secondError='E-mail is invalid'
                defaultValue={this.state.data.email}
              />
            )}
            {this.state.data.hasOwnProperty('phone') && (
              <>
                <div className='simple-autosubmit__spacer' />
                <Phone
                  resource='api/admin/users'
                  id={this.props.id}
                  field='phone'
                  label={`${lang.tablePhone} *`}
                  secondError='Pnone is invalid'
                  success='Phone saved successfully'
                  defaultValue={this.state.data.phone}
                />
              </>
            )}
            {this.state.data.hasOwnProperty('address') && (
              <>
                <div className='simple-autosubmit__spacer' />
                <Address
                  resource='api/admin/users'
                  id={this.props.id}
                  field='address'
                  label={`${lang.searchDropoffAddress} *`}
                  success='Address saved successfully'
                  secondError='Address is invalid'
                  defaultValue={this.state.data.address}
                />
              </>
            )}
          </div>
          {this.state.data.hasOwnProperty('image') && (
            <>
              <h6 className='driver__photo-title'>Driver Photo</h6>
              <DropzoneComponent
                lang={lang}
                format='image/*'
                defaultFile={this.state.data.image}
                file={() => this.loadedFile}
              />
            </>
          )}
        </div>
      </Card>
    )
  }
}

const mapStateToProps = state => ({
  lang: state.i18n.messages
})

export default connect(mapStateToProps)(UserEditComponent)
