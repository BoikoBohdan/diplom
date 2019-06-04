import React, {useState} from 'react'
import {compose} from 'recompose'
import {connect} from 'react-redux'
import PropTypes from 'prop-types'
import {withStyles} from '@material-ui/core/styles'
import TextField from '@material-ui/core/TextField'
import Button from '@material-ui/core/Button'
import ChatMessages from '../messages'
import {createMessage, createMessageWithFile} from '../../../../api/chat'
import {addMessage} from '../../../../store/actions/chat'
import IconButton from '@material-ui/core/IconButton'
import DeleteIcon from '@material-ui/icons/Delete'
import PhotoCamera from '@material-ui/icons/PhotoCamera'
import * as R from 'ramda'
import {getAllUsers} from '../../../../utils'

const styles = theme => ({
    footer: {
        display: 'flex',
        width: '100%',
        flexDirection: 'column',
        justifyContent: 'space-between',
    },
    messages: {
        height: '80%',
        overflowY: 'scroll'
    },
    sendMessageWrapper: {
        width: '100%',
        height: 100,
        display: 'flex',
        flexDirection: 'row',
        alignItems: 'center',
        alignSelf: 'flex-start'
    },
    textField: {
        margin: theme.spacing.unit,
        width: '100%',
        height: '100%'
    },
    button: {
        margin: theme.spacing.unit,
        width: '18%',
        height: '95%',
        marginBottom: 15
    },
    input: {
        display: 'none'
    },
    sendFileWrapper: {
        width: '100%',
        display: 'flex',
        flexDirection: 'row',
        justifyContent: 'space-between',
        paddingRight: 10,
        paddingLeft: 10
    },
    fileInfo: {
        width: '70%',
        display: 'flex',
        alignItems: 'center',
        paddingLeft: 10,
    },
    infoText: {
        display: 'inline-block',
        padding: '0 10px',
        borderRadius: 10,
        backgroundColor: 'lightGray',
        border: '1px solid gray'
    }
});

const ChatFooter = ({classes, selectedUser, rooms, addMessage, lang}) => {
    const [message, setMessage] = useState('');
    const [fileInfo, setFileInfo] = useState({});
    const [file, setFile] = useState('');
    const {id} = selectedUser;
    const includesUserInRoom = room => R.includes(id, R.prop('users', room));
    const driverRoom = R.find(includesUserInRoom, rooms);
    const roomId = !R.isNil(driverRoom) && driverRoom.id;
    const handleChange = (event) => {
        setMessage(event.target.value)
    };

    const handleSendMessage = () => {
        createMessage({
            reciever_id: id,
            message,
            chat_room_id: roomId
        })
            .then(res => {
                setMessage('');
                addMessage(res.data)
            })
            .catch(err => console.log(err.response))
    };

    const handleSendFile = () => {
        !R.isEmpty(file) && createMessageWithFile({
            file: file,
            chat_room_id: roomId
        })
            .then(res => {
                setFileInfo('');
                setFile('');
                addMessage(res.data)
            })
            .catch(err => console.log(err))

    };

    const handleDeleteFile = () => {
        setFileInfo('');
        setFile('')
    };


    const handleCapture = ({target}) => {
        const fileReader = new FileReader();
        const {name, size, type} = target.files[0];
        fileReader.readAsDataURL(target.files[0]);
        setFileInfo({
            name,
            size,
            type
        });
        fileReader.onload = (e) => {
            setFile(e.target['result'])
        }
    };

    return (
        <div className={classes.footer}>
            <div className={classes.messages}>
                <ChatMessages/>
            </div>
            <div>
                <div className={classes.sendFileWrapper}>
                    <div className={classes.fileInfo}>
                        {
                            !R.isEmpty(file) &&
                            <div>
                                <span className={classes.infoText}>{fileInfo.name}</span>
                                <IconButton aria-label="Delete" color="primary" onClick={handleDeleteFile}>
                                    <DeleteIcon/>
                                </IconButton>
                            </div>
                        }
                    </div>
                    {
                        R.isEmpty(file) && <input
                            accept=".doc,.docx,.csv,.xlsx,.txt,.ppt,.zip,.rar,image/*"
                            className={classes.input}
                            id="icon-button-photo"
                            onChange={handleCapture}
                            type="file"
                        />
                    }
                    <label htmlFor="icon-button-photo">
                        {
                            !R.isEmpty(file) &&
                            <div>
                                <Button variant="contained" color="primary" onClick={handleSendFile}>
                                    {lang.btnSendFile}
                                </Button>
                            </div>
                        }
                        {
                            R.isEmpty(file) &&
                            <IconButton color="primary" component="span">
                                <PhotoCamera/>
                            </IconButton>
                        }
                    </label>
                </div>
                <div className={classes.sendMessageWrapper}>
                    <TextField
                        id="outlined-multiline-flexible"
                        label={lang.message}
                        multiline
                        rows="3"
                        value={message}
                        onChange={handleChange}
                        className={classes.textField}
                        variant="outlined"
                    />
                    <Button variant="contained" color="primary" className={classes.button} onClick={handleSendMessage}>
                        {lang.btnSend}
                    </Button>
                </div>
            </div>

        </div>
    )
};

ChatFooter.propTypes = {
    classes: PropTypes.object.isRequired,
    rooms: PropTypes.array.isRequired,
    selectedUser: PropTypes.object
};

const mapStateToProps = state => {
    return {
        lang: state.i18n.messages
    }
};

export default compose(
    connect(mapStateToProps, {addMessage}),
    withStyles(styles)
)(ChatFooter)
