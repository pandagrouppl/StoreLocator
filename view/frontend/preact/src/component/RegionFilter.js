import { h } from 'preact';

const RegionFilter = (props) => {
    const addFilter = () => {
        props.onFilterClick(props.region);
    };
    return (<p onClick={addFilter}>{props.region}</p>);
};

export default RegionFilter;