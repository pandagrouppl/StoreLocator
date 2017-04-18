import { h, Component } from 'preact';
import Map, {Marker} from 'google-maps-react';


export class Directions extends Component {

    constructor() {
        super();
        this.directionsService = null;
        this.directionsDisplay = null;
    }

    componentDidUpdate(prevProps) {
        if ((this.props.map !== prevProps.map) ||
            (this.props.position !== prevProps.position) ||
            (this.props.points !== prevProps.points)) {
            this.renderDirections();
        }
    }

    renderDirections() {
        if (this.directionsService === null || this.directionsDisplay === null) {
            this.directionsService = new this.props.google.maps.DirectionsService();
            this.directionsDisplay = new this.props.google.maps.DirectionsRenderer();
        }

        this.directionsDisplay.setMap(this.props.map);
        const request = {
            origin: this.props.points.start,
            destination: this.props.points.stop,
            travelMode: this.props.points.mode
        };

        if (request.destination && request.origin) {
            this.directionsService.route(request, (result, status) => {
                if (status == 'OK') {
                    this.directionsDisplay.setDirections(result);
                    console.log('result', result);
                }
            });
        }
    }

    render() {
        return null;
    }

}

export default Directions;