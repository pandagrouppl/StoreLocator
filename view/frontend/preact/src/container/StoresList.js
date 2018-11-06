import { h, Component } from 'preact';
import { connect } from 'mobx-preact';

import SingleStore from './../component/SingleStore';


@connect(['stateStore'])
export default class StoresList extends Component {

    constructor() {
        super();
        this.applyZoom = this.applyZoom.bind(this);
        this.changeView = this.changeView.bind(this);
    }

    componentDidMount() {
        document.title = "Store Locator";
    }

    applyZoom(gps, zoom) {
        this.props.stateStore.changeMap(gps, zoom);
    }

    changeView() {
        this.props.stateStore.changeView();
    }

    render () {
        const {stores} = this.props.stateStore;
        return (
            <ul className="stores-li">
                {stores.map((store) => <SingleStore
                    onStoreClick={this.applyZoom}
                    onLinkClick={this.changeView}
                    {...store}/>)}
            </ul>
        );
    }
}