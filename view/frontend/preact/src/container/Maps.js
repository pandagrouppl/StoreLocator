import { h, Component } from 'preact';
import { connect } from 'mobx-preact';
import Map from 'google-maps-react'

@connect(['stateStore'])
export default class Maps extends Component {

    render() {
        return(
            <section style={{width: '100%', height: '400px', position: 'relative'}}>
            <Map google={this.props.google}
                 zoom={this.props.stateStore.zoom}
                 style={{width: '100%', height: '400px', position: 'relative'}}
                 initialCenter={{ lat: this.props.stateStore.geo.lat, lng: this.props.stateStore.geo.lng }}
                 center={{ lat: this.props.stateStore.geo.lat, lng: this.props.stateStore.geo.lng }}
            >
            </Map>
            </section>
        );
    }
}
