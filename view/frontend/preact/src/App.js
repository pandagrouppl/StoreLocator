import 'preact/devtools';
import { h, Component } from 'preact';
import { Provider } from 'mobx-preact'

import { Route, BrowserRouter } from 'react-router-dom';
import createBrowserHistory from 'history/createBrowserHistory'

import Directions from './component/Directions'
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
                    <Directions google={props.google} />
                    <StoreHeader google={props.google} regions={json.regions}/>
                    <Route exact path="/" component={() => (<StoresList stores={json.stores}/>)} />
                    <Route google={props.google} path="/:id" component={() => (<StoreView stores={json.stores}/>)}/>
                </div>
            </BrowserRouter>
        </Provider>
    )
};

export default GoogleApiComponent({
    apiKey: 'AIzaSyAyesbQMyKVVbBgKVi2g6VX7mop2z96jBo'
})(App);
