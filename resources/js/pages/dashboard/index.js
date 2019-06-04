import React from 'react'
import './style.css'
import {connect} from 'react-redux'
import {getResources, Title} from 'react-admin'
import Card from '@material-ui/core/Card'
import CardTitle from './title'
import Statistick from './statistick'
import Invites from './intites'
import MemberList from './member__lits'
import dataProvider from '../../components/DataProvider/dataProvider'
import authProvider from '../../components/auth/authProvider'
import CircularIndeterminate from '../../components/LoadingSpinner'
const token = localStorage.getItem('token')

class Dashboard extends React.Component {
  state = {
    drivers: [],
    orders: [],
    orders_delivery: [],
    error: ''
  }

  componentDidMount () {
    localStorage.setItem('file_name', 'admins')
    dataProvider('GET_MANY', 'api/admin/statistics', this.generateParams())
      .then(data => {
        this.setState({
          drivers: data.drivers,
          orders: data.orders,
          orders_delivery: data.orders_delivery
        })
      })
      .catch(error => {
        this.setState({ error: 'error' })
        authProvider('AUTH_LOGOUT')
      })
  }

  generateParams = () => {
    return {
      pagination: {
        page: '',
        perPage: ''
      },
      sort: {
        field: '',
        order: ''
      },
      filter: {
        q: '',
        name: ''
      }
    }
  }

  render () {
      const {lang} = this.props
      return token ? (
      <>
        <Title title={lang.dashboardTitle} />
        <Card className='dashboard__card'>
          <CardTitle name={lang.dashboardTitle} />
          <Statistick
            lang={lang}
            orders={this.state.orders}
            orders_delivery={this.state.orders_delivery}
            drivers={this.state.drivers}
          />
        </Card>
        <Card className='dashboard__card'>
          <CardTitle name={lang.invite_members_title} />
          <Invites lang={lang} />
        </Card>
        <Card className='dashboard__card'>
          <CardTitle name={lang.member_list_title} />
          <MemberList lang={lang}/>
        </Card>
        <Card className='dashboard__card'>
          <CardTitle name={lang.reports} />
        </Card>
      </>
    ) : (
      <CircularIndeterminate />
    )
  }
}

const mapStateToProps = state => ({
    resources: getResources(state),
    lang: state.i18n.messages
})

export default connect(mapStateToProps)(Dashboard)
