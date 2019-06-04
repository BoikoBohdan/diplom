import React from 'react'
import {withStyles} from '@material-ui/core/styles'
import PropTypes from 'prop-types'
import * as R from 'ramda'
import avatars from '../img/avatars.png'

const styles = theme => ({
    avatar: {
        width: 60,
        marginLeft: 20,
        marginRight: 20,
    },
    infoWrapper: {
        display: 'flex',
        flexDirection: 'column',
        justifyContent: 'space-between',
        width: '60%',
        height: 60,
        fontSize: 11
    },
    infoTopline: {
        display: 'flex',
        flexDirection: 'row',
        justifyContent: 'space-between'
    },
    infoitem: {
        display: 'block'
    }
});

const ChatUserItem = ({classes, driver, handleShowChat, rooms, selectedUser}) => {
    const formatDate = date => {
        return date ? new Date(date).toDateString() : null
    };
    const includesUserInRoom = room => R.includes(driver.id, R.prop('users', room));
    const driverRoom = R.find(includesUserInRoom, rooms);
    const message = !R.isNil(driverRoom) && driverRoom['last_message'];

    return (
        <div className={`user_list__item ${R.equals(selectedUser.id, driver.id) && 'active_chat'}`} onClick={() => handleShowChat(driver.id)}>
            <img className={classes.avatar} src={avatars}/>
            <div className={classes.infoWrapper}>
                <div className={classes.infoTopline}>
                    <div>{driver.full_name}</div>
                    <div>{!R.isNil(message) ? formatDate(message['created_at']) : ''}</div>
                </div>
                <div className={classes.infoTopline}>
                    <div>{!R.isNil(message) && message.message}</div>
                </div>
            </div>
        </div>
    )
};

ChatUserItem.propTypes = {
    classes: PropTypes.object.isRequired,
    driver: PropTypes.object.isRequired,
    rooms: PropTypes.array.isRequired,
    handleShowChat: PropTypes.func.isRequired,
};

export default withStyles(styles)(ChatUserItem)
