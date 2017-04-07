import { h, Component} from 'preact';
import Header from './container/Header';
import AllStores from './container/AllStores'

export default class App extends Component {

    constructor() {
        super();
    }

    render() {
        console.log(Header);
        return(
            <div>
            <section>
                <Header/>
            </section>
            <section>
                <AllStores stores={this.props.json.stores}/>
            </section>
            </div>
        )
    }
}
