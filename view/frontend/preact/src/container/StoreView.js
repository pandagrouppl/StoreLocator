import { h, Component } from 'preact';
import { connect } from 'mobx-preact';

import GoogleApiComponent from './../component/GoogleApiComponent';
import Maps from './Maps';

import HoursSpanFill from './../component/HoursSpanFill'

@connect(['stateStore'])
export default class StoreView extends Component {
    constructor(props, {router}) {
        super(props);
        this.state = {
            tab: 'hours'
        };
        this.store = props.stores.find(this.findStore, router.route.match.params.id);
        //this.props.stateStore.filterVisibility(false);
    }

    findStore(q) {
        return q.id == this
    }

    renderTab(active) {
        switch(active) {
            case 'hours':
                return (
                    <div className="tabs__hours">
                        <h2 className="tabs__h2">Opening Hours</h2>
                        <HoursSpanFill day={this.store.hours}/>
                    </div>
                );
                break;
            case 'directions':
                return (
                    <div className="tabs__directions">
                        Directions
                    </div>
                );
                break;
        }
    }

    setTab(selected) {
        this.setState({tab: selected});
    }

    setTabClass(selected) {
        return (this.state.tab == selected) ? "tabs__tab tabs__tab--active" : "tabs__tab";
    }

    render () {
        const tab1 = 'hours';
        const tab2 = 'directions';
        return (
            <div className="store-view">
                <div className="store-view__store-card">
                        <h1 className="store-view__name">{this.store.name}</h1>
                        <ul className="store-view__credentials">
                            <li><span class="store-view__label">Address:</span>{this.store.addr_strt} {this.store.addr_cty}</li>
                            <li><span class="store-view__label">Phone:</span><a href={'tel:'+this.store.phone}>{this.store.phone}</a></li>
                            <li><span class="store-view__label">Email:</span><a href={'mailto:'+this.store.email}>{this.store.email}</a></li>
                        </ul>
                </div>
                <div className="store-view__tabs tabs">
                    <div className="tabs__tab-bar">
                        <div className={this.setTabClass(tab1)} onClick={() => this.setTab(tab1)}>Store Information</div>
                        <div className={this.setTabClass(tab2)} onClick={() => this.setTab(tab2)}>Directions</div>
                    </div>
                    <div className="tabs__tab-body">
                        {this.renderTab(this.state.tab)}
                    </div>
                </div>
            </div>
        );
    }
}

