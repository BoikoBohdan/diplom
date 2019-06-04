import React from 'react'
import Toolbar from '@material-ui/core/Toolbar'
import TextField from '@material-ui/core/TextField'
import Button from '@material-ui/core/Button'
import Select from '@material-ui/core/Select'
import MenuItem from '@material-ui/core/MenuItem'
import { connect } from 'react-redux'
import { showNotification } from 'react-admin'
import dataProvider from '../../../DataProvider/dataProvider'
import './style.css'

class EnhancedTableToolbar extends React.Component {
  state = {
    search: '',
    field: 'all',
    delete_popup: false,
    sort: [],
    statuses: []
  }

  componentDidMount () {
    dataProvider(
      'GET_CUTOM_LINK',
      `${process.env.MIX_PUBLIC_URL}/api/admin/orders/statuses`
    ).then(res => {
      this.setState({ statuses: Object.values(res.data.statuses) })
    })
  }

  handleBulkDelete = () => {
    const { showNotification } = this.props
    dataProvider('DELETE_MANY', `${this.props.resource}/bulk-delete`, {
      data: this.props.selected
    }).then(e => {
      showNotification(lang['Item was deleted'])
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

  handleExport = () => {}

  handleCreate = () => {
    window.location.href = `#/${this.props.resource}/create`
  }

  handleBulkActivate = () => {
    const { showNotification, lang } = this.props
    dataProvider('UPDATE_MANY', `${this.props.resource}/bulk-update`, {
      data: this.props.selected,
      status: 1
    }).then(e => {
      showNotification(lang.itemsWasActivated)
      this.props.refresh()
    })
  }

  handleBulkDeactivate = () => {
    const { showNotification, lang } = this.props
    dataProvider('UPDATE_MANY', `${this.props.resource}/bulk-update`, {
      data: this.props.selected,
      status: 0
    }).then(e => {
      showNotification(lang.itemsWasDeactivated)
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
        if (order_status[order].key == key) {
          order_status[order].active = true
        } else {
          order_status[order].active = false
        }
      }
    }

    let sort = []
    for (let order in order_status) {
      if (order_status[order].active == true) {
        sort.push(order_status[order].key)
      }
    }

    this.setState({ statuses: order_status, sort }, () => {
      this.props.filter(this.state.search, this.state.field, this.state.sort)
    })
  }

  render () {
    const { numSelected, lang } = this.props
    return (
      <Toolbar className='table__tooltip'>
        <div className='dashboard-member__filter-wrapper'>
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
              className='dashboard-member__search-button'>
                {lang.btnSearch}
            </Button>
          </div>
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
                    color={status.active ? 'primary' : ''}
                    className='dashboard-member__search-button'>
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
    lang: state.i18n.messages
})

export default connect(
    mapStateToProps,
  {
    showNotification
  }
)(EnhancedTableToolbar)
