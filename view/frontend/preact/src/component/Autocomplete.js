import { h, Component } from 'preact';

export default class Autocomplete extends Component {

    constructor(props, context) {
        super(props, context);
    }

    componentDidMount() {
        if (this.context.google) {
            this.applyAutocomplete();
        }
    }

    applyAutocomplete() {
        const autocompBounds = new this.context.google.maps.LatLngBounds(
            new this.context.google.maps.LatLng(this.context.sw[0], this.context.sw[1]),
            new this.context.google.maps.LatLng(this.context.ne[0], this.context.ne[1]));

        const input = document.getElementById(this.props.target);
        const options = {
            bounds: autocompBounds
        };

        const autocomplete = new this.context.google.maps.places.Autocomplete(input, options);

        autocomplete.addListener('place_changed', function() {
            let selected = autocomplete.getPlace();
            console.log(selected);
        });
    }

    render() {

        return null;
    }

}
