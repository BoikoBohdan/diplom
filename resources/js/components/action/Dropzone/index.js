import React from 'react'
import Dropzone from 'react-dropzone'
import Cross from '@material-ui/icons/Close'
import './style.css'
require('dotenv').config()

export default class DropzoneComponent extends React.Component {
  state = {
    imagePreviewUrl: ''
  }

  onDrop = acceptedFiles => {
    const fileTypes = ['image/jpg', 'image/jpeg', 'image/png']
    if (fileTypes.indexOf(acceptedFiles[0].type) !== -1) {
      acceptedFiles.forEach(file => {
        const reader = new FileReader()
        reader.onloadend = () => {
          this.setState(
            {
              imagePreviewUrl: reader.result
            },
            () => {
              this.props.file(
                reader.result,
                this.props.vehicle_id,
                this.props.image_id,
                this.props.index
              )
            }
          )
        }
        reader.onabort = () => console.log('file reading was aborted')
        reader.onerror = () => console.log('file reading has failed')
        reader.readAsDataURL(file)
      })
    } else {
    }
  }

  handleDelete = () => {
    this.setState(
      {
        imagePreviewUrl: ''
      },
      () => {
        this.props.file('', this.props.vehicle_id, this.props.image_id)
      }
    )
  }

  render () {
    const {lang} = this.props
    return (
      <div>
        <div className='dropzone'>
          {/* {this.state.imagePreviewUrl && <div onClick={this.handleDelete} className="dropzone__cross"><Cross/></div>} */}
          <Dropzone
            accept={this.props.format}
            onDrop={(accepted, rejected) => this.onDrop(accepted, rejected)}
          >
            {({ getRootProps, getInputProps }) => (
              <div {...getRootProps()} className='dropzone'>
                <input {...getInputProps()} accept='.png, .jpg, .jpeg' />
                {this.state.imagePreviewUrl ? (
                  <div className='dropzone__preview-wrapper'>
                    <img
                      className='dropzone__preview'
                      src={this.state.imagePreviewUrl}
                      alt='You Photo'
                    />
                  </div>
                ) : (
                  <>
                    {this.props.defaultFile ? (
                      <div className='dropzone__preview-wrapper'>
                        <img
                          className='dropzone__preview'
                          src={`${process.env.MIX_PUBLIC_URL}/storage${
                            this.props.defaultFile
                          }`}
                          alt='You Photo'
                        />
                      </div>
                    ) : (
                      <div className='dropzone__field'>
                        <p>{lang['Drop your file here']}</p>
                      </div>
                    )}
                  </>
                )}
              </div>
            )}
          </Dropzone>
        </div>
      </div>
    )
  }
}
