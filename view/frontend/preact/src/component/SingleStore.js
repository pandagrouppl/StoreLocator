import { h } from 'preact';
import { Link } from 'react-router-dom';

import HoursSelectFill from './HoursSelectFill'


const SingleStore = (props) => {

    const zoomToStore = () => {
        props.onStoreClick(props.geo, props.zoom);
    };

    return (
        <li className="stores-li__store">
            <Link to={`/${props.id}`}><h1 className="stores-li__name" onClick={zoomToStore}>{props.name}</h1></Link>
            <ul className="stores-li__credentials">
                <Link to={`/${props.id}`}><li onClick={zoomToStore}><i className="icon-address"></i>{props.addr_strt} {props.addr_cty}</li></Link>
                <li><i className="icon-mobile"></i><a href={'tel:'+props.phone}>{props.phone}</a></li>
                <li><i className="icon-envelope"></i><a href={'mailto:'+props.email}>{props.email}</a></li>
            </ul>
            <HoursSelectFill day={props.hours}/>
        </li>
    );
};

export default SingleStore;