import React, {Component} from 'react'
import {withStyles} from '@material-ui/core/styles'
import TextField from '@material-ui/core/TextField'
import Fab from '@material-ui/core/Fab';
import AddIcon from '@material-ui/icons/Add';
import DeleteIcon from '@material-ui/icons/Delete';
import * as R from 'ramda'

const styles = theme => ({
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
    },
    fab: {
        margin: theme.spacing.unit
    },
    delete: {
        background: 'red',
        color: '#fff'
    }
})

class Products extends Component {
    state = {
        products: [
            {
                id: 1,
                name: '',
                quantity: '',
                fee: ''
            }
        ]
    }

    render () {
        const {classes, parentStyles, products, lang} = this.props
        return (
            <div>
                {
                    !R.isNil(products) && products.map(product => {
                        return (
                            <div key={product.id} className={parentStyles.textFieldWrapper}>
                                <div className={classes.textFieldWrapp}>
                                    <span className={classes.required}>*</span>
                                <TextField
                                    id="standard-name"
                                    label={lang.orderLabelProductName}
                                    className={parentStyles.textField}
                                    // required={true}
                                    value={this.state.name}
                                    onChange={this.props.handleChangeProduct('Name', product.id)}
                                    margin="normal"/>
                                </div>
                                <div className={classes.textFieldWrapp}>
                                    <span className={classes.required}>*</span>
                                <TextField
                                    id="standard-name"
                                    label={lang.orderLabelProductFee}
                                    className={parentStyles.textField}
                                    value={this.state.fee}
                                    type={'number'}
                                    placeholder={lang.onlyNumber}
                                    // required={true}
                                    onChange={this.props.handleChangeProduct('Fee', product.id)}
                                    margin="normal"/>
                                </div>
                                <Fab aria-label="Delete" className={classes.delete} onClick={() => this.props.handleDelete(product.id)}>
                                    <DeleteIcon />
                                </Fab>
                            </div>
                        )
                    })
                }
                <Fab color="primary" aria-label="Add" className={classes.fab} onClick={() => this.props.handleAddOrder()}>
                    <AddIcon />
                </Fab>
            </div>
        )
    }
}

export default withStyles(styles)(Products)
