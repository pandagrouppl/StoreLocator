import { h } from 'preact';
import HoursSelectFill from './HoursSelectFill'

const SingleStoreView = (props) =>
    (
        <li class="stores-li__store">
            <h1 class="stores-li__name">{props.name}</h1>
            <ul class="stores-li__credentials">
                <li><i class="icon-address"></i>{props.addr_strt} {props.addr_cty} {props.gps}</li>
                <li><i class="icon-mobile"></i><a href={'tel:'+props.phone}>{props.phone}</a></li>
                <li><i class="icon-envelope"></i><a href={'mailto:'+props.email}>{props.email}</a></li>
            </ul>
            <HoursSelectFill day={props.hours}/>
        </li>
    );

export default SingleStoreView;