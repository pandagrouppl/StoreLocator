import { h, Component } from 'preact';
import { connect } from 'mobx-preact';

import SingleStoreView from './../component/SingleStoreView'


@connect(['stateStore'])
export default class StoresView extends Component {

    render () {
        const {stores} = this.props.stateStore;
        return (
            <ul className="stores-li">
                {stores.map((store) => <SingleStoreView {...store}/>)}
            </ul>
        );
    }
}