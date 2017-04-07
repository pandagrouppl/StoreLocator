import { h, Component} from 'preact';
import SingleStore from './../component/SingleStore'

export default class AllStores extends Component {

    FilterArray (arr, filter_by, filter = '') {
        return filter ? arr.filter((q) => q[filter_by] == filter) : arr;
    }

    render () {
        const filtered = this.FilterArray(this.props.stores, 'region', '');
        return (
            <ul className="stores-li">
            {filtered.map((store) => <SingleStore {...store}/>)}
            </ul>
        );
    }
}