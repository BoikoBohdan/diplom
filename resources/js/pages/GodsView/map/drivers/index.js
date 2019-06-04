import React, {Component} from 'react'
import {Marker, InfoWindow} from 'react-google-maps'
import ChatLink from '../../../../components/action/ChatLink'
import AssignOrderBtn from '../../../../components/action/AssignOrder'
import driverIcon from '../../../../images/driver2.png'
import {withStyles} from '@material-ui/core/styles'
// import DragSortableList from 'react-drag-sortable'
import SortableComponent from '../../../../components/DraggableItem'
import {connect} from 'react-redux'
import {compose} from 'recompose'
import * as R from 'ramda'

const styles = theme => ({
    name: {
        fontWeight: 'bold'
    },
    point: {
        listStyleType: 'none',
        marginRight: 5,
        fontSize: 15
    },
    pointList: {
        display: 'flex',
        padding: 0,
        justifyContent: 'space-between'
    }
})

const DriverName = props => {
    const {title, classes: {name}} = props
    return (
        <div className={name}>
            <span>{title}</span>
        </div>
    )
}
const DriverStops = props => {
    const {stops} = props
    return (
        <div>
            <span>{stops}</span>
        </div>
    )
}
const DriverVehicle = props => {
    const {car} = props
    return (
        <div>
            <span>{car}</span>
        </div>
    )
}

class DriversMarkers extends Component {
    state = {
        isOpenInfoWindow: false,
        driverName: '',
    }

    handlerInfoWindow = (name) => {
        const {driverName, isOpenInfoWindow} = this.state
        if (R.equals(driverName, name) && isOpenInfoWindow) {
            this.setState({
                driverName: '',
                isOpenInfoWindow: false
            })
        } else {
            this.setState({
                driverName: name,
                isOpenInfoWindow: true
            })
        }
    }

    isSelectedDriver = id => R.includes(id, this.props.selected_driversIds)

    render () {
        const {
            drivers,
            isShowDriversNames,
            selected_driversIds,
            classes,
            orders,
            lang,
            role,
            selected_ordersIds
        } = this.props

        const {isOpenInfoWindow, driverName} = this.state
        /**
         * Helper function to get assigned orders Id
         * @param {object} order
         * @returns {number} id
         * */
        const getAssignedIds = order => order['id']
        /**
         * Helper function to filter assigned order for driver
         * @param {number} id
         * @returns {boolean}
         * */
        const isIncludeAssignedOrdersForDriver = id => R.find(R.propEq('id', id), orders)
        const getDriverOrderPoints = order => {
            const allPoints = []
            if(!R.isNil(order)) {
                const points = R.compose(
                    R.trim(),
                    R.tail(),
                    R.nth(0),
                    R.split('-')
                )(order.title.row)
                const formatPoint = point => [`${point}A`, `${point}B`]
                return allPoints.concat(...R.map(formatPoint, points))
            }
        }

        return (
            drivers && drivers.map((driver) => {
                const {waypoints} = driver
                const {lat, lng} = driver.location['coordinates']
                const title = driver.title.row
                const stops = driver.stops.row
                const car = driver.location.row
                // const assignedOrdersIds = R.map(getAssignedIds, driver.orders)
                // const assignedOrders = !R.isEmpty(assignedOrdersIds) && R.map(isIncludeAssignedOrdersForDriver, assignedOrdersIds)
                // const ordersOrder = !R.isEmpty(assignedOrders) && R.map(getDriverOrderPoints, assignedOrders)
                // const points = [].concat(...ordersOrder)
                // const createListItem = item => {
                //     console.log(item)
                //     return {
                //         content: (<div className={classes.point}>{item.title}</div>)
                //     }
                // }
                // const list = R.map(createListItem, waypoints)
                return (
                    <div key={driver.id}>
                        <Marker
                            position={{lat: Number(lat), lng: Number(lng)}}
                            onClick={() => this.handlerInfoWindow(title)}
                            icon={driverIcon}
                            shape={'circle'}
                            title={title}>
                            {(
                                isShowDriversNames ||
                                (isOpenInfoWindow && R.equals(title, driverName)) ||
                                R.includes(driver.id, selected_driversIds)
                            ) &&
                                <InfoWindow>
                                    <div>
                                        <DriverName title={title} classes={classes}
                                                    isOpenInfoWindow={isOpenInfoWindow}/>
                                        {
                                            (isOpenInfoWindow && title === driverName) &&
                                            <div>
                                                <DriverStops stops={stops} classes={classes}/>
                                                <DriverVehicle car={car} classes={classes}/>
                                                <ChatLink lang={lang}/>
                                                <AssignOrderBtn role={role} lang={lang} selected_ordersIds={selected_ordersIds} id={driver.id}/>
                                            </div>
                                        }
                                        {/*<DragSortableList items={ waypoints } onSort={onSort} type="horizontal"/>*/}
                                        <SortableComponent user={driver.id} waypoints={waypoints} />
                                    </div>
                                </InfoWindow>
                            }
                        </Marker>
                    </div>
                )
            })
        )
    }
}

const mapStateToProps = state => {
    return {
        orders: state.orders.orders,
        lang: state.i18n.messages,
        role: state.login.role
    }
}

export default compose(
    connect(mapStateToProps),
    withStyles(styles)
)(DriversMarkers)
