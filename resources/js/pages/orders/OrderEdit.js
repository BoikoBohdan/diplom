import React from 'react'
import Card from '@material-ui/core/Card'
import {Title} from 'react-admin'
import TextField from '@material-ui/core/TextField'
import dataProvider from '../../components/DataProvider/dataProvider'
import MapDrawRoute from '../../components/action/MapDrawRoute'
import {connect} from 'react-redux'
import {showNotification} from 'react-admin'
import Button from '@material-ui/core/Button'
import AddressWithPoints from '../../components/AutoSaveFields/addressWithPoints'
import './style.css'
import * as R from 'ramda'
import {setActiveAddressField, setSearcDropoffhAddress, setSearcPickuphAddress} from '../../store/actions/orders'

require('dotenv').config()

class OrderEdit extends React.Component {
    state = {
        data: {},
        pickupAddress: '',
        dropOffAddress: '',
        address: '',
        active: ''
    }

    componentDidMount () {
        dataProvider('GET_ONE', `${this.props.resource}`, {
            id: this.props.id
        })
            .then(res => {
                this.setState({
                    data: res.data
                })
                this.props.setSearcPickuphAddress(res.data['pickup'].location['streetaddress'])
                this.props.setSearcDropoffhAddress(res.data['dropoff'].location['streetaddress'])
            })
            .catch(err => console.log(err))
    }

    handleChange = address => {
        R.equals(this.state.active, 'pickup') ?
            this.setState({
                pickupAddress: address
            }) : this.setState({
                dropOffAddress: address
            })
    }

    handleSetActive = name => {
        !R.isEmpty(name) ? this.setState({
            active: name
        }) : this.setState({
            active: ''
        })
    }

    render () {
        const {data, active} = this.state
        const {lang} = this.props
        return (
            <Card className='driver__cards'>
                <Title title={lang.ordersEditTitle}/>
                {
                    !R.isEmpty(data) &&
                    <div className='simple-autosubmit__list'>
                        <div className={'topLine'}>
                            <h2>{lang.ordersInfoTitle}</h2>
                        </div>
                        <div className='simple-autosubmit__row'>
                            <div className={'textField'}>
                                <TextField
                                    disabled
                                    className={'field'}
                                    id='standard-disabled__small'
                                    label={lang.orderLabelDropOffTime}
                                    value={
                                        data['dropoff_time_from']
                                            ? data['dropoff_time_from']
                                            : ''
                                    }
                                    margin='normal'
                                />
                            </div>
                            <div className={'textField'}>
                                <TextField
                                    disabled
                                    className={'field'}
                                    id='standard-disabled'
                                    label={lang.tableReference}
                                    value={
                                        this.state.data['reference_id'] ? this.state.data['reference_id'] : ''
                                    }
                                    margin='normal'
                                />
                            </div>
                            <div className={'textField'}>
                                <TextField
                                    disabled
                                    className={'field'}
                                    id='standard-disabled'
                                    label={lang.orderLabelPickupDate}
                                    value={
                                        data['pickup'].date ? data['pickup'].date : ''
                                    }
                                    margin='normal'
                                />
                            </div>
                            <div className={'textField'}>
                                <TextField
                                    disabled
                                    className={'field'}
                                    id='standard-disabled'
                                    label={lang.orderLabelDropOffDate}
                                    value={
                                        data['dropoff'].date ? data['dropoff'].date : ''
                                    }
                                    margin='normal'
                                />
                            </div>
                            <div className={'textField'}>
                                <TextField
                                    disabled
                                    className={'field'}
                                    id='standard-disabled__small'
                                    label={lang.orderLabelPaymentType}
                                    value={
                                        data['payment_type'] ? data['payment_type'] : ''
                                    }
                                    margin='normal'
                                />
                            </div>
                            <div className={'textField'}>
                                <TextField
                                    disabled
                                    className={'field'}
                                    id='standard-disabled'
                                    label={lang.orderLabelAsap}
                                    value={data.asap ? data.asap : ''}
                                    margin='normal'
                                />
                            </div>
                            <div className={'textField'}>
                                <TextField
                                    disabled
                                    className={'field'}
                                    id='standard-disabled'
                                    label={lang.orderLabelPaymentInfo}
                                    value={
                                        data['payment_info'] ? data['payment_info'] : ''
                                    }
                                    margin='normal'
                                />
                            </div>
                            <div className={'textField'}>
                                <TextField
                                    disabled
                                    className={'field'}
                                    id='standard-disabled'
                                    label={lang.orderLabelPickupNote}
                                    value={
                                        data['pickup']['notes'] ? data['pickup']['notes'] : ''
                                    }
                                    margin='normal'
                                />
                            </div>
                            <div className={'textField'}>
                                <TextField
                                    disabled
                                    className={'field'}
                                    id='standard-disabled'
                                    label={lang.orderLabelDropOffNote}
                                    value={
                                        data['dropoff']['notes'] ? data['dropoff']['notes'] : ''
                                    }
                                    margin='normal'
                                />
                            </div>
                        </div>
                        <div className='simple-autosubmit__list'>
                            <h2>{lang.ordersPickUpTitle}</h2>
                            <div className='simple-autosubmit__row'>
                                <div className={'textField'}>
                                    <TextField
                                        disabled
                                        className={'field'}
                                        id='standard-disabled'
                                        label={lang.tableReference}
                                        value={
                                            data['reference_id']
                                                ? data['reference_id']
                                                : ''
                                        }
                                        margin='normal'
                                    />
                                </div>
                                <div className={'textField'}>
                                    <TextField
                                        disabled
                                        className={'field'}
                                        id='standard-disabled'
                                        label={lang.orderLabelProductName}
                                        value={
                                            data['restaurant_name']
                                                ? data['restaurant_name']
                                                : ''
                                        }
                                        margin='normal'
                                    />
                                </div>
                                <div className={'textField'}>
                                    <TextField
                                        disabled
                                        className={'field'}
                                        id='standard-disabled'
                                        label={lang.tablePhone}
                                        value={
                                            data['pickup'].phone
                                                ? data['pickup'].phone
                                                : ''
                                        }
                                        margin='normal'
                                    />
                                </div>
                                <div className={'textField'}>
                                    <TextField
                                        disabled
                                        className={'field'}
                                        id='standard-disabled'
                                        label={lang.tableCity}
                                        value={
                                            data['pickup'].location.city
                                                ? data['pickup'].location.city
                                                : ''
                                        }
                                        margin='normal'
                                    />
                                </div>
                                <div className={'textField'}>
                                    <TextField
                                        disabled
                                        className={'field'}
                                        id='standard-disabled'
                                        label={lang.orderLabelCountryCode}
                                        value={
                                            data['pickup'].location['country_code']
                                                ? data['pickup'].location['country_code']
                                                : ''
                                        }
                                        margin='normal'
                                    />
                                </div>
                                <div className={'textField'}>
                                    <TextField
                                        disabled
                                        className={'field'}
                                        id='standard-disabled'
                                        label={lang.orderLabelPostCode}
                                        value={
                                            data['restaurant_postcode']
                                                ? data['restaurant_postcode'].toString()
                                                : ''
                                        }
                                        margin='normal'
                                    />
                                </div>
                                <div className={'textField'}>
                                    <TextField
                                        disabled
                                        className={'field'}
                                        id='standard-disabled'
                                        label={lang.orderLabelContactName}
                                        value={
                                            data['pickup']['contact_name']
                                                ? data['pickup']['contact_name']
                                                : ''
                                        }
                                        margin='normal'
                                    />
                                </div>
                                <div className={'textField'}>
                                    <TextField
                                        disabled
                                        className={'field'}
                                        id='standard-disabled'
                                        label={lang.orderLabelPickupNote}
                                        value={
                                            data['pickup']['notes']
                                                ? data['pickup']['notes']
                                                : ''
                                        }
                                        margin='normal'
                                    />
                                </div>
                            </div>
                            <div className='simple-autosubmit__row'>
                                <div className={'textField'}>
                                    <AddressWithPoints
                                        resource={`api/admin/orders`}
                                        handleSetActive={() => this.props.setActiveAddressField('pickup')}
                                        id={data.id}
                                        address={this.props.searchPickupAddress}
                                        margin="normal"
                                        field='streetaddress' //address
                                        type="0"
                                        lang={lang}
                                        label={lang.orderLabelSearchAddress}
                                        secondError={lang['Address is invalid']}
                                        success={lang['Address save successfully']}
                                        defaultValue={
                                            data['pickup'].location['streetaddress']
                                                ? data['pickup'].location['streetaddress']
                                                : ''
                                        }
                                    />
                                </div>
                            </div>
                        </div>
                        <MapDrawRoute
                            lang={lang}
                            resource={`api/admin/orders`}
                            active={active}
                            success={lang['Address save successfully']}
                            changeAddress={this.handleChange}
                            data={data}/>
                        <div className='simple-autosubmit__list'>
                            <h2>{lang.ordersDropOffTitle}</h2>
                            <div className='simple-autosubmit__row'>
                                <div className={'textField'}>
                                    <TextField
                                        disabled
                                        className={'field'}
                                        label={lang.tableReference}
                                        value={
                                            data['dropoff'].reference
                                                ? data['dropoff'].reference
                                                : ''
                                        }
                                        margin='normal'
                                    />
                                </div>
                                <div className={'textField'}>
                                    <TextField
                                        className={'field'}
                                        disabled
                                        label={lang.tableCity}
                                        value={
                                            data['dropoff'].location.city
                                                ? data['dropoff'].location.city
                                                : ''
                                        }
                                        margin='normal'
                                    />
                                </div>
                                <div className={'textField'}>
                                    <TextField
                                        disabled
                                        className={'field'}
                                        label={lang.orderLabelCountryCode}
                                        value={
                                            data['dropoff'].location['country_code']
                                                ? data['dropoff'].location['country_code']
                                                : ''
                                        }
                                        margin='normal'
                                    />
                                </div>
                                <div className={'textField'}>
                                    <TextField
                                        disabled
                                        className={'field'}
                                        label={lang.orderLabelPostCode}
                                        value={
                                            data['dropoff'].location['post_code']
                                                ? data['dropoff'].location['post_code']
                                                : ''
                                        }
                                        margin='normal'
                                    />
                                </div>
                                <div className={'textField'}>
                                    <TextField
                                        disabled
                                        className={'field'}
                                        label={lang.orderLabelContactName}
                                        value={
                                            data['dropoff']['contact_name']
                                                ? data['dropoff']['contact_name']
                                                : ''
                                        }
                                        margin='normal'
                                    />
                                </div>
                                <div className={'textField'}>
                                    <TextField
                                        disabled
                                        className={'field'}
                                        label={lang.orderLabelDropOffNote}
                                        value={
                                            data['dropoff']['notes']
                                                ? data['dropoff']['notes']
                                                : ''
                                        }
                                        margin='normal'
                                    />
                                </div>
                                <div className={'textField'}>
                                    <TextField
                                        disabled
                                        className={'field'}
                                        id='standard-disabled'
                                        label={lang.tablePhone}
                                        value={
                                            data['dropoff'].phone
                                                ? data['dropoff'].phone
                                                : ''
                                        }
                                        margin='normal'
                                    />
                                </div>
                                <div className={'textField'}>
                                    <AddressWithPoints
                                        resource={`api/admin/orders`}
                                        id={data.id}
                                        margin={'normal'}
                                        address={this.props.searchDropoffAddress}
                                        handleSetActive={() => this.props.setActiveAddressField('dropoff')}
                                        field='streetaddress'
                                        type='1'
                                        lang={lang}
                                        label={lang.orderLabelSearchAddress}
                                        secondError={lang['Address is invalid']}
                                        success={lang['Address save successfully']}
                                        defaultValue={
                                            data['dropoff'].location['streetaddress']
                                                ? data['dropoff'].location['streetaddress']
                                                : ''
                                        }
                                    />
                                </div>
                            </div>
                        </div>
                        <div className='simple-autosubmit__list'>
                            <h2>{lang.ordersProductTitle}</h2>
                            {data.products &&
                            data.products.map((item, index) => {
                                return (
                                    <div className='simple-autosubmit__row' key={index}>
                                        <TextField
                                            disabled
                                            id='standard-disabled'
                                            label={lang.orderLabelProductName}
                                            value={item.name ? item.name : ''}
                                            margin='normal'
                                        />
                                        <div className='simple-autosubmit__spacer'/>
                                        <TextField
                                            disabled
                                            id='standard-disabled'
                                            label={lang.orderLabelProductQuantity}
                                            value={item.quantity ? item.quantity : ''}
                                            margin='normal'
                                        />
                                        <div className='simple-autosubmit__spacer'/>
                                        <TextField
                                            disabled
                                            id='standard-disabled'
                                            label={lang.orderLabelProductFee}
                                            value={item.fee ? item.fee : ''}
                                            margin='normal'
                                        />
                                    </div>
                                )
                            })}
                        </div>
                    </div>
                }
            </Card>
        )
    }
}

const mapStateToProps = state => ({
    lang: state.i18n.messages,
    searchPickupAddress: state.orders.searchPickupAddress,
    searchDropoffAddress: state.orders.searchDropoffAddress,
})

const mapDispatchToProps = dispatch => {
    return {
        showNotification: (mes) => dispatch(showNotification(mes)),
        setActiveAddressField: (field) => dispatch(setActiveAddressField(field)),
        setSearcPickuphAddress: (address) => dispatch(setSearcPickuphAddress(address)),
        setSearcDropoffhAddress: (address) => dispatch(setSearcDropoffhAddress(address)),
    }
}

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(OrderEdit)
