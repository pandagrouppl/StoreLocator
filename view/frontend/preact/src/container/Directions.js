import { h, Component } from 'preact';
import { connect } from 'mobx-preact';

@connect(['stateStore'])
export default class Directions extends Component {

    constructor(props) {
        super(props);
        this.directionsService = null;
        this.directionsDisplay = null;
    }

    getChildContext() {
        return {error: this.state.error};
    }

    componentDidUpdate(prevProps) {
        console.log('prevprop', prevProps);
        if (this.props.stateStore.waypoints !== prevProps.stateStore.waypoints) {
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
            origin: this.props.stateStore.waypoints.start,
            destination: this.props.stateStore.waypoints.stop,
            travelMode: this.props.stateStore.waypoints.mode
        };
        console.log('request', request);
        if (request.origin==request.destination) {
            console.log('DIRECTIONS WRONG');
        }

        if (request.destination && request.origin) {
            this.directionsService.route(request, (result, status) => {
                if (status === 'OK') {
                    this.props.stateStore.setError();
                    this.directionsDisplay.setDirections(result);
                    this.directionsDisplay.setPanel(document.getElementById('directionsPanel'));
                } else {
                    this.props.stateStore.setError('Could not find a route between A and B.');
                }
            });
        }
    }

    render() {
        return null;
    }

}
