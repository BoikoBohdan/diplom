import {FETCHED_CHAT_USERS_START, FETCHED_CHAT_USERS_SUCCESS, FETCHED_CHAT_USERS_ERROR, SEARCH_CHAT_USERS,
    FETCHED_MESSAGES_START, FETCHED_MESSAGES_SUCCESS, FETCHED_MESSAGES_ERROR, ADD_NEW_MESSAGE} from '../constantTypes'
import * as R from 'ramda'

const initialState = {
    users: [],
    loading: false,
    error: false,
    search: '',
    messages: []
};

const chatReducer = (state = initialState, action) => {
    switch (action.type) {
        case 'FETCHED_CHAT_USERS_START':
            return {
                ...state,
                loading: true
            };
        case 'FETCHED_CHAT_USERS_SUCCESS':
            return {
                ...state,
                users: action.payload,
            };
        case 'FETCHED_CHAT_USERS_ERROR':
            return {
                ...state,
                error: action.payload,
            };
        case 'SEARCH_CHAT_USERS':
            return {
                ...state,
                search: action.payload,
            };
        case 'FETCHED_MESSAGES_START':
            return {
                ...state,
                loading: true
            };
        case 'FETCHED_MESSAGES_SUCCESS':
            return {
                ...state,
                messages: !R.isEmpty(action.payload) ? action.payload : [],
            };
        case 'ADD_NEW_MESSAGE':
            return {
                ...state,
                messages: R.concat(state.messages, [action.payload]),
            };
        case 'FETCHED_MESSAGES_ERROR':
            return {
                ...state,
                error: action.payload,
            };
        default:
            return state;
    }
};

export default chatReducer
