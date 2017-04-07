import { h, Component} from 'preact';
import SingleStore from './../component/SingleStore.js'

export default class AllStores extends Component {
    render () {
        return (
            <ul className="stores-li">
            {this.props.stores.map((store) => <SingleStore {...store}/>)}
            </ul>
        );
    }
}