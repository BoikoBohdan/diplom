import { applyMiddleware, combineReducers, compose, createStore } from 'redux'
import createSagaMiddleware from 'redux-saga'
import { routerMiddleware } from 'react-router-redux'
import { reducer as formReducer } from 'redux-form'
import { all } from 'redux-saga/effects'
import { loginAsync, logoutAsync } from './workers/auth'
import {
  fetchStatusesAsync,
  setStatusAsync,
  cancelStatusesAsync
} from './workers/statuses'
import { fetchDriversAsync } from './workers/drivers'
import {
  fetchUsersAsync,
  searchUsersAsync,
  fetchMessagesAsync,
  addMessageAsync
} from './workers/chat'
import { fetchCancelReasonAsync } from './workers/canceled_reasons'
import {
  fetchOrdersAsync,
  setOrderRequestParamsOrdersAsync,
  fetchAdditionalOrdersAsync,
  assignOrdersAsync,
  addOrderAsync,
  fetchOrdersByDriversAsync,
  addUpdatedOrderAsync,
  filterOrdersAsync
} from './workers/orders'
import formMiddleware from 'ra-core/lib/form/formMiddleware'
import {
  adminReducer,
  adminSaga,
  defaultI18nProvider,
  i18nReducer,
  USER_LOGOUT
} from 'react-admin'

import driversReducer from '../store/reducers/drivers'
import ordersReducer from '../store/reducers/orders'
import loginReducer from '../store/reducers/login'
import statusesReducer from '../store/reducers/statuses'
import cancelReasonsReducer from '../store/reducers/canceled_reasons'
import chatReducer from '../store/reducers/chat'
export default ({
    authProvider,
    dataProvider,
    history,
    i18nProvider = defaultI18nProvider,
    locale = localStorage.getItem('locale_eat') || 'en',
    }) => {
    const reducer = combineReducers({
        admin: adminReducer,
        i18n: i18nReducer(locale, i18nProvider(locale)),
        form: formReducer,
        login: loginReducer,
        drivers: driversReducer,
        orders: ordersReducer,
        statuses: statusesReducer,
        cancel_reasons: cancelReasonsReducer,
        chat: chatReducer
        // add your own reducers here
    })

  const resettableAppReducer = (state, action) =>
    reducer(action.type !== USER_LOGOUT ? state : undefined, action)

  const saga = function * rootSaga () {
    yield all([
      adminSaga(authProvider, dataProvider, i18nProvider),
      loginAsync(),
      fetchDriversAsync(),
      fetchOrdersAsync(),
      fetchAdditionalOrdersAsync(),
      filterOrdersAsync(),
      fetchOrdersByDriversAsync(),
      addOrderAsync(),
      addUpdatedOrderAsync(),
      fetchStatusesAsync(),
      assignOrdersAsync(),
      setStatusAsync(),
      cancelStatusesAsync(),
      fetchCancelReasonAsync(),
      fetchUsersAsync(),
      searchUsersAsync(),
      fetchMessagesAsync(),
      addMessageAsync(),
      setOrderRequestParamsOrdersAsync(),
      logoutAsync()
    ])
  }

  const sagaMiddleware = createSagaMiddleware()

  const store = createStore(
    resettableAppReducer,
    compose(
      applyMiddleware(
        sagaMiddleware,
        formMiddleware,
        routerMiddleware(history)
        // add your own middlewares here
      ),
      typeof window !== 'undefined' && window.__REDUX_DEVTOOLS_EXTENSION__
        ? window.__REDUX_DEVTOOLS_EXTENSION__()
        : f => f
      // add your own enhancers here
    )
  )
  sagaMiddleware.run(saga)
  return store
}
