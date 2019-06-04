import React, { Component } from 'react'
import './style.css'
export default class OrderStatus extends Component {
  render () {
    const { order, lang } = this.props
    return (
      <div className='order-status'>
        <div
          className='order-status__color-block'
          style={{ background: order.color }}
        />
        <div className='order-status__text'>{order.title}</div>
      </div>
    )
  }
}
