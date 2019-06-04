import * as R from 'ramda'

export const generateParam = ({
   page= 1,
   perPage = '',
   field = '',
   order = 'desc',
   q = '',
   name = 'all',
   filter = '',
   sort = 'id',
   order_status = ''
} = {}) => {
    return {
        filter,
        name,
        sort,
        q,
        order_status,
        order,
        page,
        perPage,
        field
    }
}

export const directionCreator = order => {
    return new Promise((resolve, reject ) => {
        const {lat, lng} = order.dropoff.coordinates
        const pickUpLat = order.pickup.coordinates.lat
        const pickUpLng = order.pickup.coordinates.lng
        const DirectionsService = new google.maps.DirectionsService();
        DirectionsService.route({
            origin: new google.maps.LatLng(pickUpLat, pickUpLng),
            destination: new google.maps.LatLng(lat, lng),
            travelMode: google.maps.TravelMode.DRIVING,
        }, (result, status) => {
            if (status === google.maps.DirectionsStatus.OK) {
                order.direction = {...result}
                resolve(order)
            } else {
                reject(new Error('error'))
            }
        });
    })
}

export const getAllUsers = state => {
    const applySearch = item => R.includes(
        state.chat.search,
        R.prop('full_name', item)
    );

    return R.compose(
        R.filter(applySearch)
    )(state.chat.users)
};

export const handleGenerateColor = () => {
    const letters = '0123456789';
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 10)];
    }
    return color;
}

export const handleFormatDate = () => {
    const date = new Date().toLocaleDateString()
    return R.compose(
        R.join('-'),
        R.reverse(),
        R.split('-'),
        R.replace(/\//g, '-')
    )(date)
}
const routeColors = [
    '#DC143C', '#9ACD32', '#C71585', '#696969',
    '#FFA500', '#8A2BE2', '#483D8B', '#800000',
    '#00FFFF', '#191970']

/**
 * @param {array} orders
 * @returns {array} orders with color
 */
export const addColorToOrder = orders => {
    const addColor = key => routeColors[key]
    return orders.map((el, key) => {
        return R.assoc('color', addColor(key), el)
    })
}
