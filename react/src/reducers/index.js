import { combineReducers } from 'redux';
import auth from './auth';
import company from './company';

export default combineReducers({
    auth,
    company,
});