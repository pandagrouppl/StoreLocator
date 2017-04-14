import { h, Component } from 'preact';
import { connect } from 'mobx-preact';

@connect(['stateStore'])
export default class DirectionsTab extends Component {
    constructor(props) {
        super(props);
        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.state = {start: props.address, mode: props.stateStore.waypoints.mode}
    }

    handleChange(event) {
        this.setState({
            [event.target.name]: event.target.value
        });
    }

    handleSubmit(event) {
        this.props.stateStore.updateWaypoints(this.state.start,this.state.stop, this.state.mode);
        event.preventDefault();
    }

    swapAddress() {
        const w = this.state.start;
        this.setState({
            start: this.state.stop,
            stop: w});
    }

    render() {
        return (
            <div>
                <form onSubmit={this.handleSubmit} method="post" className="DirectionsTab">
                    <label className="DirectionsTab__radio-label DirectionsTab__radio-label--driving"><input type="radio" name="mode" value="DRIVING" onChange={this.handleChange} checked={this.state.mode === 'DRIVING'} />car</label>
                    <label className="DirectionsTab__radio-label DirectionsTab__radio-label--transit"><input type="radio" name="mode" value="TRANSIT" onChange={this.handleChange} />transit</label>
                    <label className="DirectionsTab__radio-label DirectionsTab__radio-label--walking"><input type="radio" name="mode" value="WALKING" onChange={this.handleChange} />walk</label>
                     <br />
                    <label className="DirectionsTab__input-label" for="route-start">A</label><input className="DirectionsTab__input-field" id="route-start" name="start" type="text" value={this.state.start} onChange={this.handleChange} required />
                    <label className="DirectionsTab__input-label" for="route-stop">B</label><input className="DirectionsTab__input-field" id="route-stop" name="stop" type="text" value={this.state.stop} onChange={this.handleChange} required />
                    <input className="DirectionsTab__input-submit" type="submit" value="Get Directions" />
                    <button className="DirectionsTab__input-swap" onClick={() => {this.swapAddress()}}>swap</button>
                </form>

            </div>
        );
    }
}

