import { h, Component } from 'preact';
import { Router, Route, Link } from 'react-router-dom';
import createBrowserHistory from 'history/createBrowserHistory'

import StoreHeader from './container/StoreHeader';

const history = createBrowserHistory();

class HoursSelectFill extends Component {
    constructor() {
        super();
    }

    closedCheck(hours_arr) {
        if (hours_arr[0].toLowerCase() == 'closed') {
            return hours_arr[0];
        } else {
            return hours_arr.join(' - ');
        }
    }

    render () {
        return (
        <div class="stores-li__hours-wrapper">
        <select class="stores-li__hours">
            <option>STORE HOURS</option>
            {Object.keys(this.props.day).map((days) => <option>{days} : {this.closedCheck(this.props.day[days])}</option>)}
        </select>
        <figure class="stores-li__hours-arrow"></figure>
        </div>
        )
    }
}

class SingleStore extends Component {
    render () {
        return (
            <li class="stores-li__store">
                <h1 class="stores-li__name">{this.props.name}</h1>
                <ul class="stores-li__credentials">
                    <li><i class="icon-address"></i>{this.props.addr_strt} {this.props.addr_cty} {this.props.gps}</li>
                    <li><i class="icon-mobile"></i><a href={'tel:'+this.props.phone}>{this.props.phone}</a></li>
                    <li><i class="icon-envelope"></i><a href={'mailto:'+this.props.email}>{this.props.email}</a></li>
                </ul>
                <HoursSelectFill day={this.props.hours}/>
            </li>
        );
    }
}

class AllStores extends Component {
    render () {
        return (
            <ul class="stores-li">
            {this.props.stores.map((store) => <SingleStore {...store}/>)}
            </ul>
        );
    }
}



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
