import { observable, action, computed } from 'mobx';

class StateStore {
    @observable filters = [];
    @observable stores = [];
    @observable geo;
    @observable zoom;
    @observable waypoints;
    @observable view = 'list';


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
            const newRegion = this.json.regions.filter((region) => region.name == hash)[0];
            this.filters.push(hash);
            this.geo = newRegion.geo;
            this.zoom = newRegion.zoom;
            this.stores = this.json.stores.filter((store) => store.region == hash);
        } else if (pathname !== '/') {
            const id = pathname.replace('/', '');
            const store = this.json.stores.filter((store) => store.id == id)[0];
            this.geo = store.geo;
            this.zoom = store.zoom;
            this.changeView();
        }
    }

    @action
    clearFilters() {
        this.geo = this.json.constants.geo;
        this.zoom = this.json.constants.zoom;
        this.stores = this.json.stores;
        this.filters = [];
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
    changeMap(gps, zoom = this.json.constants.zoom) {
        this.geo = gps;
        this.zoom = zoom;
    }

    @action
    changeView() {
        this.view = this.view === 'list' ? 'single' : 'list';
    }

    @action
    updateWaypoints(start, stop, mode) {
        this.waypoints = {start: start, stop: stop, mode: mode}
    }

    @computed
    get geoTotal() {
        return { "lat": this.geo.lat, "lng": this.geo.lng };
    }
}

export default StateStore;
