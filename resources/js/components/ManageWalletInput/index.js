import React, {useState} from 'react'
import './style.css'
import InputBase from '@material-ui/core/InputBase'
import DirectionsIcon from '@material-ui/icons/Directions'
import IconButton from '@material-ui/core/IconButton'
import {withStyles} from '@material-ui/core/styles'
import Popover from '@material-ui/core/Popover'
import Button from '@material-ui/core/Button'
import {setWalletAmount} from '../../api/wallet'
import * as R from 'ramda'

const styles = theme => ({
    walletInputWrapper: {
        display: 'flex',
        alignItems: 'center',
        // minWidth: '200px'
    },
    buttonsWrapper: {
        display: 'flex',
        margin: 15
    },
    button: {
        margin: 0,
        padding: 0,
        minWidth: 15,
        fontSize: 20
    },
    inputWrapper: {
        display: 'flex',
        flexDirection: 'row'
    }
});

/**
 * @param {number} id
 * @param {string} value
 * @param {object} classes
 * @returns {*}
 * @constructor
 */
const WalletInput = ({id, value, classes}) => {
    const [amount, setAmount] = useState(value);
    const [addedValue, setAddedValue] = useState(0);
    const [cashValue, setCashValue] = useState(0);
    const [anchorEl, setanchorEl] = useState(null);
    const open = Boolean(anchorEl);

    const handleChange = event => {
        const format = /^[0-9+.\-]*$/g;
        if (!R.test(format, event.target.value)) {
            event.preventDefault()
        } else {
            const value = event.target.value;
            setAmount(value)
        }
    };
    const handleChangeAddedValue = event => {
        const format = /^[0-9+.\-]*$/g;
        const value = event.target.value;
        !R.test(format, value) ? event.preventDefault() : setAddedValue(value)
    };
    const handleConfirmChange = event => {
        if (R.equals(event.which, 13)) {
            setAmount(handleChange(event))
        }
    };

    const handleCalculate = sign => event => {
        console.log(event)
        setCashValue(amount)
        if (R.equals(sign, '+')) {
            setAmount(R.add(amount, addedValue).toFixed(2))
        } else {
            setAmount(R.subtract(amount, addedValue))
        }
        setAddedValue(0)
        handleOpenPopup(event)
    };

    const handleOpenPopup = (event) => {
        addedValue > 0 && setanchorEl(event.currentTarget)
    };
    const handleClosePopup = () => {
        setanchorEl(null)
    };
    const handleSendRequest = () => {
        setWalletAmount(id, {amount})
            .then(res => {
                handleClosePopup()
            })
            .catch(err => console.log(err))
    };
    return (
        <div className={classes.walletInputWrapper}>
            <div className={classes.inputWrapper}>
                <InputBase
                    disabled
                    placeholder="value"
                    type={'number'}
                    value={amount}
                    className={'input_base'}
                    onChange={handleChange}
                    onKeyPress={handleConfirmChange}/>
                <InputBase
                    placeholder="value"
                    type={'number'}
                    value={addedValue}
                    className={'input_base'}
                    onChange={handleChangeAddedValue}
                    onKeyPress={handleConfirmChange}/>
                <div className={classes.buttonsWrapper}>
                    <Button size="small" color="secondary" onClick={handleCalculate('-')}
                            className={classes.button}>-</Button>
                    <Button size="small" color="secondary" onClick={handleCalculate('+')}
                            className={classes.button}>+</Button>
                </div>
            </div>
            <Popover
                id="simple-popper"
                open={open}
                anchorEl={anchorEl}
                onClose={handleOpenPopup}
                anchorOrigin={{
                    vertical: 'bottom',
                    horizontal: 'center'
                }}
                transformOrigin={{
                    vertical: 'top',
                    horizontal: 'center'
                }}
            >
                <div className={classes.buttonsWrapper}>
                    <Button color="primary" onClick={handleSendRequest}>Confirm</Button>
                    <Button color="secondary" onClick={R.compose(
                        () => setAmount(cashValue),
                        () => handleClosePopup()
                    )}>Cancel</Button>
                </div>
            </Popover>
        </div>
    )
};


export default withStyles(styles)(WalletInput)
