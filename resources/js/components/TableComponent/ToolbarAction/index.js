import React from 'react'

export default function BulkAction () {
  return (
    <>
      <MuiThemeProvider theme={redTheme}>
        <Tooltip title='Delete'>
          <Button color='secondary' onClick={this.handleOpenPopup}>
            <DeleteIcon />
            DELETE
          </Button>
        </Tooltip>
      </MuiThemeProvider>
      <Tooltip title='Activate'>
        <Button color='primary' onClick={this.handleBulkActivate}>
          <ActiveIcon />
          ACTIVATE
        </Button>
      </Tooltip>
      <Tooltip title='Deactivate'>
        <Button color='primary' onClick={this.handleBulkDeactivate}>
          <InactiveIcon />
          DEACTIVATE
        </Button>
      </Tooltip>
    </>
  )
}
