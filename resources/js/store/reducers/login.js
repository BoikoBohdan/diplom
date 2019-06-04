import {LOGIN_START, LOGIN_ERROR, LOGIN_SUCCESS} from '../constantTypes'

const initialState = {
    token: localStorage.getItem('token') || '',
    loading: false,
    error: [],
    isLoggedIn: false,
    role: localStorage.getItem('role') || '',
};

const loginReducer = (state = initialState, action) => {
    switch (action.type) {
        case 'LOGIN_START':
            return {
                ...state,
                error: [],
                loading: true
            };
        case 'LOGIN_SUCCESS':
            return {
                ...state,
                isLoggedIn: true,
                role: action.payload
            };
        case 'LOGIN_ERROR':
            return {
                ...state,
                loading: false,
                error: state.error.push(action.payload),
            };
        case 'LOGOUT':
            return {
                token: '',
                loading: false,
                error: [],
            };
        default:
            return state;
    }
};

export default loginReducer
