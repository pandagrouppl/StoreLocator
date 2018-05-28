import { h, Component } from 'preact';

import camelize from '../util/Camelize';

const evtNames = ['click', 'mouseover', 'recenter', 'dragend'];

export default class Marker extends Component {

    componentDidMount() {
        this.renderMarker();
    }

    componentDidUpdate(prevProps) {
        if ((this.props.map !== prevProps.map) ||
            (this.props.position !== prevProps.position)) {
            if (this.marker) {
                this.marker.setMap(null);
            }
            this.renderMarker();
        }
    }

    componentWillUnmount() {
        if (this.marker) {
            this.marker.setMap(null);
        }
    }

    renderMarker() {
        let {
            map, google, position, mapCenter, icon
            } = this.props;

        if (!google) {
            return null;
        }

        let pos = position || mapCenter;
        position = new google.maps.LatLng(pos.lat, pos.lng);

        const pref = {
            map: map,
            position: position,
            icon: icon
        };
        this.marker = new google.maps.Marker(pref);

        this.marker.addListener('click', function() {
            const evtName = `onClick`;
            if (this.props[evtName]) {
                this.props[evtName](this.props);
            }
        }.bind(this));
    }

    render() {
        return null;
    }
}
