import React from 'react';
import {
    Switch,
    Route,
} from 'react-router-dom';
import PublicRoute from './services/Router/PublicRoute';
import PrivateRoute from './services/Router/PrivateRoute';
import Home from './pages/Home/Home';
import SignUp from './pages/Auth/SignUp';
import SignIn from './pages/Auth/SignIn';
import Dash from './pages/Dash/Dash';
import Profile from './pages/Dash/Profile';

export default () => {
    return (
        <Switch>
            <Route exact path='/'>
                <Home/>
            </Route>
            <PublicRoute exact path='/sign-up'>
                <SignUp/>
            </PublicRoute>
            <PublicRoute exact path='/sign-in'>
                <SignIn/>
            </PublicRoute>
            <PrivateRoute exact path='/user/dash'>
                <Dash/>
            </PrivateRoute>
            <PrivateRoute exact path='/user/profile'>
                <Profile/>
            </PrivateRoute>
        </Switch>
    );
}