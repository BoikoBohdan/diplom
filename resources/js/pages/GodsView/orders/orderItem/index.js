import React, {Component} from 'react'
import { withStyles } from '@material-ui/core/styles'
import CanceledPopover from '../canceledPopup'
import PropTypes from 'prop-types'
import {compose} from 'recompose'
import {connect} from 'react-redux'
import {directionCreator} from '../../../../utils'
import {selectedOrderList} from '../../../../store/actions/orders'
import * as R from 'ramda'
import Button from '@material-ui/core/Button'

const styles = {
    orderItem: {
        wordBreak: 'break-all',
        margin: 10,
        borderBottom: '1px solid rgba(0,0,0,.4)'
    },
    orderInfoItem: {
        display: 'block'
    },
    editBtn: {
        selfAlign: 'flex-end',
        color: 'blue',
        fontSize: 11
    },
    editOrderWrapper: {
        display: 'flex',
        flexDirection: 'row',
        justifyContent: "center",
        alignItems: 'center'
    }
  }

class OrderItem extends Component {
    state = {
        colors: {
            'Not assigned': 'rgb(66,66,66)',
            'Assigned': 'rgb(0,231,131)',
            'On the way to restaurant': 'rgb(194, 255, 79)',
            'Arrived to restaurant': 'rgb(38, 125, 60)',
            'Left restaurant': 'rgb(255, 140, 46)',
            'Arrived to customer': 'rgb(218, 61, 33)',
            'Delivered': 'rgb(0, 121, 184)',
            'Cancelled': 'rgb(200, 31, 43)',
            'Cancel Request': 'rgb(255, 135, 81)',
            'paymentDone': 'rgb(38, 125, 60)',
            'paymentNotDone': 'rgb(200, 31, 43)',
        }
    }

    handleSelect = () => {
        const {data} = this.props
        directionCreator(data)
            .then(res => {
                this.props.dispatch(selectedOrderList(res))
            })
            .catch(err => console.log(err))
    }

    redirectToTarget = (id) => {
       return window.location.replace(`#/api/admin/orders/${id}`)
    }

    render () {
        const {classes, selected_ordersIds, data: {id, status}, color, isSelected, selectedOrders, order} = this.props
        const {title, price, pickup, dropoff, payment_type } = this.props.data
        const {colors} = this.state
        console.log(id)
        const isSelect = R.includes(order, selectedOrders)
        console.log(selectedOrders, 'selectedOrders')
        const hightLightStatus = info => {
            const infoArray = R.split('-', info)
            return (
                <div>
                    <span style={{color: colors[status]}}>{R.nth(0, infoArray)}</span>
                    <span style={
                        R.equals(payment_type, 0) ?
                            {color: colors['paymentDone']} :
                            {color: colors['paymentNotDone']}}
                    >{R.nth(1, infoArray)}</span>
                </div>
            )
        }

        return (
            <div style={{borderRight: isSelect && `5px solid ${color}`}} onClick={() => this.handleSelect()} className={`
                ${classes.orderItem}
                ${(R.includes(id, selected_ordersIds)) ? classes.isSelected : ''}`}>
                <div>
                    <span className={classes.orderInfoItem}>{title.row}</span>
                </div>
                <div>
                    {
                        hightLightStatus(price.row)
                    }
                </div>
                <div>
                    <span className={classes.orderInfoItem}>{pickup.row}</span>
                </div>
                <div>
                    <span className={classes.orderInfoItem}>{dropoff.row}</span>
                </div>
                <div className={classes.editOrderWrapper}>
                    {
                        R.equals(status, 'Cancel Request') &&
                        <CanceledPopover id={id}/>
                    }
                    {
                        !R.includes(status, ['Delivered', 'Cancelled', 'Cancel Request']) &&
                        <Button onClick={() => this.redirectToTarget(id)} className={classes.editBtn}>Edit</Button>
                    }
                </div>
            </div>
        )
    }
  }

OrderItem.propTypes = {
    classes: PropTypes.object.isRequired,
    selected_ordersIds: PropTypes.array.isRequired,
    data: PropTypes.object.isRequired,
};

const mapStateToProps = state => {
    return {
        selectedOrders: state.orders.selectedOrders
    }
};

export default compose(
    connect(mapStateToProps),
    withStyles(styles)
)(OrderItem)
