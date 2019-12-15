import React from 'react';
import {ListGroup} from "react-bootstrap";
import {NavLink} from "react-router-dom";

export default () => {
    const linkList = [
        {
            'to': '/user/dash',
            'name': 'Dashboard',
        }, {
            'to': '/user/profile',
            'name': 'Profile',
        }, {
            'to': '/user/employee',
            'name': 'Employee',
        },
    ];

    return (
        <ListGroup>
            { linkList.map((link, key) => {
                return (
                    <NavLink key={key}
                          to={link.to}
                          className="list-group-item list-group-item-action"
                    >
                        {link.name}
                    </NavLink>
                );
            }) }
        </ListGroup>
    );
};