import React, {Component} from 'react'
import {withStyles} from '@material-ui/core/styles'
import * as R from 'ramda'
import {connect} from 'react-redux'
import {compose} from 'recompose'
import {Title} from 'react-admin'
import Card from '@material-ui/core/Card'
import TextField from '@material-ui/core/TextField'
import Products from '../../components/action/OdrerCreateProducts'
import Address from '../../components/AutoSaveFields/address'
import Button from '@material-ui/core/Button'
import {createOrders} from '../../api/orders'
import {handleFormatDate} from '../../utils'
import NotificationSystem from 'react-notification-system'
import {styleNotification} from '../../utils/styles'
import MaskedInput from 'react-text-mask'
import Input from '@material-ui/core/Input'
import InputLabel from '@material-ui/core/InputLabel'

const styles = theme => ({
    container: {
        display: 'flex',
        flexDirection: 'column'
    },
    textFieldWrapper: {
      display: 'flex',
      justifyContent: 'flex-start',
      flexWrap: 'wrap'
    },
    textField: {
        // marginRight: 20,
        // minWidth: '18%'
        marginTop: 0,
        width: '100%',
        height: 40
    },
    testArea: {
        marginTop: 0,
        width: '100%'
    },
    sectionWrapper: {
        marginRight: 20,
        minWidth: '18%'
    },
    textFieldWrapp: {
        minWidth: '18%',
        marginRight: 20,
        marginBottom: 40,
    },
    required: {
        color: 'red'
    },
    notRequired: {
        color: '#fff'
    }
})

const TextMaskCustom = (props) => {
    const { inputRef, ...other } = props;
    return (
        <MaskedInput
            {...other}
            ref={ref => {
                inputRef(ref ? ref.inputElement : null);
            }}
            mask={['+', /\d/,/\d/, ' ',  '(', /[1-9]/, /\d/, /\d/, ')', ' ', /\d/, /\d/, /\d/, '-', /\d/, /\d/, '-', /\d/, /\d/]}
            // placeholderChar={'\u2000'}
            showMask
        />
    );
}


const TimeMaskCustom = (props) => {
    const { inputRef, ...other } = props;
    return (
        <MaskedInput
            {...other}
            ref={ref => {
                inputRef(ref ? ref.inputElement : null);
            }}
            mask={[/[0-2]/,/\d/, ':', /[0-5]/, /\d/, ':', /[0-5]/, /\d/]}
            // placeholderChar={'\u2000'}
            showMask
        />
    );
}


class OrderCreate extends Component {

    state = {
        reference: '',
        pickupTimeFrom: '00:00:00',
        dropoffTimeFrom: '00:00:00',
        pickupdate: handleFormatDate(new Date()),
        dropoffdate: handleFormatDate(new Date()),
        paymentType: '',
        shipmentType: '',
        fee: '',
        paymentInfo: '',
        pickupNote: '',
        dropoffNote: '',
        pickupReference: '',
        pickupName: '',
        pickupPhone: '+41(  )    -    ',
        pickupCity: '',
        pickupCountryCode: '',
        pickupPostCode: '',
        pickupContactName: '',
        pickupOrderNote: '',
        pickupSearchAddress: '',
        dropoffReference: '',
        dropoffName: '',
        dropoffPhone: '+41(  )    -    ',
        dropoffCity: '',
        dropoffCountryCode: '',
        dropoffPostCode: '',
        dropoffContactName: '',
        dropoffOrderNote: '',
        dropoffSearchAddress: '',
        products: []
    }

    notificationSystem = React.createRef()

    addNotification = event => {
        const notification = this.notificationSystem.current;
        notification.addNotification({
            title: event.title || null,
            message: event.message || null,
            level: event.level || null,
            position: 'bc'
        });
    };

    /**
     * @param {string} name
     * @returns {Function}
     */
    handleChange = name => event => {
        this.setState({ [name]: event.target.value });
    };

    handleChangeTime = time => {
        return `${R.replace(/-/g, ':', time)}Z`
    }

    handleChangeAddress = (name, address) => {
        this.setState({ [name]: address });
    }

    handleAddOrder = () => {
        const { products } = this.state
        products.push({
            id: products.length + 1,
            Fee: '',
            Name: ''
            // quantity: '1',
        })
        this.setState({products})
    }

    /**
     * @param {string} name
     * @param {number} id
     * @returns {Function}
     */
    handleChangeProduct = (name, id) => event => {
        console.log(name)
        const { products } = this.state
        const findProduct = R.find(R.propEq('id', id), products)
        const orderPosition = R.indexOf(findProduct, products)
        findProduct[name] = event.target.value
        R.update(findProduct, orderPosition, products)
        this.setState({products});
    };

    /**
     * @param {number} id
     */
    handleDelete = id => {
        const { products } = this.state
        const findProduct = el => !R.equals(el.id, id)
        const filteredProducts = R.filter(findProduct, products)
        this.setState({
            products: filteredProducts
        })
    }

    handleSubmit = (data) => {
        createOrders(data)
            .then(res => {
                this.addNotification({
                    level: 'success',
                    message: 'Order created successfully'
                })
                console.log(res)
            })
            .catch(err => {
                this.addNotification({
                    level: 'error',
                    message: err.response.statusText
                })
                console.log(err.response)
            })
    }

    render () {
        const {classes, lang} = this.props
        console.log(this.state)
        const {
            products, date,
            dropoffPhone, dropoffCity, dropoffPostCode, dropoffSearchAddress, dropoffContactName,
            pickupContactName, pickupSearchAddress, pickupPostCode, pickupCity, pickupPhone, pickupName, pickupReference,
            reference, pickupTimeFrom, dropoffTimeFrom, pickupdate, dropoffdate, paymentType, dropoffNote, fee, shipmentType} = this.state
        const preparedForm = {
            Reference: reference,
            PickupDate: pickupdate,
            DropoffDate: dropoffdate,
            PickupTimeFrom: this.handleChangeTime(pickupTimeFrom),
            DropoffTimeFrom: this.handleChangeTime(dropoffTimeFrom),
            DropoffNotes: dropoffNote,
            Fee: fee,
            PaymentType: paymentType,
            ShipmentType: shipmentType,
            Pickup: {
                Reference: pickupReference,
                Name: pickupName,
                Phone: pickupPhone,
                City: pickupCity,
                Postcode: pickupPostCode,
                Streetaddress: pickupSearchAddress,
                ContactName: pickupContactName
            },
            Dropoff: {
                Phone: dropoffPhone,
                City: dropoffCity,
                Postcode: dropoffPostCode,
                Streetaddress: dropoffSearchAddress,
                ContactName: dropoffContactName
            },
            Products: products
        }
        return (
            <Card className="order__cards">
                <Title title={lang.ordersCreateTitle}/>
                <div>
                    <form className={classes.container} onInvalid={console.log('asdasdasd')} autoComplete="off">
                        <div className={classes.sectionWrapper}>
                            <h2>{lang.ordersInfoTitle}</h2>
                            <div className={classes.textFieldWrapper}>
                                <div className={classes.textFieldWrapp}>
                                    <span className={classes.required}>*</span>
                                    <TextField
                                        id="standard-name"
                                        label={lang.tableReference}                                        // required={true}
                                        className={classes.textField}
                                        value={this.state.reference}
                                        onChange={this.handleChange('reference')}
                                        margin="normal"/>
                                </div>
                                <div className={classes.textFieldWrapp}>
                                    <span className={classes.required}>*</span>
                                <TextField
                                    id="standard-name"
                                    type="date"
                                    // required={true}
                                    label={lang.orderLabelPickupDate}
                                    className={classes.textField}
                                    value={this.state.pickupdate}
                                    onChange={this.handleChange('pickupdate')}
                                    margin="normal"/>
                                </div>
                                <div className={classes.textFieldWrapp}>
                                    <span className={classes.required}>*</span>
                                <TextField
                                    id="standard-name"
                                    label={lang.orderLabelDropOffDate}
                                    type="date"
                                    // required={true}
                                    className={classes.textField}
                                    value={this.state.dropoffdate}
                                    onChange={this.handleChange('dropoffdate')}
                                    margin="normal"/>
                                </div>
                                <div className={classes.textFieldWrapp}>
                                    <span className={classes.required}>*</span>
                                <TextField
                                    id="pickupTimeFrom"
                                    label={lang.orderPickupNameFrom}
                                    // type="time"
                                    // required={true}
                                    placeholder="00:00:00"
                                    className={classes.textField}
                                    value={this.state.pickupTimeFrom}
                                    onChange={this.handleChange('pickupTimeFrom')}
                                    InputProps={{
                                        inputComponent: TimeMaskCustom,
                                    }}
                                    margin="normal"/>
                                </div>
                                <div className={classes.textFieldWrapp}>
                                    <span className={classes.required}>*</span>
                                <TextField
                                    id="dropoffTimeFrom"
                                    label="Dropoff Time From"
                                    placeholder="00:00:00"
                                    // required={true}
                                    className={classes.textField}
                                    value={this.state.dropoffTimeFrom}
                                    InputProps={{
                                        inputComponent: TimeMaskCustom,
                                    }}
                                    onChange={this.handleChange('dropoffTimeFrom')}
                                    margin="normal"/>
                                </div>
                                <div className={classes.textFieldWrapp}>
                                    <span className={classes.required}>*</span>
                                <TextField
                                    id="standard-name"
                                    label={lang.orderLabelPaymentType}
                                    type="number"
                                    // required={true}
                                    placeholder={lang.onlyNumber}
                                    className={classes.textField}
                                    value={this.state.paymentType}
                                    onChange={this.handleChange('paymentType')}
                                    margin="normal"/>
                                </div>
                                {/*<div className={classes.textFieldWrapp}>*/}
                                {/*    <span className={classes.required}>*</span>*/}
                                {/*    <TextField*/}
                                {/*        id="shipmentType"*/}
                                {/*        label={lang.snipmentType}*/}
                                {/*        type="number"*/}
                                {/*        // required={true}*/}
                                {/*        className={classes.textField}*/}
                                {/*        value={this.state.shipmentType}*/}
                                {/*        onChange={this.handleChange('shipmentType')}*/}
                                {/*        margin="normal"/>*/}
                                {/*</div>*/}
                                <div className={classes.textFieldWrapp}>
                                    <span className={classes.required}>*</span>
                                    <TextField
                                        id="fee"
                                        type="number"
                                        placeholder={lang.onlyNumber}
                                        label={lang.orderLabelProductFee}
                                        // required={true}
                                        className={classes.textField}
                                        value={this.state.fee}
                                        onChange={this.handleChange('fee')}
                                        margin="normal"/>
                                </div>
                                <div className={classes.textFieldWrapp}>
                                    <span className={classes.required}>*</span>
                                    <TextField
                                        id="dropoffNote"
                                        label={lang.orderLabelDropOffNote}
                                        multiline
                                        // required={true}
                                        // rows={3}
                                        className={classes.testArea}
                                        value={this.state.dropoffNote}
                                        onChange={this.handleChange('dropoffNote')}
                                        margin="normal"/>
                                </div>
                            </div>
                            <div className={classes.textFieldWrapper}>
                                {/*<div className={classes.textFieldWrapp}>*/}
                                {/*    <span className={classes.notRequired}>*</span>*/}
                                {/*    <TextField*/}
                                {/*        id="standard-name"*/}
                                {/*        label={lang.orderLabelPaymentInfo}*/}
                                {/*        multiline*/}
                                {/*        // required={true}*/}
                                {/*        // rows={3}*/}
                                {/*        className={classes.testArea}*/}
                                {/*        value={this.state.paymentInfo}*/}
                                {/*        onChange={this.handleChange('paymentInfo')}*/}
                                {/*        margin="normal"/>*/}
                                {/*</div>*/}
                            </div>
                        </div>
                        <div className={classes.sectionWrapper}>
                            <h2>{lang.ordersPickUpTitle}</h2>
                            <div className={classes.textFieldWrapper}>
                                <div className={classes.textFieldWrapp}>
                                    <span className={classes.required}>*</span>
                                <TextField
                                    id="pickupReference"
                                    label={lang.tableReference}
                                    className={classes.textField}
                                    // required={true}
                                    value={this.state.pickupReference}
                                    onChange={this.handleChange('pickupReference')}
                                    margin="normal"/>
                                </div>
                                <div className={classes.textFieldWrapp}>
                                    <span className={classes.required}>*</span>
                                <TextField
                                    id="pickupName"
                                    label={lang.orderLabelProductName}
                                    // required={true}
                                    className={classes.textField}
                                    value={this.state.pickupName}
                                    onChange={this.handleChange('pickupName')}
                                    margin="normal"/>
                                </div>
                                <div className={classes.textFieldWrapp}>
                                    <span className={classes.required}>*</span>
                                    <TextField
                                    id="standard-name"
                                    label={lang.tablePhone}
                                    // type="number"
                                    // required={true}
                                    className={classes.textField}
                                    value={this.state.pickupPhone}
                                    onChange={this.handleChange('pickupPhone')}
                                    // inputComponent={TextMaskCustom}
                                    InputProps={{
                                        inputComponent: TextMaskCustom,
                                    }}
                                    margin="normal"/>
                                </div>
                                <div className={classes.textFieldWrapp}>
                                    <span className={classes.required}>*</span>
                                <TextField
                                    id="standard-name"
                                    label={lang.tableCity}
                                    className={classes.textField}
                                    // required={true}
                                    // value={this.state.pickupCity}
                                    onChange={this.handleChange('pickupCity')}
                                    margin="normal"/>
                                </div>
                                <div className={classes.textFieldWrapp}>
                                    <span className={classes.required}>*</span>
                                <TextField
                                    id="standard-name"
                                    label={lang.orderLabelPostCode}
                                    className={classes.textField}
                                    // required={true}
                                    value={this.state.pickupPostCode}
                                    onChange={this.handleChange('pickupPostCode')}
                                    margin="normal"/>
                                </div>
                                {/*<div className={classes.textFieldWrapp}>*/}
                                {/*    <span className={classes.required}>*</span>*/}
                                {/*<TextField*/}
                                {/*    id="standard-name"*/}
                                {/*    label={lang.orderLabelContactName}*/}
                                {/*    className={classes.textField}*/}
                                {/*    // required={true}*/}
                                {/*    value={this.state.pickupContactName}*/}
                                {/*    onChange={this.handleChange('pickupContactName')}*/}
                                {/*    margin="normal"/>*/}
                                {/*</div>*/}
                                <div className={classes.textFieldWrapp}>
                                    <span className={classes.required}>*</span>
                                <Address
                                    resource=''
                                    id={this.props.id}
                                    changeName="pickupSearchAddress"
                                    handleChangeAddress={this.handleChangeAddress}
                                    margin="none"
                                    field='address'
                                    lang={lang}
                                    label={lang.orderLabelSearchAddress}
                                    styles={classes.textField}
                                    secondError='Address is invalid'
                                    success='Address saved successfully'
                                    defaultValue={this.state.pickupSearchAddress}
                                />
                                </div>
                            </div>
                        </div>
                        <div className={classes.sectionWrapper}>
                            <h2>{lang.ordersDropOffTitle}</h2>
                            <div className={classes.textFieldWrapper}>
                                <div className={classes.textFieldWrapp}>
                                    <span className={classes.required}>*</span>
                                <TextField
                                    id="standard-name"
                                    label={lang.orderLabelDropOffPhone}
                                    // type="number"
                                    // required={true}
                                    className={classes.textField}
                                    value={this.state.dropoffPhone}
                                    InputProps={{
                                        inputComponent: TextMaskCustom,
                                    }}
                                    onChange={this.handleChange('dropoffPhone')}
                                    margin="normal"/>
                                </div>
                                <div className={classes.textFieldWrapp}>
                                    <span className={classes.required}>*</span>
                                <TextField
                                    id="standard-name"
                                    label={lang.orderLabelDropOffCity}
                                    className={classes.textField}
                                    // required={true}
                                    value={this.state.dropoffCity}
                                    onChange={this.handleChange('dropoffCity')}
                                    margin="normal"/>
                                </div>
                                <div className={classes.textFieldWrapp}>
                                    <span className={classes.required}>*</span>
                                <TextField
                                    id="standard-name"
                                    label={lang.orderLabelPostCode}
                                    className={classes.textField}
                                    // required={true}
                                    value={this.state.dropoffPostCode}
                                    onChange={this.handleChange('dropoffPostCode')}
                                    margin="normal"/>
                                </div>
                                <div className={classes.textFieldWrapp}>
                                    <span className={classes.required}>*</span>
                                <TextField
                                    id="standard-name"
                                    label={lang.orderLabelContactName}
                                    className={classes.textField}
                                    // required={true}
                                    value={this.state.dropoffContactName}
                                    onChange={this.handleChange('dropoffContactName')}
                                    margin="normal"/>
                                </div>
                            </div>
                            <div className={classes.textFieldWrapp}>
                                <span className={classes.required}>*</span>
                                <Address
                                    resource=''
                                    changeName="dropoffSearchAddress"
                                    handleChangeAddress={this.handleChangeAddress}
                                    id={this.props.id}
                                    // required={true}
                                    margin="none"
                                    field='address'
                                    lang={lang}
                                    label={lang.orderLabelSearchAddress}
                                    styles={classes.textField}
                                    secondError='Address is invalid'
                                    success='Address saved successfully'
                                    defaultValue={this.state.dropoffSearchAddress}
                                />
                            </div>
                        </div>
                        <div className={classes.sectionWrapper}>
                            <h2>Product</h2>
                            <Products
                                lang={lang}
                                products={products}
                                handleAddOrder={this.handleAddOrder}
                                handleChangeProduct={this.handleChangeProduct}
                                handleDelete={this.handleDelete}
                                parentStyles={classes} />
                        </div>
                    </form>
                    <Button variant="contained" type="submit" color="primary" onClick={() => this.handleSubmit(preparedForm)} className={classes.button}>
                        Create order
                    </Button>
                </div>
                <NotificationSystem ref={this.notificationSystem} style={styleNotification}/>
            </Card>
        )
    }
}

const mapStateToProps = state => ({
    lang: state.i18n.messages
})

export default compose(
    connect(mapStateToProps),
    withStyles(styles)
)(OrderCreate)
