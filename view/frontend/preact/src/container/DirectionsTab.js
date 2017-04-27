import { h, Component } from 'preact';
import { connect } from 'mobx-preact';

@connect(['stateStore'])
export default class DirectionsTab extends Component {
    constructor(props) {
        super(props);
        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleGoogleAutocomplete =  this.handleGoogleAutocomplete.bind(this);
        this.state = {start: props.stateStore.waypoints.start, stop:props.stateStore.waypoints.stop, mode: props.stateStore.waypoints.mode, locked: 'b'};
        this.textInputs = [];
    }

    handleChange(event) {
        this.setState({
            [event.target.name]: event.target.value
        });

    }

    handleGoogleAutocomplete(target) {
        this.setState({
            [target.name]: target.value
    });

    }

    handleSubmit(event) {
        this.props.stateStore.updateWaypoints(this.state.start,this.state.stop, this.state.mode);
        event.preventDefault();
    }

    swapAddress() {
        this.setState({
            start: this.state.stop,
            stop: this.state.start
        });
        (this.state.locked == 'a') ? this.setState({locked: 'b'}) : this.setState({locked: 'a'});
        this.props.stateStore.updateWaypoints(this.state.start,this.state.stop, this.state.mode);
    }

    transitClass(label) {
        const a = 'DirectionsTab__radio-label';
        const active = (this.state.mode === label) ? `${a}--active ` : '';
        const classes = `${a} ${a}--${label}`;
        return active+classes
    }

    componentDidMount() {
        if (this.context.google) {
            this.textInputs.map((q) => {
                this.googleAutocomplete(q);
            });

        }
    }

    googleAutocomplete(target) {
        const autocompBounds = new this.context.google.maps.LatLngBounds(
            new this.context.google.maps.LatLng(this.context.sw[0], this.context.sw[1]),
            new this.context.google.maps.LatLng(this.context.ne[0], this.context.ne[1]));

        const options = {
            bounds: autocompBounds
        };

        const autocomplete = new this.context.google.maps.places.Autocomplete(target, options);

        autocomplete.addListener('place_changed', () => {
            this.handleGoogleAutocomplete(target)});
    }


    render() {
        return (
            <div>
                <form onSubmit={this.handleSubmit} method="post" className="DirectionsTab">
                    <label className={this.transitClass("DRIVING")}><input type="radio" name="mode" value="DRIVING" onChange={this.handleChange} checked={this.state.mode === 'DRIVING'} />car</label>
                    <label className={this.transitClass("TRANSIT")}><input type="radio" name="mode" value="TRANSIT" onChange={this.handleChange} />transit</label>
                    <label className={this.transitClass("WALKING")}><input type="radio" name="mode" value="WALKING" onChange={this.handleChange} />walk</label>
                    <div className="DirectionsTab__directions-flexbox">
                        <div className="DirectionsTab__directions-wrapper">
                            <div>
                                <label className="DirectionsTab__input-label" for="route-start">A</label>
                                <input className="DirectionsTab__input-field"
                                    id="route-start" name="start" type="text"
                                    value={this.state.start}
                                    onChange={this.handleChange}
                                    readonly={(this.state.locked == 'a')}
                                    required
                                    ref={(input) => {this.textInputs.push(input);}}/>

                            </div>
                            <div>
                                <label className="DirectionsTab__input-label" for="route-stop">B</label>
                                <input className="DirectionsTab__input-field"
                                    id="route-stop" name="stop" type="text"
                                    value={this.state.stop}
                                    onChange={this.handleChange}
                                    readonly={(this.state.locked == 'b')}
                                    required
                                    ref={(input) => {this.textInputs.push(input);}}/>

                            </div>
                        </div>
                        <button type="button" className="DirectionsTab__input-button DirectionsTab__input-button--swap" onClick={() => {this.swapAddress()}}>swap</button>
                    </div>
                    <input className="DirectionsTab__input-button DirectionsTab__input-button--submit" type="submit" value="Get Directions" />
                </form>
                <div id="directionsPanel"></div>
            </div>
        );
    }
}

