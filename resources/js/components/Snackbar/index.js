import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { withStyles } from '@material-ui/core/styles'
import Button from '@material-ui/core/Button'
import Snackbar from '@material-ui/core/Snackbar'
import IconButton from '@material-ui/core/IconButton'
import CloseIcon from '@material-ui/icons/Close'

const styles = theme => ({
  close: {
    padding: theme.spacing.unit / 2
  }
})

class SimpleSnackbar extends Component {
  state = {
    open: false
  }

  static getDerivedStateFromProps (nextProps, prevState) {
    if (nextProps.open === prevState.open) {
      return null
    } else {
      return {
        open: true
      }
    }
  }

  handleClick = () => {
    this.setState({ open: true })
  }

  handleClose = (event, reason) => {
    // if (reason === 'clickaway') {
    //     return;
    // }
    console.log(123123, '123')
    this.setState({ open: false })
  }

  render () {
    const { classes, message, undo, closable, styles } = this.props
    return (
      <div>
        <Snackbar
          anchorOrigin={{
            vertical: 'bottom',
            horizontal: 'center'
          }}
          className={styles}
          open={this.state.open}
          autoHideDuration={3000}
          onClose={this.handleClose}
          ContentProps={{
            'aria-describedby': 'message-id'
          }}
          message={<span id='message-id'>{message}</span>}
          action={[
            undo && (
              <Button
                key='undo'
                color='secondary'
                size='small'
                onClick={this.handleClose}
              >
                UNDO
              </Button>
            ),
            closable && (
              <IconButton
                key='close'
                aria-label='Close'
                color='inherit'
                className={classes.close}
                onClick={this.handleClose}
              >
                <CloseIcon />
              </IconButton>
            )
          ]}
        />
      </div>
    )
  }
}

SimpleSnackbar.propTypes = {
  classes: PropTypes.object,
  message: PropTypes.string.isRequired,
  undo: PropTypes.bool,
  closable: PropTypes.bool,
  open: PropTypes.bool
}

export default withStyles(styles)(SimpleSnackbar)
