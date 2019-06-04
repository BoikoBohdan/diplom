import {
    ADD_MESSAGES_ASYNC,
    FETCHED_CHAT_USERS,
    FETCHED_MESSAGES,
    FETCHED_MESSAGES_SUCCESS,
    FILTER_CHAT_USERS
} from '../constantTypes'

export const fetchChatUsers = payload => {
    return {
        type: FETCHED_CHAT_USERS,
        params: payload
    }
};

export const filterChatUsers = payload => {
    return {
        type: FILTER_CHAT_USERS,
        params: payload
    }
};

export const fetchMessages = payload => {
    return {
        type: FETCHED_MESSAGES,
        params: payload
    }
};

export const addMessage = payload => {
    return {
        type: ADD_MESSAGES_ASYNC,
        params: payload
    }
};

export const clearMessages = payload => {
    return {
        type: FETCHED_MESSAGES_SUCCESS,
        payload: payload || []
    }
};
