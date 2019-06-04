import React from 'react'
import PrivateRoute from './private_route'
import GodsView from '../pages/GodsView'
import Dashboard from '../pages/dashboard'
import { Profile, EditProfile } from '../pages/profile'
import { NewAccount } from '../pages/new_account'
import Chat from '../pages/Chat'

export default [
  <PrivateRoute path='/dashboard' component={Dashboard} />,
  <PrivateRoute path='/gods_view' component={GodsView} />,
  <PrivateRoute path='/profile/edit' component={EditProfile} />,
  <PrivateRoute path='/profile' component={Profile} />,
  <PrivateRoute path='/new_user' component={NewAccount} noLayout />,
  <PrivateRoute path='/chat' component={Chat} />
]
