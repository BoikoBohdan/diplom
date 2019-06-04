import React, { Component } from 'react'
import { ValidatorForm, TextValidator } from 'react-material-ui-form-validator'
import dataProvider from '../DataProvider/dataProvider'
import { connect } from 'react-redux'
import { showNotification } from 'react-admin'

export class Email extends Component {
  state = {
    field: ''
  }

  componentWillMount () {
    this.setState({ field: this.props.defaultValue })
  }

  handleChangeField = event => {
    const field = event.target.value
    this.setState({ field })
  }

  handleSubmit = () => {
    const { showNotification, lang } = this.props
    let data = {}
    data[`${this.props.field}`] = this.state.field
    var re = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9])+.([a-zA-Z0-9])+/
    if (this.state.field.indexOf(' ') === -1) {
      if (re.test(this.state.field)) {
        dataProvider('UPDATE', `${this.props.resource}`, {
          data,
          id: this.props.id
        })
          .then(() => {
            setTimeout(() => {
              showNotification(this.props.success)
            }, 100)
          })
          .catch(error =>
            setTimeout(() => {
              showNotification(error[0])
            }, 100)
          )
      }
    } else {
      setTimeout(() => {
        showNotification(lang[`Error: E-mail has space`])
      }, 100)
    }
  }

  render () {
      const {lang} = this.props

      return (
      <ValidatorForm
        className='edit-input__field'
        ref='form'
        lang={lang}
        onBlur={this.handleSubmit}
        onSubmit={this.handleSubmit}
        onError={errors => console.log(errors)}
      >
        <TextValidator
          label={this.props.label}
          lang={lang}
          onChange={this.handleChangeField}
          name={this.props.field}
          value={this.state.field}
          validators={[
            'required',
            'matchRegexp:^([a-zA-Z0-9_.-])+@([a-zA-Z0-9])+.([a-zA-Z0-9])+'
          ]}
          errorMessages={[
            lang['This field is required'],
            `${this.props.secondError}`,
            `${this.props.secondError}`
          ]}
        />
      </ValidatorForm>
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
)(Email)
