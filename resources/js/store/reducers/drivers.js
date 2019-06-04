import {
    FETCHED_DRIVERS_ERROR,
    FETCHED_DRIVERS_START,
    FETCHED_DRIVERS_SUCCESS,
    SET_DRIVER_ORDERS,
    UPDATE_DRIVER
} from '../constantTypes'
import * as R from 'ramda'

const initialState = {
    drivers: [],
    loading: false,
    error: false,
    driver: []
};

const driversReducer = (state = initialState, action) => {
    switch (action.type) {
        case 'FETCHED_DRIVERS_START':
            return {
                ...state,
                loading: true
            };
        case 'FETCHED_DRIVERS_SUCCESS':
            return {
                ...state,
                drivers: R.uniq([...state.drivers, ...action.payload]),
            };
        case 'FETCHED_DRIVERS_ERROR':
            return {
                ...state,
                error: action.payload,
            };
        case 'SET_DRIVER_ORDERS':
            return {
                ...state,
                driver: state.driver.push(action.payload)
            };
        case 'UPDATE_DRIVER': {
            const updatedDriver = action.payload;
            const oldDriver = R.find(R.propEq('id', updatedDriver.id))(state.drivers);
            const orderPosition = R.indexOf(oldDriver, state.drivers);
            return {
                ...state,
                drivers: R.update(orderPosition, {...updatedDriver}, state.drivers),
            }
        }
        default:
            return state;
    }
};

export default driversReducer
