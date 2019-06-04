import React, { Component } from 'react'
import PropTypes from 'prop-types';
import { MuiThemeProvider } from '@material-ui/core/styles'
import TextField from '@material-ui/core/TextField'
import { withStyles, createMuiTheme } from '@material-ui/core/styles'
import { compose } from 'recompose'
import {connect} from 'react-redux'
import { authorize } from '../../store/actions/login'
import Button from '@material-ui/core/Button'
import {validateEmail} from '../../utils/validate'
import SimpleSnackbar from '../../components/Snackbar'
import * as R from 'ramda'

const theme = createMuiTheme({
    typography: {
        useNextVariants: true,
        suppressDeprecationWarnings: true
    }
});

const styles = theme => ({
    authWrapper: {
        backgroundImage: `url("/images/auth_background.jpg")`,
        backgroundRepeat: 'no-repeat',
        backgroundSize: 'cover'
    },
    fieldsWrapper: {
        background: 'white',
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        padding: 20,
        borderRadius: 5,
        backgroundColor: 'rgba(255,255,255, .97)' 
    },
    container: {
        display: 'flex',
        flexDirection: 'column',
        justifyContent: 'center',
        alignSelf: 'center',
        flexWrap: 'wrap',
        width: '400px',
        margin: '0 auto',
        height: '100vh'
    },
    textField: {
        marginLeft: theme.spacing.unit,
        marginRight: theme.spacing.unit,
        width: '90%',
    },
    button: {
        margin: theme.spacing.unit,
    },
    notValid: {
        backgroundColor: 'rgba(255,0,0, .3)',
        color: 'red'
    },
    error: {
        backgroundColor: theme.palette.error.dark,
    },
});

class MyLoginPage extends Component {

    state = {
        email: '',
        password: '',
        isValidEmail: false
    };

    /**
     * @param {string} name
     * @returns {Function}
     */
    handleChange = name => event => {
        this.setState({ [name]: event.target.value });
    };

    /**
     * @param {string} name
     * @returns {Function}
     */
    handleEmailChange = name => event => {
        this.setState({
            isValidEmail: validateEmail(event.target.value),
            [name]: event.target.value
        });
    };

    submit = e => {
        e.preventDefault();
        const { email, password } = this.state

        // Dispatch the authorize action (injected by connect)
        this.props.dispatch(authorize(email, password))
    }

    render() {
        const {classes, error} = this.props;
        const {isValidEmail} = this.state;
        return (
            <MuiThemeProvider theme={theme}>
            <div className={classes.authWrapper}>
            <form className={classes.container} onSubmit={this.submit}>
                <div className={classes.fieldsWrapper}>
                    <TextField
                        id="standard-name"
                        label="Email"
                        className={`${classes.textField} ${!isValidEmail && 'notValid'}`}
                        value={this.state.email}
                        onChange={this.handleEmailChange('email')}
                        margin="normal"/>
                    <TextField
                        id="standard-uncontrolled"
                        label="Password"
                        type="password"
                        value={this.state.password}
                        onChange={this.handleChange('password')}
                        className={classes.textField}
                        margin="normal"/>
                    <Button color="primary" type="submit" className={classes.button} onSubmit={(e) => this.submit(e)}>
                        Sign In
                    </Button>
                </div>
                </form>
            </div>
            <SimpleSnackbar
                message={'Incorrect login or password'}
                undo={false}
                styles={classes.error}
                open={!R.isEmpty(error)}
                closable={false}/>
            </MuiThemeProvider>
        );
    }
}

MyLoginPage.propTypes = {
    classes: PropTypes.object,
    isValidEmail: PropTypes.bool
};

const mapStateToProps = state => ({
    token: state.login.token,
    error: state.login.error
});

export default compose(
    withStyles(styles),
    connect(mapStateToProps)
)(MyLoginPage);
