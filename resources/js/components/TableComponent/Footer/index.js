import React from 'react'
import TableFooter from '@material-ui/core/TableFooter'
import Select from '@material-ui/core/Select'
import MenuItem from '@material-ui/core/MenuItem'
import Left from '@material-ui/icons/ChevronLeft'
import Right from '@material-ui/icons/ChevronRight'
import './style.css'

export class Footer extends React.Component {
  handleChange = event => {
    this.props.handleChangeperPage(event.target.value)
  }

  handlerPrev = () => {
    let new_page = this.props.page
    new_page--
    this.props.handleChangePage(new_page)
  }

  handlerNext = () => {
    let new_page = this.props.page
    new_page++
    this.props.handleChangePage(new_page)
  }

  render () {
    return (
      <div className='footer__tool-bar'>
        <span className='table__pows-per-page--text'>{this.props.lang.tableRowsPerPage}: </span>
        <Select
          value={this.props.perPage}
          onChange={this.handleChange}
          name='perPage'
        >
          <MenuItem value={5}>5</MenuItem>
          <MenuItem value={10}>10</MenuItem>
          <MenuItem value={25}>25</MenuItem>
        </Select>
        <div className='table__curent-page-wrapper'>
          <span className='table__from-to-curent'>
            {
                this.props.page === 1
                    ? this.props.page * this.props.page
                    : this.props.perPage
            }
            -
            {
                this.props.page * this.props.perPage < this.props.total
                    ? this.props.page * this.props.perPage
                    : `${this.props.total} ${this.props.lang.tableOf} ${this.props.total}`
            }
            </span>
          {this.props.page !== 1 && (
            <>
              <Left onClick={this.handlerPrev} className='table__change-page' />
            </>
          )}
          <span className='table__curent-page'>{this.props.page}</span>
          {this.props.page < this.props.total / this.props.perPage ? (
            <Right onClick={this.handlerNext} className='table__change-page' />
          ) : (
            <div className='table__change-page-empty' />
          )}
        </div>
      </div>
    )
  }
}
