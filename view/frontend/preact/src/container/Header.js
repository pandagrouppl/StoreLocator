import {h, Component} from 'preact';

import GoogleApiComponent from './../component/GoogleApiComponent';
import Maps from './Maps';

export class StoreHeader extends Component {

    constructor() {
        super();
    }

    render() {
        return(
            <header>
                <h1>Find nearest shop</h1>
                <section className="header-top">
                    <article>
                        <div>VIC</div>
                    </article>
                    <article>
                        <a>Reset</a>
                        <input type="text"/>
                        <button>Search</button>
                    </article>
                </section>
                <Maps google={this.props.google}/>
            </header>
        )
    }
}


export default GoogleApiComponent({
    apiKey: 'AIzaSyA56GAEym7kDnZEbGktGygTzg4txLqkiac'
})(StoreHeader);