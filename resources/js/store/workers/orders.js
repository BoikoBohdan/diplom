import {addColorToOrder} from '../../utils'
import {call, put, takeLatest, takeEvery} from "redux-saga/effects"
import {
    ASSIGN_ORDERS_ERROR, ASSIGN_ORDERS_START, ASSIGN_ORDERS_SUCCESS,
    FETCHED_ADDITIONAL_ORDERS, FETCHED_ADDITIONAL_ORDERS_SUCCESS, FETCHED_ADDITIONAL_ORDERS_START, FETCHED_ADDITIONAL_ORDERS_ERROR,
    FETCHED_FILTER_ORDERS, FETCHED_FILTER_ORDERS_START, FETCHED_FILTER_ORDERS_SUCCESS, FETCHED_FILTER_ORDERS_ERROR,
    FETCHED_ORDERS_BY_DRIVER_START, FETCHED_ORDERS_BY_DRIVER_SUCCESS, FETCHED_ORDERS_BY_DRIVER_ERROR,
    FETCHED_ORDERS_BY_DRIVER, SET_ORDERS_REQUEST_PARAMS, SET_PARAMS,
    ASSIGN_REQUEST,
    FETCHED_ORDERS,
    FETCHED_ORDERS_ERROR,
    FETCHED_ORDERS_START,
    FETCHED_ORDERS_SUCCESS,
    ADD_ORDER_TO_LIST, ADD_ORDER, ADD_UPDATED_ORDER_TO_LIST, UPDATE_ORDER
} from "../constantTypes"
import {assignOrder, getOrdersList, getOrdersByDriver} from "../../api/orders"

function* fetchOrders ({params}) {
    try {
        yield put({
            type: FETCHED_ORDERS_START
        });
        const data = yield call(getOrdersList, params);
        yield put({
            type: FETCHED_ORDERS_SUCCESS,
            payload: addColorToOrder(data.data)
        });
    } catch (error) {
        yield put({
            type: FETCHED_ORDERS_ERROR, payload: error
        });
    }
}

function* fetchadditionalOrders ({params}) {
    try {
        yield put({
            type: FETCHED_ADDITIONAL_ORDERS_START
        });
        const data = yield call(getOrdersList, params);
        yield put({
            type: FETCHED_ADDITIONAL_ORDERS_SUCCESS,
            payload: addColorToOrder(data.data)
        });
    } catch (error) {
        yield put({
            type: FETCHED_ADDITIONAL_ORDERS_ERROR,
            payload: error
        });
    }
}

function* filterOrders ({params}) {
    try {
        yield put({
            type: FETCHED_FILTER_ORDERS_START
        });
        const data = yield call(getOrdersList, params);
        yield put({
            type: FETCHED_FILTER_ORDERS_SUCCESS,
            payload: addColorToOrder(data.data)
        });
    } catch (error) {
        yield put({
            type: FETCHED_FILTER_ORDERS_ERROR, payload: error
        });
    }
}

function* setOrderParams ({params}) {
    try {
        yield put({
            type: SET_ORDERS_REQUEST_PARAMS,
            payload: params
        });
    } catch (error) {
        console.log(error)
    }
}

function* fetchOrdersByDriver ({id}) {
    try {
        yield put({
            type: FETCHED_ORDERS_BY_DRIVER_START
        });
        const data = yield call(getOrdersByDriver, id);
        yield put({
            type: FETCHED_ORDERS_BY_DRIVER_SUCCESS,
            payload: data.data
        });
    } catch (error) {
        yield put({
            type: FETCHED_ORDERS_BY_DRIVER_ERROR, payload: error
        });
    }
}

function* assignOrders ({params}) {
    try {
        yield put({
            type: ASSIGN_ORDERS_START
        });
        const data = yield call(assignOrder, params);

        yield put({
            type: ASSIGN_ORDERS_SUCCESS,
            payload: data.data
        });
    } catch (error) {
        yield put({
            type: ASSIGN_ORDERS_ERROR, payload: error
        });
    }
}

function* addOrderToList (payload) {
    try {
        yield put({
            type: ADD_ORDER,
            payload
        });
    } catch (error) {
        console.log(error)
    }
}

function* addUpdatedOrderToList (payload) {
    try {
        yield put({
            type: ADD_UPDATED_ORDER_TO_LIST,
            payload
        });
    } catch (error) {
        console.log(error)
    }
}

export function* addUpdatedOrderAsync () {
    yield takeEvery(UPDATE_ORDER, addUpdatedOrderToList);
}

export function* addOrderAsync () {
    yield takeEvery(ADD_ORDER_TO_LIST, addOrderToList);
}

export function* fetchOrdersAsync () {
    yield takeEvery(FETCHED_ORDERS, fetchOrders);
}

export function* fetchAdditionalOrdersAsync () {
    yield takeEvery(FETCHED_ADDITIONAL_ORDERS, fetchadditionalOrders);
}

export function* filterOrdersAsync () {
    yield takeEvery(FETCHED_FILTER_ORDERS, filterOrders);
}

export function* fetchOrdersByDriversAsync() {
    yield takeEvery(FETCHED_ORDERS_BY_DRIVER, fetchOrdersByDriver);
}
export function* assignOrdersAsync () {
    yield takeLatest(ASSIGN_REQUEST, assignOrders);
}
export function* setOrderRequestParamsOrdersAsync () {
    yield takeLatest(SET_PARAMS, setOrderParams);
}
