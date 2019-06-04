import React, { Component } from 'react'
import Switch from '@material-ui/core/Switch'
import { connect } from 'react-redux';
import axios from '../../DataProvider/request'
import './style.css'
import { showNotification } from 'react-admin';
import { stringify } from 'query-string';
require('dotenv').config()

class ToggleButton extends Component {
  state = {
    checked: false,
    prevPorps: 0
  }

  componentDidMount() {
    let check = (this.props.record.status === 1 || this.props.record.status) === true ? true : false
    this.setState({ checked: check, prevPorps: this.props.record.status })
  }

  static getDerivedStateFromProps(props, state) {
    if (state.prevPorps != props.record.status) {
      return { checked: (props.record.status === 1 || props.record.status) === true ? true : false }
    }
    else {
      return null
    }
  }

  handleChangeStatus = (e) => {
    const { showNotification, lang } = this.props;
    let status = e.target.checked
    let update = status ? 1 : 0
    axios.put(`${process.env.MIX_PUBLIC_URL}${this.props.data.basePath}/${this.props.record.id}`,
      stringify({ status: update }),
      {
        headers: {
          "content-type": `application/x-www-form-urlencoded`,
          "Authorization": `Bearer ${localStorage.getItem('token')}`,
        }
      })
      .then(() => {
        showNotification(`${lang.changeStatustTo} ${status ? lang.statusActive : lang.statusInactive}`);
        this.setState({ checked: status })
      })
      .catch((e) => {
        showNotification(lang.statusNotChanged, 'warning');
        window.location.replace('/#/login')
      });
  }
  render() {
    return (
      <>
        <Switch
          color="primary"
          checked={(this.state.checked === 1 || this.state.checked) === true ? true : false}
          onChange={this.handleChangeStatus} />
      </>
    )
  }
}

const mapStateToProps = state => ({
    lang: state.i18n.messages
})

export default connect(mapStateToProps, {
  showNotification,
})(ToggleButton);
