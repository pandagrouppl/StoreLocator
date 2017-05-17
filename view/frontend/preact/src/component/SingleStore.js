import { h } from 'preact';
import { Link } from 'react-router-dom';

import HoursSelectFill from './HoursSelectFill';


const SingleStore = (props) => {

    const zoomToStore = () => {
        props.onStoreClick(props.geo, props.zoom);
    };

    const zoomToStoreLink = () => {
        props.onStoreClick(props.geo, props.zoom);
        props.onLinkClick();
    };

    return (
        <li className="stores-li__store">
            <div className="stores-li__info-container">
                <h1 className="stores-li__name" onClick={zoomToStore}>{props.name}</h1>
                <ul className="stores-li__credentials">
                    <li><i className="icon-address"></i>{props.addr_strt} {props.addr_cty}</li>
                    <li><i className="icon-mobile"></i><a href={'tel:'+props.phone}>{props.phone}</a></li>
                    <li><i className="icon-envelope"></i><a href={'mailto:'+props.email}>{props.email}</a></li>
                    <li onClick={zoomToStoreLink}><Link to={`/${props.id}`}>Get directions</Link></li>
                </ul>
            </div>
            <HoursSelectFill day={props.hours}/>
        </li>
    );
};

export default SingleStore;