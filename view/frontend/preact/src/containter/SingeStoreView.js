import { h, Component} from 'preact';

export default class SingleStoreView extends Component {
    render () {
        return <h1>Hello, {this.props.name}</h1>;
    }
}