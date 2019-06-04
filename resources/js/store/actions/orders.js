import {
    FETCHED_ORDERS,
    FETCHED_ADDITIONAL_ORDERS,
    ASSIGN_REQUEST,
    ADD_ORDER_TO_LIST,
    FETCHED_ORDERS_BY_DRIVER,
    UPDATE_ORDER,
    FETCHED_FILTER_ORDERS,
    SET_PARAMS,
    CHANGE_SELECTED_LIST,
    CLEAR_SELECTED_LIST,
    CHANGE_ACTIVE_FIELD,
    SET_SEARCH_PICKUP_ADDRESS, SET_SEARCH_DROPOFF_ADDRESS
} from '../constantTypes'

export const fetchOrders = (payload) => {
    return {
        type: FETCHED_ORDERS,
        params: payload
    }
};

export const fetchAdditionalOrders = (payload) => {
    return {
        type: FETCHED_ADDITIONAL_ORDERS,
        params: payload
    }
};

export const filterOrders = (payload) => {
    return {
        type: FETCHED_FILTER_ORDERS,
        params: payload
    }
};

export const fetchOrdersByDriver = (id) => {
    return {
        type: FETCHED_ORDERS_BY_DRIVER,
        id
    }
};

export const assignRequest = (payload) => {
    return {
        type: ASSIGN_REQUEST,
        params: payload
    }
};

export const addOrderToList = (payload) => {
    return {
        type: ADD_ORDER_TO_LIST,
        params: payload
    }
};

export const addUpdatedOrderToList = (payload) => {
    return {
        type: UPDATE_ORDER,
        params: payload
    }
};

export const selectedOrderList = (payload) => {
    console.log('iddd', payload)
    return {
        type: CHANGE_SELECTED_LIST,
        payload
    }
};

export const clearSelectedOrderList = (payload) => {
    console.log('iddd', payload)
    return {
        type: CLEAR_SELECTED_LIST,
        payload
    }
};

export const setOrderRequestParams = (payload) => {
    return {
        type: SET_PARAMS,
        params: payload
    }
};

export const setActiveAddressField = payload => {
    return {
        type: CHANGE_ACTIVE_FIELD,
        payload
    }
}

export const setSearcPickuphAddress = payload => {
    return {
        type: SET_SEARCH_PICKUP_ADDRESS,
        payload
    }
}

export const setSearcDropoffhAddress = payload => {
    return {
        type: SET_SEARCH_DROPOFF_ADDRESS,
        payload
    }
}
