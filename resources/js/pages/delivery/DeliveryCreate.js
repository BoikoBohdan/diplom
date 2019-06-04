import React from 'react'
import { Title, required, minLength, getResources } from 'react-admin'
import AutoComplete from '../../components/action/AutoComplete'
import DropzoneComponent from '../../components/action/Dropzone'
import { connect } from 'react-redux'
import PlacesAutocomplete from 'react-places-autocomplete'
import TextField from '@material-ui/core/TextField'
import Button from '@material-ui/core/Button'
import Card from '@material-ui/core/Card'
import SaveIcon from '@material-ui/icons/Save'
import { saveDriversList } from '../../api/drivers'
import { showNotification } from 'react-admin'
import { ValidatorForm, TextValidator } from 'react-material-ui-form-validator'
import './style.css'

class DeliveryCreate extends React.Component {
  state = {
    company_id: 0,
    file: [],
    imagePreviewUrl: [],
    address: '',
    address_selected: true,
    image: null,
    first_name: '',
    last_name: '',
    email: '',
    phone: '+41',
    status: true,
    error_company: false,
    adress_error: false
  }

  handleChangeCompany = selected => {
    if (!selected) {
      this.setState({ error_company: true })
    } else {
      const company_id = selected.id
      this.setState({ company_id, error_company: false })
    }
  }

  loadedFile = file => {
    const { dispatch } = this.props
    this.setState({ image: file })
  }

  _handleImageChange (e) {
    e.preventDefault()
    let reader = new FileReader()
    let file = e.target.files[0]
    reader.onloadend = () => {
      this.setState({
        file: file,
        imagePreviewUrl: reader.result
      })
    }
    reader.readAsDataURL(file)
  }

  handleChange = name => event => {
    this.setState({ [name]: event.target.value })
  }

  handleChangeAddress = event => {
    console.log(event)
    if (event.length < 3) {
      this.setState({ adress_error: true, address: event })
    } else {
      this.setState({ adress_error: false, address: event })
    }
  }

  handleSubmit = () => {
    const { showNotification } = this.props
    saveDriversList(this.state)
      .then(res => {
        showNotification(lang['The driver was created successfully!'])
        window.history.back()
      })
      .catch(error => {
        showNotification(
          Object.values(error.response.data.error)[0][0],
          'warning'
        )
      })
  }

  render () {
    const { lang } = this.props
    return (
      <Card className='driver__cards'>
        <Title title={lang.driverCreate} />
        <ValidatorForm
          className='new-user__form'
          ref='form'
          onSubmit={() => {}}
          onError={errors => console.log(errors)}
        >
          <div className='driver-edit__wrapper'>
            <TextValidator
              label={lang.profileFirstName}
              source='first_name'
              name='first_name'
              required
              onChange={this.handleChange('first_name')}
              value={this.state.first_name}
              validators={['required', 'matchRegexp:^.{2,}$']}
              errorMessages={[
                lang['This field is required'],
                lang['First Name is not valid']
              ]}
            />
          </div>
          <div className='driver-edit__wrapper'>
            <TextValidator
              label={lang.profileLastName}
              source='last_name'
              required
              name='last_name'
              onChange={this.handleChange('last_name')}
              value={this.state.last_name}
              validators={['required', 'matchRegexp:^.{2,}$']}
              errorMessages={[
                lang['This field is required'],
                lang['Last Name is not valid']
              ]}
            />
          </div>
          <div className='driver-edit__wrapper'>
            <TextValidator
              label={lang.profileEmail}
              source='email'
              name='email'
              required
              onChange={this.handleChange('email')}
              value={this.state.email}
              validators={[
                'required',
                'matchRegexp:^([a-zA-Z0-9_.-])+@([a-zA-Z0-9])+.([a-zA-Z0-9])+'
              ]}
              errorMessages={[
                lang['This field is required'],
                lang['Email is not valid']
              ]}
            />
          </div>
          <div className='driver-edit__wrapper'>
            <TextValidator
              label={lang.profilePhone}
              required
              source='phone'
              name='phone'
              onChange={this.handleChange('phone')}
              value={this.state.phone}
              validators={['required', 'matchRegexp:^.{13}$']}
              errorMessages={[
                lang['This field is required'],
                lang['Phone is not valid']
              ]}
            />
          </div>
          <div className='driver-edit__company'>
            <AutoComplete
              ref={instance => {
                this.child = instance
              }}
              label={'Team' + ' *'}
              role='add'
              selected={this.handleChangeCompany}
              show_error={this.state.error_company}
            />
          </div>

          <PlacesAutocomplete
            value={this.state.address}
            onChange={this.handleChangeAddress}
          >
            {({
              getInputProps,
              suggestions,
              getSuggestionItemProps,
              loading
            }) => (
              <div className='location-search-wrapper'>
                <TextField
                  {...getInputProps({
                    label: lang.profileAddress,
                    className: 'location-search-input'
                  })}
                  required
                  error={this.state.adress_error}
                  onBlur={() => this.handleChangeAddress(this.state.address)}
                />
                <div className='autocomplete-dropdown-container'>
                  {loading && <div>{lang.loading}...</div>}
                  {suggestions.map(suggestion => {
                    return (
                      <div
                        className='location-item'
                        {...getSuggestionItemProps(suggestion, {})}
                      >
                        <span>{suggestion.description}</span>
                      </div>
                    )
                  })}
                </div>
              </div>
            )}
          </PlacesAutocomplete>
          <div className='height8' />
          <div className='last_line'>
            <DropzoneComponent
              lang={lang}
              format='image/*'
              defaultFile=''
              file={this.loadedFile}
            />
          </div>
          <div className='height8' />
          <Button
            onClick={() => this.handleSubmit()}
            variant='contained'
            color='primary'
          >
            <SaveIcon className='save-icon' />
            {lang.btnSave}
          </Button>
        </ValidatorForm>
      </Card>
    )
  }
}

const mapStateToProps = state => ({
  resources: getResources(state),
  lang: state.i18n.messages
})

export default connect(
  mapStateToProps,
  {
    showNotification
  }
)(DeliveryCreate)
