import React from 'react'
import CustomTable from '../../components/Table'
import { getResources, Title } from 'react-admin'
import { connect } from 'react-redux'

const DeliveryList = ({ lang }) => {
  localStorage.setItem('file_name', 'drivers')
  return (
    <>
      <Title title='Users' />
      <CustomTable
        resource='api/admin/drivers'
        create
        show={false}
        edit
        lang={lang}
        searchEnabled
        fields={[
          { name: 'full_name', label: lang.tableFullName, filter: true },
          { name: 'phone', label: lang.tablePhoneNumber, filter: true },
          { name: 'chat', label: lang.chat },
          { name: 'assigned_orders', label: 'Assing job' },
          //   {
          //     name: 'wallet',
          //     label: `Today Task / Today Task Done`
          //   },
          { name: 'status', label: lang.tableStatus, filter: true },
          //   { name: 'vehicle', label: lang.tableVehicle, filter: true },
          { name: 'location', label: lang.tableCity, filter: true },
          {
            name: 'stops_left',
            label: 'Number of not finish task',
            filter: true
          },
          { name: 'edit', label: lang.btnEdit },
          { name: 'delete', label: lang.btnDelete }
        ]}
        bulk
      />
    </>
  )
}

const mapStateToProps = state => ({
  resources: getResources(state),
  lang: state.i18n.messages
})

export default connect(mapStateToProps)(DeliveryList)
