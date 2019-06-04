import React, { Component } from 'react'
import AutoComplete from '../action/AutoComplete'
import dataProvider from '../DataProvider/dataProvider'
import { connect } from 'react-redux'
import { showNotification } from 'react-admin'

export class Company extends Component {
  state = {
    company: ''
  }

  componentWillMount () {
    this.setState({ company: this.props.defaultValue })
  }

  handleSubmit = value => {
    const { showNotification, lang } = this.props
    let data = {}
    data[`${this.props.field}`] = value.id
    dataProvider('UPDATE', `${this.props.resource}`, {
      data,
      id: this.props.id
    }).then(() => {
      setTimeout(() => {
        showNotification(this.props.success)
      }, 100)
    })
  }

  render () {
      const {lang} = this.props
      return (
      <AutoComplete
        ref={instance => {
          this.child = instance
        }}
        label={this.props.label || lang['Select Company']}
        role='add'
        defaultValue={this.state.company}
        selected={this.handleSubmit}
      />
    )
  }
}
export default connect(
  null,
  {
    showNotification
  }
)(Company)
