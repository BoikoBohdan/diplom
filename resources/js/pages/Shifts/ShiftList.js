import React, {Component} from 'react'
import Paper from '@material-ui/core/Paper'
import {connect} from 'react-redux'
import CustomTable from '../../components/Table'
import {Title} from 'react-admin'

class ShiftList extends Component {
    render () {
        localStorage.setItem('file_name', 'shifts');
        const {lang} = this.props
        return (
            <div>
                <Paper>
                    <Title title={lang.Shifts}/>
                    <CustomTable
                        resource='api/admin/shifts'
                        create
                        show={false}
                        delete
                        edit
                        lang={lang}
                        filterEnabled={true}
                        searchEnabled={false}
                        fields={[
                            {name: 'date', label: lang.tableDate, filter: true},
                            {name: 'city', label: lang.tableCity, filter: true},
                            {name: 'start', label: lang.tableStartShift, filter: true},
                            {name: 'end', label: lang.tableEndShift, filter: true},
                            {name: 'meal', label: lang.tableMeal, filter: true},
                            {name: 'drivers', label: lang.tableDrivers, filter: true},
                            {name: 'edit', label: lang.btnEdit},
                            {name: 'delete', label: lang.btnDelete}
                        ]}
                    />
                </Paper>
            </div>
        )
    }
}

const mapStateToProps = state => ({
    lang: state.i18n.messages
})

export default connect(mapStateToProps)(ShiftList)
