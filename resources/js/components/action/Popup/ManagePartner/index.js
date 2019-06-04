import React from 'react'
import Button from '@material-ui/core/Button'
import Popup from 'reactjs-popup'
import {ValidatorForm} from 'react-material-ui-form-validator'
import './style.css'
import axios from 'axios'
import {stringify} from 'query-string'
import {connect} from 'react-redux'
import {showNotification} from 'react-admin'
import AutoComplete from '../../AutoComplete'
import './style.css'
import request from '../../../../api/request'

export class ManagePartner extends React.Component {
    state = {
        open: false,
        company_name: '',
        refresh: false
    }

    handleChangeName = value => {
        this.setState({company_name: value})
    }

    handleFinishRefresh = () => {
        this.setState({refresh: false})
    }

    handleSubmit = () => {
        const {showNotification, lang} = this.props
        let company = {name: this.state.company_name}
        let options = {
            headers: {
                'content-type': `application/x-www-form-urlencoded`,
                Authorization: `Bearer ${localStorage.getItem('token')}`
            },
            data: stringify(company),
            method: 'POST'
        }
        let url = '/api/admin/companies'
        if (this.state.company_name) {
            this.setState({error: ''})
            request(url, options)
                .then(responce => {
                    showNotification(lang.newCompanyAdded)
                    this.setState({
                        company_name: '',
                        refresh: true
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
            {open: false, company_name: ''},
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
                    {this.props.label}
                </Button>
                {this.state.open && (
                    <Popup
                        open
                        onClose={this.handleClose}
                        className='dashboard-invite__popup-dialog'
                    >
                        <ValidatorForm
                            className='dashboard-invite__popup'
                            ref='form'
                            onSubmit={this.handleSubmit}
                            onError={errors => console.log(errors)}
                        >
                            <h2 id='simple-dialog-title'>{this.props.label}</h2>
                            <AutoComplete
                                refresh={this.state.refresh}
                                onRefreshFinish={this.handleFinishRefresh}
                                ref={instance => {
                                    this.child = instance
                                }}
                                label={lang.addNewCompany}
                                role='new'
                                changeName={this.handleChangeName}
                            />
                            <div className='dashboard-invite__popup-buttons'>
                                <Button
                                    variant='contained'
                                    component='span'
                                    color='primary'
                                    type='submit'
                                    onClick={this.handleSubmit}
                                >
                                    {lang.btnAdd}
                                </Button>
                                <Button
                                    variant='contained'
                                    component='span'
                                    color='default'
                                    onClick={this.handleClose}
                                >
                                    {lang.btnClose}
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
        showNotification
    }
)(ManagePartner)
