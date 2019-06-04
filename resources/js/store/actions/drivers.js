import {FETCHED_DRIVERS, SET_DRIVER_ORDERS, UPDATE_DRIVER} from '../constantTypes'

export const fetchDrivers = (payload) => {
    return {
        type: FETCHED_DRIVERS,
        params: payload
    }
};

export const setDriverOrders = (payload) => {
    return {
        type: SET_DRIVER_ORDERS,
        payload
    }
};

export const updateDriver = (payload) => {
    return {
        type: UPDATE_DRIVER,
        payload
    }
};
