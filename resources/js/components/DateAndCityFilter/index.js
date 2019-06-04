import React, {Component} from 'react'
import TextField from '@material-ui/core/TextField'
import Select from '@material-ui/core/Select'
import MenuItem from '@material-ui/core/MenuItem'
import Button from '@material-ui/core/Button'
import {handleFormatDate} from '../../utils'
import {getMealsandCities} from '../../api/shifts'

class DateAndCityFilter extends Component {
    state = {
        date: '',
        city: 'All',
        cities: []
    }

    componentDidMount () {
        getMealsandCities()
            .then(res => {
                const {cities} = res.data
                this.setState({
                    cities,
                })
            })
            .catch(err => console.log(err))
        // this.setState({
        //     date: handleFormatDate()
        // })
    }

    handleChange = name => event => {
        this.setState({[name]: event.target.value})
    }

    handleSearch = () => {
        this.props.handleFilterByDateAndCity(this.state.date, this.state.city)
        console.log(this.state)
    }

    render () {
        const {cities} = this.state
        const {lang} = this.props
        return (
            <div className='dashboard-member__filter-left'>
                <TextField
                    id="date"
                    label={lang.tableDate}
                    type="date"
                    onChange={this.handleChange('date')}
                    value={this.state.date}
                    InputLabelProps={{
                        shrink: true,
                    }}
                />
                <div className='dashboard-member__select-field'>
                    <Select
                        value={this.state.city.id || lang.tableFilterAll}
                        renderValue={value => this.state.city.name || lang.tableFilterAll}
                        onChange={this.handleChange('city')}
                    >
                        <MenuItem value='All'>
                            <em>All</em>
                        </MenuItem>
                        {cities.map(item => {
                            console.log(item)
                            return (
                                <MenuItem value={item} key={item.name}>
                                    {item.name}
                                </MenuItem>
                            )
                        })}
                    </Select>
                </div>
                <Button
                    onClick={this.handleSearch}
                    variant='contained'
                    color='primary'
                    className='dashboard-member__search-button'
                >
                    {lang.btnSearch}
                </Button>
            </div>
        )
    }
}

export default DateAndCityFilter
