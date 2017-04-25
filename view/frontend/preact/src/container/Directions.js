import { h, Component } from 'preact';
import { connect } from 'mobx-preact';

@connect(['stateStore'])
export class Directions extends Component {

    constructor() {
        super();
        this.directionsService = null;
        this.directionsDisplay = null;
    }

    getChildContext() {
        return {error: this.state.error};
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
                if (status === 'OK') {
                    this.props.stateStore.setError('');
                    this.directionsDisplay.setDirections(result);
                    console.log('result', result);
                } else {
                    console.log('result', result);
                   this.props.stateStore.setError('Could not find a route between A and B.');
                }
            });
        }
    }

    render() {
        return null;
    }

}

export default Directions;