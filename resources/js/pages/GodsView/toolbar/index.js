import React, {Component} from 'react'
import {connect} from 'react-redux'
import {compose} from 'recompose'
import {withStyles} from '@material-ui/core/styles'
import PropTypes from 'prop-types'
import Toolbar from '@material-ui/core/Toolbar'
import FormGroup from '@material-ui/core/FormGroup'
import Switch from '@material-ui/core/Switch'
import blue from '@material-ui/core/colors/blue'
import * as R from 'ramda'
import SelectFilteredList from '../../../components/SelectList'
import {filterOrders, setOrderRequestParams} from "../../../store/actions/orders"

const styles = theme => ({
    page: 1,
    tollbar: {},
    colorSwitchBase: {
        color: blue[300],
        '&$colorChecked': {
            color: blue[500],
            '& + $colorBar': {
                backgroundColor: blue[500],
            },
        },
    },
    colorBar: {},
    colorChecked: {},
    orderFilter: {
        minWidth: '100px',
        marginRight: '20px',
    }
});

class OrdersToolBar extends Component {
    state = {
        statuses: [],
        cities: [
            {key: 1, value: 'Zürich'},
        ],
        filter_name: 'all',
        isShowName: false,
        isShowDriversList: true,
        selectedOrder: '',
        selectedCity: '',
        isOpenCitiesList: false,
        isOpenSelectList: false,
    };

    /**
     * @param {string} name
     * @returns {Function}
     */
    handleChange = name => event => {
        name === 'isShowDriversList' ? this.props.toggleDriversList() : this.props.toggleShowDriverNames();
        this.setState({[name]: event.target.checked});
    };

    /**
     * @param event
     * @param {string} eventName
     */
    handleSelectChange = (event, eventName) => {
        const params = {
            filter: JSON.stringify(event.target.value),
            name: R.equals(event.target.value, '') ? 'all' : 'status',
            sort: 'id',
            order_status: JSON.stringify(event.target.value),
            order: 'desc',
            page: 1,
            perPage: ''
        };
        this.setState({
            [eventName]: event.target.value,
        });
        this.props.setOrderRequestParams({...params});
        this.props.filterOrders({...params});
        this.props.handleResetPage({...params})
    };

    handleSelectClose = (name) => {
        this.setState({[name]: false});
    };

    handleSelectOpen = (name) => {
        this.setState({[name]: true});
    };

    handleStatusValues = (statuses) => {
        return Object.values(statuses)
    };

    render() {
        const {classes, statuses, lang} = this.props;
        const {cities, selectedOrder, selectedCity, isOpenSelectList, isOpenCitiesList} = this.state;
        return (
            <Toolbar className={classes.tollbar}>
                <form autoComplete="off">
                    <SelectFilteredList
                        label={lang.tableStatus}
                        lang={lang}
                        classes={classes}
                        list={this.handleStatusValues(statuses)}
                        open={isOpenSelectList}
                        onClose={() => this.handleSelectClose('isOpenSelectList')}
                        onOpen={() => this.handleSelectOpen('isOpenSelectList')}
                        value={selectedOrder}
                        onChange={(e) => this.handleSelectChange(e, 'selectedOrder')}
                    />
                    <SelectFilteredList
                        label={lang.tableCity}
                        classes={classes}
                        lang={lang}
                        list={cities}
                        open={isOpenCitiesList}
                        onClose={() => this.handleSelectClose('isOpenCitiesList')}
                        onOpen={() => this.handleSelectOpen('isOpenCitiesList')}
                        value={selectedCity}
                        onChange={(e) => this.handleSelectChange(e, 'selectedCity')}
                    />
                </form>
                <FormGroup row>
                    <div>
                        <Switch
                            checked={this.state.isShowName}
                            onChange={this.handleChange('isShowName')}
                            value="isShowName"
                            classes={{
                                switchBase: classes.colorSwitchBase,
                                checked: classes.colorChecked,
                                bar: classes.colorBar,
                            }}
                        />
                        <span>{lang.godsHideMap}</span>
                    </div>
                    <div>
                        <Switch
                            checked={this.state.isShowDriversList}
                            onChange={this.handleChange('isShowDriversList')}
                            value="isShowDriversList"
                            classes={{
                                switchBase: classes.colorSwitchBase,
                                checked: classes.colorChecked,
                                bar: classes.colorBar,
                            }}
                        />
                        <span>{lang.godsShowDrivers}</span>
                    </div>
                </FormGroup>
            </Toolbar>
        )
    }
}

OrdersToolBar.propTypes = {
    classes: PropTypes.object.isRequired,
};

SelectFilteredList.propTypes = {
    classes: PropTypes.object.isRequired,
    label: PropTypes.string.isRequired,
    list: PropTypes.array.isRequired,
    open: PropTypes.bool.isRequired,
    onOpen: PropTypes.func.isRequired,
    onClose: PropTypes.func.isRequired,
    onChange: PropTypes.func.isRequired,
};

const mapStateToProps = state => ({
    lang: state.i18n.messages
})

export default compose(
    connect(mapStateToProps, {filterOrders, setOrderRequestParams}),
    withStyles(styles)
)(OrdersToolBar)
