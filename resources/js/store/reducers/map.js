import { SHOW_DRIVER_INFO } from '../constantTypes'

const initialState = {
    isShowDriverInfo: false
};

const mapReducer = (state = initialState, action) => {
    switch (action.type) {
        case 'SHOW_DRIVER_INFO':
            return {
                ...state,
                loading: action.payload
            };
        default:
            return state;
    }
};

export default mapReducer
