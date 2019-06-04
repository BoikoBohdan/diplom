import React from 'react'
import blue from '@material-ui/core/colors/blue'
import { withStyles } from '@material-ui/core/styles'
import Button from '@material-ui/core/Button'
import {assignOrder} from "../../../api/orders"
import { showNotification } from 'react-admin'
import * as R from 'ramda'
const styles = theme => ({
    button: {
        color: blue[300],
        cursor: 'pointer',
        margin: 0,
        padding: 0
    },
});

const AssignOrderBtn = (props) => {
    const {classes, selected_ordersIds, id, role} = props

    const handleAssign = (id, orders) => {
        assignOrder({
            drivers: [id],
            orders
        })
        .then((res) => {
            showNotification(props.lang.success)
        })
        .catch(e => console.log(e))
    }
    return (
        <div>
            <Button
                size="small"
                color="primary"
                disabled={R.equals(role, 'super_admin')}
                className={classes.button}
                onClick={() => handleAssign(id, selected_ordersIds)}>
                {props.lang.btnAssignOrder}
            </Button>
        </div>
    )
}

export default withStyles(styles)(AssignOrderBtn)
