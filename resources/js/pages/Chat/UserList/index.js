import React, {useEffect} from 'react'
import PropTypes from 'prop-types'
import {connect} from 'react-redux'
import {compose} from 'recompose'
import { withStyles } from '@material-ui/core/styles'
import ChatUserItem from '../UserItem'
import Paper from '@material-ui/core/Paper'
import InputBase from '@material-ui/core/InputBase'
import IconButton from '@material-ui/core/IconButton'
import SearchIcon from '@material-ui/icons/Search'
import * as R from 'ramda'

const styles = theme => ({
    root: {
        margin: '0 auto',
        display: 'flex',
        alignItems: 'center',
        alignSelf: 'center',
        boxShadow: 'none',
        border: '1px solid rgba(123, 123, 123, .2)'
      },
      input: {
        marginLeft: 8,
        flex: 1,
        height: 30,
      },
      iconButton: {
        padding: 10,
      }
})

const SearchField = ({classes, handleFilterUsers, lang}) => {
    return (
        <Paper className={classes.root} elevation={1}>
            <InputBase className={classes.input} placeholder={lang.searchMessageOrName} onChange={() => handleFilterUsers(event)} />
                <IconButton className={classes.iconButton} aria-label="Search">
                <SearchIcon />
            </IconButton>
        </Paper>
    )
}

const ChatUserList = ({classes, drivers, handleShowChat, handleFilterUsers, rooms, selectedUser, lang}) => {
    const driversList = drivers.map(driver =>
        <ChatUserItem
            selectedUser={selectedUser}
            handleShowChat={handleShowChat}
            driver={driver}
            rooms={rooms}
            key={driver.id} />)
    return (
        <div>
        <SearchField lang={lang} handleFilterUsers={handleFilterUsers} classes={classes} />
        <div className="user_list__wrapper">
            {
                !R.isEmpty(drivers) ? driversList : <div style={{margin: 10}}>No data...</div>
            }
        </div>
        </div>
    )
}

ChatUserList.propTypes = {
    classes: PropTypes.object.isRequired,
    handleFilterUsers: PropTypes.func.isRequired,
};

const mapStateToProps = state => ({
    lang: state.i18n.messages
})

export default compose(
    connect(mapStateToProps),
    withStyles(styles)
)(ChatUserList)
