import React from 'react';
import {
    Switch,
    Route
} from "react-router-dom";
import Home from "./pages/Home/Home";
import Register from "./pages/Register/Register";

export default () => {
    return (
        <Switch>
            <Route exact path="/">
                <Home/>
            </Route>
            <Route path="/register">
                <Register/>
            </Route>
            {/*<Route path="/dashboard">*/}
            {/*    <Dashboard/>*/}
            {/*</Route>*/}
        </Switch>
    );
}
