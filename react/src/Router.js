import React from 'react';
import {
    Switch,
    Route
} from "react-router-dom";
import Home from "./pages/Home/Home";
import Register from "./pages/Register/Register";
import Login from "./pages/Login/Login";

export default () => {
    return (
        <Switch>
            <Route exact path="/">
                <Home/>
            </Route>
            <Route path="/register">
                <Register/>
            </Route>
            <Route path="/login">
                <Login/>
            </Route>
        </Switch>
    );
}
