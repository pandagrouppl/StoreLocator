import { h, Component } from 'preact';
import Map, {Marker} from 'google-maps-react';

export class Directions extends Component {
    componentDidUpdate(prevProps) {
        if ((this.props.map !== prevProps.map) ||
            (this.props.position !== prevProps.position)) {
            this.renderDirections();
        }
    }

    renderDirections() {
        const directionsService = new this.props.google.maps.DirectionsService();
        const directionsDisplay = new this.props.google.maps.DirectionsRenderer();
        directionsDisplay.setMap(this.props.map);
        const start = 'chicago, il';
        const end = 'st louis, mo';
        const request = {
            origin: start,
            destination: end,
            travelMode: 'DRIVING'
        };
        directionsService.route(request, function(result, status) {
            if (status == 'OK') {
                this.directions = directionsDisplay.setDirections(result);
            }
        });

    }

    render() {
        return null;
    }

}

export default Directions;