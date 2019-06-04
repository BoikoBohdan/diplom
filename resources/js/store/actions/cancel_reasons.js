import {CANCEL_REASON} from "../constantTypes"

export const fetchCancelReasons = (payload) => {
    return {
        type: CANCEL_REASON,
        payload
    }
};
