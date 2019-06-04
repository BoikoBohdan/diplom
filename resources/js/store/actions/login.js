import {AUTH_REQUEST, LOGOUT} from "../constantTypes"

export const authorize = (email, password) => ({
    type: AUTH_REQUEST,
    payload: { email, password }
});

export const logout = () => ({
    type: LOGOUT
});
