import ShiftList from './ShiftList'
import ShiftCreate from './ShiftCreate'
import ShiftEdit from './ShiftEdit'

export {ShiftList}
export {ShiftCreate}
export {ShiftEdit}

export const styleNotification = {
    NotificationItem: {
        DefaultStyle: {
            margin: '10px 5px 2px 1px',
            background: 'rgba(0,0,0,.8)',
            border: 'none',
            borderRadius: '5px'
        },
        success: {
            color: 'green'
        },
        error: {
            color: 'red',
            fontSize: 12
        }
    }
}
