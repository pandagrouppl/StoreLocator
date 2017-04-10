import { h, Component} from 'preact';
import SingleStore from './../component/SingleStore'
import filterArray from './../component/filterArray'

export default class AllStores extends Component {

    render () {
        const filtered = filterArray(this.props.stores, 'region', 'VIC');
        return (
            <ul className="stores-li">
            {filtered.map((store) => <SingleStore {...store}/>)}
            </ul>
        );
    }
}