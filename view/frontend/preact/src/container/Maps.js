import {h, Component} from 'preact';
import Map from 'google-maps-react'

export default class Maps extends Component {

    render() {
        return(
            <section style={{width: '100%', height: '400px', position: 'relative'}}>
            <Map google={this.props.google}
                 zoom={14}
                 style={{width: '100%', height: '400px', position: 'relative'}}

            >
            </Map>
            </section>
        );
    }
}
