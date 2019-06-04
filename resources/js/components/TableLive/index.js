import React from 'react'
import 'typeface-roboto'
import Echo from 'laravel-echo'
import {connect} from 'react-redux'
import {showNotification} from 'react-admin'
import Table from '@material-ui/core/Table'
import Paper from '@material-ui/core/Paper'
import {Footer, Header} from '../TableComponent'
import TableToolbar from '../TableComponent/Toolbar'
import dataProvider from '../DataProvider/dataProvider'
import TableBody from '@material-ui/core/TableBody'
import TableCell from '@material-ui/core/TableCell'
import TableRow from '@material-ui/core/TableRow'
import Checkbox from '@material-ui/core/Checkbox'
import Button from '@material-ui/core/Button'
import EditIcon from '@material-ui/icons/Edit'
import OrderStatus from '../action/OrderStatus'
import * as R from 'ramda'

import './style.css'

class LiveTable extends React.Component {
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
        new_order: [],
        statuses: []
    }

    generateParams = () => {
        let page = this.state.page
        let perPage = this.state.perPage
        let field = this.state.field
        let order = this.state.order
        let q = this.state.filter_q
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
            order_status
        }
    }

    componentDidMount () {
        this.getData()
        window.io = require('socket.io-client')
        window.Echo = new Echo({
            broadcaster: 'socket.io',
            host: window.location.hostname + ':6001'
        })
        window.Echo.channel('Orders').listen('.action-on-order', e => {
            this.newOrder(e)
        })
    }

    newOrder = new_order => {
        let data
        let orders = this.state.data
        switch (new_order.action) {
            case 'created':
                data = this.addNewOrder(new_order, orders)
                break
            case 'updated':
                data = this.updateOrder(new_order, orders)
        }
        this.setState({data})
    }

    addNewOrder = (new_order, data) => {
        const {showNotification} = this.props
        const {value, title} = new_order.order.status
        let selected_statuses = this.state.order_status
        let order = {...new_order.order, type: 'created'}

        if (selected_statuses.length) {
            selected_statuses = selected_statuses
                .split(',')
                .map(el => Number(el))
                .filter(el => el !== '')
        }
        if (R.isEmpty(selected_statuses) || R.nth(0, selected_statuses) === value) {
            data.unshift(order)
        } else {
            showNotification(`You have a new order in ${title}`)
        }
        return data
    }

    updateOrder = (new_order, data) => {
        for (let order in data) {
            if (data[order].id == new_order.order.id) {
                data[order] = {...new_order.order, type: 'updated'}
            }
        }
        return data
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
        const {showNotification} = this.props
        dataProvider('DELETE', this.props.resource, {
            id: this.state.delete_item
        }).then(e => {
            showNotification('Item was deleted')
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

    isSelected = id => this.state.selected.indexOf(id) !== -1

    render () {
        const {searchEnabled, lang} = this.props
        console.log('sdasdas', lang)
        const {data, order, field, selected} = this.state
        return (
            <>
                <Paper>
                    <TableToolbar
                        lang={lang}
                        numSelected={selected.length}
                        resource={this.props.resource}
                        selected={this.state.selected}
                        filter={this.handlerFilterChange}
                        refresh={this.getData}
                        rows={this.props.fields}
                        create={this.props.create}
                        searchEnabled={searchEnabled}
                        order={this.props.order}
                    />
                    <div>
                        <Table aria-labelledby='tableTitle'>
                            <Header
                                numSelected={selected.length}
                                order={order}
                                orderBy={field}
                                lang={lang}
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
                                            className={n.type === 'created' ? 'table__new' : ''}
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
                                                    case 'order_status':
                                                        return (
                                                            <TableCell key={n.id * index} align='left'>
                                                                <OrderStatus lang={lang} order={n.status}/>
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
)(LiveTable)
