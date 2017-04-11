import { h } from 'preact';
import HoursFormatter from './HoursFormatter'

const HoursSelectFill = (props) => {
    return (
        <div class="stores-li__hours-wrapper">
            <select class="stores-li__hours">
                <option>STORE HOURS</option>
                {Object.keys(props.day).map((day) => {
                    return <HoursFormatter day={day} hours={props.day[day]} wrapper="option"/>
                    })}
            </select>
            <figure class="stores-li__hours-arrow"></figure>
        </div>
    )
};

export default HoursSelectFill;