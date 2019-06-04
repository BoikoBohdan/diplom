import {FETCHED_CANCEL_REASON_START, FETCHED_CANCEL_REASON_SUCCESS, FETCHED_CANCEL_REASON_ERROR} from '../constantTypes'

const initialState = {
    cancel_reasons: [],
    loading: false,
    error: false,
};

const cancelReasonsReducer = (state = initialState, action) => {
    switch (action.type) {
        case 'FETCHED_CANCEL_REASON_START':
            return {
                ...state,
                loading: true,
            };
        case 'FETCHED_CANCEL_REASON_SUCCESS':
            return {
                ...state,
                cancel_reasons: action.payload,
            };
        case 'FETCHED_CANCEL_REASON_ERROR':
            return {
                ...state,
                error: action.payload,
            };
        default:
            return state;
    }
};

export default cancelReasonsReducer
