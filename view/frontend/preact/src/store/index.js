import { observable, action, computed } from 'mobx';

class StateStore {
    @observable filters = [];
    @observable stores = [];
    @observable geo;
    @observable zoom;
    @observable waypoints;
    @observable view = 'list';
    @observable error = '';
    @observable refDiv;

    constructor(json) {
        this.json = json;
        this.geo = json.constants.geo;
        this.zoom = json.constants.zoom;
        this.stores = json.stores;
        this.waypoints = {start: '', stop: '', mode: 'DRIVING'};
    }

    @action
    initializeStore(router) {
        let {pathname, hash} = router.route.location;
        if (hash !== '') {
            hash = hash.replace('#', '').toUpperCase();
            this.addFilters(hash);
        } else if (pathname !== '/') {
            pathname = pathname.replace('/', '');
            const store = this.json.stores.filter((store) => store.id == pathname)[0];
            this.changeMap(store.geo, store.zoom);
            this.changeView();
        }
    }

    @action
    clearFilters() {
        this.geo = this.json.constants.geo;
        this.zoom = this.json.constants.zoom;
        this.stores = this.json.stores;
        this.filters = [];
        this.error = '';
    }

    @action
    addFilters(filter) {
        this.filters = [];
        const newRegion = this.json.regions.filter((region) => region.name == filter)[0];
        this.stores = this.json.stores.filter((store) => store.region == filter);
        this.geo = newRegion.geo;
        this.zoom = newRegion.zoom;
        this.filters.push(filter);
    }

    @action
    changeMap(gps=this.json.constants.geo, zoom=this.json.constants.zoom) {
        this.geo = gps;
        this.zoom = zoom;
    }

    @action
    changeView() {
        this.view = this.view === 'list' ? 'single' : 'list';
        this.error = '';
    }

    @action
    updateWaypoints(start, stop, mode) {
        this.waypoints = {start: start, stop: stop, mode: mode};
    }

    @action
    setError(error='') {
        this.error = error;
    }

    @action
    updateRef(ref) {
        this.refDiv = ref;
    }

    @computed
    get geoTotal() {
        return { "lat": this.geo.lat, "lng": this.geo.lng };
    }

    @computed
    get getWaypoints() {
        return {
            origin: this.waypoints.start,
            destination: this.waypoints.stop,
            travelMode: this.waypoints.mode
        };
    }
}

export default StateStore;
