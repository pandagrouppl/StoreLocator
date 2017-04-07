import { h, Component} from 'preact';
import HoursSelectFill from './HoursSelectFill'

export default class SingleStore extends Component {
    render () {
        return (
            <li class="stores-li__store">
                <h1 class="stores-li__name">{this.props.name}</h1>
                <ul class="stores-li__credentials">
                    <li><i class="icon-address"></i>{this.props.addr_strt} {this.props.addr_cty} {this.props.gps}</li>
                    <li><i class="icon-mobile"></i><a href={'tel:'+this.props.phone}>{this.props.phone}</a></li>
                    <li><i class="icon-envelope"></i><a href={'mailto:'+this.props.email}>{this.props.email}</a></li>
                </ul>
                <HoursSelectFill day={this.props.hours}/>
            </li>
        );
    }
}