import { h, Component } from 'preact';
import { connect } from 'mobx-preact';

@connect(['stateStore'])
export default class StoreView extends Component {

    render () {
        return (
            <h1>Single Store View!</h1>
        );
    }
}