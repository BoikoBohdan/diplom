import {call, put, takeEvery} from "redux-saga/effects"
import {FETCHED_DRIVERS, FETCHED_DRIVERS_ERROR, FETCHED_DRIVERS_START, FETCHED_DRIVERS_SUCCESS} from "../constantTypes"
import {getDriversList} from "../../api/drivers"

export function* fetchDrivers ({params}) {
    try {
        yield put({
            type: FETCHED_DRIVERS_START
        });
        const data = yield call(getDriversList, {page: params});

        yield put({
            type: FETCHED_DRIVERS_SUCCESS,
            payload: data.data
        });
    } catch (error) {
        yield put({
            type: FETCHED_DRIVERS_ERROR, payload: error
        });
    }
}

export function* fetchDriversAsync () {
    yield takeEvery(FETCHED_DRIVERS, fetchDrivers)
}
