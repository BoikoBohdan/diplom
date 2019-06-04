import React, { Component } from 'react'
import { ValidatorForm, TextValidator } from 'react-material-ui-form-validator'
import dataProvider from '../DataProvider/dataProvider'
import { connect } from 'react-redux'
import { showNotification } from 'react-admin'
import TextMaskCustom from '../TextMaskCustom/TextMaskCustom'
class Phone extends Component {
  state = {
    field: '+41'
  }

  componentWillMount () {
    this.setState({
      field: this.props.defaultValue ? this.props.defaultValue : ''
    })
  }

  handleChangeField = event => {
    const field = event.target.value
    this.setState({ field })
  }

  handleSubmit = () => {
    const { showNotification, lang } = this.props
    let data = {}
    data[`${this.props.field}`] = this.state.field
    if (this.state.field.length > 11) {
      dataProvider('UPDATE', `${this.props.resource}`, {
        data,
        id: this.props.id
      }).then(() => {
        setTimeout(() => {
          showNotification(this.props.success)
        }, 100)
      })
    }
  }

  render () {
    const { lang } = this.props
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
          // InputProps={{
          //   inputComponent: TextMaskCustom
          // }}
          validators={['required', 'matchRegexp:^[+][0-9]{12}$|^[0-9]{11}$']}
          // validators={['required', 'matchRegexp:^[+][0-9]{2} [(][0-9]{3}[)] [0-9]{3}[-][0-9]{2}[-][0-9]{2}']}
          errorMessages={[
            lang['This field is required'],
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
)(Phone)
