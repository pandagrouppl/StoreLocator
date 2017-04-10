import { h, Component } from 'preact';
import { connect } from 'mobx-preact';

import SingleStoreView from './../component/SingleStoreView'


@connect(['stateStore'])
export default class StoresView extends Component {

    constructor() {
        super();
        this.applyZoom = this.applyZoom.bind(this);
    }

    applyZoom(gps, zoom) {
        this.props.stateStore.addZoom(gps, zoom);
    }

    render () {
        const {stores} = this.props.stateStore;
        return (
            <ul className="stores-li">
                {stores.map((store) => <SingleStoreView onStoreClick={this.applyZoom} {...store}/>)}
            </ul>
        );
    }
}