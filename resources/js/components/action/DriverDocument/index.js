import React, { Component } from 'react'
import DropzoneComponent from '../Dropzone'
import Fab from '@material-ui/core/Fab'
import AddIcon from '@material-ui/icons/Add'
import axios from 'axios'
import { stringify } from 'query-string'
import './style.css'
import {connect} from 'react-redux'
import {showNotification} from 'ra-core'
require('dotenv').config()

class DriverDocument extends Component {
  state = {
    images: [],
    error: ''
  }

  componentWillMount () {
    this.setState({ images: this.props.data.driver.documents })
    if (this.props.data.driver.documents.length == 0) {
      this.handlerNewDropzone()
    }
  }

  handlerNewDropzone = () => {
    let images = this.state.images
    if (images.length < 3) {
      let image = {
        file: '',
        id: images.length
      }
      images.push(image)
      this.setState({ images })
    }
  }

  loadedFile = (file, vehicle_id, image_id, index) => {
    let images = [...this.state.images]
    let status = images[index].file
    images[index] = { ...images[index], file: file }
    this.setState({ images })
    if (!status) {
      this.createNewFile(file)
      this.handlerNewDropzone()
    } else {
      this.updateFile(file, image_id)
    }
  }

  createNewFile = file => {
    axios
      .post(
        `${process.env.MIX_PUBLIC_URL}/api/admin/drivers/${
          this.props.record.id
        }/documents`,
        stringify({ documentable_type: this.props.type, file: file }),
        {
          headers: {
            'content-type': `application/x-www-form-urlencoded`,
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        }
      )
      .then(() => {
        this.props.show()
      })
  }

  updateFile = (file, position) => {
    axios
      .patch(
        `${process.env.MIX_PUBLIC_URL}/api/admin/drivers/${
          this.props.record.id
        }/documents/${position}`,
        stringify({ documentable_type: this.props.type, file: file }),
        {
          headers: {
            'content-type': `application/x-www-form-urlencoded`,
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        }
      )
      .then(() => {
        this.props.show()
      })
  }

  render () {
      const {lang} = this.props

      return (
      <>
        <div className='driver-documents__list'>
          {this.state.images.map((image, index) => {
            return (
              <DropzoneComponent
                format='image/*'
                lang={lang}
                defaultFile={image.file}
                file={this.loadedFile}
                vehicle_id=''
                image_id={image.id}
                index={index}
                key={index}
              />
            )
          })}
          {this.state.images.length < 3 && (
            <div className='dropzone'>
              <div className='dropzone__field'>
                <Fab
                  color='primary'
                  aria-label='Add'
                  onClick={() => this.handlerNewDropzone()}
                >
                  <AddIcon />
                </Fab>
              </div>
            </div>
          )}
        </div>
      </>
    )
  }
}

const mapStateToProps = state => ({
    lang: state.i18n.messages
})

export default connect(
    mapStateToProps,
    {showNotification}
)(DriverDocument)
