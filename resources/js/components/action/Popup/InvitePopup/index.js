import React from 'react'
import Button from '@material-ui/core/Button'
import Popup from 'reactjs-popup'
import {connect} from 'react-redux'
import {ValidatorForm, TextValidator} from 'react-material-ui-form-validator'
import {getResources, showNotification} from 'react-admin'
import {push} from 'react-router-redux'
import './style.css'
import axios from 'axios'
import {stringify} from 'query-string'
import AutoComplete from '../../AutoComplete'
import request from '../../../../api/request'

class InvitePopup extends React.Component {
    state = {
        open: false,
        full_name: '',
        email: '',
        company: ''
    }

    handleChangeFullName = event => {
        const full_name = event.target.value
        this.setState({full_name})
    }

    handleChangeEmail = event => {
        const email = event.target.value
        this.setState({email})
    }

    handleChangeCompany = selected => {
        const company = selected.id
        this.setState({company})
    }

    handleSubmit = () => {
        const {showNotification, lang} = this.props
        let invite = {
            full_name: this.state.full_name,
            email: this.state.email,
            role: this.props.role,
            company: this.state.company
        }
        let options = {
            headers: {
                'content-type': `application/x-www-form-urlencoded`,
                Authorization: `Bearer ${localStorage.getItem('token')}`
            },
            data: stringify(invite),
            method: 'POST'
        }
        let url = '/api/admin/invite'
        if (this.state.full_name || this.state.email) {
            this.setState({error: ''})
            request(url, options)
                .then(responce => {
                    showNotification(lang['The invitation sent successfully!'], 'success')
                    this.setState({
                        full_name: '',
                        email: '',
                        company: ''
                    })
                })
                .catch(error => {
                    let errors = ''
                    for (let text in error.response.data.errors) {
                        errors += error.response.data.errors[text][0]
                    }
                    showNotification(errors, 'warning')
                })
        } else {
            showNotification(lang.invalidForm, 'warning')
        }
    }

    handleClickOpen = () => {
        this.setState(
            {open: true},
            () => (document.body.style.overflow = 'hidden')
        )
    }

    handleClose = () => {
        this.setState(
            {open: false, full_name: '', email: '', company: ''},
            () => (document.body.style.overflow = 'visible')
        )
    }

    render () {
        const {lang} = this.props
        return (
            <React.Fragment>
                <Button
                    variant='contained'
                    component='span'
                    color='primary'
                    onClick={this.handleClickOpen}
                >
                    {`${lang.invite} ${this.props.label}`}
                </Button>
                {this.state.open && (
                    <Popup
                        open
                        onClose={this.handleClose}
                        fullWidth
                        className='dashboard-invite__popup-dialog'
                        aria-labelledby='max-width-dialog-title'
                    >
                        <ValidatorForm
                            className='dashboard-invite__popup'
                            ref='form'
                            onSubmit={this.handleSubmit}
                            onError={errors => console.log(errors)}>
                            <h2 id='simple-dialog-title'> {this.props.label}</h2>
                            <TextValidator
                                label={lang.tableFullName}
                                onChange={this.handleChangeFullName}
                                name='full_name'
                                value={this.state.full_name}
                                validators={[
                                    'required',
                                    'matchRegexp:^[a-zA-Z]{2,200}[ ]{1}[a-zA-Z]{2,200}'
                                ]}
                                errorMessages={[
                                    lang['This field is required'],
                                    lang['Full Name is not valid']
                                ]}
                                className='dashboard-invite__popup-input'
                            />
                            <TextValidator
                                label={lang.tableEmail}
                                onChange={this.handleChangeEmail}
                                name='email'
                                value={this.state.email}
                                type='email'
                                validators={[
                                    'required',
                                    'matchRegexp:^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})'
                                ]}
                                errorMessages={[lang['This field is required'], lang['Email is not valid']]}
                                className='dashboard-invite__popup-input'
                            />
                            {this.props.role === 'admin' && (
                                <AutoComplete selected={this.handleChangeCompany} role='add'/>
                            )}
                            <div className='dashboard-invite__popup-buttons'>
                                <Button
                                    variant='contained'
                                    component='span'
                                    color='primary'
                                    type='submit'
                                    onClick={this.handleSubmit}>
                                    {lang.btnSubmit}
                                </Button>
                                <Button
                                    variant='contained'
                                    component='span'
                                    color='default'
                                    onClick={this.handleClose}
                                >
                                    {lang.btnCancel}
                                </Button>
                            </div>
                        </ValidatorForm>
                    </Popup>
                )}
            </React.Fragment>
        )
    }
}

const mapStateToProps = state => ({
    lang: state.i18n.messages
})

export default connect(
    mapStateToProps,
    {
        showNotification,
        push
    }
)(InvitePopup)
