import React from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import './App.css';
import Router from "./Router";
import {Link, useHistory} from "react-router-dom";
import {
    Navbar,
    Nav,
    NavDropdown,
} from 'react-bootstrap';
import HttpClient from "./services/HttpClient";
import UserService from "./services/UserService";
import {useDispatch, useSelector} from "react-redux";
import {getUser, signOut} from "./actions";

export default () => {
    const history = useHistory();
    const dispatch = useDispatch();

    dispatch(getUser());
    const userName = useSelector(state => state.auth.name) || UserService.name;

    return (
        <>
            <Navbar collapseOnSelect expand="lg" bg="dark" variant="dark">
                <div className="container">
                    <Link to='/' className="navbar-brand">
                        Site name
                    </Link>
                    <Navbar.Toggle aria-controls="responsive-navbar-nav" />
                    <Navbar.Collapse id="responsive-navbar-nav">
                        <Nav className="mr-auto">
                            <Nav.Link href="#features">Features</Nav.Link>
                            <Nav.Link href="#pricing">Pricing</Nav.Link>
                            <NavDropdown title="Dropdown" id="collasible-nav-dropdown">
                                <NavDropdown.Item href="#action/3.1">Action</NavDropdown.Item>
                                <NavDropdown.Item href="#action/3.2">Another action</NavDropdown.Item>
                                <NavDropdown.Item href="#action/3.3">Something</NavDropdown.Item>
                                <NavDropdown.Divider />
                                <NavDropdown.Item href="#action/3.4">Separated link</NavDropdown.Item>
                            </NavDropdown>
                        </Nav>
                        <Nav>
                            { userName
                                ? (
                                    <a href='#' className="nav-link default" role="button" onClick={() => {
                                        HttpClient
                                            .preparePostRequest('auth/logout')
                                            .then(response => {
                                                if (response.data.success) {
                                                    dispatch(signOut());
                                                    history.replace({from: {pathname: '/'}});
                                                } else {
                                                    console.log(response);
                                                }
                                            })
                                            .catch(error => {
                                                console.log(error);
                                            });
                                    }}>
                                        { userName } (Logout)
                                    </a>
                                )
                                : (<>
                                    <Link to='/sign-in' className="nav-link default" role="button">
                                        Login
                                    </Link>
                                    <Link to='/sign-up' className="nav-link" role="button">
                                        Register
                                    </Link>
                                </>)
                            }
                        </Nav>
                    </Navbar.Collapse>
                </div>
            </Navbar>

            <Router/>
        </>
    );
};
