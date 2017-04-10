import { h, render } from 'preact';
import App from './App';
import { useStrict } from 'mobx';

export function init(json) {
    useStrict(true);
    render(<App json={json}/>, document.getElementById('preact-root'));
}
