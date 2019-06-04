import red from '@material-ui/core/colors/red'
import Fab from '@material-ui/core/Fab'
import MenuItem from '@material-ui/core/MenuItem'
import Select from '@material-ui/core/Select'
import { createMuiTheme, MuiThemeProvider } from '@material-ui/core/styles'
import Switch from '@material-ui/core/Switch'
import AddIcon from '@material-ui/icons/Add'
import DeleteIcon from '@material-ui/icons/Delete'
import React, { Component } from 'react'
import { showNotification } from 'react-admin'
import { TextValidator, ValidatorForm } from 'react-material-ui-form-validator'
import { connect } from 'react-redux'
import {
  deleteVehicles,
  saveVehicles,
  saveVehiclesDocuments,
  updateVehicles,
  updateVehiclesDocuments
} from '../../../api'
import DropzoneComponent from '../Dropzone'
import './style.css'

const redTheme = createMuiTheme({
  typography: {
    useNextVariants: true
  },
  palette: { secondary: red }
})

class VehicleDocument extends Component {
  state = {
    vehicles: []
  }

  componentDidMount () {
    this.setState({ vehicles: this.props.data })
  }

  handlerNewVehicle = () => {
    let vehicles = this.state.vehicles
    if (vehicles.length < 5) {
      let image = {
        documents: [],
        is_shift: false,
        name: '',
        vehicle_type_id: 1,
        id: vehicles.length,
        new: true
      }
      vehicles.push(image)
      this.setState({ vehicles })
    }
  }

  handlerDeleteVehicle = (id, vehicle_new) => {
    let vehicles = this.state.vehicles
    let { showNotification, lang } = this.props
    if (!vehicle_new) {
      deleteVehicles(vehicles[id].id).then(res => {
        vehicles.splice(id, 1)
        this.setState({ vehicles }, () =>
          showNotification(lang.vehicleWasDeleted)
        )
      })
    } else {
      vehicles.splice(id, 1)
      this.setState({ vehicles }, () => showNotification(lang.vehicleWasDeleted))
    }
  }

  handlerNewDropzone = id => {
    let vehicles = this.state.vehicles

    vehicles[id].documents.push({
      id: vehicles[id].documents.length,
      file: ''
    })
    this.setState({ vehicles })
  }

  handlerSetFile = (file, id, image_id, index) => {
    let vehicles = this.state.vehicles
    let { showNotification, lang } = this.props
    if (vehicles[index].documents[image_id].file !== '') {
      updateVehiclesDocuments(id, vehicles[index].documents[image_id].id, {
        file: file
      })
        .then(res => {
          vehicles[index].documents[image_id].file = file
          this.setState({ vehicles }, () => showNotification(lang.fileSaved))
        })
        .catch(e => {
          showNotification(lang.fileNotSaved)
        })
    } else {
      saveVehiclesDocuments(id, { file: file })
        .then(res => {
          vehicles[index].documents[image_id].file = file
          vehicles[index].documents[image_id].id = res.data.id
          this.setState({ vehicles }, () => showNotification(lang.fileSaved))
        })
        .catch(() => {
          showNotification(lang.fileNotSaved)
        })
    }
  }

  updateVehicleFile = () => {
    vehicles[id].documents[image_id] = file

    this.setState({ vehicles }, () => {
      this.saveData(id, 'Image')
    })
  }

  handleChangeType = (id, e) => {
    let vehicles = this.state.vehicles
    vehicles[id].vehicle_type_id = e.target.value
    this.setState({ vehicles }, () => this.saveData(id, 'Vehicle Name'))
  }

  handleChangeName = id => event => {
    let name = event.target.value
    let vehicles = this.state.vehicles
    vehicles[id].name = name
    this.setState({ vehicles })
  }

  handleChangeStatus = id => event => {
    let status = event.target.checked
    let vehicles = this.state.vehicles
    vehicles[id].is_shift = status
    setTimeout(this.setState({ vehicles }, () => this.saveData(id, 'Status')),1000)
  }

  saveData = (id, name) => {
    const {lang} = this.props
    let vehicles = this.state.vehicles
    let driver_id = this.props.driver_id
    let { showNotification } = this.props
    if (vehicles[id].name.length > 2) {
      if (vehicles[id].new) {
        vehicles[id].new = false
        let data = { ...vehicles[id], driver_id }
        saveVehicles(data)
          .then(res => {
            vehicles[id].id = res.data.data[0].id
            this.setState({ vehicles }, () =>
              showNotification(`${name} ${lang.savedSuccessfully}`)
            )
          })
          .catch(() => {
            showNotification(`${name} ${lang.notSaved}`)
          })
      } else {
        let data = { ...vehicles[id], driver_id }
        updateVehicles(vehicles[id].id, data)
          .then(res => {
            let vehicles = this.state.vehicles
            let activeID = false
            for (let i = 0; i < res.data.data.length; i++) {
              vehicles[i] = res.data.data[i]
            }
            this.setState({ vehicles: vehicles }, () =>
              showNotification(`${name} ${lang.savedSuccessfully}`)
            )
          })
          .catch(() => {
            showNotification(`${name} ${lang.notSaved}`)
          })
      }
    }
  }

  render () {
    const {lang} = this.props
    return (
      <div>
        <div className='vehicle-documents__list'>
          {this.state.vehicles.length ? (
            this.state.vehicles.map((vehicle, index) => {
              console.log(vehicle, 'vehicle')
              return (
                <div key={index} className='vehicle-documents__item'>
                  <Select
                    value={vehicle.vehicle_type_id}
                    onChange={e => this.handleChangeType(index, e)}
                    className='driver-documents__select-type'>
                    {this.props.vehicles_types.length > 0 &&
                      this.props.vehicles_types.map((item, index) => {
                        return (
                          <MenuItem key value={item.id}>
                            {lang[item.type]}
                          </MenuItem>
                        )
                      })}
                  </Select>
                  <Switch
                    className='driver-documents__switch'
                    color='primary'
                    checked={vehicle.is_shift}
                    onChange={
                      vehicle.name.length > 2
                        ? this.handleChangeStatus(index)
                        : () => {}
                    }
                  />
                  <ValidatorForm
                    className='edit-input__field'
                    ref='form'
                    onSubmit={() => {}}
                    onError={errors => console.log(errors)}
                  >
                    <TextValidator
                      name='name'
                      id='driver-edit__vehicle-name'
                      label={lang.vehicleName}
                      onChange={this.handleChangeName(index)}
                      onBlur={() => this.saveData(index, 'Vehicle Name')}
                      value={vehicle.name}
                      validators={[
                        'required',
                        'matchRegexp:^[a-zA-Z0-9]{3,200}$'
                      ]}
                      errorMessages={lang['This field is required']}
                    />
                  </ValidatorForm>
                  {vehicle.documents.map((image, index2) => {
                    return (
                      <div key={index2} className='driver-edit__vehicle-images'>
                        <DropzoneComponent
                          lang={lang}
                          defaultFile={image.file}
                          file={this.handlerSetFile}
                          image_id={index2}
                          vehicle_id={vehicle.id}
                          index={index}
                          format='image/*'
                        />
                      </div>
                    )
                  })}

                  {vehicle.documents.length < 3 && (
                    <div className='dropzone'>
                      <div className='dropzone__field'>
                        <Fab
                          color='primary'
                          aria-label='Add'
                          onClick={() => this.handlerNewDropzone(index)}
                        >
                          <AddIcon />
                        </Fab>
                      </div>
                    </div>
                  )}
                  <MuiThemeProvider theme={redTheme}>
                    <Fab
                      className='driver-vehicle__delete'
                      aria-label='Delete'
                      color='secondary'
                      onClick={() =>
                        this.handlerDeleteVehicle(index, vehicle.new)
                      }>
                      <DeleteIcon />
                    </Fab>
                  </MuiThemeProvider>
                </div>
              )
            })
          ) : (
            <></>
          )}
        </div>
        {this.state.vehicles.length < 5 ? (
          <Fab
            className='driver-vehicle__add'
            color='primary'
            aria-label='Add'
            onClick={() => this.handlerNewVehicle()}>
            <AddIcon />
          </Fab>
        ) : (
          ''
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
  { showNotification }
)(VehicleDocument)
