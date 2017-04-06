import { h, Component} from 'preact';

import Header from './container/Header';

export default class App extends Component {

    constructor() {
        super();
    }

    render() {
        console.log(Header);
        return(
            <section>
                <Header/>
            </section>
        )
    }
}
