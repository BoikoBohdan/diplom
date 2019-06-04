import React, { Component } from 'react'
import PlacesAutocomplete from 'react-places-autocomplete';
import TextField from '@material-ui/core/TextField';
import dataProvider from '../DataProvider/dataProvider'
import { connect } from 'react-redux';
import * as R from 'ramda';
import { showNotification } from 'react-admin';
import './style.css'

export class Address extends Component {

  state = {
    address: '',
    address_selected: true,
    first_load: true,
  }

  componentWillMount() {
    this.setState({ address: this.props.defaultValue ? this.props.defaultValue : '' })
  }

  handleChange = address => {
    this.setState({ address, address_selected: false, first_load: false });
  };

  handleSelect = address => {
    const { showNotification, type } = this.props;
    let data = {}
    data[`${this.props.field}`] = address
    data.type = type
    if (address.length > 2) {
      this.setState({ address, address_selected: true }, () => {
        dataProvider('UPDATE', `${this.props.resource}`, {
          data, id: this.props.id
        }).then(() => {
          setTimeout(() => { showNotification(this.props.success) }, 100);
        })
      })
    }
  };

  render() {
    const {lang} = this.props
    return (
      <PlacesAutocomplete
        value={this.state.address}
        onChange={this.handleChange}
        onSelect={this.handleSelect}>
        {({ getInputProps, suggestions, getSuggestionItemProps, loading }) => (
          <div className="edit-input__address">
            <TextField
                value={this.props.defaultValue}
                margin={this.props.margin || ''}
              {...getInputProps({
                label: this.props.label,
                className: 'location-search-input',
              })}
            />
            <div className="autocomplete-dropdown-container">
              {loading && <div>{`${lang.loading}`}...</div>}
              {suggestions.map(suggestion => {
                  return (
                  <div
                    className="location-item"
                    {...getSuggestionItemProps(suggestion, {})}>
                    <span>{suggestion.description}</span>
                  </div>
                );
              })}
            </div>
          </div>
        )}
      </PlacesAutocomplete>
    )
  }
}

const mapStateToProps = state => ({
    lang: state.i18n.messages
})

export default connect(mapStateToProps, {
  showNotification,
})(Address)
