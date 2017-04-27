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
        if (this.props.points !== prevProps.points) {
            this.renderDirections();
        }
    }

    renderDirections() {
        if (this.directionsService === null || this.directionsDisplay === null) {
            this.directionsService = new this.props.google.maps.DirectionsService();
            this.directionsDisplay = new this.props.google.maps.DirectionsRenderer();
        }

        this.directionsDisplay.setMap(this.props.map);
        const request = this.props.stateStore.getWaypoints;

        if (request.destination && request.origin) {
            this.directionsService.route(request, (result, status) => {
                if (status === 'OK') {
                    this.props.stateStore.setError();
                    this.directionsDisplay.setDirections(result);
                    this.directionsDisplay.setPanel(document.getElementById('directionsPanel'));
                    this.props.stateStore.setRoute(result.routes[0].legs[0].steps);
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
