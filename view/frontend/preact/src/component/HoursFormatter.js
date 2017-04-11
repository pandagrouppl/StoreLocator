import { h } from 'preact';

const HoursFormatter = (props) => {
    const closedCheck = (hours_arr) => {
        return (hours_arr[0].toLowerCase() == 'closed') ? hours_arr[0] : hours_arr.join(' - ');
    };

    const Wrapper = props.wrapper;

    return (
        <Wrapper>{props.day} : {closedCheck(props.hours)}</Wrapper>)
};

export default HoursFormatter;