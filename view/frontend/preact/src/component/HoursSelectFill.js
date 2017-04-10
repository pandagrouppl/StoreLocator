import { h } from 'preact';

const HoursSelectFill = (props) => {
    const closedCheck = (hours_arr) => {
        return (hours_arr[0].toLowerCase() == 'closed') ? hours_arr[0] : hours_arr.join(' - ');
    };

    return (
        <div class="stores-li__hours-wrapper">
            <select class="stores-li__hours">
                <option>STORE HOURS</option>
                {Object.keys(props.day).map((days) => <option>{days} : {closedCheck(props.day[days])}</option>)}
            </select>
            <figure class="stores-li__hours-arrow"></figure>
        </div>
    )
};

export default HoursSelectFill;