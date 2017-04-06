import { h, render } from 'preact';
import App from './App';

function init() {
    render(<App/>, document.getElementById('preact-root'));
}

init();


