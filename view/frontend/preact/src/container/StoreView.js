import { h, Component } from 'preact';
import { connect } from 'mobx-preact';

@connect(['stateStore'])
export default class StoreView extends Component {

    render ({ match }) {
        return (
            <div>
            <h1>Single Store View!</h1>
                    <h3>ID: {match.params.id}</h3>
                </div>
        );
    }
}

