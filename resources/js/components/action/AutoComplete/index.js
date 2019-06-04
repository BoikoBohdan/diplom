import React from 'react'
import TextField from '@material-ui/core/TextField'
import Popup from 'reactjs-popup'
import dataProvider from '../../DataProvider/dataProvider'
import DeleteIcon from '@material-ui/icons/Delete'
import Button from '@material-ui/core/Button'
import { MuiThemeProvider, createMuiTheme } from '@material-ui/core/styles'
import red from '@material-ui/core/colors/red'
import Card from '@material-ui/core/Card'
import { connect } from 'react-redux'
import { showNotification } from 'react-admin'
import Item from './Item'
import './style.css'

const redTheme = createMuiTheme({
  typography: {
    useNextVariants: true
  },
  palette: { secondary: red }
})

class AutoComplete extends React.Component {
  state = {
    companies: [],
    suggestions: [],
    selected_name: '',
    selected_id: '',
    role: '',
    show_list: false,
    name: '',
    delete_item: 0,
    delete_popup: false,
    current_name: '',
    error: false
  }

  componentWillMount () {
    this.setState({
      current_name: this.props.defaultValue ? this.props.defaultValue : ''
    })
    this.updateList()
  }

  componentDidMount () {
    document.getElementById('standard-name').setAttribute('autocomplete', 'off')
    document.addEventListener('mousedown', this.handleClickOutside)
  }

  componentWillUnmount () {
    document.removeEventListener('mousedown', this.handleClickOutside)
  }

  handleClickOutside = event => {
    if (this.wrapperRef && !this.wrapperRef.contains(event.target)) {
      this.setState({ show_list: false })
    }
  }
  setWrapperRef = node => {
    this.wrapperRef = node
  }

  updateList = () => {
    let role = this.props.role
    let url = `/api/admin/companies?search=`
    dataProvider('GET_CUTOM_LINK', `${process.env.MIX_PUBLIC_URL}/${url}`)
      .then(responce => {
        this.setState({ companies: Object.values(responce.data), role }, () => {
          this.handlerSort()
        })
      })
      .catch(error => {
        this.setState({ error: error.response.data.errors[0].name, role })
      })
  }

  getSuggestionValue = suggestion => {
    if (this.state.role === 'add') this.props.selected(suggestion)
    this.setState({ name: suggestion.name, show_list: false })
  }

  handlerSort = () => {
    let { companies, suggestions } = this.state
    let name = this.state.current_name
    suggestions = []
    if (name.length > 0) {
      for (let item in companies) {
        let lowe_item = companies[item].name.toLowerCase()
        if (lowe_item.indexOf(name.toLowerCase()) !== -1) {
          suggestions.push(companies[item])
        }
      }
    } else {
      suggestions = companies
    }
    this.setState({ name, suggestions })
    if (this.state.role === 'new') this.props.changeName(name)
  }

  handleChange = () => event => {
    this.setState({ current_name: event.target.value, show_list: true }, () =>
      this.handlerSort()
    )
  }

  handlerDelete = () => {
    const { showNotification } = this.props
    let url = `/api/admin/companies/` + this.state.delete_id
    dataProvider('DELETE_CUTOM_LINK', `${process.env.MIX_PUBLIC_URL}/${url}`)
      .then(responce => {
        this.updateList()
        this.setState({ delete_popup: false })
        showNotification(lang.companyWasDeleted)
      })
      .catch(error => {
        showNotification(error.response.data.errors[0].name)
      })
  }

  handleOpenPopup = id => {
    this.setState({ delete_id: id, delete_popup: true })
  }

  handleClose = () => {
    this.setState({ delete_popup: false })
  }

  handeShowList = () => {
    let list = this.state.companies
    this.setState({ show_list: true, suggestions: list })
  }

  render () {
    if (this.props.refresh) {
      this.setState({ current_name: '', show_list: false })
      this.updateList()
      this.props.onRefreshFinish()
    }
    const { suggestions, show_list } = this.state
    const { lang } = this.props
    return (
      <div className='dashboard__autosaggest-wrapper' ref={this.setWrapperRef}>
        <TextField
          type='company'
          id='standard-name'
          label={this.props.label || lang['Select Company']}
          value={this.state.name}
          onChange={this.handleChange()}
          margin='normal'
          autocomplete='off'
          autocomplete='new-password'
          onBlur={() => {
            this.props.role !== 'new'
              ? setTimeout(() => this.setState({ show_list: false }), 150)
              : () => {}
            this.props.selected()
          }}
          error={this.props.show_error}
          autocomplete='off'
        />
        {show_list &&
          suggestions &&
          (suggestions.length > 0 ? (
            <Card
              className='dashboard__autosaggest-list'
              role={this.props.role}
            >
              {suggestions.map(item => {
                return (
                  <Item
                    key={item.id}
                    getSuggestionValue={this.getSuggestionValue}
                    handleEdit={this.handleEdit}
                    handleOpenPopup={this.handleOpenPopup}
                    item={item}
                    updateList={this.updateList}
                    role={this.props.role}
                  />
                )
              })}
            </Card>
          ) : this.props.role !== 'new' ? (
            <Card
              className='dashboard__autosaggest-list'
              role={this.props.role}
            >
              <p className='no__result'>{lang['No such result']}</p>
            </Card>
          ) : (
            ''
          ))}
        <Popup
          open={this.state.delete_popup}
          onClose={this.handleClose}
          className='dashboard-member__popup-delete'
        >
          <>
            <h2>
              Are you sure you want delete this item and all relation drivers?
            </h2>
            <div className='dashboard-popup__buttons-actions'>
              <Button
                color='primary'
                variant='contained'
                onClick={() => this.handleClose()}
              >
                {lang.btnCancel}
              </Button>
              <div className='dashboard-popup__buttons-spacer' />
              <MuiThemeProvider theme={redTheme}>
                <Button
                  color='secondary'
                  variant='contained'
                  onClick={() => this.handlerDelete()}
                >
                  <DeleteIcon />
                  {lang.btnDelete}
                </Button>
              </MuiThemeProvider>
            </div>
          </>
        </Popup>
      </div>
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
)(AutoComplete)
