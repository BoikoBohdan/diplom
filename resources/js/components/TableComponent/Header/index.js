import React from 'react'
import TableHead from '@material-ui/core/TableHead'
import TableSortLabel from '@material-ui/core/TableSortLabel'
import Tooltip from '@material-ui/core/Tooltip'
import TableCell from '@material-ui/core/TableCell'
import TableRow from '@material-ui/core/TableRow'
import Checkbox from '@material-ui/core/Checkbox'

export class Header extends React.Component {
  createSortHandler = property => event => {
    this.props.onRequestSort(event, property)
  }

  handlerExport = () => {
    let filename = localStorage.getItem('file_name')
    let anchor = document.createElement('a')
    document.body.appendChild(anchor)
    let file =
      process.env.MIX_PUBLIC_URL +
      '/api/admin/export?export=' +
      this.props.title

    let headers = new Headers()
    headers.append('Authorization', `Bearer ${localStorage.getItem('token')}`)

    fetch(file, { headers })
      .then(response => response.blob())
      .then(blobby => {
        let objectUrl = window.URL.createObjectURL(blobby)
        anchor.href = objectUrl
        anchor.download = filename + '.xlsx'
        anchor.click()
        window.URL.revokeObjectURL(objectUrl)
      })
  }

  render () {
    const {
      onSelectAllClick,
      order,
      orderBy,
      numSelected,
      rowCount,
      lang,
      handleClick,
      rows
    } = this.props
    console.log(rows, 'event')

    return (
      <TableHead>
        <TableRow>
          {this.props.bulk && (
            <TableCell padding='checkbox'>
              <Checkbox
                indeterminate={numSelected > 0 && numSelected < rowCount}
                checked={numSelected === rowCount}
                onChange={onSelectAllClick}
              />
            </TableCell>
          )}
          {this.props.rows.map(
            (row, index) => (
              <TableCell
                key={index}
                align={row.numeric ? 'right' : 'left'}
                padding={row.disablePadding ? 'none' : 'default'}
                sortDirection={orderBy === row.name ? order : false}
              >
                <Tooltip
                  title='Sort'
                  placement={row.numeric ? 'bottom-end' : 'bottom-start'}
                  enterDelay={300}
                >
                  <TableSortLabel
                    active={orderBy === row.name}
                    direction={order}
                    onClick={
                      row.filter ? this.createSortHandler(row.name) : () => {}
                    }
                  >
                    {row.label}
                  </TableSortLabel>
                </Tooltip>
              </TableCell>
            ),
            this
          )}
        </TableRow>
      </TableHead>
    )
  }
}
