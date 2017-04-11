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
import StoreView2 from './container/StoreView2'

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
                    <Route path="/a" component={() => (<StoresList stores={json.stores}/>)} />
                    <Route path="/b" component={StoreView} />
                    <Route path="/c" component={StoreView2} />

                    <h2>Accounts</h2>
                    <ul>
                        <li><Link to="/netflix">Netflix</Link></li>
                        <li><Link to="/zillow-group">Zillow Group</Link></li>
                        <li><Link to="/yahoo">Yahoo</Link></li>
                        <li><Link to="/modus-create">Modus Create</Link></li>
                    </ul>

                    <Route path="/:id" component={Child}/>
                </div>
            </BrowserRouter>
        </Provider>
    )
};

const Child = ({ match }) => (
    <div>
        <h3>ID: {match.params.id}</h3>
    </div>
);


export default App;
