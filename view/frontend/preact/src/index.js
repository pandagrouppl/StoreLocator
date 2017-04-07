import { h, render } from 'preact';
import App from './App';

export function init(json) {
    render(<App json={json}/>, document.getElementById('preact-root'));
}
