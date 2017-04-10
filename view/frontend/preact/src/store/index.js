import { observable, action, computed } from 'mobx';

class StateStore {
    @observable filters = [];
    @observable geo;
    @observable zoom;


    constructor(json) {
        this.json = json;
        this.geo = json.constants.geo;
        this.zoom = json.constants.zoom;
    }

    @action clearFilters() {
        this.geo = json.constants.geo;
        this.zoom = json.constants.zoom;
        this.filters = [];
    }

    @action addFilters(filter) {
        const newRegion = this.json.regions.filter((region) => region.name == filter)[0];
        this.geo = newRegion.geo;
        this.zoom = newRegion.zoom;
        this.filters.push(filter);
    }

    @computed get geoTotal() {
        return { "lat": 52.4046, "lng": 16.9252 };
    }
}

export default StateStore;
