import React from 'react'
import blue from '@material-ui/core/colors/blue'
import { withStyles } from '@material-ui/core/styles';
import Button from "@material-ui/core/Button/Button"

const styles = theme => ({
    link: {
        display: 'block',
        color: blue[300],
        cursor: 'pointer',
        textDecoration: 'none',
        margin: 0,
        padding: 0,
        fontSize: 11
    },
});

const ChatLink = (props) => {
    const {classes, lang} = props
    return (
        <div>
            <Button size="small" color="primary" href={'#/chat'} className={classes.link}>CHAT</Button>
        </div>
    )
}

export default withStyles(styles)(ChatLink)
