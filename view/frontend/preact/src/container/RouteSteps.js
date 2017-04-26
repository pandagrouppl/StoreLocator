import { h, Component } from 'preact';
import { connect } from 'mobx-preact';

@connect(['stateStore'])
export default class RouteSteps extends Component {

    constructor(props) {
        super(props);
    }

    updateWaypoints(target) {
        if ((this.props.stateStore.route) && (target)) {
            let parsed = '';
            this.props.stateStore.route.map((q) => (parsed += `<div class='Directions__row'>${q.instructions}</div>`));
            target.innerHTML = parsed;
        }
    }


    render () {
        let parsed = '';
        this.props.stateStore.route.map((q) => (parsed += `<div class='Directions__row'>${q.instructions}</div>`));
        return (
            <div dangerouslySetInnerHTML={{__html: parsed}}></div>
        );
    }

}
