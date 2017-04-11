import { h, Component } from 'preact';
import { connect } from 'mobx-preact';

import HoursSpanFill from './../component/HoursSpanFill'

@connect(['stateStore'])
export default class StoreView extends Component {
    constructor(props, {router}) {
        super(props);
        this.state = {
            tab: 'hours'
        };
        this.store = props.stores.find(this.findStore, router.route.match.params.id);
    }

    findStore(q) {
        return q.id == this
    }

    renderTab(active) {
        switch(active) {
            case 'hours':
                return (
                    <div className="tabs__hours">
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


    render () {
        return (
            <div className="store-view">
                <div className="store-view__store-card">
                    <h1>{this.store.name}</h1>
                    <li className="stores-li__store">
                        <h1 className="stores-li__name">{this.store.name}</h1>
                        <ul className="stores-li__credentials">
                            <li><i className="icon-address"></i>{this.store.addr_strt} {this.store.addr_cty}</li>
                            <li><i className="icon-mobile"></i><a href={'tel:'+this.store.phone}>{this.store.phone}</a></li>
                            <li><i className="icon-envelope"></i><a href={'mailto:'+this.store.email}>{this.store.email}</a></li>
                        </ul>
                    </li>
                </div>
                <div className="store-view__tabs tabs">
                    <div className="tabs__tab-bar">
                        <div className="tabs__tab-hours" onClick={() => this.setTab('hours')}>Hours</div>
                        <div className="tabs__tab-directions" onClick={() => this.setTab('directions')}>Directions</div>
                    </div>
                    <div className="tabs__tab-body">
                        {this.renderTab(this.state.tab)}
                    </div>
                </div>
            </div>
        );
    }
}

