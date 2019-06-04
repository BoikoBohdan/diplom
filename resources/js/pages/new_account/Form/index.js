import React, { Component } from 'react'
import Button from '@material-ui/core/Button'
import { ValidatorForm, TextValidator } from 'react-material-ui-form-validator'
import axios from 'axios'
import { stringify } from 'query-string'
import { showNotification } from 'react-admin'
import { connect } from 'react-redux'
import SimpleSnackbar from '../../../components/Snackbar'
import './style.css'

class AccountForm extends Component {
  state = {
    password: '',
    confirm_password: '',
    phone: '+41',
    error: '',
    success: '',
    errors: {
      password: false,
      repeatPassword: false,
      phone: false
    }
  }

  handlePassword = event => {
    const password = event.target.value
    this.setState({ password })
    let errors = this.state.errors
    errors.password = false
    this.setState({ errors })
  }

  handleConfirmPassword = event => {
    const confirm_password = event.target.value
    this.setState({ confirm_password })
    let errors = this.state.errors
    errors.repeatPassword = false
    this.setState({ errors })
  }

  handlePhone = event => {
    let errors = this.state.errors
    errors.phone = false
    this.setState({ errors })
    const phone = event.target.value
    this.setState({ phone })
  }

  handleSubmit = () => {
    const { showNotification, lang } = this.props
    var urlParams = new URLSearchParams(window.location.search)
    let params = urlParams.getAll('token')
    let new_user = {
      password: this.state.password,
      password_confirmation: this.state.confirm_password,
      phone: this.state.phone,
      token: params[0]
    }
    let options = {
      headers: {
        'content-type': `application/x-www-form-urlencoded`,
        Authorization: `Bearer ${params[0]}`
      },
      data: stringify(new_user),
      method: 'POST'
    }
    let url = '/api/invite-confirmation'
    let error = {}
    if (this.state.password.length === 0) {
      error.password = true
    } else {
      error.password = false
    }
    if (this.state.confirm_password.length === 0) {
      error.repeatPassword = true
    } else {
      error.repeatPassword = false
    }
    if (this.state.phone.length === 0) {
      error.phone = true
    } else {
      error.phone = false
    }
    this.setState({ errors: error })
    if (
      this.state.password &&
      this.state.confirm_password &&
      this.state.phone
    ) {
      if (this.state.confirm_password === this.state.password) {
        this.setState({ error: '' })
        axios(url, options)
          .then(responce => {
            window.location.replace('/#/login')
          })
          .catch(error => {
            let errors = ''
            for (let text in error.response.data.errors) {
              errors += error.response.data.errors[text][0]
            }
            this.setState({ error: errors }, () => {
              setTimeout(() => this.setState({ error: '' }), 3000)
            })
          })
      } else {
        this.setState({ error: lang["Password don't match"] }, () => {
          setTimeout(() => this.setState({ error: '' }), 3000)
        })
      }
    } else {
      showNotification(lang["Password don't match"])
    }
  }

  handleClickOpen = () => {
    this.setState({ open: true })
  }

  render () {
    let errors = this.state.errors
    const {lang} = this.props
    return (
      <div>
        <ValidatorForm
          className='new-user__form'
          ref='form'
          onSubmit={this.handleSubmit}
          onError={errors => console.log(errors)}
        >
          <h2 id='simple-dialog-title'>
              {lang['Fill all fields to finish registration']}
          </h2>
          <TextValidator
            autoFocus
            label={lang.password}
            onChange={this.handlePassword}
            name='password'
            type='password'
            value={this.state.password}
            error={errors.password}
            validators={['required', 'matchRegexp:^.{6,}$']}
            errorMessages={[lang['This field is required'], lang['Password is not valid']]}
            className='new-user__form-input'
          />
          <TextValidator
            label='Repeat password'
            onChange={this.handleConfirmPassword}
            name='password_confirmation'
            type='password'
            error={errors.repeatPassword}
            value={this.state.confirm_password}
            validators={['required', 'matchRegexp:^.{6,}$']}
            errorMessages={[
              lang['This field is required'],
              lang['Repeat password is not valid']
            ]}
            className='new-user__form-input'
          />
          <TextValidator
            label='Phone'
            onChange={this.handlePhone}
            name='phone'
            error={errors.phone}
            value={this.state.phone}
            validators={['required', 'matchRegexp:^[+][0-9]{12}$|^[0-9]{11}$']}
            errorMessages={[lang['This field is required'], lang['Phone is not valid']]}
            className='new-user__form-input'
          />
          <div className='new-user__form-buttons'>
            <Button
              variant='contained'
              component='span'
              color='primary'
              type='submit'
              onClick={this.handleSubmit}
            >
                {lang.btnSubmit}
            </Button>
          </div>
        </ValidatorForm>
        {this.state.error.length !== 0 && (
          <SimpleSnackbar
            message={this.state.error}
            undo={false}
            open
            closable={false}
          />
        )}
      </div>
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
)(AccountForm)
