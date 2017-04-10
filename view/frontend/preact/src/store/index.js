import { observable, action, computed } from 'mobx';

class StateStore {
    @observable filters = [];
    @observable stores = [];
    @observable geo;
    @observable zoom;

    constructor(json) {
        this.json = json;
        this.geo = json.constants.geo;
        this.zoom = json.constants.zoom;
        this.stores = json.stores;
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

    @computed
    get geoTotal() {
        return { "lat": this.geo.lat, "lng": this.geo.lng };
    }
}

export default StateStore;
