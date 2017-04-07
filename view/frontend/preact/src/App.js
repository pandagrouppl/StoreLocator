import { h, Component } from 'preact';
import { Router, Route, Link } from 'react-router-dom';
import createBrowserHistory from 'history/createBrowserHistory'

import StoreHeader from './container/StoreHeader';
import AllStores from './container/AllStores'

const history = createBrowserHistory();

export default class App extends Component {

    constructor() {
        super();
    }

    render() {
        return(
        <Router history={history}>
            <div>
                <StoreHeader />
                <Route path="/" component={() => (<AllStores stores={this.props.json.stores}/>)}/>
            </div>
        </Router>
        )
    }
}
