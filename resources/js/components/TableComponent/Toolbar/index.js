import React from 'react'
import Tooltip from '@material-ui/core/Tooltip'
import Toolbar from '@material-ui/core/Toolbar'
import DeleteIcon from '@material-ui/icons/Delete'
import TextField from '@material-ui/core/TextField'
import ActiveIcon from '@material-ui/icons/Check'
import InactiveIcon from '@material-ui/icons/Cancel'
import AddIcon from '@material-ui/icons/Add'
import Button from '@material-ui/core/Button'
import Select from '@material-ui/core/Select'
import MenuItem from '@material-ui/core/MenuItem'
import { MuiThemeProvider, createMuiTheme } from '@material-ui/core/styles'
import DateAndCityFilter from '../../../components/DateAndCityFilter'
import red from '@material-ui/core/colors/red'
import { connect } from 'react-redux'
import { showNotification } from 'react-admin'
import dataProvider from '../../DataProvider/dataProvider'
import Export from '../../action/Exporter'
import Popup from 'reactjs-popup'
import * as R from 'ramda'
import './style.css'

const redTheme = createMuiTheme({
  typography: {
    useNextVariants: true
  },
  palette: { secondary: red }
})

class TableToolbar extends React.Component {
  state = {
    search: '',
    field: 'all',
    delete_popup: false,
    sort: '',
    statuses: [
       { key: 1, value: 'No Name' } ,
       { key: 2, value: 'Metal' } 
    ]
  }

  // componentDidMount () {
  //   dataProvider(
  //     'GET_CUTOM_LINK',
  //     `${process.env.MIX_PUBLIC_URL}/api/admin/orders/statuses`
  //   ).then(res => {
  //     this.setState({ statuses: Object.values(res.data.statuses) })
  //   })
  // }

  handleBulkDelete = () => {
    const { showNotification } = this.props
    dataProvider('DELETE_MANY', `${this.props.resource}/bulk-delete`, {
      data: this.props.selected
    }).then(e => {
      showNotification(
        this.props.selected.length > 1
          ? 'Items were deleted'
          : 'Item was deleted'
      )
      this.props.refresh()
    })
  }

  handleChangeField = event => {
    this.setState({ field: event.target.value })
  }
  handleChangeSearch = event => {
    this.setState({ search: event.target.value })
  }

  handleSearch = () => {
    this.props.filter(this.state.search, this.state.field)
  }

  handleExport = () => {
    Export()
  }

  handleCreate = () => {
    window.location.href = `#/${this.props.resource}/create`
  }

  handleBulkActivate = () => {
    const { showNotification } = this.props
    dataProvider('UPDATE_MANY', `${this.props.resource}/bulk-update`, {
      data: this.props.selected,
      status: 1
    }).then(e => {
      showNotification(
        this.props.selected.length > 1
          ? 'Items were activated'
          : 'Item was activated'
      )
      this.props.refresh()
    })
  }

  handleBulkDeactivate = () => {
    const { showNotification } = this.props
    dataProvider('UPDATE_MANY', `${this.props.resource}/bulk-update`, {
      data: this.props.selected,
      status: 0
    }).then(e => {
      showNotification(
        this.props.selected.length > 1
          ? 'Items were deactivated'
          : 'Item was deactivated'
      )
      this.props.refresh()
      this.handleClose()
    })
  }

  handleClose = () => {
    this.setState({ delete_popup: false })
  }

  handleOpenPopup = () => {
    this.setState({ delete_popup: true })
  }

  handleOrderStatus (e, key) {
    let order_status = this.state.statuses
    if (e.ctrlKey || e.metaKey) {
      for (let order in order_status) {
        if (order_status[order].key == key) {
          if (
            order_status[order].active == false &&
            !order_status[order].active
          ) {
            order_status[order].active = true
          } else {
            order_status[order].active = false
          }
        }
      }
    } else {
      for (let order in order_status) {
        if (order_status[order].key === key) {
          if (order_status[order].active) {
            order_status[order].active = false
          } else {
            order_status[order].active = true
          }
        } else {
          order_status[order].active = false
        }
      }
    }

    let sort = ''
    for (let order in order_status) {
      if (order_status[order].active === true) {
        sort += `${order_status[order].key},`
      }
    }

    this.setState({ statuses: order_status, sort }, () => {
      this.props.filter(this.state.search, this.state.field, this.state.sort)
    })
  }

  render () {
    const { numSelected, searchEnabled, filterEnabled, lang, role } = this.props
    return (
      <Toolbar
        className={
          numSelected > 0
            ? 'table__tooltip table__tooltip-active'
            : 'table__tooltip'
        }
      >
        <div className='table__toltip-actions'>
          {numSelected > 0 && (
            <div>
              <p color='inherit'>
                {numSelected} {numSelected > 1 ? 'items' : 'item'} selected
              </p>
            </div>
          )}
          {numSelected > 0 ? (
            <div>
              <MuiThemeProvider theme={redTheme}>
                <Tooltip title={lang.btnDelete}>
                  <Button color='secondary' onClick={this.handleOpenPopup}>
                    <DeleteIcon />
                    {lang.btnDelete}
                  </Button>
                </Tooltip>
              </MuiThemeProvider>
              <Tooltip title={lang.btnActivate}>
                <Button color='primary' onClick={this.handleBulkActivate}>
                  <ActiveIcon />
                  {lang.btnActivate}
                </Button>
              </Tooltip>
              <Tooltip title={lang.btnDeActivate}>
                <Button color='primary' onClick={this.handleBulkDeactivate}>
                  <InactiveIcon />
                  {lang.btnDeActivate}
                </Button>
              </Tooltip>
              <Popup
                open={this.state.delete_popup}
                onClose={this.handleClose}
                fullWidth
                className='dashboard-member__popup-delete'
                aria-labelledby='max-width-dialog-title'
              >
                <>
                  <h2 className='dashboard-member__delete-button'>
                    {`Are you sure you want delete ${
                      numSelected > 1 ? 'these' : 'this'
                    } ${numSelected} ${numSelected > 1 ? 'items' : 'item'}?`}
                  </h2>
                  <MuiThemeProvider theme={redTheme}>
                    <div className='dashboard-popup__buttons-actions'>
                      <Button
                        color='primary'
                        variant='contained'
                        onClick={() => this.handleClose()}
                      >
                        {lang.btnCancel}
                      </Button>
                      <div className='dashboard-popup__buttons-spacer' />
                      <Button
                        color='secondary'
                        variant='contained'
                        onClick={() => this.handleBulkDelete()}
                      >
                        <DeleteIcon />
                        {lang.btnEdit}
                      </Button>
                    </div>
                  </MuiThemeProvider>
                </>
              </Popup>
            </div>
          ) : (
            <div className='dashboard-member__filter-wrapper'>
              {filterEnabled && (
                <DateAndCityFilter
                  lang={lang}
                  handleFilterByDateAndCity={
                    this.props.handleFilterByDateAndCity
                  }
                />
              )}
              {searchEnabled && (
                <div className='dashboard-member__filter-left'>
                  <TextField
                    id='standard-uncontrolled'
                    label={lang.btnSearch}
                    onChange={this.handleChangeSearch}
                  />
                  <div className='dashboard-member__select-field'>
                    <Select
                      value={this.state.field}
                      onChange={this.handleChangeField}
                    >
                      <MenuItem value='all'>
                        <em>{lang.tableFilterAll}</em>
                      </MenuItem>
                      {this.props.rows.map((item, index) => {
                        if (item.filter) {
                          return (
                            <MenuItem value={item.name} key={index}>
                              {item.label}
                            </MenuItem>
                          )
                        }
                      })}
                    </Select>
                  </div>
                  <Button
                    onClick={this.handleSearch}
                    variant='contained'
                    color='primary'
                    className='dashboard-member__search-button'
                  >
                    {lang.btnSearch}
                  </Button>
                </div>
              )}

              <div className='dashboard-member__filter-left'>
                {this.props.create && (
                  <Button
                    color='primary'
                    disabled={R.equals(role, 'super_admin')}
                    onClick={this.handleCreate}
                  >
                    <AddIcon />
                    {lang.btnCreate}
                  </Button>
                )}
                <Button color='primary' onClick={this.handleExport}>
                  <svg
                    className='dashboard-member__export-icon'
                    focusable='false'
                    viewBox='0 0 24 24'
                    aria-hidden='true'
                  >
                    <g>
                      <path d='M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z' />
                    </g>
                  </svg>
                  {lang.btnExport}
                </Button>
              </div>
            </div>
          )}
        </div>
        {this.props.order && (
          <div className='table__order-buttons'>
            {this.state.statuses.length > 0 &&
              this.state.statuses.map((status, index) => {
                return (
                  <Button
                    onClick={e => this.handleOrderStatus(e, status.key)}
                    key={index}
                    className='table-sort__order-button'
                    variant='contained'
                    color={status.active ? 'primary' : 'inherit'}
                    className='dashboard-member__search-button'
                  >
                    {status.value}
                  </Button>
                )
              })}
          </div>
        )}
      </Toolbar>
    )
  }
}

const mapStateToProps = state => ({
  role: state.login.role,
  lang: state.i18n.messages
})

export default connect(
  mapStateToProps,
  {
    showNotification
  }
)(TableToolbar)
