import React, {Component} from 'react'
import {connect} from 'react-redux'
import PropTypes from 'prop-types'
import {selectedOrderList} from '../../../../store/actions/orders'
import {DirectionsRenderer, InfoWindow, Marker} from 'react-google-maps'
import * as R from 'ramda'
import markerItem from '../../../../images/glossy-red-icon.png'
import notAssignedMarker from '../../../../images/notAssignedMarker.png'
import {directionCreator} from '../../../../utils'

const isIncludeId = (id, arr) => {
    return R.includes(id, arr)
  }

class OrdersMarkers extends Component {

    state = {
        isOpenInfoWindow: ''
    }

    handleOpenInfoWindow = (order) => {
        const {isOpenInfoWindow} = this.state
        const {id} = order
        directionCreator(order)
            .then(res => {
                this.props.dispatch(selectedOrderList(res))
            })
            .catch(err => console.log(err))
        if(R.equals(id, isOpenInfoWindow)) {
            this.setState({
                isOpenInfoWindow: ''
            })
        } else {
            this.setState({
                isOpenInfoWindow: id
            })
        }
    }

    /**
     * @param {string} text
     * @returns {{color: string, fontSize: string, text: string, type: string, fontWeight: string}}
     */
    handleFormatMarkerLabel = text => {
        return {
            text: text,
            color: '#fff',
            fontWeight: 'bold',
            fontSize: '12px',
            type: 'circle'
        }
    }

    render () {
        const {
            selectedOrders,
            orders,
            selected_assignedOrdersIds,
        } = this.props
        const notAssignedOrders = R.filter(R.propEq('status', 'Not Assigned'), orders)
        return (
            <div>
                {
                    notAssignedOrders.map(order => {
                        const {isOpenInfoWindow} = this.state
                        const {lat: pickupLat, lng: pickupLng} = order.pickup.coordinates
                        return (
                            <Marker
                                key={order.id}
                                position={{ lat: Number(pickupLat), lng: Number(pickupLng) }}
                                icon={notAssignedMarker}
                                onClick={() => this.handleOpenInfoWindow(order)}
                                // label={this.handleFormatMarkerLabel(`${orderNumber} A`)}
                            >
                                {/*{*/}
                                {/*    R.equals(isOpenInfoWindow, order.id) &&*/}
                                {/*    <InfoWindow>*/}
                                {/*        <div>*/}
                                {/*            {order.pickup.row}*/}
                                {/*        </div>*/}
                                {/*    </InfoWindow>*/}
                                {/*}*/}
                            </Marker>
                            )
                    })
                }
                {
                    orders.map((order) => {
                        const orderNumber = R.replace('#', '', R.nth(0, R.split('-', order.title.row)))
                        const {lat: dropoffLat, lng: dropoffLng} = order.dropoff.coordinates
                        const {lat: pickupLat, lng: pickupLng} = order.pickup.coordinates
                        return (
                            (R.includes(order, selectedOrders) || isIncludeId(order.id, selected_assignedOrdersIds)) &&
                            <div key={order.id}>
                                <Marker
                                    position={{ lat: Number(pickupLat), lng: Number(pickupLng) }}
                                    icon={markerItem}
                                    label={this.handleFormatMarkerLabel(`${orderNumber} A`)}
                                />
                                <Marker
                                    position={{ lat: Number(dropoffLat), lng: Number(dropoffLng) }}
                                    icon={markerItem}
                                    label={this.handleFormatMarkerLabel(`${orderNumber} B`)}
                                />
                                {
                                    order.direction && <DirectionsRenderer
                                        options={{
                                            suppressMarkers: true,
                                            polylineOptions: {
                                                strokeColor: order.color,
                                                strokeOpacity: 0.8,
                                                strokeWeight: 4
                                            },
                                            scale: 3,
                                        }}
                                        key={order.id} directions={order.direction}/>
                                }
                            </div>
                        )
                    })
                }
            </div>

        )
    }
}

OrdersMarkers.propTypes = {
    orders: PropTypes.array,
    selected_ordersIds: PropTypes.array,
    selected_assignedOrdersIds: PropTypes.array,
};

const mapStateToProps = state => {
    return {
        selectedOrders: state.orders.selectedOrders
    }
};

export default connect(mapStateToProps)(OrdersMarkers)
