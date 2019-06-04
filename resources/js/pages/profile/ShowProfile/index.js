import React, { Component } from 'react'
import {getResources, Title} from 'react-admin'
import {connect} from 'react-redux'
import Button from '@material-ui/core/Button'
import Card from '@material-ui/core/Card'
import dataProvider from '../../../components/DataProvider/dataProvider'
import * as R from 'ramda'
import './style.css'

class Profile extends Component {
  state = {
    data: null
  }

  componentWillMount () {
    dataProvider(
      'GET_CUTOM_LINK',
      `${process.env.MIX_PUBLIC_URL}/api/admin/auth/user`
    ).then(res => {
      this.setState({ data: res.data })
    })
  }

  render () {
    let profile = this.state.data
      const {lang, role} = this.props
    return (
      <Card className='profile-wrapper'>
        <Title title='Profile' />
        <div className='profile__header'>
          <h2>{lang.profileTitle}</h2>
          <Button
            className='profile__edit'
            color='primary'
            href='#/profile/edit'
          >
              {lang.btnEdit}
          </Button>
        </div>
        <div className='profile'>
          <div className='profile__image'>
            <img
              src={
                profile
                  ? `${process.env.MIX_PUBLIC_URL}/storage${profile.photo}`
                  : ''
              }
            />
          </div>
          <div className='profile__info'>
            <div className='profile__info-area'>
              <span className='profile__info-label'>{lang.profileFirstName}</span>
              <span className='profile__info-value'>
                {profile ? profile.first_name : ''}
              </span>
            </div>
            <div className='profile__info-area'>
              <span className='profile__info-label'>{lang.profileLastName}</span>
              <span className='profile__info-value'>
                {profile ? profile.last_name : ''}
              </span>
            </div>
            <div className='profile__info-area'>
              <span className='profile__info-label'>{lang.profileEmail}</span>
              <span className='profile__info-value'>
                {profile ? profile.email : ''}
              </span>
            </div>
            <div className='profile__info-area'>
              <span className='profile__info-label'>{lang.profileAddress}</span>
              <span className='profile__info-value'>
                {profile ? profile.address : ''}
              </span>
            </div>
            <div className='profile__info-area'>
              <span className='profile__info-label'>{lang.profilePhone}</span>
              <span className='profile__info-value'>
                {profile ? profile.phone : ''}
              </span>
            </div>
              {
                  R.equals(role, 'admin') &&
                  <div className='profile__info-area'>
                      <span className='profile__info-label'>{lang.profileCompany}</span>
                      <span className='profile__info-value'>{profile ? profile.company : ''}</span>
                  </div>
              }
          </div>
        </div>
      </Card>
    )
  }
}
const mapStateToProps = state => ({
    resources: getResources(state),
    lang: state.i18n.messages,
    role: state.login.role
})

export default connect(mapStateToProps)(Profile)
