import { h, render } from 'preact';
import App from './App';
import 'whatwg-fetch';
require('es6-promise').polyfill();


const dots = (loader) => {
    let i = 0;
    return setInterval(function() {
        i++;
        loader.insertAdjacentHTML('beforeend', '<span>.</span>');
        if (i >= 4) {
            i = 0;
            loader.querySelectorAll("span").forEach(span => {
                span.remove();
            });
        }
    }, 500);
};

export function init() {

    const loader = document.getElementById('preact-loader');
    const intrv = dots(loader);
    fetch('/storelocator/index/json')
        .then(data => data.json())
        .then(json => {
            clearInterval(intrv);
            loader.remove();
            render(<App json={json}/>, document.getElementById('preact-root'));
        });
}
