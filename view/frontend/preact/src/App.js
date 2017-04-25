import 'preact/devtools';
import { h, Component } from 'preact';
import { Provider } from 'mobx-preact'

import { Route, BrowserRouter } from 'react-router-dom';
import createBrowserHistory from 'history/createBrowserHistory'

import Directions from './container/Directions'
import StateStore from './store/';
import StoreHeader from './container/StoreHeader';
import StoresList from './container/StoresList';
import StoreView from './container/StoreView'

import GoogleApiComponent from './component/GoogleApiComponent';

const history = createBrowserHistory();

class App extends Component {

    constructor(props) {
        super();
        this.stateStore = new StateStore(props.json);
    }

    getChildContext() {
        return {
            sw: this.props.json.constants.autocomplete_bounds.sw,
            ne: this.props.json.constants.autocomplete_bounds.ne,
            google: this.props.google
        };
    }

    render() {
        const { json } = this.props;
        return (
            <Provider stateStore={this.stateStore}>
                <BrowserRouter
                    basename='/storelocator'
                    history={history}>
                    <div>
                        <StoreHeader google={this.props.google} regions={json.regions}/>
                        <Route exact path="/" component={() => (<StoresList stores={json.stores}/>)} />
                        <Route path="/:id" component={() => (<StoreView stores={json.stores}/>)}/>
                    </div>
                </BrowserRouter>
            </Provider>
        )
    }
}


export default GoogleApiComponent()(App);
