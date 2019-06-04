import React from 'react'
import Card from '@material-ui/core/Card'
import { getResources, Title } from 'react-admin'
import Phone from '../../components/AutoSaveFields/phone'
import StringValid from '../../components/AutoSaveFields/string_valid'
import Email from '../../components/AutoSaveFields/email'
import Address from '../../components/AutoSaveFields/address'
import DropzoneComponent from '../../components/action/Dropzone'
import DriverDocument from '../../components/action/DriverDocument'
import VehicleDocument from '../../components/action/VehicleDocument'
import dataProvider from '../../components/DataProvider/dataProvider'
import { connect } from 'react-redux'
import { showNotification } from 'react-admin'
import { getVehicles } from '../../api'
import { Company } from '../../components/AutoSaveFields'
import './style.css'
require('dotenv').config()

class DeliveryEdit extends React.Component {
  state = {
    data: {},
    company: 0,
    file: [],
    imagePreviewUrl: [],
    address: '',
    address_selected: true,
    first_load: true,
    driver__photo: [],
    vehicles_types: {}
  }

  componentDidMount () {
    dataProvider('GET_ONE', `${this.props.resource}`, {
      id: this.props.id
    }).then(res => {
      this.setState({ data: res.data })
    })
    getVehicles().then(res => {
      this.setState({ vehicles_types: res.data })
    })
  }

  loadedFile = file => {
    const { showNotification, lang } = this.props
    let data = {}
    data.image = file
    dataProvider('UPDATE', `${this.props.resource}`, {
      data,
      id: this.props.id
    }).then(() => {
      this.setState(
        {
          imagePreviewUrl: file
        },
        () => {
          showNotification(lang.imageSaveSuccessfully)
        }
      )
    })
  }

  showmessage = () => {
    const { showNotification } = this.props
    showNotification(lang.imageSaveSuccessfully)
  }

  handlerDocuments = driver__photo => {
    // const { dispatch } = this.props;
    // this.setState({ driver__photo }, () => { dispatch(change('record-form', 'driver__photo', driver__photo)); });
  }

  handlerVehicles = vehicles => {
    // const { dispatch } = this.props;
    // this.setState({ vehicles }, () => { dispatch(change('record-form', 'vehicles', vehicles)); });
  }

  render () {
    const { lang } = this.props
    return (
      <Card className='driver__cards'>
        <Title title={lang.driverEdit} />
        <div className='simple-autosubmit__list'>
          <h2>{lang.driverInfo}</h2>
          <div className='simple-autosubmit__row'>
            {this.state.data.hasOwnProperty('first_name') && (
              <StringValid
                lang={lang}
                resource='api/admin/users'
                id={this.props.id}
                field='first_name'
                label={`${lang.profileFirstName} *`}
                secondError={lang['First Name is not valid']}
                success={lang['First Name saved successfully']}
                defaultValue={this.state.data.first_name}
              />
            )}
            <div className='simple-autosubmit__spacer' />
            {this.state.data.hasOwnProperty('last_name') && (
              <StringValid
                resource='api/admin/users'
                lang={lang}
                id={this.props.id}
                field='last_name'
                label={`${lang.profileLastName} *`}
                secondError={lang['Last Name is not valid']}
                success={lang['Last Name saved successfully']}
                defaultValue={this.state.data.last_name}
              />
            )}
            <div className='simple-autosubmit__spacer' />
            {this.state.data.hasOwnProperty('company') && (
              <div className='driver-edit__no-space'>
                <Company
                  resource='api/admin/users'
                  lang={lang}
                  id={this.props.id}
                  field='company_id'
                  label={`Team *`}
                  secondError={lang['Company is invalid']}
                  success={lang['Company saved successfully']}
                  defaultValue={this.state.data.company}
                />
              </div>
            )}
          </div>
          <div className='simple-autosubmit__row'>
            {this.state.data.hasOwnProperty('email') && (
              <Email
                resource='api/admin/users'
                id={this.props.id}
                field='email'
                lang={lang}
                label={`${lang.profileEmail} *`}
                secondError={lang['Email is not valid']}
                success={lang['E-mail saved successfully']}
                defaultValue={this.state.data.email}
              />
            )}
            {this.state.data.hasOwnProperty('phone') && (
              <>
                <div className='simple-autosubmit__spacer' />
                <Phone
                  resource='api/admin/users'
                  id={this.props.id}
                  field='phone'
                  lang={lang}
                  label={`${lang.profilePhone} *`}
                  secondError={lang['Pnone is not valid']}
                  success={lang['Phone saved successfully']}
                  defaultValue={this.state.data.phone}
                />
              </>
            )}
            {this.state.data.hasOwnProperty('address') && (
              <>
                <div className='simple-autosubmit__spacer' />
                <Address
                  resource='api/admin/users'
                  id={this.props.id}
                  field='address'
                  margin={'none'}
                  label={`${lang.profileAddress} *`}
                  secondError={lang['Address is not valid']}
                  success={lang['Address save successfully']}
                  defaultValue={this.state.data.address}
                />
              </>
            )}
          </div>
          {this.state.data.hasOwnProperty('image') && (
            <>
              <h6 className='driver__photo-title'>{lang.driverPhoto}</h6>
              <DropzoneComponent
                lang={lang}
                format='image/*'
                defaultFile={this.state.data.image}
                file={this.loadedFile}
              />
            </>
          )}
          <div className='simple-autosubmit__list'>
            <h2>Documents</h2>
            <div className='simple-autosubmit__row'>
              {this.state.data.driver && (
                <DriverDocument
                  lang={lang}
                  data={this.state.data}
                  type={this.state.data.driver.driver_model}
                  record={{ id: this.props.id }}
                  documents={this.handlerDocuments}
                  show={this.showmessage}
                />
              )}
            </div>
            {/* <h2>{lang.vehicleDocuments}</h2>
                        <div className='simple-autosubmit__row'>
                            {this.state.data.driver && (
                                <VehicleDocument
                                    data={this.state.data.driver.vehicles}
                                    driver_id={this.state.data.driver.id}
                                    record={{id: this.props.id}}
                                    vehicles_types={this.state.vehicles_types}
                                    vehicles={this.handlerVehicles}
                                />
                            )}
                        </div> */}
          </div>
        </div>
      </Card>
    )
  }
}

const mapStateToProps = state => ({
  lang: state.i18n.messages
})

export default connect(
  mapStateToProps,
  { showNotification }
)(DeliveryEdit)
