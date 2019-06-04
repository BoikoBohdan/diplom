import {call, put, takeLatest} from "redux-saga/effects"
import { AUTH_REQUEST, LOGIN_ERROR, LOGIN_START, LOGIN_SUCCESS, LOGOUT } from "../constantTypes"
import {login, logout} from "../../api/login"

const goToLocation = (token) => {
    if (token) {
        window.location.assign('/')
    }
}

function* logIn ({payload: email, password}) {
    try {
        yield put({
            type: LOGIN_START
        });
        const data = yield call(login, email, password);
        const {role, token, id, company_id} = data.data
        setTimeout(() => goToLocation(token), 1000)
        yield put({
            type: LOGIN_SUCCESS,
            payload: role
        });
        localStorage.setItem('token', token)
        localStorage.setItem('company_id', company_id)
        localStorage.setItem('role', role)
        localStorage.setItem('id', id)
    } catch (error) {
        yield put({
            type: LOGIN_ERROR,
            payload: error.response.data
        });
        localStorage.removeItem('token');
    }
}

function* logOut () {
    try {
        yield call(logout);
        localStorage.removeItem('token');
        window.location.replace('#/login')
    } catch (error) {
        localStorage.removeItem('token');
    }
}

export function* loginAsync () {
    yield takeLatest(AUTH_REQUEST, logIn);
}

export function* logoutAsync () {
    yield takeLatest(LOGOUT, logOut);
}

