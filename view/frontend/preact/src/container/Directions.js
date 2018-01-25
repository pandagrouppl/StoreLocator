import { h, Component } from 'preact';
import { connect } from 'mobx-preact';

@connect(['stateStore'])
export default class Directions extends Component {

    constructor(props, context) {
        super(props, context);
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
        if ((this.props.stateStore.view == 'list') && this.directionsDisplay) {
            this.directionsDisplay.setMap(null);
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
                    const dirDiv = document.createElement("div");
                    const dirPanel = document.querySelector("#dirPanel");
                    dirPanel.appendChild(dirDiv);
                    this.directionsDisplay.setPanel(dirDiv);
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
