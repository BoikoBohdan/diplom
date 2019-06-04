import React, { Component } from 'react'
import Button from '@material-ui/core/Button'
import dataProvider from '../../DataProvider/dataProvider'
import './style.css'

class Tabs extends Component {
  state = {
    statuses: []
  }
  componentDidMount () {
    dataProvider(
      'GET_CUTOM_LINK',
      `${process.env.MIX_PUBLIC_URL}/api/admin/orders/statuses`
    ).then(res => {
      this.setState({ statuses: Object.values(res.data.statuses) })
    })
  }

  handleOrderStatus (e, key) {
    let order_status = this.state.statuses
    if (e.ctrlKey || e.metaKey) {
      for (let order in order_status) {
        if (order_status[order].key == key) {
          if (
            order_status[order].active == false &&
            !order_status[order].active
          ) {
            order_status[order].active = true
          } else {
            order_status[order].active = false
          }
        }
      }
    } else {
      for (let order in order_status) {
        if (order_status[order].key == key) {
          order_status[order].active = true
        } else {
          order_status[order].active = false
        }
      }
    }

    let sort = []
    for (let order in order_status) {
      if (order_status[order].active == true) {
        sort.push(order_status[order].key)
      }
    }

    this.setState({ statuses: order_status, sort })
  }

  render () {
    return (
      <div className='table__order-buttons'>
        {this.state.statuses.length > 0 &&
          this.state.statuses.map((status, index) => {
            return (
              <Button
                onClick={e => this.handleOrderStatus(e, status.key)}
                key={index}
                className='table-sort__order-button'
                variant='contained'
                color={status.active ? 'primary' : ''}
                className='dashboard-member__search-button'
              >
                {status.value}
              </Button>
            )
          })}
      </div>
    )
  }
}
export default Tabs
