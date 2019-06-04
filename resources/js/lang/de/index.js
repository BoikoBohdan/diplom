import dashboard from './dashboard'
import buttons from './buttons'
import deliveryDrivers from './deliveryDrivers'
import notification from './notification'
import orders from './orders'
import profile from './profile'
import shifts from './shifts'
import sidebar from './sidebar'
import godsView from './godsView'
import tableCollumns from './tableCollumns'
export default {
    ...dashboard,
    ...buttons,
    ...deliveryDrivers,
    ...notification,
    ...orders,
    ...profile,
    ...sidebar,
    ...tableCollumns,
    ...shifts,
    ...godsView
}
