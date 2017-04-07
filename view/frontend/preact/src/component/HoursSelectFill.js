import { h, Component} from 'preact';

export default class HoursSelectFill extends Component {
    constructor() {
        super();
    }

    closedCheck(hours_arr) {
        if (hours_arr[0].toLowerCase() == 'closed') {
            return hours_arr[0];
        } else {
            return hours_arr.join(' - ');
        }
    }

    render () {
        return (
            <div class="stores-li__hours-wrapper">
                <select class="stores-li__hours">
                    <option>STORE HOURS</option>
            {Object.keys(this.props.day).map((days) => <option>{days} : {this.closedCheck(this.props.day[days])}</option>)}
                </select>
                <figure class="stores-li__hours-arrow"></figure>
            </div>
        )
    }
}