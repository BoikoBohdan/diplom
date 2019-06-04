import React from 'react'
import 'typeface-roboto'
import {connect} from 'react-redux'
import {getResources, showNotification} from 'react-admin'
import Table from '@material-ui/core/Table'
import Paper from '@material-ui/core/Paper'
import {Footer, Header} from '../../../components/TableComponent'
import TableToolbar from '../../../components/TableComponent/Toolbar'
import dataProvider from '../../../components/DataProvider/dataProvider'
import TableBody from '@material-ui/core/TableBody'
import TableCell from '@material-ui/core/TableCell'
import TableRow from '@material-ui/core/TableRow'
import Checkbox from '@material-ui/core/Checkbox'
import Button from '@material-ui/core/Button'
import EditIcon from '@material-ui/icons/Edit'
import DeleteIcon from '@material-ui/icons/Delete'
import ChatIcon from '@material-ui/icons/Chat'
import ToggleButtonCustom from '../../../components/action/ToggleButtonCustom'
import OrderStatus from '../../../components/action/OrderStatus'
import {MuiThemeProvider, createMuiTheme} from '@material-ui/core/styles'
import Popup from 'reactjs-popup'
import SelectRole from '../../../components/action/SelectRole'
import red from '@material-ui/core/colors/red'
import './style.css'

const redTheme = createMuiTheme({
    typography: {
        useNextVariants: true
    },
    palette: {secondary: red}
})

class OrderList extends React.Component {
    state = {
        order: 'desc',
        field: 'id',
        selected: [],
        data: [],
        page: 1,
        perPage: 5,
        total: 5,
        filter_q: '',
        filter_name: '',
        delete_popup: false,
        delete_item: 0,
        order_status: []
    }

    generateParams = () => {
        let page = this.state.page
        let perPage = this.state.perPage
        let field = this.state.field
        let order = this.state.order
        let q = this.state.filter_q
        let order_status = this.state.order_status
        let name = this.state.filter_name == 'all' ? '' : this.state.filter_name
        return {
            pagination: {
                page,
                perPage
            },
            sort: {
                field,
                order
            },
            filter: {
                q,
                name
            },
            order_status
        }
    }

    componentWillMount () {
        this.getData()
    }

    getData = () => {
        dataProvider('GET_LIST', this.props.resource, this.generateParams()).then(
            e => {
                this.setState({data: e.data, total: e.total, selected: []})
            }
        )
    }

    handleOpenPopup = id => {
        this.setState({delete_item: id, delete_popup: true})
    }

    handlerDelete = () => {
        const {showNotification, lang} = this.props
        dataProvider('DELETE', this.props.resource, {
            id: this.state.delete_item
        }).then(e => {
            showNotification(lang['Item was deleted'])
            this.setState({delete_popup: false})
            this.getData()
        })
    }

    handleRequestSort = (event, property) => {
        const field = property
        let order = 'desc'

        if (this.state.field === property && this.state.order === 'desc') {
            order = 'asc'
        }
        this.setState({order, field}, () => {
            this.getData()
        })
    }

    handlerFilterChange = (q, name, order_status) => {
        this.setState({filter_q: q, filter_name: name, order_status}, () => {
            this.getData()
        })
    }

    handleSelectAllClick = event => {
        if (event.target.checked) {
            this.setState(state => ({selected: state.data.map(n => n.id)}))
            return
        }
        this.setState({selected: []})
    }

    handleClick = id => {
        const {selected} = this.state
        const selectedIndex = selected.indexOf(id)
        let newSelected = []
        if (selectedIndex === -1) {
            newSelected = newSelected.concat(selected, id)
        } else if (selectedIndex === 0) {
            newSelected = newSelected.concat(selected.slice(1))
        } else if (selectedIndex === selected.length - 1) {
            newSelected = newSelected.concat(selected.slice(0, -1))
        } else if (selectedIndex > 0) {
            newSelected = newSelected.concat(
                selected.slice(0, selectedIndex),
                selected.slice(selectedIndex + 1)
            )
        }
        this.setState({selected: newSelected})
    }

    handleChangePage = page => {
        this.setState({page}, () => this.getData())
    }

    handleChangeperPage = perPage => {
        this.setState({perPage, page: 1}, () => this.getData())
    }

    handleClose = () => {
        this.setState({delete_popup: false})
    }

    isSelected = id => this.state.selected.indexOf(id) !== -1

    render () {
        const {data, order, field, selected} = this.state
        const {lang} = this.props
        return (
            <>
                <Paper>
                    <TableToolbar
                        numSelected={selected.length}
                        resource={this.props.resource}
                        selected={this.state.selected}
                        filter={this.handlerFilterChange}
                        refresh={this.getData}
                        rows={this.props.fields}
                        create={this.props.create}
                        order={this.props.order}
                    />
                    <div>
                        <Table aria-labelledby='tableTitle'>
                            <Header
                                numSelected={selected.length}
                                order={order}
                                orderBy={field}
                                onSelectAllClick={this.handleSelectAllClick}
                                onRequestSort={this.handleRequestSort}
                                rowCount={data.length}
                                rows={this.props.fields}
                                title={this.props.title}
                                bulk={this.props.bulk}
                            />
                            <TableBody>
                                {data.map(n => {
                                    let isSelected = this.isSelected(n.id)
                                    return (
                                        <TableRow
                                            hover
                                            role='checkbox'
                                            aria-checked={isSelected}
                                            tabIndex={-1}
                                            key={n.id}
                                            selected={isSelected}
                                        >
                                            {this.props.bulk && (
                                                <TableCell padding='checkbox'>
                                                    <Checkbox
                                                        checked={isSelected}
                                                        onClick={() => this.handleClick(n.id)}
                                                    />
                                                </TableCell>
                                            )}
                                            {this.props.fields.map((item, index) => {
                                                switch (item.name) {
                                                    case 'status':
                                                        return (
                                                            <TableCell key={n.id * index} align='left'>
                                                                <ToggleButtonCustom
                                                                    refresh={this.getData}
                                                                    record={{status: n.status, id: n.id}}
                                                                    data={{basePath: this.props.resource}}
                                                                />
                                                            </TableCell>
                                                        )
                                                    case 'order_status':
                                                        return (
                                                            <TableCell key={n.id * index} align='left'>
                                                                <OrderStatus order={n.status}/>
                                                            </TableCell>
                                                        )
                                                    case 'edit':
                                                        if (this.props.edit) {
                                                            return (
                                                                <TableCell key={n.id * index} align='left'>
                                                                    <Button
                                                                        color='primary'
                                                                        onClick={() =>
                                                                            (window.location.href =
                                                                                '#/' + this.props.resource + '/' + n.id)
                                                                        }
                                                                    >
                                                                        <EditIcon/>
                                                                        {lang.btnEdit}
                                                                    </Button>
                                                                </TableCell>
                                                            )
                                                        } else {
                                                            return null
                                                        }
                                                    case 'role':
                                                        return (
                                                            <TableCell key={n.id * index} align='left'>
                                                                <SelectRole
                                                                    role_id={n.role_id}
                                                                    status={n.status}
                                                                    id={n.id}
                                                                    resource={this.props.resource}
                                                                    refresh={this.getData}
                                                                />
                                                            </TableCell>
                                                        )
                                                    case 'chat':
                                                        return (
                                                            <TableCell key={n.id * index} align='left'>
                                                                <Button color='primary'>
                                                                    <ChatIcon/>
                                                                </Button>
                                                            </TableCell>
                                                        )
                                                    case 'delete':
                                                        return (
                                                            <TableCell key={n.id * index} align='left'>
                                                                <MuiThemeProvider theme={redTheme}>
                                                                    <Button
                                                                        color='secondary'
                                                                        onClick={() => this.handleOpenPopup(n.id)}
                                                                    >
                                                                        <DeleteIcon/>
                                                                        {lang.btnDelete}
                                                                    </Button>
                                                                </MuiThemeProvider>
                                                            </TableCell>
                                                        )
                                                    default:
                                                        return (
                                                            <TableCell key={n.id * index} align='left'>
                                                                {n[item.name]}
                                                            </TableCell>
                                                        )
                                                }
                                            })}
                                        </TableRow>
                                    )
                                })}
                            </TableBody>
                        </Table>
                        <Footer
                            total={this.state.total}
                            page={this.state.page}
                            perPage={this.state.perPage}
                            handleChangePage={this.handleChangePage}
                            handleChangeperPage={this.handleChangeperPage}
                        />
                    </div>
                </Paper>
                <Popup
                    open={this.state.delete_popup}
                    onClose={this.handleClose}
                    fullWidth
                    className='dashboard-member__popup-delete'
                    aria-labelledby='max-width-dialog-title'
                >
                    <MuiThemeProvider theme={redTheme}>
                        <h2>{lang['Are you sure you want delete this item?']}</h2>
                        <div className='dashboard-popup__buttons-actions'>
                            <Button
                                color='primary'
                                variant='contained'
                                onClick={() => this.handleClose()}
                            >
                                {lang.btnCancel}
                            </Button>
                            <div className='dashboard-popup__buttons-spacer'/>
                            <Button
                                color='secondary'
                                variant='contained'
                                onClick={() => this.handlerDelete()}
                            >
                                <DeleteIcon/>
                                {lang.btnDelete}
                            </Button>
                        </div>
                    </MuiThemeProvider>
                </Popup>
            </>
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
)(OrderList)
