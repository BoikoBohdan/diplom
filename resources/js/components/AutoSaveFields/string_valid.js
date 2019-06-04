import React, { Component } from 'react'
import { ValidatorForm, TextValidator } from 'react-material-ui-form-validator'
import dataProvider from '../DataProvider/dataProvider'
import { connect } from 'react-redux'
import { showNotification } from 'react-admin'
export class StringValid extends Component {
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
    var re = /^[a-zA-Z0-9]{2,200}$/
    if (this.state.field.length > 2 && re.test(this.state.field)) {
      dataProvider('UPDATE', `${this.props.resource}`, {
        data,
        id: this.props.id
      }).then(() => {
        setTimeout(() => {
          showNotification(this.props.success)
        }, 100)
      })
    } else {
      setTimeout(() => {
        showNotification(this.props.secondError, 'danger')
      }, 500)
    }
  }

  render () {
      const {lang} = this.props
      return (
      <ValidatorForm
        className='edit-input__field'
        ref='form'
        onBlur={this.handleSubmit}
        onSubmit={this.handleSubmit}
        onError={errors => console.log(errors)}
      >
        <TextValidator
          label={this.props.label}
          onChange={this.handleChangeField}
          name={this.props.field}
          value={this.state.field}
          validators={['required', 'matchRegexp:^[a-zA-Z0-9]{2,200}$']}
          errorMessages={[
            'This field is required',
            `${this.props.secondError}`
          ]}
        />
      </ValidatorForm>
    )
  }
}
export default connect(
  null,
  {
    showNotification
  }
)(StringValid)
