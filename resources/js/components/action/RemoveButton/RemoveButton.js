import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { showNotification, RefreshButton } from 'react-admin';
import { push } from 'react-router-redux';
import axios from 'axios';
import Popup from "reactjs-popup";
require('dotenv').config()
import './style.css'
class RemoveButton extends Component {

  state = {
    openPopup: false
  }

  handleClick = () => {
    const { push, record, showNotification, lang } = this.props;
    axios.delete(`${process.env.MIX_PUBLIC_URL}${this.props.basePath}/${this.props.record.id}`,
      {
        headers: {
          "content-type": `application/x-www-form-urlencoded`,
          "Authorization": `Bearer ${localStorage.getItem('token')}`,
        }
      })
      .then(() => {
        showNotification(lang.elementDeleted);
        document.getElementById('triger_for_refresh').click()
        this.closePopup()
      })
      .catch((e) => {
          replace('Error: Element not deleted', 'warning')
        window.location.replace('/#/login')
        this.closePopup()
      });
  }

  openPopup = () => {
    this.setState({ openPopup: true })
  }

  closePopup = () => {
    this.setState({ openPopup: false })
  }

  render () {
    return (
      <>
        <Popup
          open={this.state.openPopup}
          closeOnDocumentClick
          onClose={this.closePopup}
          className="popup-import"
        >
          <div className='popup-wrapper'>
            <h1 className='popup-header'>Are you sure to delete this record?</h1>
            <div className='popup-buttons'>
              <button onClick={this.closePopup} className='popup-button popup-button__white'>Not now</button>
              <button onClick={this.handleClick} className='popup-button popup-button__red'>Yes</button>
              <RefreshButton id='triger_for_refresh' />
            </div>
          </div>
        </Popup>
        <button className='delete-btn' onClick={this.openPopup}>
          <svg className="MuiSvgIcon-root-98" focusable="false" viewBox="0 0 24 24" aria-hidden="true" fill='red'>
            <g>
              <path id='delete-icon' d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z">
              </path>
            </g>
          </svg>
        </button>
      </>);
  }
}

RemoveButton.propTypes = {
  push: PropTypes.func,
  record: PropTypes.object,
  showNotification: PropTypes.func,
};

const mapStateToProps = state => ({
    lang: state.i18n.messages
})

export default connect(mapStateToProps, {
  showNotification,
  push,
})(RemoveButton);
