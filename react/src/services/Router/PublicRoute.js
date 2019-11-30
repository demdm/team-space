import {useDispatch, useSelector} from "react-redux";
import {getUser} from "../../actions";
import UserService from "../UserService";
import React from "react";
import {Redirect, Route} from "react-router-dom";

export default ({ children, ...rest }) => {
    useDispatch()(getUser());
    const isAuthenticated = !!(useSelector(state => state.auth.name) || UserService.name);

    return (
        <Route
            {...rest}
            render={({ location }) =>
                isAuthenticated ? (
                    <Redirect
                        to={{
                            pathname: '/',
                            state: { from: location }
                        }}
                    />
                ) : (
                    children
                )
            }
        />
    );
}