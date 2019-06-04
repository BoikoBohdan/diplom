import 'react-hot-loader/patch';
import React from 'react';
import ReactDOM from 'react-dom';
import {AppContainer} from 'react-hot-loader';
import App from './app';

ReactDOM.render(
    <AppContainer>
        <App />
    </AppContainer>,
document.getElementById('app'));

if (process.env.NODE_ENV === 'development' && module.hot) {
    module.hot.accept();
}
