import { h, Component } from 'preact';
import { connect } from 'mobx-preact';
import { Link } from 'react-router-dom';

import GoogleApiComponent from './../component/GoogleApiComponent';
import HeaderMap from './HeaderMap';
import RegionFilter from './../component/RegionFilter';

@connect(['stateStore'])
export default class StoreHeader extends Component {

    constructor(props) {
        super();
        this.postcodeInput = null;
        this.geocode = null;
        this.applyFilter = this.applyFilter.bind(this);
        this.resetFilters = this.resetFilters.bind(this);
        this.searchPostcode = this.searchPostcode.bind(this);
    }

    componentWillMount() {
        this.props.stateStore.initializeStore(this.context.router);
    }

    resetFilters() {
        this.props.stateStore.clearFilters();
    }

    searchPostcode() {
        if(!this.geocode) {
            this.geocode = new this.props.google.maps.Geocoder();
        }
        this.geocode.geocode({
            componentRestrictions: {
                country: 'AU',
                postalCode: this.postcodeInput.value
            }
        }, (results, status) => {
            console.log(status);
            if (status === 'OK') {
                const newGeo = {lat: results[0].geometry.location.lat(), lng: results[0].geometry.location.lng()};
                this.props.stateStore.setError();
                this.props.stateStore.changeMap(newGeo);
            } else {
                this.props.stateStore.setError('Invalid postcode');
            }
        });
    }

    isActiveFilter(region) {
        return this.props.stateStore.filters.indexOf(region) > -1
            ? 'storelocator-header__filter storelocator-header__filter--active'
            : 'storelocator-header__filter';
    }

    backButton() {
        this.props.stateStore.changeView();
    }

    applyFilter(region) {
        this.props.stateStore.addFilters(region);
    }

    render() {
        const { regions } = this.props;
        return(
            <header className="storelocator-header">
                <h1 className="storelocator-header__title">Find nearest shop</h1>
                <section className="storelocator-header__row">
                    {this.props.stateStore.view === 'list' ?
                        <article className="storelocator-header__filters">
                            {regions.map((region) => (
                                <RegionFilter className={this.isActiveFilter(region.name)} region={region.name}
                                              onFilterClick={this.applyFilter}/>
                            ))}
                        </article> :
                        <Link to='/' onClick={() => this.backButton()}><button className="storelocator-header__back">Back</button></Link> }
                    <article>
                        <a className="storelocator-header__reset" onClick={this.resetFilters}>Reset</a>
                        <input className="storelocator-header__input"
                               ref={(input) => { this.postcodeInput = input; }}
                               placeholder="Postcode"
                               type="text"
                               name="postcode"
                        />
                        <button className="storelocator-header__search" onClick={this.searchPostcode}>Search</button>
                    </article>
                </section>
                <p className="storelocator-header__error">{this.props.stateStore.error}</p>
                <HeaderMap google={this.props.google}/>
            </header>
        )
    }
}