import React, {Component} from 'react'
import {connect} from 'react-redux'
import {compose} from 'recompose'
import { withStyles } from '@material-ui/core/styles'
import blue from '@material-ui/core/colors/blue'
import {assignRequest} from "../../../../store/actions/orders"
import * as R from "ramda"
import Button from '@material-ui/core/Button';
import ChatLink from '../../../../components/action/ChatLink'

const styles = {
    driverItem: {
        wordBreak: 'break-all',
        borderBottom: '1px solid rgba(0,0,0, .4)'
    },
    button: {
        padding: 0,
        color: blue[300],
        cursor: 'pointer',
        fontSize: 11
    },
    isSelected: {
        backgroundColor: '#EDEDED'
    },
    driverInfoItem: {
        display: 'block',
    }
  }

class DriverItem extends Component {
    state = {
        open: true,
        Transition: null,
        isSelected: false
    }

    render () {
        const { classes, assignRequest, selected_ordersIds, selected_driversIds, lang, role} = this.props
        const { location, stops, title, id } = this.props.data
        return (
            <div className={`${R.includes(id, selected_driversIds) ? classes.isSelected : ''} ${classes.driverItem}`}>
                <div>
                    <span className={classes.driverInfoItem}>{title.row}</span>
                </div>
                <div>
                    <span className={classes.driverInfoItem}>{location.row}</span>
                </div>
                <div>
                    <span className={classes.driverInfoItem}>{stops.row}</span>
                </div>
                <ChatLink size="small">{lang.btnChat}</ChatLink>
                <Button className={classes.button}
                        size="small"
                        disabled={R.equals(role, 'super_admin')}
                        onClick={() => assignRequest({
                            drivers: [id],
                            orders: selected_ordersIds
                        })
                    }>{lang.btnAssignOrder}</Button>
            </div>
        )
    }
  }

const mapStateToProps = state => {
    return {
        message: state.orders,
        lang: state.i18n.messages,
        role: state.login.role
    }
}

export default compose(
  connect(mapStateToProps, {assignRequest}),
  withStyles(styles)
)(DriverItem)
