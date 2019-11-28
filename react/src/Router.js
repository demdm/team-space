import React from 'react';
import {
    Switch,
    Route
} from "react-router-dom";
import Home from "./pages/Home/Home";
import SignUp from "./pages/Auth/SignUp";
import SignIn from "./pages/Auth/SignIn";

export default () => {
    return (
        <Switch>
            <Route exact path="/">
                <Home/>
            </Route>
            <Route path="/sign-up">
                <SignUp/>
            </Route>
            <Route path="/sign-in">
                <SignIn/>
            </Route>
        </Switch>
    );
}
