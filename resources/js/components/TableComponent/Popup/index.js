import React from 'react'
import { MuiThemeProvider } from '@material-ui/core/styles'
import Button from '@material-ui/core/Button'
import Popup from 'reactjs-popup'
import { redTheme } from '../../Layout/Themes'
import DeleteIcon from '@material-ui/icons/Delete'

export const DeletePopup = ({ status, onClose, onCancel, onSuccess }) => {
  return (
    <Popup
      open={status}
      onClose={onClose}
      fullWidth
      className='dashboard-member__popup-delete'
      aria-labelledby='max-width-dialog-title'
    >
      <MuiThemeProvider theme={redTheme}>
        <h2>Are you sure you want delete this item?</h2>
        <div className='dashboard-popup__buttons-actions'>
          <Button color='primary' variant='contained' onClick={onCancel}>
            CANCEL
          </Button>
          <div className='dashboard-popup__buttons-spacer' />
          <Button color='secondary' variant='contained' onClick={onSuccess}>
            <DeleteIcon />
            DELETE
          </Button>
        </div>
      </MuiThemeProvider>
    </Popup>
  )
}
