import React, {useRef} from 'react';
import { connect } from 'react-redux';
import { Responsive, userLogout } from 'react-admin';
import {compose} from 'recompose'
import Button from '@material-ui/core/Button';
import ExitIcon from '@material-ui/icons/PowerSettingsNew';
import {logout} from '../../store/actions/login'
import { withStyles } from '@material-ui/core/styles'

const styles = theme => ({
    button: {
        display: 'flex',
        padding: '17px',
        height: '50px',
        width: '100%'
    }
})

const LogoutButton = ({ logout, classes, lang }) => {
    return (
        <Responsive
            medium={
                <button
                    className='logoutButton'
                    onClick={logout}>
                    <ExitIcon/> {lang.logout}
                </button>
            }
        />
    );
};

const mapStateToProps = state => {
    return {
        lang: state.i18n.messages
    }
}

export default compose(
    withStyles(styles),
    connect(mapStateToProps, { logout: logout })
)(LogoutButton)
