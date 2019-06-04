import dashboard from './dashboard'
import buttons from './buttons'
import deliveryDrivers from './deliveryDrivers'
import notification from './notification'
import orders from './orders'
import profile from './profile'
import shifts from './shifts'
import sidebar from './sidebar'
import godsView from './godsView'
import tableColums from './tableColums'
import admin from './admin'
export default {
    ...dashboard,
    ...buttons,
    ...deliveryDrivers,
    ...notification,
    ...orders,
    ...profile,
    ...sidebar,
    ...tableColums,
    ...shifts,
    ...godsView,
    ...admin
}
