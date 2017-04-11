import { h, Component } from 'preact';
import { connect } from 'mobx-preact';

@connect(['stateStore'])
export default class StoreView2 extends Component {

    render () {
        return (
            <h1>SECOND Store View!</h1>
        );
    }
}