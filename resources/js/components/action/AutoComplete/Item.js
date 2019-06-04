import React, { Component } from 'react'
import DeleteIcon from '@material-ui/icons/Delete'
import EditIcon from '@material-ui/icons/Edit'
import CheckIcon from '@material-ui/icons/Check'
import Button from '@material-ui/core/Button'
import { MuiThemeProvider, createMuiTheme } from '@material-ui/core/styles'
import red from '@material-ui/core/colors/red'
import { connect } from 'react-redux'
import { showNotification } from 'react-admin'
import { stringify } from 'query-string'
import axios from 'axios'

const redTheme = createMuiTheme({
  typography: {
    useNextVariants: true
  },
  palette: { secondary: red }
})

class Item extends Component {
  state = {
    name: '',
    new_name: '',
    edit: false
  }

  componentWillMount () {
    let name = this.props.item.name
    this.setState({ name, new_name: name })
  }

  handleEdit = () => {
    this.setState({ edit: true })
  }

  handlerNewName = () => event => {
    this.setState({ new_name: event.target.value })
  }

  handleSubmit = () => {
    const { showNotification, lang } = this.props
    let data = { name: this.state.new_name }
    let options = {
      headers: {
        'content-type': `application/x-www-form-urlencoded`,
        Authorization: `Bearer ${localStorage.getItem('token')}`
      },
      data: stringify(data),
      method: 'PUT'
    }
    let url = `/api/admin/companies/${this.props.item.id}`
    if (this.state.new_name) {
      this.setState({ error: '' })
      axios(url, options)
        .then(responce => {
          showNotification(lang.companyNameWasChanged)
          this.setState({ name: this.state.new_name })
          this.props.updateList()
        })
        .catch(error => {
          let errors = ''
          for (let text in error.response.data.errors) {
            errors += error.response.data.errors[text][0]
          }
          this.setState({new_name: this.state.name})
          showNotification(errors, 'warning')
        })
    } else {
      showNotification(lang['can\'t be empty'], 'warning')
    }
    this.setState({ edit: false })
  }

  render () {
    return (
      <div className='dashboard__autosaggest-item'>
        {this.state.edit ? (
          <input value={this.state.new_name} onChange={this.handlerNewName()} />
        ) : (
          <p onClick={() => this.props.getSuggestionValue(this.props.item)}>
            {this.state.name}
          </p>
        )}
        {this.props.role === 'new' && (
          <div className='dashboard__autosaggest-buttons'>
            {this.state.edit ? (
              <Button color='secondary' onClick={() => this.handleSubmit()}>
                <CheckIcon />
              </Button>
            ) : (
              <Button color='secondary' onClick={() => this.handleEdit()}>
                <EditIcon />
              </Button>
            )}
            <MuiThemeProvider theme={redTheme}>
              <Button
                color='secondary'
                onClick={() => this.props.handleOpenPopup(this.props.item.id)}
              >
                <DeleteIcon />
              </Button>
            </MuiThemeProvider>
          </div>
        )}
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
)(Item)
