import React from 'react'
import StatisticItem from './statistic_item'
import './style.css'
import Circle from './Circle'

const colors = [
  '#2CC0EE',
  '#F39C11',
  '#DD4B39',
  '#26a65a',
  '#2CC0EE',
  '#F39C11',
  '#DD4B39',
  '#26a65a',
  '#2CC0EE',
  '#F39C11',
  '#DD4B39',
  '#26a65a',
  '#DD4B39'
]
localStorage.getItem('role') === 'master_admin' ||
  (localStorage.getItem('role') === 'super_admin' &&
    window.location.replace('/#/profile'))
const Statistick = ({ drivers, orders_delivery, lang }) => {
  let orders = [
    {
      color: '#2E7D32',
      name: 'arrived_to_restaurant',
      title: 'No Name',
      value: 10
    },
    {
      name: 'assigned',
      title: 'Metal',
      value: 0,
      color: '#00E676'
    },
    {
      color: '#C6FF00',
      name: 'on_the_way_to_restaurant',
      title: 'Aluminium',
      value: 0
    }
  ]
  return (
    <>
      <div className='dashboard__charts-wrapper'>
        {orders.length > 0 && (
          <Circle
            lang={lang}
            label={'Count of Materials'}
            orders
            data={orders}
          />
        )}
        {drivers.length > 0 && (
          <Circle lang={lang} label={'Count of Users'} data={drivers} />
        )}
      </div>
    </>
  )
}
export default Statistick
