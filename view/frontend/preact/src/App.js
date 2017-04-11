import 'preact/devtools';
import { h, Component } from 'preact';
import { Provider } from 'mobx-preact'

import { Router, Route, Link, BrowserRouter } from 'react-router-dom';
import createBrowserHistory from 'history/createBrowserHistory'

import StateStore from './store/';
import StoreHeader from './container/StoreHeader';
import StoresList from './container/StoresList';
import StoreView from './container/StoreView'

import GoogleApiComponent from './component/GoogleApiComponent';

const history = createBrowserHistory();

const App = (props) => {
    const { json } = props;
    const stateStore = new StateStore(json);

    return(
        <Provider stateStore={stateStore}>
            <BrowserRouter
                basename='/storelocator'
                history={history}>
                <div>
                    <StoreHeader google={props.google} regions={json.regions}/>
                    <Route exact path="/" component={() => (<StoresList stores={json.stores}/>)} />
                    <Route google={props.google} path="/:id" component={() => (<StoreView stores={json.stores}/>)}/>
                </div>
            </BrowserRouter>
        </Provider>
    )
};

export default GoogleApiComponent({
    apiKey: 'AIzaSyBu3pjyCmHyMo8h98fCZv32QVbBf8bNqSY'
})(App);
