import { h } from 'preact';
import HoursFormatter from './HoursFormatter';

const HoursSpanFill = props => {
	return (
		<div class="hours-wrapper">
			{Object.keys(props.day).map(day => (
				<HoursFormatter day={day} hours={props.day[day]} wrapper="span" />
			))}
		</div>
	);
};

export default HoursSpanFill;
