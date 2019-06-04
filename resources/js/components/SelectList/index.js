import React, {useState} from 'react'
import FormControl from '@material-ui/core/FormControl'
import InputLabel from '@material-ui/core/InputLabel'
import Select from '@material-ui/core/Select'
import MenuItem from '@material-ui/core/MenuItem'
import * as R from 'ramda'

const SelectFilteredList = ({list, classes, open, onClose, onOpen, value, onChange, label, lang}) => {
    const [stateValue, setValue] = useState('All')
    const onChangeField = event => {
        onChange(event)
        setValue(event.target.value)
    }
    return (
        <FormControl>
            <InputLabel htmlFor="demo-controlled-open-select">{label}</InputLabel>
            <Select
                className={classes.orderFilter}
                open={open}
                onClose={onClose}
                onOpen={onOpen}
                value={value}
                onChange={onChangeField}
                renderValue={ value => stateValue}
                inputProps={{
                    name: 'selectedOrder',
                    id: label,
                }}>
                <MenuItem value="">
                    <em>{lang.tableFilterAll}</em>
                </MenuItem>
                {list && list.map(el => (
                    <MenuItem value={el.key} key={el.key}>{lang[el.value]}</MenuItem>
                ))}
            </Select>
        </FormControl>
    )
};

export default SelectFilteredList
