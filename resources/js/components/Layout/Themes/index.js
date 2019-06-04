import red from '@material-ui/core/colors/red'
import { createMuiTheme } from '@material-ui/core/styles'

export const redTheme = () => {
  const redTheme = createMuiTheme({
    typography: {
      useNextVariants: true
    },
    palette: { secondary: red }
  })
  return redTheme
}
