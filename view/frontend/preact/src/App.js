import 'preact/devtools';
import { h, Component } from 'preact';
import { Provider } from 'mobx-preact'
//import { observable } from 'mobx'
import { Router, Route, Link, BrowserRouter } from 'react-router-dom';
import createBrowserHistory from 'history/createBrowserHistory'

import StateStore from './store/';
import StoreHeader from './container/StoreHeader';
import StoresList from './container/StoresList';
import StoreView from './container/StoreView'

const history = createBrowserHistory();

const App = (props, { match }) => {
    const { json } = props;
    const stateStore = new StateStore(json);

    return(
        <Provider stateStore={stateStore}>
            <BrowserRouter
                basename='/storelocator'
                history={history}>
                <div>
                    <StoreHeader regions={json.regions}/>
                    <Route path="/" component={() => (<StoresList stores={json.stores}/>)} />
                    <Route path="/:id" component={StoreView}/>

                    <ul>
                        <li><Link to="/a">StoreView</Link></li>
                        <li><Link to="/b">StoreView2</Link></li>
                    </ul>
                </div>
            </BrowserRouter>
        </Provider>
    )
};

const Child = () => (
    <div>
        <h3>ID: {match.params.id}</h3>
    </div>
);


export default App;
