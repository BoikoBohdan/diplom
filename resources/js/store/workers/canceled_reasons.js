import {generateParams} from "../../utils"
import {call, put, takeEvery} from "redux-saga/effects"
import {FETCHED_CANCEL_REASON_START, FETCHED_CANCEL_REASON_SUCCESS, FETCHED_CANCEL_REASON_ERROR, CANCEL_REASON} from "../constantTypes"
import {getCanceledReasons} from "../../api/cancel_reasons"

export function* fetchCancelReason () {
    try {
        yield put({
            type: FETCHED_CANCEL_REASON_START
        });
        const data = yield call(getCanceledReasons);
        yield put({
            type: FETCHED_CANCEL_REASON_SUCCESS,
            payload: data.data
        });
    } catch (error) {
        yield put({
            type: FETCHED_CANCEL_REASON_ERROR, payload: error
        });
    }
}

export function* fetchCancelReasonAsync () {
    yield takeEvery(CANCEL_REASON, fetchCancelReason)
}
