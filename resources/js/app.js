import React from 'react'
import './index.css'
import { Provider } from 'react-redux'
import { BrowserRouter as Router } from 'react-router-dom'
import createAdminStore from './store/createAdminStore'
import createHistory from 'history/createHashHistory'
import customRoutes from './routers/cutomRoutes'
import { Admin, Resource } from 'react-admin'
import MyLoginPage from './pages/login'
import Dashboard from './pages/dashboard'
import LogoutButton from './components/LogoutButton'
import { DeliveryCreate, DeliveryEdit, DeliveryList } from './pages/delivery'
import { ShiftCreate, ShiftEdit, ShiftList } from './pages/Shifts'
import MyLayout from './components/Layout'
import { UserEditComponent, UserList } from './pages/users'
import { OrderCreate, OrderEdit, OrdersList } from './pages/orders'
import authProvider from './components/providers/authProvider'
import dataProvider from './components/DataProvider/dataProvider'
import messages from './lang'

require('dotenv').config()
const token = localStorage.getItem('token')

const history = createHistory()
const i18nProvider = locale => {
  if (locale !== 'en') {
    return messages[locale]
  }
  return messages['en']
}

class App extends React.Component {
  render () {
    document.body.style.overflow = 'visible'
    return (
      <Provider
        store={createAdminStore({
          authProvider,
          dataProvider,
          i18nProvider,
          history
        })}
      >
        <Router>
          <Admin
            title='Uber Eat.ch'
            dashboard={Dashboard}
            loginPage={MyLoginPage}
            logoutButton={LogoutButton}
            authProvider={authProvider}
            dataProvider={dataProvider}
            appLayout={MyLayout}
            history={history}
            customRoutes={customRoutes}
          >
            <Resource
              name={
                localStorage.getItem('role') === 'master_admin' ||
                localStorage.getItem('role') === 'super_admin'
                  ? 'api/admin/drivers'
                  : ''
              }
              options={{ label: 'Users' }}
              list={DeliveryList}
              create={DeliveryCreate}
              edit={DeliveryEdit}
            />

            <Resource
              name='api/admin/orders'
              options={{ label: 'Materials' }}
              list={OrdersList}
              edit={OrderEdit}
              create={OrderCreate}
            />
            {/* {(localStorage.getItem('role') === 'master_admin' ||
              localStorage.getItem('role') === 'super_admin') && (
              <Resource
                name='api/admin/users'
                options={{ label: 'Users' }}
                list={UserList}
                edit={UserEditComponent}
              />
            )} */}
            {/* <Resource
              name='api/admin/shifts'
              options={{ label: 'Shifts' }}
              list={ShiftList}
              edit={ShiftEdit}
              create={ShiftCreate}
            /> */}
          </Admin>
        </Router>
      </Provider>
    )
  }
}

export default App
