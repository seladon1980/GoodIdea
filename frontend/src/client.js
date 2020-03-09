import React from 'react'
import {render} from 'react-dom'
import {Provider} from 'react-redux'
import App from './components/app'
import configureStore from './redux/configureStore'

let initialState = {
    page: {
        type: "blog",
        postSlug: 1,
        order: 'ASC',
        by: 'id'
    }
};
const m = /^\/blog\/([^\/]+)$/.exec(location.pathname);
if (m !== null) {
    initialState = {
        page: {
            type: "blog",
            postSlug: m[1],
            order: 'ASC',
            by: 'id'
        },
    }
}

const store = configureStore(initialState);

render(
    <Provider store={store}>
        <App/>
    </Provider>,
    document.querySelector('#app')
);