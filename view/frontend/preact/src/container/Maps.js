import { h, Component } from 'preact';
import { connect } from 'mobx-preact';
import Map, {Marker} from 'google-maps-react';
//import  from 'google-maps-react/dist';

import Directions from '../component/Directions'


@connect(['stateStore'])
export default class Maps extends Component {

    render() {
        return(
            <section  style={{width: '100%', height: '400px', position: 'relative', overflow: 'hidden'}}>
            <Map className='google-maps-container'
                 google={this.props.google}
                 zoom={this.props.stateStore.zoom}
                 style={{width: '100%', height: '400px', position: 'relative'}}
                 initialCenter={this.props.stateStore.geoTotal}
                 center={this.props.stateStore.geoTotal}>
                    {this.props.stateStore.stores.map((store) => (
                        <Marker key={store.name} position={{lat: store.geo.lat, lng: store.geo.lng}} />
                    ))}
                <Directions points={this.props.stateStore.waypoints}/>
            </Map>
            </section>
        );
    }
}
