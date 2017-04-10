import { h, Component } from 'preact';
import { connect } from 'mobx-preact';

import SingleStore from './../component/SingleStore'


@connect(['stateStore'])
export default class AllStores extends Component {

    render () {
        const {stores} = this.props.stateStore;
        return (
            <ul className="stores-li">
                {stores.map((store) => <SingleStore {...store}/>)}
            </ul>
        );
    }
}