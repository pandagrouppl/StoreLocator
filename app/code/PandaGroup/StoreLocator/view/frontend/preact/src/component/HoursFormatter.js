import { h } from 'preact';

const HoursFormatter = (props) => {
    const closedCheck = (hours_arr) => {

        return ((hours_arr[0] == '12:00 AM') && (hours_arr[1] == '12:00 AM') ) ? 'Closed' : hours_arr.join(' - ');
    };

    const Wrapper = props.wrapper;

    return (
        <Wrapper className="hours-wrapper__day"><span className="hours-wrapper__day-name">{props.day}:</span> {closedCheck(props.hours)}</Wrapper>
    );
};

export default HoursFormatter;