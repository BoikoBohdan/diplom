import React, {Component} from 'react';
import {withGoogleMap, GoogleMap, withScriptjs} from 'react-google-maps';
import DriversMarkers from './drivers';
import OrdersMarkers from './orders';
import {connect} from 'react-redux'

const GOOGLE_API_KEY = process.env.MIX_GOOGLE_MAPS_GEOCODING_API_KEY;
const MyGoogleMap = withScriptjs(withGoogleMap((
    {
        isMarkerShown,
        orders,
        drivers,
        selected_ordersIds,
        selected_driversIds,
        isShowDriversNames,
        selected_assignedOrdersIds
    }) => {
        return (
        <GoogleMap defaultCenter={{lat: 47.3666700, lng: 8.5500000}} defaultZoom={13}>
            <OrdersMarkers
                isMarkerShown={isMarkerShown}
                selected_ordersIds={selected_ordersIds}
                selected_assignedOrdersIds={selected_assignedOrdersIds}
                drivers={drivers}
                orders={orders}/>
            <DriversMarkers
                selected_driversIds={selected_driversIds}
                isShowDriversNames={isShowDriversNames}
                selected_ordersIds={selected_ordersIds}
                selected_assignedOrdersIds={selected_assignedOrdersIds}
                drivers={drivers}/>
        </GoogleMap>
        )
    }
));

class Map extends Component {
    render () {
        const {
            orders,
            drivers,
            selected_ordersIds,
            selected_driversIds,
            selected_assignedOrdersIds,
            isShowDriversNames} = this.props;
        const styles = {
            container: {
                height: '90%',
                width: 'auto'
            },
            map: {
                height: '100%'
            }
        };

        return (
            <div className={styles.map}>
                <MyGoogleMap
                    isMarkerShown
                    isShowDriversNames={isShowDriversNames}
                    selected_driversIds={selected_driversIds}
                    selected_assignedOrdersIds={selected_assignedOrdersIds}
                    orders={orders}
                    drivers={drivers}
                    selected_ordersIds={selected_ordersIds}
                    googleMapURL={`https://maps.googleapis.com/maps/api/js?key=AIzaSyBZx7tvnbWL5thE_11q5h1XlHHhhUA968c`}
                    loadingElement={<div style={{height: `100%`}}/>}
                    containerElement={<div style={styles.container}/>}
                    mapElement={<div style={styles.map}/>}/>
            </div>
        );
    }
}



export default Map;
