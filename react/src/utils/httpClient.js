import axios from 'axios';
import {useDispatch, useSelector} from "react-redux";
import {getUser} from "../actions";

// const dispatch = useDispatch();
// dispatch(getUser());

let headers = {
    'X-Requested-With': 'XMLHttpRequest',
};

const userToken = null; //useSelector(state => state.auth ? state.auth.token : null);
if (userToken) {
    headers['Authorization'] = 'Bearer ' + userToken;
}

let requestConfig = {
    // baseUrl: 'http://api.localhost/',
    responseType: 'json',
    headers,
};


export function getHttpClient(
    url,
    params = {},
) {
    let getRequestConfig = requestConfig;

    getRequestConfig.url = 'http://api.localhost/' + url;
    getRequestConfig.method = 'get';
    getRequestConfig.params = params;

    return axios.request(getRequestConfig);
}

export function postHttpClient(
    url,
    data = {},
) {
    let postRequestConfig = requestConfig;

    postRequestConfig.url = 'http://api.localhost/' + url;
    postRequestConfig.method = 'post';
    postRequestConfig.params = data;

    return axios.request(postRequestConfig);
}