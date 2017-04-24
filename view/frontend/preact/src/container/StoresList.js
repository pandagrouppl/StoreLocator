import { h, Component } from 'preact';
import { connect } from 'mobx-preact';

import SingleStore from './../component/SingleStore'


@connect(['stateStore'])
export default class StoresList extends Component {

    constructor() {
        super();
        this.applyZoom = this.applyZoom.bind(this);
    }

    applyZoom(gps, zoom) {
        this.props.stateStore.changeView();
        this.props.stateStore.changeMap(gps, zoom);
    }

    render () {
        const {stores} = this.props.stateStore;
        return (
            <ul className="stores-li">
                {stores.map((store) => <SingleStore onStoreClick={this.applyZoom} {...store}/>)}
            </ul>
        );
    }
}