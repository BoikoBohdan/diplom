import React, {Component} from 'react'
import {connect} from 'react-redux'
import {compose} from 'recompose'
import {withStyles} from '@material-ui/core/styles'
import './style.css'
import PropTypes from 'prop-types'
import * as R from 'ramda'
import Card from '@material-ui/core/Card'
import CardContent from '@material-ui/core/CardContent'
import {Title} from 'react-admin'
import Echo from 'laravel-echo'
import GoogleMapsContainer from './map'
import OrdersToolBar from './toolbar'
import OrderItem from './orders/orderItem'
import DriverItem from './drivers/driverItem'
import {addOrderToList, addUpdatedOrderToList, fetchOrders, filterOrders, fetchAdditionalOrders, clearSelectedOrderList} from '../../store/actions/orders'
import {fetchDrivers, updateDriver} from '../../store/actions/drivers'
import {fetchStatuses} from '../../store/actions/statuses'
import {fetchCancelReasons} from '../../store/actions/cancel_reasons'
import InfiniteScroll from 'react-infinite-scroll-component'
import {generateParam} from '../../utils'

const token = localStorage.getItem('token');
const styles = theme => ({
    ordersWrapper: {
        height: '100%',
        overflow: 'auto'
    },
    driverAssigned: {
        backgroundColor: 'red'
    },
    selectedOrder: {
    }
});

class GodsView extends Component {
    state = {
        selected_ordersIds: [],
        selected_driversIds: [],
        selected_assignedOrdersIds: [],
        drivers: [],
        order_status: '',
        total: '',
        driverIds: [],
        isShowDriversList: true,
        isShowDriversNames: false,
        page: 1,
        driverPage: 1,
        filter: '',
        name: 'all',
        sort: 'id',
        order: 'desc',
        perPage: '',
        showSnackBar: false
    };

    componentDidMount() {
        this.setState({
            page: 1,
            driverPage: 1
        });
        const {requestParams} = this.props;
        this.props.dispatch(fetchDrivers(1));
        this.props.dispatch(fetchOrders(requestParams));
        this.props.dispatch(fetchStatuses());
        this.props.dispatch(fetchCancelReasons());
        window.io = require('socket.io-client');
        window.Echo = new Echo({
            broadcaster: 'socket.io',
            host: window.location.hostname + ':6001'
        });
        window.Echo.channel('GodsEye').listen('.GodsEyeOrder', e => {
            this.newOrder(e)
        });
        window.Echo.channel('GodsEye').listen('.action-on-driver', e => {
            this.newDriver(e)
        })
    }

    /**
     * @param {number} id
     * @param {string} key
     * @param {object} e
     */
    handleClickIds = (id, key, e) => {
        const selectedIds = this.state[key];
        if (R.includes('orderInfoItem', e.target.className) && R.equals(e.target.tagName, 'SPAN')) {
            if (!R.includes(id, selectedIds)) {
                this.setState(state => {
                    return {
                        [key]: state[key].concat(id)
                    }
                })
            } else {
                this.setState(state => {
                    return {
                        [key]: R.without([id], state[key])
                    }
                })
            }
        } else {
            e.stopPropagation()
        }

    };

    /**
     * @param {number} id
     * @param {string} key
     * @param {object} e
     */
    handleDriverClick = (id, key, e) => {
        const {orders} = this.props;
        const {selected_driversIds} = this.state;
        const isEmptyAndNotIncludes = () => {
            this.props.dispatch(filterOrders(generateParam({
                name: 'driver',
                filter: id
            })));
            this.setState({
                selected_ordersIds: [],
                selected_driversIds: [id],
                name: 'driver'
            })
        };
        const isIncludesIds = () => {
            this.setState({
                selected_ordersIds: [],
                selected_driversIds: [],
                name: 'all'
            });
            this.props.dispatch(fetchOrders(generateParam({
                page: 1,
                name: 'status',
                order_status: '1'
            })));
            this.handleResetPage({})
        };
        if (R.includes('driverInfoItem', e.target.className) && R.equals(e.target.tagName, 'SPAN')) {
            if (!R.isEmpty(orders) && !R.includes(id, selected_driversIds)) {
                isEmptyAndNotIncludes()
            } else if (R.includes(id, selected_driversIds)) {
                isIncludesIds()
            } else if (R.isEmpty(orders) && !R.includes(id, selected_driversIds)) {
                isEmptyAndNotIncludes()
            }
        } else {
            console.log('3')
        }
        this.props.dispatch(clearSelectedOrderList())
    };

    handleShowDriverList() {
        this.setState({
            isShowDriversList: !this.state.isShowDriversList
        })
    }

    handleShowDriverNames() {
        this.setState({
            isShowDriversNames: !this.state.isShowDriversNames
        })
    }

    /**
     * @param {object} new_order
     */
    newOrder = new_order => {
        let {method} = new_order;
        let order = {...new_order.order, type: 'created'};
        switch (method) {
            case 'created':
                this.props.dispatch(addOrderToList(order));
                break;
            case 'updated':
                this.props.dispatch(addUpdatedOrderToList(new_order));
                break
        }
    };
    /**
     * @param {object} new_driver
     */
    newDriver = new_driver => {
        const {driver} = new_driver;
        this.props.dispatch(updateDriver(driver))
    };

    filterEmptyOrders = order => {
        const {coordinates: {lat, lng}} = order.dropoff;
        return (lat !== '0' && lng !== '0') && order
    };

    /**
     * @param {string} name
     * @param {string} filter
     * @param {number} order_status
     */
    handleResetPage = ({name = 'status', filter = '', order_status = 1}) => {
        this.props.dispatch(clearSelectedOrderList())
        this.setState({
            selected_driversIds: [],
            page: 1,
            name,
            filter,
            order_status
        })
    };

    fetchDataOnScroll = () => {
        this.setState({
            page: ++this.state.page,
            name: this.props.requestParams.name,
            filter: this.props.requestParams.filter,
            order_status: this.props.requestParams.order_status
        });
        const {page, name, filter, order_status} = this.state;
        this.props.dispatch(fetchAdditionalOrders(generateParam({page, name, filter, order_status})))
    };

    fetchDataDriversOnScroll = () => {
        this.setState({
            page: ++this.state.driverPage,
        });
        const {driverPage} = this.state;
        this.props.dispatch(fetchDrivers(driverPage))
    };

    render() {
        const {classes, drivers, orders, statuses, lang} = this.props;
        const {
            driverIds,
            selected_ordersIds,
            selected_driversIds,
            isShowDriversList,
            isShowDriversNames,
            selected_assignedOrdersIds,
        } = this.state;
        return (
            token &&
            <div>
                <Card>
                    <Title title={lang.godsView}/>
                    <OrdersToolBar
                        statuses={statuses}
                        handleResetPage={this.handleResetPage}
                        toggleDriversList={() => this.handleShowDriverList()}
                        toggleShowDriverNames={() => this.handleShowDriverNames()}/>
                    <CardContent>
                        <div className='main'>
                            <div className='main__list'>
                                <div className='main__item order__list'>
                                    <div id="scrollableOrdersDiv" className={classes.ordersWrapper}>
                                        <InfiniteScroll
                                            pageStart={1}
                                            dataLength={this.props.orders.length}
                                            next={this.fetchDataOnScroll}
                                            hasMore={true || false}
                                            useWindow={false}
                                            threshold={200}
                                            scrollableTarget="scrollableOrdersDiv"
                                            loader={<h4>...</h4>}>
                                            {
                                                orders && orders.map((el) => {
                                                    return (
                                                        <div
                                                            key={el.id}
                                                            onClick={(e) => this.handleClickIds(el.id, 'selected_ordersIds', e)}
                                                            className={`
                                                                    ${el.type === 'created' ? 'table__new' : el.type === 'updated' ? 'table__update' : ''}
                                                                    ${R.includes(el.id, selected_assignedOrdersIds) ? 'assigned_order' : ''}
                                                                    
                                                                `}>
                                                            <OrderItem
                                                                order={el}
                                                                data={el}
                                                                color={el.color}
                                                                isSelected={R.includes(el.id, selected_ordersIds)}
                                                                selected_assignedOrdersIds={selected_assignedOrdersIds}
                                                                selected_ordersIds={selected_ordersIds}/>
                                                        </div>
                                                    )
                                                })
                                            }
                                        </InfiniteScroll>
                                    </div>
                                </div>
                                <div className={isShowDriversList ? 'driver__list' : 'not_show_driver__list'}>
                                    <div>
                                        <div style={{height: '100%', overflow: 'auto'}} id="scrollableDriverDiv">
                                            <InfiniteScroll
                                                pageStart={1}
                                                next={this.fetchDataDriversOnScroll}
                                                dataLength={drivers.length}
                                                hasMore={true || false}
                                                useWindow={false}
                                                threshold={200}
                                                scrollableTarget="scrollableDriverDiv"
                                                loader={<h4>...</h4>}
                                            >
                                                {
                                                    drivers && drivers.map((el, index) => {
                                                        return (
                                                            <div
                                                                key={`${el.id}${index}`}
                                                                onClick={R.compose(
                                                                    (e) => this.handleDriverClick(el.id, 'selected_assignedOrdersIds', e)
                                                                )
                                                                }>
                                                                <DriverItem
                                                                    data={el}
                                                                    selected_driversIds={selected_driversIds}
                                                                    selected_ordersIds={selected_ordersIds}
                                                                    driverIds={driverIds}/>
                                                            </div>
                                                        )
                                                    })
                                                }
                                            </InfiniteScroll>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className='main__map'>
                                <GoogleMapsContainer
                                    style={{height: '100%'}}
                                    selected_driversIds={selected_driversIds}
                                    isShowDriversNames={isShowDriversNames}
                                    selected_ordersIds={selected_ordersIds}
                                    selected_assignedOrdersIds={selected_assignedOrdersIds}
                                    drivers={drivers}
                                    orders={orders}
                                />
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        )
    }
}

GodsView.propTypes = {
    classes: PropTypes.object.isRequired,
    drivers: PropTypes.array.isRequired,
    orders: PropTypes.array.isRequired,
};

const mapStateToProps = state => {
    return {
        drivers: state.drivers.drivers,
        orders: state.orders.orders,
        selectedOrders: state.orders.selectedOrders,
        statuses: state.statuses.statuses,
        requestParams: state.orders.requestParams,
        lang: state.i18n.messages
    }
};

export default compose(
    withStyles(styles),
    connect(mapStateToProps)
)(GodsView)
