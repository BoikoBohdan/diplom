import React, {Component} from 'react'
import {compose, withProps} from 'recompose'
import {connect} from 'react-redux'
import DirectionRenderComponent from './DirectionRenderComponent'
import * as R from 'ramda'
import {showNotification} from 'ra-core'
import {setActiveAddressField, setSearcDropoffhAddress, setSearcPickuphAddress} from '../../../store/actions/orders'
import dataProvider from '../../DataProvider/dataProvider'

const {withScriptjs, withGoogleMap, GoogleMap} = require('react-google-maps')

class MapDrawRoute extends Component {
    state = {
        defaultZoom: 12,
        map: null,
        geaCoder: null,
        address: ""
    }

    componentDidMount () {
        this.initialize()
    }

    handleSelect = address => {
        const {lang, showNotification, active, data: {id}, resource, success} = this.props
        let data = {}
        data.streetaddress = address
        data.type = R.equals(active, 'pickup') ? 0 : 1
        R.equals(active, 'pickup')
            ? this.props.setSearcPickuphAddress(address)
            : this.props.setSearcDropoffhAddress(address)
        if (address.length > 2) {
            this.setState({address, address_selected: true}, () => {
                dataProvider('UPDATE', `${resource}`, {
                    data, id: id
                }).then(() => {
                    setTimeout(() => {
                        this.props.cleanActiveField('')
                        showNotification(lang[success])
                    }, 100)
                })
            })
        }
    }

    handleClick = event => {
        const lat = event.latLng.lat()
        const lng = event.latLng.lng()
        const latlng = new google.maps.LatLng(lat, lng);
        !R.isNil(this.props.active) && !R.isEmpty(this.props.active) && this.state.geaCoder.geocode({
            'latLng': latlng
        }, (results, status) => {
            if (status === google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    this.handleSelect(results[1].formatted_address)
                } else {
                    alert('No results found');
                }
            } else {
                alert('Geocoder failed due to: ' + status);
            }
        });
    }

    initialize () {
        this.setState({
            geaCoder: new google.maps.Geocoder()
        })
    }

    render () {
        const {data} = this.props
        const pickupLat = data.pickup.coordinates.lat
        const dropoffLat = data.dropoff.coordinates.lat
        const pickupLng = data.pickup.coordinates.lng
        const dropoffLng = data.dropoff.coordinates.lng
        return (
            <GoogleMap
                defaultZoom={this.state.defaultZoom}
                onClick={this.handleClick}
                // center={{
                //     lat: Number(pickupLat),
                //     lng: Number(pickupLng)
                // }}
                defaultCenter={{lat: Number(pickupLat), lng: Number(pickupLng)}}>
                <DirectionRenderComponent
                    index={1}
                    strokeColor="#f68f54"
                    from={{
                        label: '123',
                        lat: pickupLat,
                        lng: pickupLng
                    }}
                    to={{
                        lat: dropoffLat,
                        lng: dropoffLng,
                    }}
                />
            </GoogleMap>
        )
    }
}

const mapStateToProps = state => {
    return {
        active: state.orders.activeAddressField,
        searchAddress: state.orders.searchAddress
    }
}

const mapDispatchToProps = dispatch => {
    return {
        showNotification: (mes) => dispatch(showNotification(mes)),
        cleanActiveField: (field) => dispatch(setActiveAddressField(field)),
        setSearcPickuphAddress: (address) => dispatch(setSearcPickuphAddress(address)),
        setSearcDropoffhAddress: (address) => dispatch(setSearcDropoffhAddress(address)),
    }
}

export default compose(
    connect(
        mapStateToProps,
        mapDispatchToProps
    ),
    withProps({
        googleMapURL: 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBZx7tvnbWL5thE_11q5h1XlHHhhUA968c&&v=3.exp&libraries=geometry,drawing,places',
        loadingElement: <div style={{height: `100%`}}/>,
        containerElement: <div style={{height: `500px`, width: `1000px`}}/>,
        mapElement: <div style={{height: `100%`}}/>
    }),
    withScriptjs,
    withGoogleMap
)(MapDrawRoute)
