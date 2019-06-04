import React, {Component} from 'react'
import PropTypes from 'prop-types'
import {withStyles} from '@material-ui/core/styles'
import Fab from '@material-ui/core/Fab';
import AddIcon from '@material-ui/icons/Add';

const styles = theme => ({
    header: {
        display: 'flex',
        color: 'white',
        justifyContent: 'space-between',
        alignItems: 'center',
        width: '100%',
        // height: '5%',
        backgroundColor: 'rgb(65, 83, 175)'
    },
    title: {
        marginLeft: 20
    },
    fab: {
        margin: theme.spacing.unit,
        transform: 'rotate(45deg)',
        background: 'transparent',
        boxShadow: 'none'
    }
});

class ChatHeader extends Component {
    state = {
        value: ''
    };

    render() {
        const {classes, selectedUser, handleCloseChat} = this.props;
        return (
            <div className={classes.header}>
                <div>
                    <h4 className={classes.title}>{selectedUser.full_name}</h4>
                </div>
                <Fab color="primary" aria-label="Add" onClick={() => handleCloseChat()} className={classes.fab}>
                    <AddIcon/>
                </Fab>
            </div>
        );
    }
}

ChatHeader.propTypes = {
    classes: PropTypes.object.isRequired,
    selectedUser: PropTypes.object,
    handleCloseChat: PropTypes.func.isRequired,
};

export default withStyles(styles)(ChatHeader);
