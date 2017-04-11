import { h, Component } from 'preact';
import { connect } from 'mobx-preact';

import HoursSelectFill from './../component/HoursSelectFill'

@connect(['stateStore'])
export default class StoreView extends Component {
    constructor(props, {router}) {
        super(props);
        this.store = props.stores.find(this.findStore, router.route.match.params.id);
    }

    findStore(q) {
        return q.id == this
    }

    render () {
        return (
            <div>
            <h1>{this.store.name}</h1>
                <dl>
                    <dt></dt>
                    <dd></dd>
                    <dt></dt>
                    <dd></dd>
                    <dt></dt>
                    <dd></dd>
                </dl>

                <li className="stores-li__store">
                    <h1 className="stores-li__name">{this.store.name}</h1>
                    <ul className="stores-li__credentials">
                        <li><i className="icon-address"></i>{this.store.addr_strt} {this.store.addr_cty}</li>
                        <li><i className="icon-mobile"></i><a href={'tel:'+this.store.phone}>{this.store.phone}</a></li>
                        <li><i className="icon-envelope"></i><a href={'mailto:'+this.store.email}>{this.store.email}</a></li>
                    </ul>
                    <HoursSelectFill day={this.store.hours}/>
                </li>
            </div>
        );
    }
}

