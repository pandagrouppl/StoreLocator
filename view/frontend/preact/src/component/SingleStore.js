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
                <Link to={`/${((props.addr_cty + '/' + props.name).split(' ').join('-').toLowerCase())}`} onClick={zoomToStoreLink}><h3 className="stores-li__name">{props.name}</h3></Link>
                <ul className="stores-li__credentials">
                    <li>{props.addr_strt} {props.addr_cty} {props.zipcode}</li>
                    <li><a href={'tel:'+props.phone}>{props.phone}</a></li>
                    <li><a href={'mailto:'+props.email}>{props.email}</a></li>
                </ul>
            </div>
            <HoursSelectFill day={props.hours}/>
        </li>
    );
};

export default SingleStore;