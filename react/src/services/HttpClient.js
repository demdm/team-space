import axios from 'axios';
import UserService from "./UserService";

export default {

    apiUrl: 'http://api.localhost/',

    headers: {
        responseType: 'json',
    },

    prepareAuthorizationHeader: function() {
        if (UserService.isSignedIn()) {
            this.headers.Authorization = 'Bearer ' + UserService.token;
        }
    },

    prepareGetRequest: function(url, params = {}) {
        this.prepareAuthorizationHeader();

        let config = {
            baseURL: this.apiUrl,
            headers: this.headers,
            method: 'get',
            url,
            params,
        };

        return axios.request(config);
    },

    preparePostRequest: function(url, data = {}) {
        this.prepareAuthorizationHeader();

        let config = {
            baseURL: this.apiUrl,
            headers: this.headers,
            method: 'post',
            url,
            data,
        };

        return axios.request(config);
    },

};
