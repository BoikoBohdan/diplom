import {FETCHED_STATUSES_START, FETCHED_STATUSES_SUCCESS, FETCHED_STATUSES_ERROR,
        SET_STATUSES_START, SET_STATUSES_SUCCESS, SET_STATUSES_ERROR, CANCEL_STATUS_CONFIRM_START, CANCEL_STATUS_CONFIRM_SUCCESS,
        CANCEL_STATUS_CONFIRM_ERROR} from '../constantTypes'

const initialState = {
    statuses: [],
    loading: false,
    error: false,
    cancelStatus: ''
};

const statusesReducer = (state = initialState, action) => {
    switch (action.type) {
        case 'FETCHED_STATUSES_START':
            return {
                ...state,
                loading: true,
            };
        case 'FETCHED_STATUSES_SUCCESS':
            return {
                ...state,
                statuses: action.payload.statuses,
            };
        case 'FETCHED_STATUSES_ERROR':
            return {
                ...state,
                error: action.payload,
            };
        case 'SET_STATUSES_START':
            return {
                ...state,
                loading: true,
            };
        case 'SET_STATUSES_SUCCESS':
            return {
                ...state,
                error: false,
            };
        case 'SET_STATUSES_ERROR':
            return {
                ...state,
                error: action.payload,
            };
        case 'CANCEL_STATUS_CONFIRM_START':
            return {
                ...state,
                loading: true,
            };
        case 'CANCEL_STATUS_CONFIRM_SUCCESS':
            return {
                ...state,
                cancelStatus: 'success',
                error: false,
            };
        case 'CANCEL_STATUS_CONFIRM_ERROR':
            return {
                ...state,
                error: action.payload,
            };        
        default:
            return state;
    }
};

export default statusesReducer
