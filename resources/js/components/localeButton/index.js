import React, {useState} from 'react';
import { connect } from 'react-redux';
import {compose} from 'recompose'
import MenuItem from '@material-ui/core/MenuItem';
import Select from '@material-ui/core/Select'
import { withStyles } from '@material-ui/core/styles'

const styles = theme => ({
    wrapper: {
        width: 100,
        display: 'flex',
        flexDirection: 'row',
        alignItems: 'center',
        marginLeft: 20,
    },
    button: {
        display: 'flex',
        padding: '17px',
        height: '50px',
        width: '100%'
    },
    select: {
        width: 50,
        '&::before': {
            borderBottomWidth: 0,
            borderBottom: 'none'
        },
        '&:hover': {
            borderBottomWidth: 0,
        },
        marginLeft: 5,
    },
    icon: {
        width: 20,
        height: 16,
        backgroundImage: `url("/images/en.png")`,
    }
})

const locales = ['en', 'de']

const LocaleButton = ({classes, loc}) => {
    const [locale, setLocale] = useState(loc)
    const handleChange = (event) => {
        setLocale(event.target.value)
        localStorage.setItem('locale_eat', event.target.value)
        window.location.reload()
    }

    return (

        <div className={classes.wrapper}>
            <div>
                {
                    loc === 'en' ?
                        <img alt={''} src={'../../images/en.png'} className={classes.icon}/>
                        :
                        <img alt={''} src={'../../images/ge.png'} className={classes.icon}/>

                }
            </div>
            <Select
                value={locale || ''}
                onChange={handleChange}
                className={classes.select}
                renderValue={ value => locale}
                inputProps={{
                    name: 'locale',
                    id: 'locale-simple'
                }}>
                {
                    locales && locales.map(locale => {
                        return (
                            <MenuItem
                                value={locale}
                                key={locale}>
                                {locale}
                            </MenuItem>
                        )
                    })
                }
            </Select>
        </div>

    );
};

const mapStateToProps = state => {
    return {
        lang: state.i18n.messages,
        loc: state.i18n.locale
    }
}

export default compose(
    withStyles(styles),
    connect(mapStateToProps)
)(LocaleButton)
