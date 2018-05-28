import { h } from 'preact';

const RegionFilter = (props) => {
    const addFilter = () => {
        props.onFilterClick(props.region);
    };
    return (<p className={props.className} onClick={addFilter}>{props.region}</p>);
};

export default RegionFilter;