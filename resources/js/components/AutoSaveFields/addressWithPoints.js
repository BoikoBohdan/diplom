import React, {Component} from 'react'
import PlacesAutocomplete from 'react-places-autocomplete'
import TextField from '@material-ui/core/TextField'
import dataProvider from '../DataProvider/dataProvider'
import {connect} from 'react-redux'
import * as R from 'ramda'
import {setSearcPickuphAddress, setSearcDropoffhAddress, setActiveAddressField} from '../../store/actions/orders'
import {showNotification} from 'react-admin'
import './style.css'

export class addressWithPoints extends Component {

    state = {
        address: '',
        address_selected: true,
        first_load: true,
    }

    handleChange = address => {
        const {active} = this.props
        R.equals(active, 'pickup')
            ? this.props.setSearcPickuphAddress(address)
            : this.props.setSearcDropoffhAddress(address)
        this.setState({
            address
        })
    }

    handleSelect = address => {
        const {showNotification, type, active} = this.props
        const data = {}
        data[`${this.props.field}`] = address
        data.type = type
        R.equals(active, 'pickup')
            ? this.props.setSearcPickuphAddress(address)
            : this.props.setSearcDropoffhAddress(address)
        if (address.length > 2) {
            dataProvider(
                'UPDATE',
                `${this.props.resource}`,
                {
                    id: this.props.id,
                    data,
            }).then(() => {
                setTimeout(() => {
                    showNotification(this.props.lang[this.props.success])
                }, 100)
            })
        }
    }

    render () {
        const {lang} = this.props
        return (
            <PlacesAutocomplete
                value={this.props.address}
                onChange={this.handleChange}
                onSelect={this.handleSelect}>
                {({getInputProps, suggestions, getSuggestionItemProps, loading}) => (
                    <div className="edit-input__address">
                        <TextField
                            onFocus={this.props.handleSetActive}
                            onBlur={this.asd}
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
        )
    }
}

const mapStateToProps = state => {
    return {
        active: state.orders.activeAddressField,
        searchPickupAddress: state.orders.searchPickupAddress,
        searchDropoffAddress: state.orders.searchDropoffAddress,
    }
}

const mapDispatchToProps = dispatch => {
    return {
        showNotification: (mes) => dispatch(showNotification(mes)),
        setSearcPickuphAddress: (address) => dispatch(setSearcPickuphAddress(address)),
        setSearcDropoffhAddress: (address) => dispatch(setSearcDropoffhAddress(address)),
        cleanActiveField: (field) => dispatch(setActiveAddressField(field))
    }
}

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(addressWithPoints)
