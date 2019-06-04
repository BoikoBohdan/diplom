import {FETCHED_ORDERS_START, FETCHED_ORDERS_SUCCESS, FETCHED_ORDERS_ERROR, CHANGE_SELECTED_LIST,
        FETCHED_ADDITIONAL_ORDERS_START, FETCHED_ADDITIONAL_ORDERS_SUCCESS, FETCHED_ADDITIONAL_ORDERS_ERROR,
        ASSIGN_ORDERS_START, ASSIGN_ORDERS_SUCCESS, ASSIGN_ORDERS_ERROR, ADD_ORDER, SET_SEARCH_PICKUP_ADDRESS, SET_SEARCH_DROPOFF_ADDRESS, CHANGE_ACTIVE_FIELD,
        FETCHED_ORDERS_BY_DRIVER_START, FETCHED_ORDERS_BY_DRIVER_SUCCESS, FETCHED_ORDERS_BY_DRIVER_ERROR, ADD_UPDATED_ORDER_TO_LIST,
        FETCHED_FILTER_ORDERS_START, FETCHED_FILTER_ORDERS_SUCCESS, FETCHED_FILTER_ORDERS_ERROR, SET_ORDERS_REQUEST_PARAMS
} from '../constantTypes'
import * as R from 'ramda'
import {handleGenerateColor} from '../../utils'
const addColorToObject = object => R.assoc('color', handleGenerateColor(), object)

const initialState = {
    orders: [],
    selectedOrders: [],
    assign: '',
    loading: false,
    error: false,
    driverOrders: [],
    activeAddressField: '',
    searchPickupAddress: '',
    searchDropoffAddress: '',
    requestParams: {
        page: 1,
        perPage: '',
        field: '',
        order: 'desc',
        q: '',
        name: 'all',
        filter: '',
        sort: 'id',
        order_status: ''
     }
};

const ordersReducer = (state = initialState, action) => {
    switch (action.type) {
        case 'FETCHED_ORDERS_START':
            return {
                ...state,
                loading: true,
            };
        case 'FETCHED_ORDERS_SUCCESS':
            return {
                ...state,
                orders: action.payload,
            };
        case 'FETCHED_ORDERS_ERROR':
            return {
                ...state,
                error: action.payload,
            };
        case 'FETCHED_ADDITIONAL_ORDERS_START':
            return {
                ...state,
                loading: true,
            };
        case 'FETCHED_ADDITIONAL_ORDERS_SUCCESS':
            return {
                ...state,
                orders: R.uniq([...state.orders, ...action.payload]),
            };
        case 'FETCHED_ADDITIONAL_ORDERS_ERROR':
            return {
                ...state,
                error: action.payload,
            };
        case 'FETCHED_FILTER_ORDERS_START':
            return {
                ...state,
                loading: true,
            };
        case 'FETCHED_FILTER_ORDERS_SUCCESS':
            return {
                ...state,
                orders: action.payload,
            };
        case 'FETCHED_FILTER_ORDERS_ERROR':
            return {
                ...state,
                error: action.payload,
            };    
        case 'ASSIGN_ORDERS_START':
            return {
                ...state,
                loading: true,
            };
        case 'ASSIGN_ORDERS_SUCCESS':
            return {
                ...state,
                assign: 'Success'
            };
        case 'ASSIGN_ORDERS_ERROR':
            return {
                ...state,
                error: action,
            };
        case 'ADD_ORDER':
            const order = action.payload.params
            return {
                ...state,
                orders: R.prepend(order, state.orders),
            };
        case 'ADD_UPDATED_ORDER_TO_LIST':
            const updatedOrder = action.payload.params.order
            const oldOrder = R.find(R.propEq('id', updatedOrder.id))(state.orders);
            const orderPosition = R.indexOf(oldOrder, state.orders)
            return {
                ...state,
                orders: R.update(orderPosition, {...oldOrder, ...updatedOrder, type: 'updated'}, state.orders ),
            };
        case 'CHANGE_SELECTED_LIST':
            const id = action.payload.id
            const findOrderByID = R.find(R.propEq('id', id), state.orders)
            const addSelected = () => R.append(action.payload, state.selectedOrders)
            const removeSelected = R.without([findOrderByID], state.selectedOrders)
            const isIncludesSelected = R.includes(findOrderByID, state.selectedOrders)
            return {
                ...state,
                selectedOrders: isIncludesSelected ? removeSelected : addSelected(),
                loading: true,
            };
        case 'CLEAR_SELECTED_LIST':
            return {
                ...state,
                selectedOrders: [],
                loading: true,
            };
        case 'FETCHED_ORDERS_BY_DRIVER_START':
            return {
                ...state,
                loading: true,
            };
        case 'FETCHED_ORDERS_BY_DRIVER_SUCCESS':
            return {
                ...state,
                driverOrders: action.payload,
            };
        case 'FETCHED_ORDERS_BY_DRIVER_ERROR':
            return {
                ...state,
                error: action.payload,
            };
        case 'SET_ORDERS_REQUEST_PARAMS':
            return {
                ...state,
                requestParams: action.payload,
                error: action.payload,
            };
        case 'CHANGE_ACTIVE_FIELD':
            return {
                ...state,
                activeAddressField: action.payload
            };
        case 'SET_SEARCH_PICKUP_ADDRESS':
            return {
                ...state,
                searchPickupAddress: action.payload
            };
        case 'SET_SEARCH_DROPOFF_ADDRESS':
            return {
                ...state,
                searchDropoffAddress: action.payload
            };
        default:
            return state;
    }
};

export default ordersReducer
