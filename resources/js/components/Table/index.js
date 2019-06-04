import React from 'react'
import 'typeface-roboto'
import {connect} from 'react-redux'
import * as R from 'ramda'
import {getResources, showNotification} from 'react-admin'
import Table from '@material-ui/core/Table'
import Paper from '@material-ui/core/Paper'
import {DeletePopup, Footer, Header} from '../TableComponent'
import TableToolbar from '../TableComponent/Toolbar'
import dataProvider from '../DataProvider/dataProvider'
import TableBody from '@material-ui/core/TableBody'
import TableCell from '@material-ui/core/TableCell'
import TableRow from '@material-ui/core/TableRow'
import Checkbox from '@material-ui/core/Checkbox'
import Button from '@material-ui/core/Button'
import EditIcon from '@material-ui/icons/Edit'
import DeleteIcon from '@material-ui/icons/Delete'
import ChatIcon from '@material-ui/icons/Chat'
import {MuiThemeProvider} from '@material-ui/core/styles'
import ToggleButtonCustom from '../action/ToggleButtonCustom'
import OrderStatus from '../action/OrderStatus'
import SelectRole from '../action/SelectRole'
import {redTheme} from '../Layout/Themes'
import WalletInput from '../../components/ManageWalletInput'
import './style.css'

class CusomTable extends React.Component {
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
        order_status: [],
        walletCalculation: '',
        date: '',
        city: ''
    }

    generateParams = () => {
        let page = this.state.page
        let perPage = this.state.perPage
        let field = this.state.field
        let order = this.state.order
        let q = this.state.filter_q
        let city = this.state.city
        let date = this.state.date
        let order_status = this.state.order_status
        let name = this.state.filter_name === 'all' ? '' : this.state.filter_name
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
            order_status,
            city,
            date
        }
    }

    componentWillMount () {
        this.getData()
    }

    getData = (params = this.generateParams) => {
        dataProvider('GET_LIST', this.props.resource, params())
            .then(e => {
                    this.setState({
                        data: e.data,
                        total: e.total,
                        selected: []
                    })
                }
            )
            .catch(err => console.log(err))
    }

    handleOpenPopup = id => {
        this.setState({
            delete_item: id,
            delete_popup: true
        })
    }

    handlerDelete = () => {
        const {showNotification, lang} = this.props
        dataProvider('DELETE', this.props.resource, {
            id: this.state.delete_item
        }).then(e => {
            showNotification(lang.successDeleteItem)
            this.setState({delete_popup: false})
            this.getData()
        })
    }

    showNotificationM  = () => {
        const {lang} = this.props
        const {showNotification} = this.props
        showNotification(lang['Role was changed'])
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
        this.setState({
            filter_q: q,
            filter_name: name,
            order_status
        }, () => {
            this.getData()
        })
    }

    handleFilterByDateAndCity = (date, city) => {

        this.setState({date, city: city.id}, () => {
            this.getData()
        })
    }

    handleFilterByCity = (city) => {
        this.setState({city}, () => {
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
        const {searchEnabled, filterEnabled, lang, role} = this.props
        const {data, order, field, selected, filter_q} = this.state
        return (
            <>
                <Paper>
                    <TableToolbar
                        lang={lang}
                        role={role}
                        numSelected={selected.length}
                        resource={this.props.resource}
                        selected={this.state.selected}
                        filter={this.handlerFilterChange}
                        handleFilterByDateAndCity={this.handleFilterByDateAndCity}
                        refresh={this.getData}
                        rows={this.props.fields}
                        create={this.props.create}
                        order={this.props.order}
                        searchEnabled={searchEnabled}
                        filterEnabled={filterEnabled}
                    />
                    <div>
                        <Table aria-labelledby='tableTitle'>
                            <Header
                                numSelected={selected.length}
                                order={order}
                                lang={lang}
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
                                            selected={isSelected}>
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
                                                    case 'wallet':
                                                        const {amount, id} = n.wallet
                                                        return (
                                                            <TableCell key={n.id * index} align='left'>
                                                                <WalletInput value={amount} id={id}/>
                                                            </TableCell>
                                                        )
                                                    case 'assigned_orders':
                                                        return (
                                                            <TableCell key={n.id * index} align='left'
                                                                       className="min-width200">
                                                                {n.assigned_orders.map((item, index) => {
                                                                    return `${item.reference_id}  ${n
                                                                        .assigned_orders.length !== index + 1 ? ' ,' : ''}`
                                                                })}
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
                                                                        className="margin10"
                                                                        disabled={R.equals(role, 'super_admin')}
                                                                        color='primary'
                                                                        onClick={() =>
                                                                            (window.location.href = `#/${
                                                                                this.props.resource
                                                                                }/${n.id}`)
                                                                        }>
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
                                                                    lang={lang}
                                                                    role_id={n.role_id}
                                                                    status={n.status}
                                                                    id={n.id}
                                                                    resource={this.props.resource}
                                                                    refresh={this.getData}
                                                                    showPopupM={this.showNotificationM}
                                                                />
                                                            </TableCell>
                                                        )
                                                    case 'chat':
                                                        return (
                                                            <TableCell key={n.id * index} align='left'>
                                                                <Button color='primary' className="margin10"
                                                                        onClick={() => window.location.assign(`#/chat?user=${n.id}`)}>
                                                                    <ChatIcon/>
                                                                </Button>
                                                            </TableCell>
                                                        )
                                                    case 'delete':
                                                        return (
                                                            <TableCell key={n.id * index} align='left'>
                                                                <MuiThemeProvider theme={redTheme()}>
                                                                    <Button
                                                                        disabled={
                                                                            !R.isNil(n['assigned_orders']) &&
                                                                            !R.isEmpty(n['assigned_orders']) ||
                                                                            R.equals(role, 'super_admin')
                                                                        }
                                                                        className="margin10"
                                                                        color='secondary'
                                                                        onClick={() => this.handleOpenPopup(n.id)}>
                                                                        <DeleteIcon/>
                                                                        {lang.btnDelete}
                                                                    </Button>
                                                                </MuiThemeProvider>
                                                            </TableCell>
                                                        )
                                                    case 'phone':
                                                        return (
                                                            <TableCell key={n.id * index} align='left' className="no__wrap">
                                                                {n[item.name]}
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
                            lang={lang}
                            total={this.state.total}
                            page={this.state.page}
                            perPage={this.state.perPage}
                            handleChangePage={this.handleChangePage}
                            handleChangeperPage={this.handleChangeperPage}
                        />
                    </div>
                </Paper>
                <DeletePopup
                    status={this.state.delete_popup}
                    onClose={this.handleClose}
                    onCancel={this.handleClose}
                    onSuccess={() => this.handlerDelete()}
                />
            </>
        )
    }
}

const mapStateToProps = state => ({
    role: state.login.role
})

export default connect(
    mapStateToProps,
    {
        showNotification
    }
)(CusomTable)
