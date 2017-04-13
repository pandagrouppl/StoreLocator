import { h, Component } from 'preact';
import { connect } from 'mobx-preact';

@connect(['stateStore'])
export default class DirectionsTab extends Component {
    constructor(props) {
        super(props);
        this.state = {value: ''};
        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleChange(event) {
        this.setState({
            [event.target.name]: event.target.value
        });
    }

    handleSubmit(event) {
        this.props.stateStore.updateWaypoints(this.state.start,this.state.stop);
        event.preventDefault();
    }

    render() {
        return (
            <form onSubmit={this.handleSubmit}>
                <label>
                    Start:
                    <input name="start" type="text" value={this.state.start} onChange={this.handleChange} />
                    Stop:
                    <input name="stop" type="text" value={this.state.stop} onChange={this.handleChange} />
                </label>
                <input type="submit" value="Get Directions" />
            </form>
        );
    }
}

