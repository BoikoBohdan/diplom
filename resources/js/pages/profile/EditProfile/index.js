import React, { Component } from 'react'
import {showNotification, Title} from 'react-admin'
import Button from '@material-ui/core/Button'
import Card from '@material-ui/core/Card'
import * as R from 'ramda'
import {
  StringValid,
  Email,
  Address,
  Phone,
  Image,
  Company
} from '../../../components/AutoSaveFields'
import dataProvider from '../../../components/DataProvider/dataProvider'
import './style.css'
import {connect} from 'react-redux'

class EditProfile extends Component {
  state = {
    profile: null
  }

  componentWillMount () {
    dataProvider(
      'GET_CUTOM_LINK',
      `${process.env.MIX_PUBLIC_URL}/api/admin/auth/user`
    ).then(res => {
      this.setState({ profile: res.data })
    })
  }

  handlerSetFile = file => {}

  handleChangeName = () => {}

  render () {
    const { profile } = this.state
    const { lang } = this.props
    const role = localStorage.getItem('role')
    return (
      <Card className='profile-wrapper'>
        <Title title='Profile Edit' />
        <div className='profile__header'>
          <Button
            className='profile__back'
            color='primary'
            href='#/profile'
          >{`< Back`}</Button>
        </div>
        <div className='profile'>
          {profile && (
            <>
              <div className='profile__image-edit'>
                <Image
                  lang={lang}
                  resource='api/admin/users'
                  id={localStorage.getItem('id')}
                  field='image'
                  secondError={lang['Image is invalid']}
                  success={lang['Image saved successfully']}
                  defaultValue={profile.photo}
                />
              </div>
              <div className='profile__info'>
                <StringValid
                  resource='api/admin/users'
                  id={localStorage.getItem('id')}
                  field='first_name'
                  label={`${lang.tableFirstName} *`}
                  secondError={lang['First Name is not valid']}
                  success={lang['First Name saved successfully']}
                  defaultValue={profile.first_name}
                />
                <StringValid
                  resource='api/admin/users'
                  id={localStorage.getItem('id')}
                  field='last_name'
                  label={`${lang.tableLastName} *`}
                  secondError={lang['Last Name is not valid']}
                  success={lang['Last Name saved successfully']}
                  defaultValue={profile.last_name}
                />
                <Email
                  resource='api/admin/users'
                  id={localStorage.getItem('id')}
                  field='email'
                  label={`${lang.tableEmail} *`}
                  secondError={lang['E-mail is not valid']}
                  success='E-mail saved successfully'
                  defaultValue={profile.email}
                />
                <Address
                  resource='api/admin/users'
                  id={localStorage.getItem('id')}
                  field='address'
                  lang={lang}
                  label={`${lang.profileAddress} *`}
                  secondError={lang['Address is not valid']}
                  success={lang['Address save successfully']}
                  defaultValue={profile.address}
                />
                <Phone
                  resource='api/admin/users'
                  id={localStorage.getItem('id')}
                  field='phone'
                  label={`${lang.tablePhone} *`}
                  secondError={lang['Pnone is not valid']}
                  success={lang['Phone saved successfully']}
                  defaultValue={profile.phone}
                />
                  {
                      R.equals(role, 'admin') && <Company
                          resource='api/admin/users'
                          id={localStorage.getItem('id')}
                          field='company_id'
                          label={`${lang.profileCompany} *`}
                          secondError={lang['Company is not valid']}
                          success={lang['Company saved successfully']}
                          defaultValue={profile.company}
                      />
                  }

              </div>
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

export default connect(
    mapStateToProps,
    {
        showNotification
    }
)(EditProfile)
