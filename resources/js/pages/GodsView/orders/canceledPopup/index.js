import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { withStyles } from '@material-ui/core/styles'
import Button from '@material-ui/core/Button'
import Popover from '@material-ui/core/Popover'
import {compose} from 'recompose'
import {connect} from 'react-redux'
import {cancelStatus} from '../../../../store/actions/statuses'
import {setStatus} from '../../../../api/statuses'
import FormControl from '@material-ui/core/FormControl'
import Select from '@material-ui/core/Select'
import InputLabel from '@material-ui/core/InputLabel'
import MenuItem from '@material-ui/core/MenuItem'
import SnackBar from 'react-material-snackbar'
import * as R from 'ramda'

const styles = theme => ({
  buttonWrapper: {
    display: 'flex',
    justifyContent: 'center'
  },
  popover_body: {
      width: '500px',
      padding: 20,
      display: 'flex',
      flexDirection: 'column',
      justifyContent: 'center'
  },
  reason: {
      fontSize: 13
  },
  adminReason: {},
  select: {
      width: '100%'
  },
  formControl: {
    margin: theme.spacing.unit,
    width: '100%',
  }
});

class CanceledPopover extends Component {
  state = {
    anchorEl: null,
    adminMainReason: '',
    additionalReason: '',
    isShowNotification: false
  };

  handleClick = event => {
    this.setState({
      anchorEl: event.currentTarget,
    });
  };

  handleClose = () => {
    this.setState({
      anchorEl: null,
    });
  };

  changeStatusHandler = (id, status) => {
    setStatus(id, status)
        .then(res => console.log(res))
        .catch(err => console.log(err.response))
  }

  changeCancelStatusConfirm = (id, adminMainReason, additionalReason,) => {
    this.setState({
      isShowNotification: true
    })
    const reasons = {
      "additional_cancel_reason_id": id,
      "drivers_reason": additionalReason,
      "admins_reason": adminMainReason
    }
    this.props.dispatch(cancelStatus({id, reasons}))
  }

  handleChange = event => {
    this.setState({ [event.target.name]: event.target.value}
    );
  };

  render() {
    const { classes, id, reasons, message } = this.props
    const { anchorEl, adminMainReason, additionalReason, isShowNotification } = this.state

    isShowNotification && setTimeout(() => this.setState({
      isShowNotification: false
    }), 3000)

    // const mainReasonValue = !R.isEmpty(adminMainReason) ? adminMainReason.value : 'reason'
    const open = Boolean(anchorEl)
    return (
      <div>
        <Button
          className={classes.button}
          aria-owns={open ? 'canceled-popper' : undefined}
          aria-haspopup="true"
          size="small"
          color="primary"
          onClick={this.handleClick}
        >
          Show Details
        </Button>
        <Popover
          id="canceled-popper"
          open={open}
          anchorEl={anchorEl}
          onClose={this.handleClose}
          anchorOrigin={{
            vertical: 'bottom',
            horizontal: 'center',
          }}
          transformOrigin={{
            vertical: 'top',
            horizontal: 'center',
          }}
        >
          <div className={classes.popover_body}>
            <div className={classes.reason}>
                <span><b>Driver reason: </b></span>
                <span>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</span>
            </div>
            <div className={classes.adminReason}>
              <FormControl className={classes.formControl}>
                <InputLabel htmlFor="admin-MainReason">Admin Reason</InputLabel>
                <Select
                  value={this.state.adminMainReason}
                  onChange={this.handleChange}
                  inputProps={{
                    name: 'adminMainReason',
                    id: 'admin-MainReason',
                  }}
                >
                {
                  reasons.map((el) => {
                    return (
                      <MenuItem value={el.info} key={el.id}>{el.info}</MenuItem>
                    )
                  })
                }
                </Select>
          </FormControl>
          {
              reasons.map((el) => {
              return (
                R.equals(adminMainReason, el.info) &&
                <div key={el.id}>
                  <FormControl className={classes.formControl}>
                  <InputLabel htmlFor="admin-additionalReason">{`${el.info}`}</InputLabel>
                  <Select
                    value={this.state.additionalReason}
                    onChange={this.handleChange}
                    className={classes.select}
                    inputProps={{
                      name: 'additionalReason',
                      id: 'admin-additionalReason',
                    }}
                  >
                  {
                    el.additional_info.map((reason) => {
                      return (
                        <MenuItem value={reason.info} key={reason.info}>{reason.info}</MenuItem>
                      )
                    })
                  }
                  </Select>
          </FormControl>
                </div>
              )
            })
          }
            </div>
            <div className={classes.buttonWrapper}>
                <Button color="primary" size="small" onClick={() => this.changeCancelStatusConfirm(id, adminMainReason, additionalReason)}>Confirm</Button>
                <Button color="secondary" size="small" onClick={() => this.changeStatusHandler(id, 9)}>Cancel</Button>
                <Button size="small" onClick={() => this.changeStatusHandler(id, 0)}>Reassign</Button>
            </div>
          </div>
        </Popover>
        {
          <SnackBar
            show={R.equals(message, 'success') && isShowNotification}
            timer={3000}>
            <p>Status was cancel succesfully</p>
          </SnackBar>
        }
      </div>
    );
  }
}

CanceledPopover.propTypes = {
  classes: PropTypes.object.isRequired,
};

const mapStateToProps = state => {
    return {
        reasons: state.cancel_reasons.cancel_reasons,
        message: state.statuses.cancelStatus
    }
}

export default compose(
    connect(mapStateToProps),
    withStyles(styles)
)(CanceledPopover)
