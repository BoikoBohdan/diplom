import Geocode from 'react-geocode'
// set Google Maps Geocoding API for purposes of quota management. Its optional but recommended.
Geocode.setApiKey('AIzaSyBZx7tvnbWL5thE_11q5h1XlHHhhUA968c')

// Enable or disable logs. Its optional.
Geocode.enableDebug()

// Get address from latidude & longitude.
// Geocode.fromLatLng('48.8583701', '2.2922926').then(
//   response => {
//     const address = response.results[0].formatted_address
//   },
//   error => {
//     console.error(error)
//   }
// )

export const adressToGeocode = text => {
  Geocode.fromAddress('Eiffel Tower')
}
