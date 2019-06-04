import React, { Component } from 'react'
import OrderStatus from '../../../../components/action/OrderStatus'
import './style.css'
let DoughnutChart = require('react-chartjs').Doughnut

class Circle extends Component {
  state = {
    status: '',
    value: 0,
    total: 0,
    activeColor: null
  }

  componentDidMount () {
    let sum = 0
    for (let key in this.props.data) {
      sum += this.props.data[key].value
    }
    this.setState({
      status: this.props.data[0].title,
      value: this.props.data[0].value,
      total: sum
    })
  }

  onClickFunc = evt => {
    let activePoints = this.refs.chart.getSegmentsAtEvent(evt)
    this.props.data[0].color = '#FF5A5E'
    if (activePoints[0] !== undefined) {
      this.setState(() => ({
        status: activePoints[0].title,
        value: activePoints[0].value
      }))
    }
  }

  render () {
    let persentage = ((this.state.value / this.state.total) * 100).toFixed(2)
    const {lang} = this.props
    return (
      <div className='statContainer'>
        <div className='statHeader'>
          <span className='statTitle' />
        </div>
        <div className='ordersChartBox'>
          <div className='dataBox'>
            <div className='coBox'>
              <DoughnutChart
                className='dashboard-chart'
                ref='chart'
                data={this.props.data}
                options={{ percentageInnerCutout: 70 }}
                width='200'
                height='200'
                onClick={this.onClickFunc}
              />
            </div>
            <div className='chartData'>
              <p className='circleHeader'>{lang[this.state.status]}</p>
              <p className='circleNumbers'>
                {persentage}% ({this.state.value})
              </p>
            </div>
          </div>
          <div className='ordersTextBox'>
            <p className='ordersHeader'>
              {this.props.label}: {this.state.total}
            </p>
            {this.props.orders
              ? this.props.data.map((item, index) => {
                return <OrderStatus lang={lang} key={index} order={item} />
              })
              : this.props.data.map((item, index) => {
                return (
                  <p key={index} className='ordersNumbers'>
                    {item.title} : {item.value}
                  </p>
                )
              })}
          </div>
        </div>
      </div>
    )
  }
}

export default Circle
