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
        const { json } = this.props;
        return(
        <Router history={history}>
            <div>
                <StoreHeader regions={json.regions}/>
                <Route path="/" component={() => (<AllStores stores={json.stores}/>)}/>
            </div>
        </Router>
        )
    }
}
