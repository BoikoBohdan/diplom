import React, {Component} from 'react';
import {SortableContainer, SortableElement, arrayMove} from 'react-sortable-hoc';
import {setWayPoints} from '../../api/drivers'
import * as R from 'ramda'

const SortableItem = SortableElement(({value}) =>
    <li style={{listStyleType: 'none', background: 'grey', margin: '2px'}}>{value.title}</li>
);

const SortableList = SortableContainer(({items}) => {
    return (
        <ul style={{display: 'flex', padding: 0}}>
            {!R.isNil(items) && items.map((value, index) => (
                <SortableItem key={`item-${index}`} index={index} value={value} />
            ))}
        </ul>
    );
});

class SortableComponent extends Component {
    state = {
        waypoints: []
    }

    componentDidUpdate(prevProps, prevState) {
        if (R.equals(this.props.waypoints, prevState.waypoints)) {
            return null
        } else {
            this.setState({
                waypoints: this.props.waypoints
            })
        }
    }

    handleSetWayPoints = () => {
        const {user} = this.props
        const {waypoints} = this.state
        setWayPoints(user, waypoints)
            .then(res => console.log(res))
            .catch(err => console.log(err))
    }


    onSortEnd = ({oldIndex, newIndex}) => {
        this.setState(state => {
            return {
                waypoints: arrayMove(state.waypoints, oldIndex, newIndex)
            }
        });
        this.handleSetWayPoints()
    };
    render () {
        return <SortableList axis={'x'} items={this.state.waypoints} onSortEnd={this.onSortEnd} />;
    }
}

export default SortableComponent
