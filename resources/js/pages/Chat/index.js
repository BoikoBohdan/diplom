import React, {Component, Fragment} from 'react'
import './chat.css'
import PropTypes from 'prop-types'
import Echo from 'laravel-echo'
import {compose} from 'recompose'
import {connect} from 'react-redux'
import {withStyles} from '@material-ui/core/styles'
import ChatUserList from './UserList'
import ChatBody from './ChatBody'
import {getAllUsers} from '../../utils'
import {
    addMessage,
    clearMessages,
    fetchChatUsers,
    fetchMessages,
    filterChatUsers
} from '../../store/actions/chat'
import {createRoom, getRoomList} from '../../api/chat'
import * as R from 'ramda'

const styles = theme => ({
    chatMessagesWrapper: {
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
        flexBasis: '70%'
    }
})

class Chat extends Component {
    state = {
        value: '',
        isOpenChat: false,
        selectedUser: {},
        rooms: [],
        selectedChat: ''
    }

    componentDidMount () {
        const token = localStorage.getItem('token')
        const authUserId = localStorage.getItem('id')
        this.props.fetchChatUsers()
        this.getRooms()
        window.io = require('socket.io-client')
        window.Echo = new Echo({
            broadcaster: 'socket.io',
            host: window.location.hostname + ':6001',
            auth: {
                headers: {Authorization: 'Bearer ' + token}
            }
        })
        console.log(12)
        window.Echo.private(`User.${authUserId}`).listen('.room.created', event => {
            console.log('eeeeee2222vent', event)
            this.props.clearMessages([event['last_message']])
            this.getRooms()
        })
    }

    componentDidUpdate (prevProps, prevState, snapshot) {
        const {rooms} = this.state
        const authUserId = localStorage.getItem('id')
        if (R.equals(prevState.rooms, rooms)) {
            return null
        } else {
            console.log('refreshed')
            console.log(window)

            rooms.forEach(room => {
                window.Echo.join(`Chat.${room.id}`)
                    .here(users => {
                        console.log(users)
                    })
                    .joining(user => {
                        console.log(user.name + 'j')
                    })
                    .leaving(user => {
                        console.log(user.name + 'l')
                    })
                    .listen('.message.sent', e => {
                        console.log('123123123123123', e)
                        !R.equals(Number(authUserId), e['sender_id']) &&
                        this.props.addMessage(e)
                    })
            })
        }
    }

    componentWillUnmount () {
        this.props.clearMessages()
    }

    /**
     * Open Chat handler. Create room with selected drivers
     * @param id
     */
    handleShowChat = id => {
        const {users} = this.props
        const {rooms} = this.state

        /**
         * Find user by id helper
         * @type {f1}
         */
        const selectedUser = R.find(R.propEq('id', id), users)

        /**
         * Check the presence of the user in the chat-room helper
         * @param room
         * @returns {f1}
         */
        const includesUserInRoom = room => R.includes(id, R.prop('users', room))

        /**
         * Find chat-room with selected user helper
         * @type {f1}
         */
        const driverRoom = R.find(includesUserInRoom, rooms)

        /**
         * Get room id helper
         * @type {boolean|*}
         */
        const roomId = !R.isNil(driverRoom) && driverRoom['id']
        if (R.includes(driverRoom, rooms)) {
            this.props.fetchMessages(roomId)
        } else {
            createRoom({
                reciever_id: id
            })
            this.props.clearMessages()
        }
        this.setState({
            isOpenChat: true,
            selectedUser: selectedUser
        })
    }

    /**
     * Fetch all chat-roms with drivers
     */
    getRooms = () => {
        getRoomList()
            .then(res => {
                this.setState({
                    rooms: res.data
                })
            })
            .catch(err => console.log(err.response))
    }

    handleCloseChat = () => {
        this.setState({
            isOpenChat: false
        })
        this.props.clearMessages()
    }

    /**
     * Filtering users by input field value
     * @param event
     */
    handleFilterUsers = event => {
        this.props.filterChatUsers(event.target.value)
    }

    render () {
        const {users} = this.props
        const {isOpenChat, selectedUser, rooms, lang, role} = this.state
        return (
            <div className='chat__wrapper'>
                {
                    !R.equals(role, 'super_admin') ? <Fragment>
                            <ChatUserList
                                lang={lang}
                                selectedUser={selectedUser}
                                handleShowChat={this.handleShowChat}
                                drivers={users}
                                rooms={rooms}
                                handleFilterUsers={this.handleFilterUsers}
                            />
                            <ChatBody
                                handleCloseChat={this.handleCloseChat}
                                isOpenChat={isOpenChat}
                                rooms={rooms}
                                selectedUser={selectedUser}
                            />
                        </Fragment>
                        : <div>Permission denied</div>
                }
            </div>
        )
    }
}

Chat.propTypes = {
    users: PropTypes.array.isRequired
}

const mapStateToProps = state => {
    return {
        users: getAllUsers(state),
        lang: state.i18n.messages,
        role: state.login.role
    }
}

export default compose(
    connect(
        mapStateToProps,
        {
            fetchChatUsers,
            filterChatUsers,
            fetchMessages,
            addMessage,
            clearMessages
        }
    ),
    withStyles(styles)
)(Chat)
