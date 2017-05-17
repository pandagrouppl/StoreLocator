import { h } from 'preact';
import { Component } from 'react';

import ScriptCache from './../util/ScriptCache';
import GoogleApi from './../util/GoogleApi';

const defaultMapConfig = {};
const defaultCreateCache = (options) => {
    options = options || {};
    const apiKey = options.apiKey;
    const libraries = options.libraries || ['places', 'geometry'];
    const version = options.version || '3';
    return ScriptCache({
        google: GoogleApi({apiKey: apiKey, libraries: libraries, version: version})
    });
};

export const wrapper = (options = {}) => (WrappedComponent) => {
    const apiKey = options.apiKey;
    const libraries = ['places', 'geometry', 'drawing'];
    const version = options.version || '3';
    const createCache = options.createCache || defaultCreateCache;

    class Wrapper extends Component {
        constructor(props, context) {
            super(props, context);
            options.apiKey = props.json.constants.apiKey;
            this.scriptCache = createCache(options);
            this.scriptCache.google.onLoad(this.onLoad.bind(this));

            this.state = {
                loaded: false,
                map: null,
                google: null
            };
        }

        onLoad(err, tag) {
            this._gapi = window.google;

            this.setState({loaded: true, google: this._gapi});
        }

        render() {
            const props = Object.assign({}, this.props, {
                loaded: this.state.loaded,
                google: window.google
            });

            return (
                <div>
                    <WrappedComponent {...props}/>
                </div>
            );
        }
    }

    return Wrapper;
};

export default wrapper;
