import {call, put, takeEvery} from "redux-saga/effects"
import {
    ADD_MESSAGES_ASYNC,
    ADD_NEW_MESSAGE,
    FETCHED_CHAT_USERS,
    FETCHED_CHAT_USERS_ERROR,
    FETCHED_CHAT_USERS_START,
    FETCHED_CHAT_USERS_SUCCESS,
    FETCHED_MESSAGES,
    FETCHED_MESSAGES_ERROR,
    FETCHED_MESSAGES_START,
    FETCHED_MESSAGES_SUCCESS,
    FILTER_CHAT_USERS,
    SEARCH_CHAT_USERS
} from "../constantTypes"
import {getMessageList, getUserList} from "../../api/chat"

function* fetchUsers() {
    try {
        yield put({
            type: FETCHED_CHAT_USERS_START
        });
        const data = yield call(getUserList);

        yield put({
            type: FETCHED_CHAT_USERS_SUCCESS,
            payload: data.data
        });
    } catch (error) {
        yield put({
            type: FETCHED_CHAT_USERS_ERROR, payload: error
        });
    }
}

function* fetchMessagesHistory({params}) {
    try {
        yield put({
            type: FETCHED_MESSAGES_START
        });
        const data = yield call(getMessageList, params);

        yield put({
            type: FETCHED_MESSAGES_SUCCESS,
            payload: data.data
        });
    } catch (error) {
        yield put({
            type: FETCHED_MESSAGES_ERROR,
            payload: error
        });
    }
}

function* searchUsers({params}) {
    yield put({
        type: SEARCH_CHAT_USERS,
        payload: params
    });
}

function* addNewMessage({params}) {
    yield put({
        type: ADD_NEW_MESSAGE,
        payload: params
    });
}

export function* searchUsersAsync() {
    yield takeEvery(FILTER_CHAT_USERS, searchUsers)
}

export function* addMessageAsync() {
    yield takeEvery(ADD_MESSAGES_ASYNC, addNewMessage)
}

export function* fetchUsersAsync() {
    yield takeEvery(FETCHED_CHAT_USERS, fetchUsers)
}

export function* fetchMessagesAsync() {
    yield takeEvery(FETCHED_MESSAGES, fetchMessagesHistory)
}
