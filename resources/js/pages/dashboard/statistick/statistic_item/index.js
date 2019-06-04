import React from 'react'
import Icons from './statistic_icon'
import './style.css'
export default ({title, type, color, count}) => {
  return (
    <li className='dashboard-statistic__item' style={{ 'backgroundColor': color }}>
      <Icons icon={type} />
      <div className='dashboard-statistic__item-info'>
        <h3>{title}</h3>
        <h6>{count}</h6>
      </div>
    </li>
  )
}
