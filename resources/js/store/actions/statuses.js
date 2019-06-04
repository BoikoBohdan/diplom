import {FETCH_STATUSES, SET_STATUS, CANCEL_STATUS_CONFIRM} from "../constantTypes"

export const fetchStatuses = (payload) => {
    return {
        type: FETCH_STATUSES,
        params: payload
    }
};

export const setStatus = (payload) => {
    return {
        type: SET_STATUS,
        payload
    }
};

export const cancelStatus = (payload) => {
    return {
        type: CANCEL_STATUS_CONFIRM,
        payload
    }
};