import { h, Component } from 'preact';
import { connect } from 'mobx-preact';

import GoogleApiComponent from './../component/GoogleApiComponent';
import Maps from './Maps';
import RegionFilter from './../component/RegionFilter';


@connect(['stateStore'])
class StoreHeader extends Component {

    constructor() {
        super();
        this.postcodeInput = null;
        this.applyFilter = this.applyFilter.bind(this);
        this.resetFilters = this.resetFilters.bind(this);
        this.searchPostcode = this.searchPostcode.bind(this);
    }

    resetFilters() {
        this.props.stateStore.clearFilters();
    }

    searchPostcode() {
        console.log(this.postcodeInput.value);
    }

    isActiveFilter(region) {
        return this.props.stateStore.filters.indexOf(region) > -1 ? 'filter-active' : 'filter';
    }

    applyFilter(region) {
        this.props.stateStore.addFilters(region);
    }

    render() {
        const { regions } = this.props;
        return(
            <header>
                <h1>Find nearest shop</h1>
                <section className="header-top">
                    <article>
                        {regions.map((region) => (
                            <RegionFilter className={this.isActiveFilter(region.name)} region={region.name}
                                          onFilterClick={this.applyFilter}/>
                        ))}
                    </article>
                    <article>
                        <a onClick={this.resetFilters}>Reset</a>
                        <input ref={(input) => { this.postcodeInput = input; }}  type="text" name="postcode"/>
                        <button onClick={this.searchPostcode}>Search</button>
                    </article>
                </section>
                <Maps google={this.props.google}/>
            </header>
        )
    }
}


export default GoogleApiComponent({
    apiKey: 'AIzaSyA56GAEym7kDnZEbGktGygTzg4txLqkiac'
})(StoreHeader);