import React, {Component} from 'react'
import PropTypes from 'prop-types'
import {compose} from 'recompose'
import {connect} from 'react-redux'
import {withStyles} from '@material-ui/core'
import * as R from 'ramda'

const styles = theme => ({
    messagesWrapper: {
        display: 'flex',
        flexDirection: 'column',
        padding: 30,
        overflowX: 'scroll',
    },
    messageItem: {
        minHeight: 60,
        maxWidth: 400,
        padding: 10,
        margin: 10,
        borderRadius: 5,
        marginBottom: 5,
        verticalAlign: 'center'
    },
    imgWrapper: {
        margin: 10,
        maxWidth: 400,
    },
    img: {
        maxWidth: 200,
        padding: 10,
        display: 'block',
    },
    file: {
        padding: 10,
    },
    adminMessage: {
        alignSelf: 'flex-start',
        maxWidth: 400,
        margin: 10,
        background: 'rgba(135, 206, 235, .4)',
        borderRadius: '10px 10px 10px 0px'
    },
    driverMessage: {
        alignSelf: 'flex-end',
        maxWidth: 400,
        margin: 10,
        background: 'rgba(255, 228, 181, .4)',
        borderRadius: '10px 10px 0px 10px'
    },
    createDate: {
        fontSize: 10
    }
});

const MessageItem = ({message, adminId, classes: {messageItem, img, file, imgWrapper, adminMessage, driverMessage, createDate}}) => {
    const pathComponents = R.split('/');
    return (
        <div className={R.equals(adminId, Number(message['sender_id'])) ? adminMessage : driverMessage}>
            {
                R.equals(message.type, 1) &&
                <div className={messageItem}>
                    <div>
                        {message.message}
                    </div>
                    <span className={createDate}>{!R.isNil(message['created_at']) && message['created_at']}</span>
                </div>
            }
            {
                R.equals(message.type, 2) &&
                <div className={imgWrapper}>
                    <div>
                        <img
                            className={img}
                            src={
                                message
                                    ? `${process.env.MIX_PUBLIC_URL}/storage${message.message}`
                                    : ''
                            }
                        />
                    </div>
                    <span className={createDate}>{!R.isNil(message['created_at']) && message['created_at']}</span>
                </div>
            }
            {
                R.equals(message.type, 3) &&
                <div className={file}>
                    <div>
                        <a className={file} href={`${process.env.MIX_PUBLIC_URL}/storage${message.message}`}
                           download>{R.last(pathComponents(message.message))}</a>
                    </div>
                    <span className={createDate}>{!R.isNil(message['created_at']) && message['created_at']}</span>
                </div>
            }
        </div>

    )

};

class ChatMessages extends Component {
    constructor(props) {
        super(props);
        this.myRef = React.createRef();
    }

    componentDidUpdate(prevProps, prevState, snapshot) {
        this.myRef.current.scrollIntoView(false)
    }

    render() {
        const {classes, messages} = this.props;
        const adminId = localStorage.getItem('id');
        return (
            <div ref={this.myRef} className={classes.messagesWrapper}>
                {
                    !R.isEmpty(messages) &&
                    messages.map(message => message &&
                        <MessageItem
                            adminId={Number(adminId)}
                            message={message}
                            classes={classes}
                            key={message.id}/>)
                }
            </div>
        )
    }
}

ChatMessages.propTypes = {
    classes: PropTypes.object.isRequired,
    messages: PropTypes.array.isRequired,
};

MessageItem.propTypes = {
    message: PropTypes.object.isRequired,
    adminId: PropTypes.number.isRequired,
};

const mapDispatchToProps = state => {
    return {
        messages: state.chat.messages
    }
};

export default compose(
    connect(mapDispatchToProps),
    withStyles(styles)
)(ChatMessages)
