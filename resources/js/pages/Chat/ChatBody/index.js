import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { withStyles } from '@material-ui/core/styles'
import ChatHeader from './header'
import ChatFooter from './footer'

const styles = theme => ({
    isNotShowChat: {
        width: 0,
        opacity: 0
    }
})

class ChatBody extends Component {
    state = {
        value: '',
    }
    render () {
        const {classes, isOpenChat, handleCloseChat, selectedUser, rooms} = this.props
        return (
        <div className={isOpenChat ? 'chat__body' : classes.isNotShowChat}>
            <ChatHeader handleCloseChat={handleCloseChat} selectedUser={selectedUser}/>
            <ChatFooter selectedUser={selectedUser} rooms={rooms}/>
        </div>
        );
    }
}

ChatBody.propTypes = {
    classes: PropTypes.object.isRequired,
    rooms: PropTypes.array.isRequired,
    isOpenChat: PropTypes.bool.isRequired,
    selectedUser: PropTypes.object,
    handleCloseChat: PropTypes.func.isRequired,
};


export default withStyles(styles)(ChatBody);
