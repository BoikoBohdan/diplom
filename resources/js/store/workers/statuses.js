import {FETCH_STATUSES, FETCHED_STATUSES_ERROR, FETCHED_STATUSES_START, FETCHED_STATUSES_SUCCESS,
        SET_STATUSES_START, SET_STATUSES_SUCCESS, SET_STATUSES_ERROR, SET_STATUS,
        CANCEL_STATUS_CONFIRM, CANCEL_STATUS_CONFIRM_START, CANCEL_STATUS_CONFIRM_SUCCESS, CANCEL_STATUS_CONFIRM_ERROR} from "../constantTypes"
import {getStatusesList, setNewStatus, cancelStatusConfirm} from "../../api/statuses"
import {call, put, takeEvery} from "redux-saga/effects"

function* fetchStatuses () {
    try {
        yield put({
            type: FETCHED_STATUSES_START
        });
        const data = yield call(getStatusesList);
        yield put({
            type: FETCHED_STATUSES_SUCCESS,
            payload: data.data
        });
    } catch (error) {
        yield put({
            type: FETCHED_STATUSES_ERROR, payload: error
        });
    }
}

function* setStatus ({payload}) {
    const {id, status} = payload
    try {
        yield put({
            type: SET_STATUSES_START
        });
        const data = yield call(setNewStatus, id, status);
        yield put({
            type: SET_STATUSES_SUCCESS,
            payload: data.data
        });
    } catch (error) {
        yield put({
            type: SET_STATUSES_ERROR, payload: error
        });
    }
}

function* cancelStatus ({payload}) {
    const {id, reasons} = payload
    try {
        yield put({
            type: CANCEL_STATUS_CONFIRM_START
        });
        const data = yield call(cancelStatusConfirm, id, reasons);
        yield put({
            type: CANCEL_STATUS_CONFIRM_SUCCESS,
            payload: data.data
        });
    } catch (error) {
        yield put({
            type: CANCEL_STATUS_CONFIRM_ERROR, payload: error
        });
    }
}

export function* setStatusAsync () {
    yield takeEvery(SET_STATUS, setStatus)
}

export function* fetchStatusesAsync () {
    yield takeEvery(FETCH_STATUSES, fetchStatuses)
}

export function* cancelStatusesAsync () {
    yield takeEvery(CANCEL_STATUS_CONFIRM, cancelStatus)
}

