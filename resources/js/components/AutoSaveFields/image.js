import React, { Component } from 'react'
import DropzoneComponent from '../action/Dropzone'
import dataProvider from '../DataProvider/dataProvider'
import { connect } from 'react-redux'
import { showNotification } from 'react-admin'

export class Image extends Component {
  state = {
    image: ''
  }

  componentWillMount () {
    this.setState({ image: this.props.defaultValue })
  }

  handlerSetFile = value => {
    const { showNotification } = this.props
    let data = {}
    data[`${this.props.field}`] = value
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
      <DropzoneComponent
        lang={lang}
        defaultFile={this.state.image}
        file={this.handlerSetFile}
        format='image/*'
      />
    )
  }
}
export default connect(
  null,
  {
    showNotification
  }
)(Image)
