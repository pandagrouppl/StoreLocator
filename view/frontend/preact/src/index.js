import { h, render } from 'preact';
import App from './App';
import { useStrict } from 'mobx';


const dots = (loader) => {
    let i = 0;
    setInterval(function() {
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
    useStrict(true);
    const loader = document.getElementById('preact-loader');
    dots(loader);
    fetch('/storelocator/index/json')
        .then(data => data.json())
        .then(json => {
            loader.remove();
            render(<App json={json}/>, document.getElementById('preact-root'));
        });
}
