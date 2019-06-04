import React from 'react'
import LiveTable from '../../components/TableLive'
import { Title } from 'react-admin'
import { connect } from 'react-redux'

// const driver_field = [
//     {name: 'reference_id', label: 'Reference', filter: true},
//     {name: 'pickup_date', label: 'Pickup date', filter: true},
//     {name: 'pickup_time_from', label: 'Pickup Time From'},
//     {name: 'dropoff_time_from', label: 'Dropoff Time From'},
//     {name: 'restaurant_postcode', label: 'Restaurant postcode '},
//     {name: 'restaurant_name', label: 'Restaurant Name', filter: true},
//     {name: 'dropoff_postcode', label: 'Dropoff postcode '},
//     {name: 'fee', label: 'Order fee '},
//     {name: 'driver_name', label: 'Driver name'},
//     {name: 'order_status', label: 'Status'},
//     {name: 'edit', label: 'Edit'}
// ]
const OrdersList = ({ lang }) => {
  localStorage.setItem('file_name', 'orders')
  return (
    <>
      <Title title={'Materials'} />
      <LiveTable
        resource='api/admin/orders'
        create
        lang={lang}
        show={false}
        edit
        order
        fields={[
          { name: 'reference_id', label: lang.tableReference, filter: true },
          { name: 'pickup_date', label: 'Date Start', filter: true },
          { name: 'pickup_time_from', label: 'Time Start' },
          // {name: 'dropoff_time_from', label: lang.tableDropOffTime},
          { name: 'restaurant_postcode', label: 'Code' },
          //   {
          //     name: 'restaurant_name',
          //     label: 'Name',
          //     filter: true
          //   },
          { name: 'dropoff_postcode', label: 'Team' },
          { name: 'fee', label: 'Price' },
          { name: 'driver_name', label: 'Super Admin Name' },
          //   { name: 'order_status', label: lang.tableStatus },
          { name: 'edit', label: lang.btnEdit }
        ]}
        searchEnabled
        bulk={false}
      />
    </>
  )
}
const mapStateToProps = state => ({
  lang: state.i18n.messages
})

export default connect(mapStateToProps)(OrdersList)
