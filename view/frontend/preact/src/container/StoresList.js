import { h, Component } from 'preact';
import { connect } from 'mobx-preact';

import SingleStore from './../component/SingleStore'


@connect(['stateStore'])
export default class StoresList extends Component {

    constructor(props) {
        super(props);
        this.applyZoom = this.applyZoom.bind(this);
        //this.props.stateStore.filterVisibility(true);
    }

    applyZoom(gps, zoom) {
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