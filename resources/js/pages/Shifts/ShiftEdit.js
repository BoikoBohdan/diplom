import React, {Component} from 'react'
import './style.css'
import {connect} from 'react-redux'
import {compose} from 'recompose'
import {showNotification} from 'react-admin'
import {styleNotification} from '../../utils/styles'
import Paper from '@material-ui/core/Paper'
import { withStyles } from '@material-ui/core/styles'
import TextField from '@material-ui/core/TextField'
import Select from '@material-ui/core/Select'
import MenuItem from '@material-ui/core/MenuItem'
import {editShiftById, getMealsandCities, getShiftById} from '../../api/shifts'
import NotificationSystem from 'react-notification-system'
import Button from '@material-ui/core/Button'
import {Title} from 'react-admin'
import * as R from 'ramda'

const styles = theme => ({
    button: {
        margin: theme.spacing.unit,
    },
});

class ShiftEdit extends Component {
    state = {
        date: '',
        city: '',
        shift: '',
        startShift: '00:00',
        endShift: '00:00',
        cities: null,
        meals: null
    }
    notificationSystem = React.createRef()

    componentDidMount () {
        getMealsandCities()
            .then(res => {
                const {cities, meals} = res.data
                this.setState({
                    cities,
                    meals,
                    city: R.nth(0, cities),
                    shift: R.nth(0, meals)
                })
            })
            .catch(err => console.log(err))
        getShiftById(this.props.id)
            .then(res => {
                const {data} = res
                this.setState({
                        date: data.date,
                        city: this.findCity(data.city_id),
                        shift: this.findShift(data.meal),
                        startShift: data.start,
                        endShift: data.end
                })
            })
            .catch(err => console.log(err))
    }

    addNotification = event => {
        const notification = this.notificationSystem.current;
        notification.addNotification({
            title: event.title || null,
            message: event.message || null,
            level: event.level || null,
            position: 'bc'
        });
    };

    findCity = (id) => {
        const {cities} = this.state
        return !R.isEmpty(cities) && R.find(R.propEq('id', id), cities)
    }

    findShift = (id) => {
        const {meals} = this.state
        return !R.isEmpty(meals) && R.find(R.propEq('id', id), meals)
    }

    handleFormatDate = (date) => {
        return R.compose(
            R.join('-'),
            R.reverse(),
            R.split('-'),
            R.replace(/\//g, '-')
        )(date)
    }

    handleChange = name => event => {
        this.setState({ [name]: event.target.value });
    };

    handleSendMessage = () => {
        const {id, showNotification, lang} = this.props
        const {date, city, shift, startShift, endShift} = this.state
        editShiftById(id, {
            date,
            city_id: city.id,
            start: startShift,
            end: endShift,
            meal: shift.id
        })
            .then(res => {
                this.addNotification({
                    level: 'success',
                    message: 'Shift edited successfully'
                })
                showNotification(lang['Shift edited successfully'])
            })
            .then(() => window.location.replace('/#/api/admin/shifts', 2000))
            .catch(err => {
                // this.addNotification({
                //     level: 'error',
                //     message: err.response.statusText
                // })
                showNotification(err.response.statusText, 'warning')
            })
    }

    render() {
        const { classes, lang } = this.props
        const { cities, meals } = this.state
        return (
            <Paper>
                <Title title={lang.editShiftTitle} />
                <h3 className='title'>{lang.editShiftTitle}</h3>
                <form className='shift-container'>
                    <TextField
                        id="date"
                        label={lang.tableDate}
                        type="date"
                        onChange={this.handleChange('date')}
                        className='textField'
                        value={this.state.date}
                        InputLabelProps={{
                            shrink: true,
                        }}
                    />
                    <TextField
                        id="time"
                        label={lang.tableStartShift}
                        type="time"
                        className='textField'
                        onChange={this.handleChange('startShift')}
                        value={this.state.startShift}
                        InputLabelProps={{
                            shrink: true,
                        }}
                        inputProps={{
                            step: 300,
                        }}
                    />
                    <TextField
                        id="time"
                        label={lang.tableEndShift}
                        type="time"
                        className='textField'
                        onChange={this.handleChange('endShift')}
                        value={this.state.endShift}
                        InputLabelProps={{
                            shrink: true,
                        }}
                        inputProps={{
                            step: 300,
                        }}
                    />
                    <Select
                        value={this.state.city.id || ''}
                        onChange={this.handleChange('city')}
                        className='select'
                        renderValue={ value => this.state.city.name}
                        inputProps={{
                            name: 'city',
                            id: 'city-simple',
                        }}
                    >
                        {
                            cities && cities.map(city => {
                                return (
                                    <MenuItem value={city} key={city.name}>{city.name}</MenuItem>
                                )
                            })
                        }
                    </Select>
                    <Select
                        value={this.state.shift.id || ''}
                        onChange={this.handleChange('shift')}
                        className='select'
                        renderValue={ value => lang[this.state.shift.title]}
                        inputProps={{
                            name: 'shift',
                            id: 'shift-simple'
                        }}
                    >
                        {
                            meals && meals.map(shift => {
                                return (
                                    <MenuItem value={shift} key={shift.title}>
                                        {lang[shift.title]}
                                    </MenuItem>
                                )
                            })
                        }
                    </Select>
                </form>
                <Button
                    variant="contained"
                    color="primary"
                    className={classes.button}
                    onClick={this.handleSendMessage}>
                    {lang.btnEdit}
                </Button>
                <NotificationSystem ref={this.notificationSystem} style={styleNotification}/>
            </Paper>
        )
    }
}


const mapStateToProps = state => ({
    lang: state.i18n.messages
})

export default compose(
    connect(mapStateToProps, {showNotification}),
    withStyles(styles)
)(ShiftEdit)
